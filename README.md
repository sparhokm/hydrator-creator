# Hydrator creator

[![Code Coverage](https://codecov.io/gh/sparhokm/hydrator-creator/branch/master/graph/badge.svg)](https://codecov.io/gh/sparhokm/hydrator-creator)
[![type-coverage](https://shepherd.dev/github/sparhokm/hydrator-creator/coverage.svg)](https://shepherd.dev/github/sparhokm/hydrator-creator)
[![psalm-level](https://shepherd.dev/github/sparhokm/hydrator-creator/level.svg)](https://shepherd.dev/github/sparhokm/hydrator-creator)

The library is designed for hydrating objects through the class constructor. Additionally, with the help of expandable attributes, it is possible to add your own data extractors, modifiers, and validators. The attribute can be applied to the class or to parameters.

- [Installation](#installation)
- [Usage](#usage)
    - [Array of objects](#array-of-objects)
    - [Alias for field](#alias-for-field)
    - [Default value](#default-value)
    - [Required value for parameter](#required-value-for-parameter)
    - [Not empty validator](#not-empty-validator)
- [Your own attributes](#your-own-attributes)


## **Installation**

The package can be installed via composer:

```
composer require sav/hydrator-creator
```

> Note: The current version of the package supports only PHP 8.1 +.

### **Usage**

```php
use Sav\Hydrator\Hydrator;

final class User
{
    public readonly string $email;

    public function __construct(
        string $email,
        public readonly string $name
    ) {
        $this->email = strtolower($email);
    }
}

$data = [
    'email' => 'Test@mail.com',
    'name' => 'Sam',
];

$user = Hydrator::init()->hydrate(User::class, $data);
var_dump($user);
```

Result:

```php
object(User) {
  ["email"]=> string(13) "test@mail.com"
  ["name"]=> string(3) "Sam"
}
```

If the property is not of a scalar type, but a class of another object is allowed, it will also be automatically converted.  If a class uses one parameter in the constructor, it will be automatically passed to the constructor without the need to specify the exact name. This allows for converting data into simple ValueObjects.

```php
use Sav\Hydrator\Hydrator;

final class Item
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }
}

final class PriceValueObject
{
    public function __construct(
        private readonly float $value,
    ) {
    }

    public function getValue(): float
    {
        return $this->value;
    }
}

final class OrderItem
{
    public function __construct(
        public readonly Item $product,
        public readonly PriceValueObject $cost,
    ) {
    }
}

$data = [
    'product' => ['id' => 1, 'name' => 'phone'],
    'cost' => 10012.23,
];

$orderItem = Hydrator::init()->hydrate(OrderItem::class, $data);
var_dump($orderItem);
```

Result:

```php
object(OrderItem) {
  ["product"]=> object(Item) {
    ["id"]=> int(1)
    ["name"]=> string(5) "phone"
  }
  ["cost"]=> object(PriceValueObject) {
    ["value":"PriceValueObject":private]=> float(10012.23)
  }
}
```

### **Array of objects**

If you have an array of objects of a certain class, then you must specify the ArrayOfObjects attribute for it, passing it to which class you need to bring the elements.

Example:

```php
use Sav\Hydrator\Hydrator;
use Sav\Hydrator\Attribute\ArrayMap\ArrayOfObjects;

class Product
{
    public function __construct(
        public readonly int $id,
        public readonly DateTimeImmutable $dateCreate,
    ) {
    }
}

class Products
{
    public function __construct(
        #[ArrayOfObjects(Product::class)]
        public readonly array $products
    ) {
    }
}

$data = [
    'products' => [
        ['id' => 1, 'dateCreate' => '2023-01-01'],
        ['id' => 2, 'dateCreate' => '2023-01-02'],
    ],
];
$products = Hydrator::init()->hydrate(Products::class, $data);
var_dump($products);
```

Result

```php
object(Products) {
  ["products"]=> array(2) {
    [0]=> object(Product) {
      ["id"]=> int(1)
      ["dateCreate"]=> object(DateTimeImmutable) {
        ["date"]=> string(26) "2023-01-01 00:00:00.000000"
        ["timezone_type"]=> int(0)
        ["timezone"]=> string(3) "UTC"
      }
    }
    [1]=>object(Product) {
      ["id"]=> int(2)
      ["dateCreate"]=> object(DateTimeImmutable) {
        ["date"]=> string(26) "2023-01-02 00:00:00.000000"
        ["timezone_type"]=> int(0)
        ["timezone"]=> string(3) "UTC"
      }
    }
  }
}
```

### **Alias for field**

Various possible aliases can be set for the property, which will also be searched in the data source. 

```php
use Sav\Hydrator\Hydrator;
use Sav\Hydrator\Attribute\ValueExtractor\Alias;

class User
{
    public function __construct(
        private readonly int $id,
        #[Alias('personalPhone')]
        private readonly string $phone,
    ) {
    }
}
```

### **Default value**

Using the DefaultValue attribute, you can set a default value for a parameter. You can also set default values using standard php syntax.

```php
use Sav\Hydrator\Hydrator;
use Sav\Hydrator\Attribute\ValueModifier\DefaultValue;

class User
{
    public function __construct(
        public readonly int $id,
        #[DefaultValue(new DateTimeImmutable('2023-01-03'))]
        public readonly DateTimeImmutable $dateCreate,
        #[DefaultValue(100, applyForEmpty: true)]
        public readonly int $balance,
        public readonly int $limit = 30,
    ) {
    }
}

$user = Hydrator::init()->hydrate(User::class, ['id' => 1, 'balance' => 0]);
var_dump($user);
```
Result: 
```php
object(User) {
  ["id"]=> int(1)
  ["dateCreate"]=> object(DateTimeImmutable) {
    ["date"]=> string(26) "2023-01-03 00:00:00.000000"
    ["timezone_type"]=> int(0)
    ["timezone"]=> string(3) "UTC"
  }
  ["balance"]=> int(100)
  ["limit"]=> int(30)
}
```

### **Required value for parameter**

The attribute specifies whether a parameter value is required in the input data. The attribute can be applied to the class or to parameters.

```php
use Sav\Hydrator\Hydrator;
use Sav\Hydrator\Attribute\RequiredKeyValue;

#[RequiredKeyValue]
class User
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $balance = 3,
    ) {
    }
}

try {
    $user = Hydrator::init()->hydrate(User::class, ['id' => 1]);
} catch(HydratorException $e) {
    echo $e->getMessage();
}
```
Result:
``` 
balance: Value not exist.
```

### **Not empty validator**

The NotEmpty attribute allows checking the completeness of arguments. It checks only if a value has been assigned to the argument, and it is not null. If the check fails, an exception will be thrown.

```php
use Sav\Hydrator\Hydrator;
use Sav\Hydrator\Attribute\ValueValidator\NotEmpty;

class User
{
    public function __construct(
        public readonly int $id,
        #[NotEmpty]
        public readonly int $balance,
    ) {
    }
}

try {
    $user = Hydrator::init()->hydrate(User::class, ['id' => 1, 'balance' => 0]);
} catch(HydratorException $e) {
    echo $e->getMessage();
}
```
Result:
``` 
balance: Value is empty.
```
## **Your own attributes**

To expand the functionality, you can write your own attributes that implement special interfaces depending on their purpose. 

- `Sav\Hydrator\Attribute\ValueExtractor` is used to change the search for a parameter value among input data. An example of such an attribute is Alias. 

- `\Sav\Hydrator\Attribute\ValueModifier` is needed to transform the found value. An example could be the DefaultValue attribute. 

- `\Sav\Hydrator\Attribute\ValueValidator` is used to check extracted and modified data. An example is the NotEmpty attribute that validates empty values.

- `\Sav\Hydrator\Attribute\ArrayMap` is used to define the type of interpretation for values with a specific key. An example is the ArrayOfObjects attribute, which interprets each element of the array as a value of a specified class.

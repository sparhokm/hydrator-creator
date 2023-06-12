<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER' => true,
        '@PER:risky' => true,
        'strict_param' => true,
        'final_class' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setCacheFile(__DIR__ . '/var/.php-cs-fixer.cache')
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . '/src')
            ->in(__DIR__ . '/tests')
            ->append([__FILE__])
    );

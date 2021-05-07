<?php
    $finder = PhpCsFixer\Finder::create()
        ->exclude('bootstrap/')
        ->exclude('public/')
        ->exclude('resources/')
        ->exclude('storage/')
        ->name('*.php')
        ->in(__DIR__)
    ;

    $config = new PhpCsFixer\Config();

    return $config
        ->setRules([
            '@PSR2' => true,
            'array_syntax' => ['syntax' => 'short'],
            'ordered_imports' => ['sort_algorithm' => 'alpha'],
            'no_unused_imports' => true,
        ])
        ->setFinder($finder);

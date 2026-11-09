<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude([
        'var',
        'config',
    ])
    ->notPath([
        'config/bundles.php',
        'config/reference.php',
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,

        // File layout
        'declare_strict_types' => true,
        'blank_line_after_opening_tag' => false,
        'linebreak_after_opening_tag' => false,
        'no_closing_tag' => true,

        // Imports
        'global_namespace_import' => [
            'import_classes' => true,
            'import_functions' => true,
            'import_constants' => true,
        ],
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['class', 'function', 'const'],
        ],
        'no_unused_imports' => false,

        // Array usage
        'array_push' => true,
        'array_syntax' => ['syntax' => 'short'],
        'list_syntax' => ['syntax' => 'short'],

        // Function usage
        'strict_param' => true,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true)
;

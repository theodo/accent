<?php
$finder = PhpCsFixer\Finder::create()
   ->in(
       [
           __DIR__.'/src'
       ]
   );
return PhpCsFixer\Config::create()
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(false) // cache is always an issue when you switch branch because the cached file is ignored by git
    ->setRules([
        '@Symfony' => true,
        // risky rules
        '@Symfony:risky' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        // non risky rules
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'combine_consecutive_unsets' => true,
        'declare_strict_types' => true,
        'dir_constant' => true,
        'fully_qualified_strict_types' => true,
        'linebreak_after_opening_tag' => true,
        'mb_str_functions' => true,
        'modernize_types_casting' => true,
        'no_alternative_syntax' => true,
        'no_multiline_whitespace_before_semicolons' => true,
        'no_null_property_initialization' => true,
        'no_php4_constructor' => true,
        'no_short_echo_tag' => true,
        'no_superfluous_elseif' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'phpdoc_to_comment' => false,
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last',
            'sort_algorithm' => 'none'
        ],
        'php_unit_set_up_tear_down_visibility' => true,
        'pow_to_exponentiation' => true,
        'protected_to_private' => true,
        'semicolon_after_instruction' => true,
        'ternary_to_null_coalescing' => true,
    ])
;

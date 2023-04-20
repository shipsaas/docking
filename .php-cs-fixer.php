<?php

use PhpCsFixer\Finder;
use PhpCsFixer\Config;

$finder = Finder::create()
    ->in([
        __DIR__ . '/app/',
        __DIR__ . '/config/',
        __DIR__ . '/database/',
        __DIR__ . '/tests/',
    ]);

$config = new Config();

return $config->setFinder($finder)
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'combine_consecutive_unsets' => true,
        'multiline_whitespace_before_semicolons' => true,
        'single_quote' => true,
        'binary_operator_spaces' => ['default' => 'single_space'],
        'blank_line_before_statement' => ['statements' => ['return']],
        'braces' => [
            'allow_single_line_closure' => true,
            'position_after_anonymous_constructs' => 'same',
            'position_after_control_structures' => 'same',
            'position_after_functions_and_oop_constructs' => 'next',
        ],
        'combine_consecutive_issets' => true,
        'class_attributes_separation' => ['elements' => ['method' => 'one']],
        'concat_space' => ['spacing' => 'one'],
        'include' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'square_brace_block',
                'throw',
                'use',
            ],
        ],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_spaces_around_offset' => true,
        'no_unused_imports' => true,
        'no_whitespace_before_comma_in_array' => true,
        'object_operator_without_whitespace' => true,
        'php_unit_fqcn_annotation' => true,
        'phpdoc_no_package' => true,
        'phpdoc_scalar' => true,
        'phpdoc_single_line_var_spacing' => true,
        'protected_to_private' => true,
        'return_assignment' => true,
        'no_useless_return' => true,
        'simplified_null_return' => true,
        'single_line_after_imports' => true,
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'single_class_element_per_statement' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'whitespace_after_comma_in_array' => true,
        'no_null_property_initialization' => true,

        'function_typehint_space' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'no_empty_statement' => true,
        'no_leading_namespace_whitespace' => true,
        'return_type_declaration' => ['space_before' => 'none'],

        'method_chaining_indentation' => true,
        'align_multiline_comment' => ['comment_type' => 'all_multiline'],
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => false,
            'remove_inheritdoc' => false,
            'allow_unused_params' => false,
        ],
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_trim' => true,
        'no_empty_phpdoc' => true,
        'clean_namespace' => true,
        'array_indentation' => true,
        'elseif' => true,
        'phpdoc_order' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => false,
            'import_functions' => false,
        ],
        'fully_qualified_strict_types' => true,
        'no_leading_import_slash' => true,
    ])
    ->setLineEnding("\n");

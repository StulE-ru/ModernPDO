<?php

return (new PhpCsFixer\Config)
    ->setRiskyAllowed(true)
    ->setRules([
        // Symfony rulesets

        '@Symfony' => true,
        '@Symfony:risky' => true,

        // PSR12 rulesets

        '@PSR12:risky' => true,
        '@PSR12' => true,

        // Alias

        'array_push' => true,
        'backtick_to_shell_exec' => true,
        'modernize_strpos' => true,
        'no_alias_functions' => true,
        'no_mixed_echo_print' => ['use' => 'print'],
        'pow_to_exponentiation' => true,
        'random_api_migration' => true,
        'set_type_to_cast' => true,

        // Array Notation

        'array_syntax' => ['syntax' => 'short'],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_whitespace_before_comma_in_array' => true,
        'normalize_index_brace' => true,
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => ['ensure_single_space' => true],

        // Other...

        'align_multiline_comment' => ['comment_type' => 'phpdocs_like'],
        'combine_consecutive_unsets' => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'heredoc_to_nowdoc' => true,
        'no_useless_return' => true,
        'ternary_to_null_coalescing' => true,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
    ])
    ->setFinder(
        (new PhpCsFixer\Finder())
            ->in(__DIR__)
            ->exclude([
                'vendor',
            ])
    );

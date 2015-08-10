<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS\Config;
use Symfony\CS\FixerInterface;

/**
 * @author Martin Major ( https://github.com/MartinMajor )
 */
class SbksConfig extends Config
{
    public function __construct()
    {
        parent::__construct();

        $this->level = FixerInterface::NONE_LEVEL;
        $this->fixers = array(
            'encoding',
            'short_tag',
            'braces',
            'elseif',
            'eof_ending',
            'function_call_space',
            'function_declaration',
            'tabs_indentation',
            'line_after_namespace',
            'linefeed',
            'uppercase_constants',
            'lowercase_keywords',
            'method_argument_space',
            'multiple_use',
            'parenthesis',
            'php_closing_tag',
            'single_line_after_imports',
            'trailing_spaces',
            'visibility',
            'blankline_after_open_tag',
            'concat_with_spaces',
            'duplicate_semicolon',
            'include',
            'join_function',
            'list_commas',
            'multiline_array_trailing_comma',
            'namespace_no_leading_whitespace',
            'new_with_braces',
            'no_empty_lines_after_phpdocs',
            'object_operator',
            'operators_spaces',
            'phpdoc_indent',
            'phpdoc_scalar',
            'remove_leading_slash_use',
            'remove_lines_between_uses',
            'self_accessor',
            'single_array_no_trailing_comma',
            'single_blank_line_before_namespace',
            'single_quote',
            'spaces_before_semicolon',
            'spaces_cast',
            'standardize_not_equal',
            'ternary_spaces',
            'trim_array_spaces',
            'unalign_double_arrow',
            'unalign_equals',
            'unary_operators_spaces',
            'unused_use',
            'whitespacy_lines',
            'concat_with_spaces',
            'newline_after_open_tag',
            'ordered_use',
            'phpdoc_order',
            'short_array_syntax',
            'lines_between_methods',
            'trim_array_access_spaces',
        );
    }

    public function getName()
    {
        return 'sbks';
    }

    public function getDescription()
    {
        return 'The configuration for a SBKS application';
    }
}

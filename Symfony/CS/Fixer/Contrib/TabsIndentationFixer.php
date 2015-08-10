<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS\Fixer\Contrib;

use Symfony\CS\AbstractFixer;
use Symfony\CS\Tokenizer\Tokens;

/**
 * @author Martin Major ( https://github.com/MartinMajor )
 */
final class TabsIndentationFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, Tokens $tokens)
    {
        $lastTokenEndsWithNewline = false;
        $indentation = null;
        $replaceCallback = function ($str) use (&$indentation) {
            if ($indentation === null) {
                $indentation = strlen($str[0]);
            }
            return str_repeat("\t", strlen($str[0]) / $indentation);
        };

        foreach ($tokens as $index => $token) {
            if ($token->isWhitespace() || $token->isComment()) {
                $startPattern = $lastTokenEndsWithNewline ? '^|' : '';
                $pattern = "(?:$startPattern(?<=[\n\r\t])) {2,}(?![\\*|])";
                $tokens[$index]->setContent(preg_replace_callback("/$pattern/", $replaceCallback, $token->getContent()));
            }
            $lastTokenEndsWithNewline = in_array(substr($token->getContent(), -1), array("\n", "\r"));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'Code MUST use an indent of tabs, and MUST NOT use spaces for indenting.';
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return -50;
    }
}

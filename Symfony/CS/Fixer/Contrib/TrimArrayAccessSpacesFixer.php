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
final class TrimArrayAccessSpacesFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        foreach ($tokens as $token) {
            if ($token->equals('[')) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, Tokens $tokens)
    {
        foreach ($tokens as $index => $token) {
            if ($token->equals('[')) {
                $this->ensureNotWhitespace($tokens, $index + 1);
                $endIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_INDEX_SQUARE_BRACE, $index);
                $this->ensureNotWhitespace($tokens, $endIndex - 1);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'Arrays access should be formatted without leading or trailing spaces and tabs.';
    }

    /**
     * @param Tokens $tokens
     * @param int    $index
     */
    private function ensureNotWhitespace(Tokens $tokens, $index)
    {
        if ($tokens[$index]->isWhitespace(" \t")) {
            $tokens[$index]->clear();
        }
    }
}

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
final class UppercaseConstantsFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isTokenKindFound(T_STRING);
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, Tokens $tokens)
    {
        foreach ($tokens as $index => $token) {
            if (!$token->isNativeConstant()) {
                continue;
            }

            if (
                $this->isNeighbourAccepted($tokens, $tokens->getPrevNonWhitespace($index))
                &&
                $this->isNeighbourAccepted($tokens, $tokens->getNextNonWhitespace($index))
            ) {
                $token->setContent(strtoupper($token->getContent()));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'The PHP constants TRUE, FALSE, and NULL MUST be in UPPER case.';
    }

    private function isNeighbourAccepted(Tokens $tokens, $index)
    {
        static $forbiddenTokens = null;

        if (null === $forbiddenTokens) {
            $forbiddenTokens = array(
                T_AS,
                T_CLASS,
                T_CONST,
                T_EXTENDS,
                T_IMPLEMENTS,
                T_INSTANCEOF,
                T_INTERFACE,
                T_NEW,
                T_NS_SEPARATOR,
                T_PAAMAYIM_NEKUDOTAYIM,
                T_USE,
                CT_USE_TRAIT,
                CT_USE_LAMBDA,
            );

            if (defined('T_TRAIT')) {
                $forbiddenTokens[] = T_TRAIT;
            }

            if (defined('T_INSTEADOF')) {
                $forbiddenTokens[] = T_INSTEADOF;
            }
        }

        if (null === $index) {
            return true;
        }

        $token = $tokens[$index];

        if ($token->equalsAny(array('{', '}'))) {
            return false;
        }

        return !$token->isGivenKind($forbiddenTokens);
    }
}

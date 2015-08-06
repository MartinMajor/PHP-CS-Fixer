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
use Symfony\CS\Tokenizer\Token;
use Symfony\CS\Tokenizer\Tokens;

/**
 * @author Martin Major ( https://github.com/MartinMajor )
 */
final class LinesBetweenMethodsFixer extends AbstractFixer
{
    const DEFAULT_INDENTATION = '    ';

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isAnyTokenKindsFound($this->getClassyTokens());
    }

    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, Tokens $tokens)
    {
        $classyTokens = $this->getClassyTokens();
        array_walk($classyTokens, function (&$item) {
            $item = array($item);
        });

        $index = 0;
        while ($start = $tokens->getNextTokenOfKind($index, $classyTokens)) {
            $curlyStart = $tokens->getNextTokenOfKind($start, array('{'));
            $curlyEnd = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_CURLY_BRACE, $curlyStart);

            $this->fixOneClass($tokens, $curlyStart, $curlyEnd);

            $index = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_CURLY_BRACE, $curlyStart);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'There MUST be exactly one empty line between methods in class/interface/trait.';
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return -40;
    }

    /**
     * @return array
     */
    private function getClassyTokens()
    {
        static $classyTokens = null;
        if ($classyTokens === null) {
            $classyTokens = array(T_CLASS, T_INTERFACE);
            if (defined('T_TRAIT')) {
                $classyTokens[] = T_TRAIT;
            }
        }

        return $classyTokens;
    }

    /**
     * @param Tokens $tokens
     * @param int $classStart
     * @param int $classEnd
     */
    private function fixOneClass(Tokens $tokens, $classStart, $classEnd)
    {
        $index = $classStart;
        while ($function = $tokens->getNextTokenOfKind($index, array(array(T_FUNCTION)))) {
            if ($function > $classEnd) {
                break;
            }

            $curlyStart = $tokens->getNextTokenOfKind($function, array('{', ';'));
            if ($tokens[$curlyStart]->getContent() === ';') {
                $curlyEnd = $curlyStart;
            } else {
                $curlyEnd = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_CURLY_BRACE, $curlyStart);
            }

            $nonWhitespacePosition = $tokens->getNextNonWhitespace($curlyEnd);
            if ($tokens[$nonWhitespacePosition]->getContent() === '}') {
                // end of class/interface/trait
                break;
            }


            $whitespacePosition = $this->getNextWhitespaceWithNewLine($tokens, $curlyEnd, $nonWhitespacePosition);

            if ($whitespacePosition === null) {
                // no whitespace between methods => add whitespace directly after method end
                $tokens->insertAt($curlyEnd + 1, new Token(array(T_WHITESPACE, "\n\n" . self::DEFAULT_INDENTATION)));
                ++$classEnd;
            } else {
                // there is whitespace between methods => make sure it has exactly one empty line and then indentation
                $whitespaceToken = $tokens[$whitespacePosition];
                $indentation = $this->getIndentation($whitespaceToken->getContent(), self::DEFAULT_INDENTATION);
                $whitespaceToken->setContent("\n\n$indentation");
            }
            $index = $curlyEnd;
        }
    }

    private function getNextWhitespaceWithNewLine(Tokens $tokens, $position, $limit)
    {
        $lastPosition = null;
        while ($position < $limit) {
            if ($tokens[$position]->isWhitespace()) {
                $lastPosition = $position;
            }

            $position = $tokens->getNextTokenOfKind($position, array(array(T_WHITESPACE)));
            if (strpos($tokens[$position]->getContent(), "\n") !== false) {
                return $position;
            }
        }
        return $lastPosition;
    }

    /**
     * @param string $content
     * @param string|null $defaultIndentation
     * @return string|null
     */
    private function getIndentation($content, $defaultIndentation = null)
    {
        if (preg_match("/[\n\r]([ \t]*)$/", $content, $matches)) {
            return $matches[1];
        }
        return $defaultIndentation;
    }
}

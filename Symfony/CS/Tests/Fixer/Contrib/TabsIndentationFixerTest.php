<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS\Tests\Fixer\Contrib;

use Symfony\CS\Tests\Fixer\AbstractFixerTestBase;

/**
 * @author Martin Major ( https://github.com/MartinMajor )
 */
class TabsIndentationFixerTest extends AbstractFixerTestBase
{
    /**
     * @dataProvider provideTestCases
     */
    public function testTabIndentation($expected, $input = null)
    {
        $this->makeTest($expected, $input);
    }

    public function provideTestCases()
    {
        $cases = array();

        $cases[] = array(
            "<?php
\techo ALPHA;",
            '<?php
    echo ALPHA;',
        );

        $cases[] = array(
            "<?php
\tif (true) {
\t\techo BRAVO;
\t}",
            '<?php
    if (true) {
        echo BRAVO;
    }',
        );

        $cases[] = array(
            "<?php
\tif (true) {
\t\techo CHARLIE;
\t}",
                '<?php
  if (true) {
    echo CHARLIE;
  }',
        );

        $cases[] = array(
            "<?php
\techo DELTA;
\t\techo ECHO;
\t\techo FOXTROT;
\t\t\techo GOLF;",
            '<?php
    echo DELTA;
        echo ECHO;
         echo FOXTROT;
            echo GOLF;',
        );

        $cases[] = array(
            "<?php \$x = \"a: \\t    \t\";"
        );

        $cases[] = array(
            "<?php\necho 1;\n?>    \$a = ellow;",
        );


        $cases[] = array(
            "<?php
\t/**
\t * Test that spaces in docblocks are converted to tabs.
\t *
\t * @test
\t *
\t * @return
\t */",
            '<?php
    /**
     * Test that spaces in docblocks are converted to tabs.
     *
     * @test
     *
     * @return
     */',
        );

        $cases[] = array(
            "<?php
class Test {
\tpublic function method {
\t\t/**
\t\t * Test that multi indent docblocks are converted to tabs.
\t\t */
\t\t\$variable='var';
\t}
}",
            '<?php
class Test {
  public function method {
    /**
     * Test that multi indent docblocks are converted to tabs.
     */
     $variable=\'var\';
  }
}',
        );

        $cases[] = array(
            "<?php
\t/*
\t | Test that spaces in comments are converted to tabs.
\t */",
            '<?php
    /*
     | Test that spaces in comments are converted to tabs.
     */',
        );

        $cases[] = array(
            "<?php
\t/**
\t * This variable
\t * should not be '\t' nor '    ', really!
\t */",
            "<?php
    /**
     * This variable
     * should not be '\t' nor '    ', really!
     */",
        );

        return $cases;
    }
}

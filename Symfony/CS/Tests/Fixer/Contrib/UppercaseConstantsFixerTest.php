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
class UppercaseConstantsFixerTest extends AbstractFixerTestBase
{
    /**
     * @dataProvider provideCases
     */
    public function testFix($expected, $input = null)
    {
        $this->makeTest($expected, $input);
    }

    /**
     * @dataProvider provide54Cases
     * @requires PHP 5.4
     */
    public function test54($expected, $input = null)
    {
        $this->makeTest($expected, $input);
    }

    public function provideCases()
    {
        return array(
            array('<?php $x = TRUE;'),
            array('<?php $x = TRUE;', '<?php $x = True;'),
            array('<?php $x = TRUE;', '<?php $x = TruE;'),
            array('<?php $x = TRUE;', '<?php $x = true;'),
            array('<?php $x = FALSE;'),
            array('<?php $x = FALSE;', '<?php $x = False;'),
            array('<?php $x = FALSE;', '<?php $x = FalsE;'),
            array('<?php $x = FALSE;', '<?php $x = false;'),
            array('<?php $x = NULL;'),
            array('<?php $x = NULL;', '<?php $x = Null;'),
            array('<?php $x = NULL;', '<?php $x = NulL;'),
            array('<?php $x = NULL;', '<?php $x = null;'),
            array('<?php $x = "true story";'),
            array('<?php $x = "false";'),
            array('<?php $x = "that is null";'),
            array('<?php $x = new True;'),
            array('<?php $x = new True();'),
            array('<?php $x = False::foo();'),
            array('<?php namespace Foo\Null;'),
            array('<?php use Foo\Null;'),
            array('<?php use Foo\Null as Null;'),
            array(
                '<?php if (TRUE) if (FALSE) if (NULL) {}',
                '<?php if (true) if (false) if (null) {}',
            ),
            array(
                '<?php if (!TRUE) if (!FALSE) if (!NULL) {}',
                '<?php if (!true) if (!false) if (!null) {}',
            ),
            array(
                '<?php if ($a == TRUE) if ($a == FALSE) if ($a == NULL) {}',
                '<?php if ($a == true) if ($a == false) if ($a == null) {}',
            ),
            array(
                '<?php if ($a === TRUE) if ($a === FALSE) if ($a === NULL) {}',
                '<?php if ($a === true) if ($a === false) if ($a === null) {}',
            ),
            array(
                '<?php if ($a != TRUE) if ($a != FALSE) if ($a != NULL) {}',
                '<?php if ($a != true) if ($a != false) if ($a != null) {}',
            ),
            array(
                '<?php if ($a !== TRUE) if ($a !== FALSE) if ($a !== NULL) {}',
                '<?php if ($a !== true) if ($a !== false) if ($a !== null) {}',
            ),
            array(
                '<?php if (TRUE && TRUE and TRUE AND TRUE || FALSE or FALSE OR FALSE xor NULL XOR NULL) {}',
                '<?php if (true && true and true AND true || false or false OR false xor null XOR null) {}',
            ),
            array(
                '<?php /* foo */ TRUE; /** bar */ FALSE;',
                '<?php /* foo */ true; /** bar */ false;',
            ),
            array('<?php class True {} class False {}, class Null {}'),
            array('<?php class Foo extends True {}'),
            array('<?php class Foo implements False {}'),
            array('<?php Class Null { use True; }'),
            array('<?php interface True {}'),
            array('<?php $foo instanceof True; $foo instanceof False; $foo instanceof Null;'),
            array(
                '<?php
    class Foo
    {
        const true;
        const false;
        const null;
    }',
            ),
        );
    }

    public function provide54Cases()
    {
        return array(
            array('<?php trait False {}'),
            array(
                '<?php
    class Null {
        use True, False {
            False::bar insteadof True;
            True::baz insteadof False;
            False::baz as Null;
        }
    }',
            ),
        );
    }
}

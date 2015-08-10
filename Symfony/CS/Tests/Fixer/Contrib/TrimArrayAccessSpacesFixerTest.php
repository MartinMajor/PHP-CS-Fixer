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
class TrimArrayAccessSpacesFixerTest extends AbstractFixerTestBase
{
    /**
     * @dataProvider provideFixCases
     */
    public function testFix($expected, $input = null)
    {
        $this->makeTest($expected, $input);
    }

    public function provideFixCases()
    {
        return array(
            array(
                '<?php $x = $y["foo"];',
            ),

            array(
                '<?php $x = $y["bar"];',
                '<?php $x = $y[ "bar" ];',
            ),

            array(
                "<?php \$x = \$y['z'];",
                "<?php \$x = \$y[\t'z'\t];",
            ),

            array(
                '<?php $a = $b[$c[d()]];',
                '<?php $a = $b[ $c[ d() ] ];',
            ),

            array(
                '<?php foo($baf[true]);',
                '<?php foo($baf[ true ]);',
            ),

            array(
                '<?php $foo[] = null;',
                '<?php $foo[ ] = null;',
            ),

            array(
                "<?php \$a[\n    'foo'\n]",
            ),

            array(
                "<?php \$a[\n\t'foo']",
                "<?php \$a[\n\t'foo' ]",
            ),

            array(
                "<?php /* \$foo[ 'a' ] = \$bar[\t'b'\t];*/",
            ),

            array(
                "<?php // \$foo[ 'a' ] = \$bar[\t'b'\t];",
            ),
        );
    }
}

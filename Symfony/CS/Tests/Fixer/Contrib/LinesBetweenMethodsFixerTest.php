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
class LinesBetweenMethodsFixerTest extends AbstractFixerTestBase
{
    public function testLines()
    {
        $input = '<?php
class MyClass
{
    public function a() {
        echo "a";
    }

    private function b($b) {
        echo "b";
    }


    function c(MyClass $c) {
        echo "c";
    }
    protected function d() {
        echo "d";
    }public static function e() {
        echo "e";
    }


    /**
     * @param MyClass $f
     */
    final function f(MyClass $f) {
        echo "f";
    }



        final public static function g(MyClass $g) {
            echo "g";
        }
        /**
         * @param MyClass $h
         */
        function h(MyClass $h) {
            echo "h";
        }


    function i() {} // comment
    abstract public function j();


}


interface MyInterface {
        public function a();
        function b();


        /**
         * @return mixed
         */
        function c();
}

function a() {}


function b() {
    echo "b";
}function c(){};

';

        $expected = '<?php
class MyClass
{
    public function a() {
        echo "a";
    }

    private function b($b) {
        echo "b";
    }

    function c(MyClass $c) {
        echo "c";
    }

    protected function d() {
        echo "d";
    }

    public static function e() {
        echo "e";
    }

    /**
     * @param MyClass $f
     */
    final function f(MyClass $f) {
        echo "f";
    }

        final public static function g(MyClass $g) {
            echo "g";
        }

        /**
         * @param MyClass $h
         */
        function h(MyClass $h) {
            echo "h";
        }

    function i() {} // comment

    abstract public function j();


}


interface MyInterface {
        public function a();

        function b();

        /**
         * @return mixed
         */
        function c();
}

function a() {}


function b() {
    echo "b";
}function c(){};

';

        if (defined(T_TRAIT)) {
            $input .= '
trait MyTrait {
        public function a() {}
        protected function b() {
                echo "b";
        }


        private function c() { echo "c"; }
        abstract function d();
        /**
         * phpdoc
         */
        final public static function e() {
                echo "e";
        }
}
';

            $expected .= '
trait MyTrait {
        public function a() {}

        protected function b() {
                echo "b";
        }

        private function c() { echo "c"; }

        abstract function d();

        /**
         * phpdoc
         */
        final public static function e() {
                echo "e";
        }
}
';
        }

        $this->makeTest($expected, $input);
    }
}

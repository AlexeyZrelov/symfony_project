<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Services\Calculator;

class CalculatorTest extends TestCase
{
    public function testSomething(): void
    {
        $calculator = new Calculator();
        $result = $calculator->add(1,9);

        $this->assertEquals(10, $result);
    }
}

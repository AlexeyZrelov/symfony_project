<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Services\PromotionCalculator;

class PromotionCalculatorTest extends TestCase
{
    public function testSomething()
    {
        $calculator = $this->getMockBuilder(PromotionCalculator::class)
        ->onlyMethods(['getPromotionPercentage'])
        // ->setMethods(['getPromotionPercentage'])
        ->getMock();

        $calculator->expects($this->any())
        ->method('getPromotionPercentage')
        ->willReturn(20);
        
        $result = $calculator->calculatePriceAfterPromotion(1,9);
        // 10 - 20%*10{2} = 8
        $this->assertEquals(8, $result);

        $result = $calculator->calculatePriceAfterPromotion(10,20,50);
        // 80 - 20%*80{16} = 64
        $this->assertEquals(64, $result);
    }
}

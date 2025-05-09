<?php

use com\tyme\rabbyung\RabByungMonth;
use PHPUnit\Framework\TestCase;

class RabByungMonthTest extends TestCase
{

    public function test0()
    {
        $this->assertEquals('第十六饶迥铁虎年十二月', RabByungMonth::fromYm(1950, 12)->__toString());
    }
}

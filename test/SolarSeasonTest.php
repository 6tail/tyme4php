<?php

use com\tyme\solar\SolarSeason;
use PHPUnit\Framework\TestCase;

/**
 * 公历季度测试
 * @author 6tail
 */
class SolarSeasonTest extends TestCase
{
    function test0()
    {
        $season = SolarSeason::fromIndex(2023, 0);
        $this->assertEquals('2023年一季度', $season->__toString());
        $this->assertEquals('2021年四季度', $season->next(-5)->__toString());
    }
}

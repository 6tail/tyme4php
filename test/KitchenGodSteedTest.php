<?php

use com\tyme\culture\KitchenGodSteed;
use PHPUnit\Framework\TestCase;

/**
 * 灶马头测试
 * @author 6tail
 */
class KitchenGodSteedTest extends TestCase
{
    function test0()
    {
        $this->assertEquals('二龙治水', KitchenGodSteed::fromLunarYear(2017)->getDragon());
    }
}

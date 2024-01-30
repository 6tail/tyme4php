<?php

use com\tyme\sixtycycle\EarthBranch;
use PHPUnit\Framework\TestCase;

/**
 * 地支测试
 * @author 6tail
 */
class EarthlyBranchTest extends TestCase
{
    function test0()
    {
        $this->assertEquals('子', EarthBranch::fromIndex(0)->getName());
    }

    function test1()
    {
        $this->assertEquals(0, EarthBranch::fromName('子')->getIndex());
    }
}

<?php

use com\tyme\holiday\LegalHoliday;
use com\tyme\solar\SolarDay;
use PHPUnit\Framework\TestCase;

/**
 * 法定节假日测试
 * @author 6tail
 */
class LegalHolidayTest extends TestCase
{
    function test0()
    {
        $d = LegalHoliday::fromYmd(2011, 5, 1);
        $this->assertNotNull($d);
        $this->assertEquals('2011年5月1日 劳动节(休)', $d->__toString());

        $this->assertEquals('2011年5月2日 劳动节(休)', $d->next(1)->__toString());
        $this->assertEquals('2011年6月4日 端午节(休)', $d->next(2)->__toString());
        $this->assertEquals('2011年4月30日 劳动节(休)', $d->next(-1)->__toString());
        $this->assertEquals('2011年4月5日 清明节(休)', $d->next(-2)->__toString());
    }


    function test1()
    {
        $this->assertNotNull(LegalHoliday::fromYmd(2010, 1, 1));
    }

    function test3()
    {
        $d = LegalHoliday::fromYmd(2001, 12, 29);
        $this->assertNotNull($d);
        $this->assertEquals('2001年12月29日 元旦节(班)', $d->__toString());
        $this->assertNull($d->next(-1));
    }

    function test4()
    {
        $d = LegalHoliday::fromYmd(2022, 10, 5);
        $this->assertNotNull($d);
        $this->assertEquals('2022年10月5日 国庆节(休)', $d->__toString());
        $this->assertEquals('2022年10月4日 国庆节(休)', $d->next(-1)->__toString());
        $this->assertEquals('2022年10月6日 国庆节(休)', $d->next(1)->__toString());
    }

    function test5()
    {
        $d = SolarDay::fromYmd(2010, 10, 1)->getLegalHoliday();
        $this->assertNotNull($d);
        $this->assertEquals('2010年10月1日 国庆节(休)', $d->__toString());
    }
}

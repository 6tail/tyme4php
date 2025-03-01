<?php

namespace com\tyme\culture;

use com\tyme\AbstractCulture;
use com\tyme\lunar\LunarDay;
use com\tyme\sixtycycle\SixtyCycle;

/**
 * 灶马头
 * @author 6tail
 * @package com\tyme\culture
 */
class KitchenGodSteed extends AbstractCulture
{
    static array $NUMBERS = ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二'];

    /**
     * 正月初一的干支
     * @var SixtyCycle
     */
    protected SixtyCycle $firstDaySixtyCycle;

    function __construct(int $lunarYear)
    {
        $this->firstDaySixtyCycle = LunarDay::fromYmd($lunarYear, 1, 1)->getSixtyCycle();
    }

    static function fromLunarYear(int $lunarYear): KitchenGodSteed
    {
        return new self($lunarYear);
    }

    protected function byHeavenStem(int $n): string
    {
        return self::$NUMBERS[$this->firstDaySixtyCycle->getHeavenStem()->stepsTo($n)];
    }

    protected function byEarthBranch(int $n): string
    {
        return self::$NUMBERS[$this->firstDaySixtyCycle->getEarthBranch()->stepsTo($n)];
    }

    /**
     * 几鼠偷粮
     *
     * @return string 几鼠偷粮
     */
    function getMouse(): string
    {
        return sprintf('%s鼠偷粮', $this->byEarthBranch(0));
    }

    /**
     * 草子几分
     *
     * @return string 草子几分
     */
    function getGrass(): string
    {
        return sprintf('草子%s分', $this->byEarthBranch(0));
    }

    /**
     * 几牛耕田（正月第一个丑日是初几，就是几牛耕田）
     *
     * @return string 几牛耕田
     */
    function getCattle(): string
    {
        return sprintf('%s牛耕田', $this->byEarthBranch(1));
    }

    /**
     * 花收几分
     *
     * @return string 花收几分
     */
    function getFlower(): string
    {
        return sprintf('花收%s分', $this->byEarthBranch(3));
    }

    /**
     * 几龙治水（正月第一个辰日是初几，就是几龙治水）
     *
     * @return string 几龙治水
     */
    function getDragon(): string
    {
        return sprintf('%s龙治水', $this->byEarthBranch(4));
    }

    /**
     * 几马驮谷
     *
     * @return string 几马驮谷
     */
    function getHorse(): string
    {
        return sprintf('%s马驮谷', $this->byEarthBranch(6));
    }

    /**
     * 几鸡抢米
     *
     * @return string 几鸡抢米
     */
    function getChicken(): string
    {
        return sprintf('%s鸡抢米', $this->byEarthBranch(9));
    }

    /**
     * 几姑看蚕
     *
     * @return string 几姑看蚕
     */
    function getSilkworm(): string
    {
        return sprintf('%s姑看蚕', $this->byEarthBranch(9));
    }

    /**
     * 几屠共猪
     *
     * @return string 几屠共猪
     */
    function getPig(): string
    {
        return sprintf('%s屠共猪', $this->byEarthBranch(11));
    }

    /**
     * 甲田几分
     *
     * @return string 甲田几分
     */
    function getField(): string
    {
        return sprintf('甲田%s分', $this->byHeavenStem(0));
    }

    /**
     * 几人分饼（正月第一个丙日是初几，就是几人分饼）
     *
     * @return string 几人分饼
     */
    function getCake(): string
    {
        return sprintf('%s人分饼', $this->byHeavenStem(2));
    }

    /**
     * 几日得金（正月第一个辛日是初几，就是几日得金）
     *
     * @return string 几日得金
     */
    function getGold(): string
    {
        return sprintf('%s日得金', $this->byHeavenStem(7));
    }

    /**
     * 几人几丙
     *
     * @return string 几人几丙
     */
    function getPeopleCakes(): string
    {
        return sprintf('%s人%s丙', $this->byEarthBranch(2), $this->byHeavenStem(2));
    }

    /**
     * 几人几锄
     *
     * @return string 几人几锄
     */
    function getPeopleHoes(): string
    {
        return sprintf('%s人%s锄', $this->byEarthBranch(2), $this->byHeavenStem(3));
    }

    function getName(): string
    {
        return '灶马头';
    }
}

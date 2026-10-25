<?php

namespace com\tyme\sixtycycle;


use com\tyme\unit\AbstractTraditionalYear;

/**
 * 干支年
 * @author 6tail
 * @package com\tyme\sixtycycle
 */
class SixtyCycleYear extends AbstractTraditionalYear
{
    function __construct(int $year)
    {
        parent::validateRange($year, -1, 9999, 'sixty cycle year');
        parent::__construct($year);
    }

    static function fromYear(int $year): static
    {
        return new static($year);
    }

    /**
     * 名称
     *
     * @return string 名称
     */
    function getName(): string
    {
        return $this->getSixtyCycle() . '年';
    }

    function next(int $n): SixtyCycleYear
    {
        return static::fromYear($this->year + $n);
    }

    /**
     * 首月（五虎遁：甲己之年丙作首，乙庚之岁戊为头，丙辛必定寻庚起，丁壬壬位顺行流，若问戊癸何方发，甲寅之上好追求。）
     *
     * @return SixtyCycleMonth 干支月
     */
    function getFirstMonth(): SixtyCycleMonth
    {
        return new SixtyCycleMonth($this, SixtyCycle::fromIndex($this->year * 12 - 46));
    }

    /**
     * 干支月列表
     *
     * @return SixtyCycleMonth[] 干支月列表
     */
    function getMonths(): array
    {
        $l = array();
        $m = $this->getFirstMonth();
        $l[] = $m;
        for ($i = 1; $i < 12; $i++) {
            $l[] = $m->next($i);
        }
        return $l;
    }
}

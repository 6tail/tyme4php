<?php

namespace com\tyme\culture\pengzu;


use com\tyme\AbstractCulture;
use com\tyme\sixtycycle\SixtyCycle;

/**
 * 彭祖百忌
 * @author 6tail
 * @package com\tyme\culture\pengzu
 */
class PengZu extends AbstractCulture
{
    /**
     * 天干彭祖百忌
     */
    protected PengZuHeavenStem $pengZuHeavenStem;

    /**
     * 地支彭祖百忌
     */
    protected PengZuEarthBranch $pengZuEarthBranch;

    protected function __construct(SixtyCycle $sixtyCycle)
    {
        $this->pengZuHeavenStem = PengZuHeavenStem::fromIndex($sixtyCycle->getHeavenStem()->getIndex());
        $this->pengZuEarthBranch = PengZuEarthBranch::fromIndex($sixtyCycle->getEarthBranch()->getIndex());
    }

    /**
     * 从干支初始化
     *
     * @param SixtyCycle $sixtyCycle 干支
     * @return PengZu 彭祖百忌
     */
    static function fromSixtyCycle(SixtyCycle $sixtyCycle): static
    {
        return new static($sixtyCycle);
    }

    function getName(): string
    {
        return sprintf('%s %s', $this->pengZuHeavenStem, $this->pengZuEarthBranch);
    }

    /**
     * 天干彭祖百忌
     *
     * @return PengZuHeavenStem 天干彭祖百忌
     */
    function getPengZuHeavenStem(): PengZuHeavenStem
    {
        return $this->pengZuHeavenStem;
    }

    /**
     * 地支彭祖百忌
     *
     * @return PengZuEarthBranch 地支彭祖百忌
     */
    function getPengZuEarthBranch(): PengZuEarthBranch
    {
        return $this->pengZuEarthBranch;
    }
}

<?php

namespace com\tyme\sixtycycle;


use com\tyme\culture\pengzu\PengZu;
use com\tyme\culture\Sound;
use com\tyme\culture\Ten;
use com\tyme\LoopTyme;

/**
 * 六十甲子(六十干支周)
 * @author 6tail
 * @package com\tyme\sixtycycle
 */
class SixtyCycle extends LoopTyme
{
    static array $NAMES = ['甲子', '乙丑', '丙寅', '丁卯', '戊辰', '己巳', '庚午', '辛未', '壬申', '癸酉', '甲戌', '乙亥', '丙子', '丁丑', '戊寅', '己卯', '庚辰', '辛巳', '壬午', '癸未', '甲申', '乙酉', '丙戌', '丁亥', '戊子', '己丑', '庚寅', '辛卯', '壬辰', '癸巳', '甲午', '乙未', '丙申', '丁酉', '戊戌', '己亥', '庚子', '辛丑', '壬寅', '癸卯', '甲辰', '乙巳', '丙午', '丁未', '戊申', '己酉', '庚戌', '辛亥', '壬子', '癸丑', '甲寅', '乙卯', '丙辰', '丁巳', '戊午', '己未', '庚申', '辛酉', '壬戌', '癸亥'];

    protected function __construct(int $index = null, string $name = null)
    {
        if ($index !== null) {
            parent::__construct(self::$NAMES, $index);
        } else if ($name !== null) {
            parent::__construct(self::$NAMES, null, $name);
        }
    }

    static function fromIndex(int $index): static
    {
        return new static($index);
    }

    static function fromName(string $name): static
    {
        return new static(null, $name);
    }

    function next(int $n): static
    {
        return self::fromIndex($this->nextIndex($n));
    }

    /**
     * 天干
     *
     * @return HeavenStem 天干
     */
    function getHeavenStem(): HeavenStem
    {
        return HeavenStem::fromIndex($this->index % count(HeavenStem::$NAMES));
    }

    /**
     * 地支
     *
     * @return EarthBranch 地支
     */
    function getEarthBranch(): EarthBranch
    {
        return EarthBranch::fromIndex($this->index % count(EarthBranch::$NAMES));
    }

    /**
     * 纳音
     *
     * @return Sound 纳音
     */
    function getSound(): Sound
    {
        return Sound::fromIndex(intdiv($this->index, 2));
    }

    /**
     * 彭祖百忌
     *
     * @return PengZu 彭祖百忌
     */
    function getPengZu(): PengZu
    {
        return PengZu::fromSixtyCycle($this);
    }

    /**
     * 旬
     *
     * @return Ten 旬
     */
    function getTen(): Ten
    {
        return Ten::fromIndex(intdiv($this->getHeavenStem()->getIndex() - $this->getEarthBranch()->getIndex(), 2));
    }

    /**
     * 旬空(空亡)，因地支比天干多2个，旬空则为每一轮干支一一配对后多出来的2个地支
     *
     * @return EarthBranch[] 旬空(空亡)
     */
    function getExtraEarthBranches(): array
    {
        $l = array();
        $l[] = EarthBranch::fromIndex(10 + $this->getEarthBranch()->getIndex() - $this->getHeavenStem()->getIndex());
        $l[] = $l[0]->next(1);
        return $l;
    }

}

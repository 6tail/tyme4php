<?php

namespace com\tyme\culture\phenology;


use com\tyme\LoopTyme;

/**
 * 候
 * @author 6tail
 * @package com\tyme\culture\phenology
 */
class Phenology extends LoopTyme
{
    static array $NAMES = ['蚯蚓结', '麋角解', '水泉动', '雁北乡', '鹊始巢', '雉始雊', '鸡始乳', '征鸟厉疾', '水泽腹坚', '东风解冻', '蛰虫始振', '鱼陟负冰', '獭祭鱼', '候雁北', '草木萌动', '桃始华', '仓庚鸣', '鹰化为鸠', '玄鸟至', '雷乃发声', '始电', '桐始华', '田鼠化为鴽', '虹始见', '萍始生', '鸣鸠拂奇羽', '戴胜降于桑', '蝼蝈鸣', '蚯蚓出', '王瓜生', '苦菜秀', '靡草死', '麦秋至', '螳螂生', '鵙始鸣', '反舌无声', '鹿角解', '蜩始鸣', '半夏生', '温风至', '蟋蟀居壁', '鹰始挚', '腐草为萤', '土润溽暑', '大雨行时', '凉风至', '白露降', '寒蝉鸣', '鹰乃祭鸟', '天地始肃', '禾乃登', '鸿雁来', '玄鸟归', '群鸟养羞', '雷始收声', '蛰虫坯户', '水始涸', '鸿雁来宾', '雀入大水为蛤', '菊有黄花', '豺乃祭兽', '草木黄落', '蛰虫咸俯', '水始冰', '地始冻', '雉入大水为蜃', '虹藏不见', '天气上升地气下降', '闭塞而成冬', '鹖鴠不鸣', '虎始交', '荔挺出'];

    protected function __construct(?int $index = null, ?string $name = null)
    {
        if ($index !== null) {
            parent::__construct(static::$NAMES, $index);
        } else if ($name !== null) {
            parent::__construct(static::$NAMES, null, $name);
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
        return static::fromIndex($this->nextIndex($n));
    }

    /**
     * 三候
     *
     * @return ThreePhenology 三候
     */
    function getThreePhenology(): ThreePhenology
    {
        return ThreePhenology::fromIndex($this->index % 3);
    }

}

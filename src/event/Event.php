<?php

namespace com\tyme\event;


use com\tyme\AbstractCulture;
use com\tyme\enums\EventType;
use com\tyme\lunar\LunarDay;
use com\tyme\lunar\LunarMonth;
use com\tyme\solar\SolarDay;
use com\tyme\solar\SolarMonth;
use com\tyme\solar\SolarTerm;
use InvalidArgumentException;

/**
 * 事件
 * @author 6tail
 * @package com\tyme\event
 */
class Event extends AbstractCulture
{
    /**
     * 名称
     */
    protected string $name;

    /**
     * 数据
     */
    protected string $data;

    /**
     * 构造方法
     * @param string $name 名称
     * @param string $data 数据
     */
    function __construct(string $name, string $data)
    {
        self::validate($data);
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * 验证数据格式
     * @param string $data 数据
     */
    public static function validate(string $data): void
    {
        if (strlen($data) !== 9) {
            throw new InvalidArgumentException('illegal event data: ' . $data);
        }
    }

    /**
     * 获取构造器
     * @return EventBuilder 事件构造器
     */
    public static function builder(): EventBuilder
    {
        return new EventBuilder();
    }

    /**
     * 根据名称从事件管理器中获取事件
     * @param string $name 名称
     * @return self|null 事件
     */
    public static function fromName(string $name): ?self
    {
        if (preg_match(sprintf(EventManager::REGEX, preg_quote($name, '/')), EventManager::$DATA, $matches)) {
            return new self($name, $matches[1]);
        }
        return null;
    }

    /**
     * 获取事件类型
     * @return EventType|null 事件类型
     */
    public function getType(): ?EventType
    {
        return EventType::fromCode(strpos(EventManager::CHARS, $this->data[1]));
    }

    /**
     * 获取名称
     * @return string 名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 获取数据
     * @return string 数据
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * 获取起始年
     * @return int 年
     */
    public function getStartYear(): int
    {
        $n = 0;
        $size = strlen(EventManager::CHARS);
        for ($i = 0; $i < 3; $i++) {
            $n = $n * $size + strpos(EventManager::CHARS, $this->data[6 + $i]);
        }
        return $n;
    }

    /**
     * 获取指定公历日的事件列表
     * @param SolarDay $d 公历日
     * @return self[] 事件列表
     */
    public static function fromSolarDay(SolarDay $d): array
    {
        $l = [];
        foreach (self::all() as $e) {
            if ($d->equals($e->getSolarDay($d->getYear()))) {
                $l[] = $e;
            }
        }
        return $l;
    }

    /**
     * 获取所有事件
     * @return self[] 事件列表
     */
    public static function all(): array
    {
        $l = [];
        preg_match_all(sprintf(EventManager::REGEX, '[^@]+'), EventManager::$DATA, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $l[] = new self($match[2], $match[1]);
        }
        return $l;
    }

    /**
     * 根据年份获取对应的公历日
     * @param int $year 年
     * @return SolarDay|null 公历日
     */
    public function getSolarDay(int $year): ?SolarDay
    {
        $type = $this->getType();
        if ($type === null) {
            return null;
        }
        if ($year < $this->getStartYear()) {
            return null;
        }
        $d = null;
        switch ($type) {
            case EventType::SOLAR_DAY:
                $d = $this->getSolarDayBySolarDay($year);
                break;
            case EventType::SOLAR_WEEK:
                $d = $this->getSolarDayByWeek($year);
                break;
            case EventType::LUNAR_DAY:
                $d = $this->getSolarDayByLunarDay($year);
                break;
            case EventType::TERM_DAY:
                $d = $this->getSolarDayByTerm($year);
                break;
            case EventType::TERM_HS:
                $d = $this->getSolarDayByTermHeavenStem($year);
                break;
            case EventType::TERM_EB:
                $d = $this->getSolarDayByTermEarthBranch($year);
                break;
        }
        if ($d === null) {
            return null;
        }
        $offset = strpos(EventManager::CHARS, $this->data[5]) - 31;
        return $offset === 0 ? $d : $d->next($offset);
    }

    /**
     * 根据公历日获取公历日
     * @param int $year 年
     * @return SolarDay|null 公历日
     */
    protected function getSolarDayBySolarDay(int $year): ?SolarDay
    {
        $y = $year;
        $m = strpos(EventManager::CHARS, $this->data[2]) - 31;
        if ($m > 12) {
            $m = 1;
            $y += 1;
        }
        $d = strpos(EventManager::CHARS, $this->data[3]) - 31;
        $delay = strpos(EventManager::CHARS, $this->data[4]) - 31;
        $month = SolarMonth::fromYm($y, $m);
        $lastDay = $month->getDayCount();
        if ($d > $lastDay) {
            if ($delay === 0) {
                return null;
            }
            return $delay < 0 ? SolarDay::fromYmd($y, $m, $d + $delay) : SolarDay::fromYmd($y, $m, $lastDay)->next($delay);
        }
        return SolarDay::fromYmd($y, $m, $d);
    }

    /**
     * 根据农历日获取公历日
     * @param int $year 年
     * @return SolarDay|null 公历日
     */
    protected function getSolarDayByLunarDay(int $year): ?SolarDay
    {
        $y = $year;
        $m = strpos(EventManager::CHARS, $this->data[2]) - 31;
        if ($m > 12) {
            $m = 1;
            $y += 1;
        }
        $d = strpos(EventManager::CHARS, $this->data[3]) - 31;
        $delay = strpos(EventManager::CHARS, $this->data[4]) - 31;
        $month = LunarMonth::fromYm($y, $m);
        $lastDay = $month->getDayCount();
        if ($d > $lastDay) {
            if ($delay === 0) {
                return null;
            }
            return $delay < 0 ? LunarDay::fromYmd($y, $m, $d + $delay)->getSolarDay() : LunarDay::fromYmd($y, $m, $lastDay)->getSolarDay()->next($delay);
        }
        return LunarDay::fromYmd($y, $m, $d)->getSolarDay();
    }

    /**
     * 根据星期获取公历日
     * @param int $year 年
     * @return SolarDay|null 公历日
     */
    protected function getSolarDayByWeek(int $year): ?SolarDay
    {
        $n = strpos(EventManager::CHARS, $this->data[3]) - 31;
        if ($n === 0) {
            return null;
        }
        $m = SolarMonth::fromYm($year, strpos(EventManager::CHARS, $this->data[2]) - 31);
        $w = strpos(EventManager::CHARS, $this->data[4]) - 31;
        if ($n > 0) {
            $d = $m->getFirstDay();
            return $d->next($d->getWeek()->stepsTo($w) + 7 * $n - 7);
        } else {
            $d = SolarDay::fromYmd($year, $m->getMonth(), $m->getDayCount());
            return $d->next($d->getWeek()->stepsBackTo($w) + 7 * $n + 7);
        }
    }

    /**
     * 根据节气获取公历日
     * @param int $year 年
     * @return SolarDay|null 公历日
     */
    protected function getSolarDayByTerm(int $year): ?SolarDay
    {
        $offset = strpos(EventManager::CHARS, $this->data[4]) - 31;
        $termIndex = strpos(EventManager::CHARS, $this->data[2]) - 31;
        $d = SolarTerm::fromIndex($year, $termIndex)->getSolarDay();
        return $offset === 0 ? $d : $d->next($offset);
    }

    /**
     * 根据节气天干获取公历日
     * @param int $year 年
     * @return SolarDay|null 公历日
     */
    protected function getSolarDayByTermHeavenStem(int $year): ?SolarDay
    {
        $d = $this->getSolarDayByTerm($year);
        $targetHsIndex = strpos(EventManager::CHARS, $this->data[3]) - 31;
        $steps = $d->getLunarDay()->getSixtyCycle()->getHeavenStem()->stepsTo($targetHsIndex);
        return $d->next($steps);
    }

    /**
     * 根据节气地支获取公历日
     * @param int $year 年
     * @return SolarDay|null 公历日
     */
    protected function getSolarDayByTermEarthBranch(int $year): ?SolarDay
    {
        $d = $this->getSolarDayByTerm($year);
        $targetEbIndex = strpos(EventManager::CHARS, $this->data[3]) - 31;
        $steps = $d->getLunarDay()->getSixtyCycle()->getEarthBranch()->stepsTo($targetEbIndex);
        return $d->next($steps);
    }
}

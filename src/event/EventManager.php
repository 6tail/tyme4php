<?php

namespace com\tyme\event;


/**
 * 事件管理器
 * @author 6tail
 * @package com\tyme\event
 */
class EventManager
{
    /**
     * 有效字符
     */
    public const CHARS = '0123456789ABCDEFGHIJKLMNOPQRSTU_VWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * 全量事件数据
     */
    public static string $DATA = '';

    /**
     * 数据匹配的正则表达式
     */
    public const REGEX = '/(@[0-9A-Za-z_]{8})(%s)/';

    /**
     * 删除事件
     * @param string $name 名称
     */
    public static function remove(string $name): void
    {
        self::$DATA = preg_replace(sprintf(self::REGEX, preg_quote($name, '/')), '', self::$DATA);
    }

    /**
     * 保存或更新事件数据
     * @param string $name 名称
     * @param string $data 数据
     */
    protected static function saveOrUpdate(string $name, string $data): void
    {
        $pattern = sprintf(self::REGEX, preg_quote($name, '/'));
        if (preg_match($pattern, self::$DATA)) {
            self::$DATA = preg_replace($pattern, $data, self::$DATA);
        } else {
            self::$DATA .= $data;
        }
    }

    /**
     * 新增或更新事件（通过Event对象）
     * @param string $name 名称
     * @param Event $event 事件
     */
    public static function update(string $name, Event $event): void
    {
        self::saveOrUpdate($name, $event->getData() . (empty($event->getName()) ? $name : $event->getName()));
    }

    /**
     * 新增或更新事件（直接提供数据）
     * @param string $name 名称
     * @param string $data 数据
     */
    public static function updateData(string $name, string $data): void
    {
        Event::validate($data);
        self::saveOrUpdate($name, $data);
    }
}

<?php

namespace com\tyme;

use BadMethodCallException;
use Closure;

/**
 * 扩展Trait
 * @author 6tail
 * @package com\tyme
 */
trait ExtendTrait
{
    /**
     * @var array 扩展方法列表
     */
    protected static array $methods = [];

    /**
     * 扩展方法
     * @param string $methodName 方法名
     * @param Closure $function 方法体
     * @return void
     */
    static function extend(string $methodName, Closure $function): void
    {
        static::$methods[$methodName] = $function;
    }

    /**
     * 方法调用
     * @param $method string 方法名
     * @param $parameters mixed 参数
     * @return mixed
     * @throws BadMethodCallException
     */
    function __call(string $method, mixed $parameters)
    {
        if (!isset(static::$methods[$method])) {
            throw new BadMethodCallException(sprintf('Method %s not exist in %s', $method, static::class));
        }
        $function = static::$methods[$method];
        $function = $function->bindTo($this, static::class);
        return $function(...$parameters);
    }
}

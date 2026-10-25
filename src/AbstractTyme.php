<?php

namespace com\tyme;


use BadMethodCallException;

/**
 * 抽象Tyme
 *
 * @author 6tail
 * @package com\tyme
 */
abstract class AbstractTyme extends AbstractCulture implements Tyme
{
    function next(int $n): Tyme
    {
        throw new BadMethodCallException(__FUNCTION__ . ' is not implemented');
    }
}

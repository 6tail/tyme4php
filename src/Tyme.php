<?php

namespace com\tyme;


/**
 * Tyme
 * @author 6tail
 * @package com\tyme
 */
interface Tyme extends Culture
{
    /**
     * 推移
     * @param int $n 推移步数
     * @return Tyme Tyme
     */
    function next(int $n): Tyme;
}

# Tyme [![License](https://img.shields.io/badge/license-MIT-4EB1BA.svg?style=flat-square)](https://github.com/6tail/tyme4php/blob/master/LICENSE)

Tyme是一个非常强大的日历工具库，可以看作 [Lunar](https://6tail.cn/calendar/api.html "https://6tail.cn/calendar/api.html") 的升级版，拥有更优的设计和扩展性，支持公历、农历、藏历、星座、干支、生肖、节气、法定假日等。


> 基于php8.1开发。

## composer

    composer require 6tail/tyme4php
     
    <?php
    use com\tyme\solar\SolarDay;
     
    $solarDay = SolarDay::fromYmd(1986, 5, 29);
     
    // 1986年5月29日
    echo $solarDay;
     
    // 农历丙寅年四月廿一
    echo $solarDay->getLunarDay();
     
    // 第十七饶迥火虎年四月廿一
    echo $solarDay->getRabByungDay();
     
    // 扩展方法
    SolarDay::extend('myMethod', function () {
        return sprintf('%04d-%02d-%02d', $this->getYear(), $this->getMonth(), $this->getDay());
    });
    // 1986-05-29
    echo $solarDay->myMethod();

## 单文件版本

> 1. 下载本源代码，执行<code>tools/build-standalone.php</code>，可在tools目录下生成<code>Tyme.php</code>单文件。
> 2. 可在 [Releases](https://github.com/6tail/tyme4php/releases) 中下载对应版本的<code>Tyme.php</code>单文件。

    <?php
    require 'Tyme.php';
     
    use com\tyme\solar\SolarDay;
     
    $solarDay = SolarDay::fromYmd(1986, 5, 29);
     
    // 1986年5月29日
    echo $solarDay;
     
    // 农历丙寅年四月廿一
    echo $solarDay->getLunarDay();
     
    // 第十七饶迥火虎年四月廿一
    echo $solarDay->getRabByungDay();

## 文档

请移步至 [https://6tail.cn/tyme.html](https://6tail.cn/tyme.html "https://6tail.cn/tyme.html")

## Star History

[![Star History Chart](https://api.star-history.com/svg?repos=6tail/tyme4php&type=Date)](https://star-history.com/#6tail/tyme4php&Date)

## 致谢
1. 感谢许剑伟老师分享的寿星天文历，本项目节气算法引自 [https://github.com/sxwnl/sxwnl](https://github.com/sxwnl/sxwnl "https://github.com/sxwnl/sxwnl")
2. 感谢[stonelf](https://github.com/stonelf "https://github.com/stonelf")，本项目藏历数据引自 [https://github.com/stonelf/zangli](https://github.com/stonelf/zangli "https://github.com/stonelf/zangli")

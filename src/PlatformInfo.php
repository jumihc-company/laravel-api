<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful;

class PlatformInfo
{
    const OTHER = 'other';
    const ANDROID = 'android';
    const IOS = 'ios';
    const WEI_MP = 'wei_mp';
    const ALI_MP = 'ali_mp';

    /**
     * @var array
     */
    protected static $platforms = [
        'JmhcAndroid' => self::ANDROID,
        'JmhcIos' => self::IOS,
        'JmhcWeiMp' => self::WEI_MP,
        'JmhcAliMp' => self::ALI_MP,
    ];

    /**
     * 获取所有平台
     * @return array
     */
    public static function getAllPlatform()
    {
        return static::$platforms;
    }
}

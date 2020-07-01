<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Contracts;

/**
 * 平台信息
 * @package Jmhc\Restful\Contracts
 */
interface PlatformInfoInterface
{
    // 所在平台
    const OTHER = 'other';
    const PC = 'pc';
    const MOBILE = 'mobile';
    const ANDROID = 'android';
    const IOS = 'ios';
    const WECHAT = 'wechat';
    const WECHAT_MP = 'wechat_mp';
    const ALIPAY = 'alipay';
    const ALIPAY_MP = 'alipay_mp';

    /**
     * 平台信息 [关键字 => 平台]
     * @var array
     */
    const KEYWORDS_PLATFORMS = [
        'JmhcPc' => self::PC,
        'JmhcMobile' => self::MOBILE,
        'JmhcAndroid' => self::ANDROID,
        'JmhcIos' => self::IOS,
        'JmhcWechat' => self::WECHAT,
        'JmhcWechatMp' => self::WECHAT_MP,
        'JmhcAlipay' => self::ALIPAY,
        'JmhcAlipayMp' => self::ALIPAY_MP,
    ];
}
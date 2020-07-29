<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Contracts;

/**
 * 版本模型
 * @package Jmhc\Restful\Contracts
 */
interface VersionModelInterface
{
    const PLATFORM_ANDROID = 1; // 安卓
    const PLATFORM_APPLE = 2; // 苹果

    // 获取最新版本信息
    public function getLastInfo();

    // 通过平台获取最新版本信息
    public function getLastInfoByPlatform(int $platform);
}

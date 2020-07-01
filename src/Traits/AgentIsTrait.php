<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Traits;

use Jmhc\Restful\Contracts\PlatformInfoInterface;
use Jmhc\Restful\Utils\RequestPlatform;

trait AgentIsTrait
{
    public function isDesktop($userAgent = null, $httpHeaders = null)
    {
        return parent::isDesktop($userAgent, $httpHeaders) || $this->requestPlatformIs(PlatformInfoInterface::PC, $userAgent, $httpHeaders);
    }

    public function isMobile($userAgent = null, $httpHeaders = null)
    {
        return parent::isMobile($userAgent, $httpHeaders) || $this->requestPlatformIs(PlatformInfoInterface::MOBILE, $userAgent, $httpHeaders);
    }

    public function isIPhone($userAgent = null, $httpHeaders = null)
    {
        return $this->is('IPhone', $userAgent, $httpHeaders) || $this->requestPlatformIs(PlatformInfoInterface::IOS, $userAgent, $httpHeaders);
    }

    public function isIOS($userAgent = null, $httpHeaders = null)
    {
        return $this->is('IOS', $userAgent, $httpHeaders) || $this->requestPlatformIs(PlatformInfoInterface::IOS, $userAgent, $httpHeaders);
    }

    public function isSafari($userAgent = null, $httpHeaders = null)
    {
        return $this->is('Safari', $userAgent, $httpHeaders) || $this->requestPlatformIs(PlatformInfoInterface::IOS, $userAgent, $httpHeaders);
    }

    public function isAndroid($userAgent = null, $httpHeaders = null)
    {
        return $this->is('Android', $userAgent, $httpHeaders) || $this->requestPlatformIs(PlatformInfoInterface::ANDROID, $userAgent, $httpHeaders);
    }

    public function isAndroidOS($userAgent = null, $httpHeaders = null)
    {
        return $this->is('AndroidOS', $userAgent, $httpHeaders) || $this->requestPlatformIs(PlatformInfoInterface::ANDROID, $userAgent, $httpHeaders);
    }

    public function isWechat($userAgent = null, $httpHeaders = null)
    {
        return $this->is('Wechat', $userAgent, $httpHeaders) || $this->requestPlatformIs(PlatformInfoInterface::WECHAT, $userAgent, $httpHeaders);
    }

    public function isWechatMp($userAgent = null, $httpHeaders = null)
    {
        return $this->requestPlatformIs(PlatformInfoInterface::WECHAT_MP, $userAgent, $httpHeaders);
    }

    public function isAlipay($userAgent = null, $httpHeaders = null)
    {
        return $this->requestPlatformIs(PlatformInfoInterface::ALIPAY, $userAgent, $httpHeaders);
    }

    public function isAlipayMp($userAgent = null, $httpHeaders = null)
    {
        return $this->requestPlatformIs(PlatformInfoInterface::ALIPAY_MP, $userAgent, $httpHeaders);
    }

    public function requestPlatformIs(string $platform, $userAgent = null, $httpHeaders = null)
    {
        if ($userAgent) {
            $this->requestPlatforms = RequestPlatform::check($userAgent);
        }

        return in_array($platform, $this->requestPlatforms);
    }
}
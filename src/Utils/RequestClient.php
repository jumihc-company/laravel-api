<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Utils;

use GuzzleHttp\Client;
use Jmhc\Restful\Traits\InstanceTrait;

/**
 * 请求客户端
 * @package Jmhc\Restful\Utils
 */
class RequestClient extends Client
{
    use InstanceTrait;

    public function __construct(array $config = [])
    {
        $default = [
            'verify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36',
            ],
        ];
        parent::__construct($default + $config);
    }
}
<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Http\Request;
use Jmhc\Restful\Traits\AgentIsTrait;
use Jmhc\Support\Traits\InstanceTrait;

/**
 * 请求 Agent 类
 * @package Jmhc\Restful\Utils
 */
class Agent extends \Jenssegers\Agent\Agent
{
    use InstanceTrait;
    use AgentIsTrait;

    /**
     * @var array
     */
    protected $requestPlatforms;

    public function __construct(
        Request $request,
        array $headers = null,
        $userAgent = null
    ) {
        parent::__construct($headers, $userAgent);
        $this->requestPlatforms = RequestPlatform::run($request);
    }
}
<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Jmhc\Restful\Utils\LogHelper;

class RequestLog
{
    /**
     * 请求日志
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws BindingResolutionException
     */
    public function handle(Request $request, Closure $next)
    {
        // 记录请求日志
        LogHelper::request()
            ->debug('', $this->buildContent($request));

        return $next($request);
    }

    /**
     * 生成消息
     * @param Request $request
     * @return string
     */
    protected function buildContent(Request $request)
    {
        $data = json_encode($request->all(), JSON_UNESCAPED_UNICODE);
        return <<<EOF
ip : {$request->ip()}
referer : {$request->server('HTTP_REFERER', '-')}
user_agent : {$request->server('HTTP_USER_AGENT', '-')}
method : {$request->getMethod()}
url : {$request->fullUrl()}
data : {$data}
EOF;
    }
}

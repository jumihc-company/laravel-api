<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jmhc\Restful\Contracts\VersionModelInterface;
use Jmhc\Restful\ResultCode;
use Jmhc\Restful\ResultMsg;
use Jmhc\Restful\Traits\ResultThrowTrait;

/**
 * 检测版本中间件
 * @package Jmhc\Restful\Middleware
 */
class CheckVersionMiddleware
{
    use ResultThrowTrait;

    public function handle(Request $request, Closure $next)
    {
        // 当前版本
        $version = $this->getVersion(
            $request,
            'version'
        );

        // 判断版本号是否存在
        if (empty($version)) {
            $this->error('版本号不存在');
        }

        // 验证版本
        $info = app()->get(VersionModelInterface::class)->getLastInfo();
        if (! empty($info)) {
            if ($version < $info->code && $info->is_force) {
                $this->error(ResultMsg::OLD_VERSION, ResultCode::OLD_VERSION, [
                    'content' => $info->content,
                    'url' => $info->url,
                ]);
            }
        }

        return $next($request);
    }

    /**
     * 获取version
     * @param Request $request
     * @param string $name
     * @return array|string|null
     */
    protected function getVersion(Request $request, string $name)
    {
        $version = $request->header(ucwords($name, '-'), 0);
        if (empty($version)) {
            $version = $request->input($name, 0);
        }

        return $version;
    }
}

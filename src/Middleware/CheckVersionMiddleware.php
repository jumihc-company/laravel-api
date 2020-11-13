<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jmhc\Restful\Contracts\ResultCodeInterface;
use Jmhc\Restful\Contracts\VersionModelInterface;
use Jmhc\Restful\Traits\ResultThrowTrait;
use Jmhc\Support\Utils\RequestInfo;

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
        $version = RequestInfo::getParam($request, 'version', 0);

        // 判断版本号是否存在
        if (empty($version)) {
            $this->error(jmhc_api_lang_messages_trans('version_no_exist'));
        }

        // 验证版本
        $info = $this->getVersionLastInfo();
        if (! empty($info)) {
            if ($version < $info->code && $info->is_force) {
                $this->error(jmhc_api_lang_messages_trans('old_version'), ResultCodeInterface::OLD_VERSION, [
                    'content' => $info->content,
                    'url' => $info->url,
                ]);
            }
        }

        return $next($request);
    }

    /**
     * 获取版本最新信息
     * @return mixed
     */
    protected function getVersionLastInfo()
    {
        return app()->get(VersionModelInterface::class)->getLastInfo();
    }
}

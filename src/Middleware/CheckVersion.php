<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jmhc\Restful\Contracts\Version;
use Jmhc\Restful\ResultCode;
use Jmhc\Restful\ResultException;
use Jmhc\Restful\ResultMsg;
use Jmhc\Restful\Traits\ResultThrow;
use Jmhc\Restful\Utils\Env;

class CheckVersion
{
    use ResultThrow;

    /**
     * 验证版本
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ResultException
     */
    public function handle(Request $request, Closure $next)
    {
        // 当前版本
        $version = $this->getVersion(
            $request,
            Env::get('jmhc.request.version_name', 'version')
        );

        // 判断版本号是否存在
        if (empty($version)) {
            static::error('版本号不存在');
        }

        // 验证版本
        $info = app()->get(Version::class)->getLastInfo();
        if (! empty($info)) {
            if ($version < $info->code && $info->is_force) {
                static::error(ResultMsg::OLD_VERSION, ResultCode::OLD_VERSION, [
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
        $version = $request->header($name, 0);
        if (empty($version)) {
            $version = $request->input($name, 0);
        }

        return $version;
    }
}

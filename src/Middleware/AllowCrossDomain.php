<?php
/**
 * User: YL
 * Date: 2019/10/16
 */

namespace Jmhc\Restful\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowCrossDomain
{
    /**
     * 设置跨域
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $header = config('jmhc-cross', []);

        if (empty($header['Access-Control-Allow-Origin'])) {
            $origin = $request->header('origin');

            if ($origin) {
                $header['Access-Control-Allow-Origin'] = $origin;
            } else {
                $header['Access-Control-Allow-Origin'] = '*';
            }
        }

        if ($request->getMethod() === 'OPTIONS') {
            return response('', 204, $header);
        }

        return $next($request)->withHeaders($header);
    }
}

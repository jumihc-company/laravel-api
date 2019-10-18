<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

namespace Jmhc\Restful\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest as BaseTransformsRequest;
use Symfony\Component\HttpFoundation\ParameterBag;

class TransformsRequest extends BaseTransformsRequest
{
    protected function clean($request)
    {
        $this->cleanParameterBag(
            new ParameterBag($request->params ?? [])
        );
    }
}

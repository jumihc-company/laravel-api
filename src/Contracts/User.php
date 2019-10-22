<?php
/**
 * User: YL
 * Date: 2019/10/21
 */

namespace Jmhc\Restful\Contracts;

interface User
{
    // 通过id获取信息
    public static function getInfoById(int $id);
}

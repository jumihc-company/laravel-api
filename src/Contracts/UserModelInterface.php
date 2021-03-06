<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Contracts;

/**
 * 用户模型
 * @package Jmhc\Restful\Contracts
 */
interface UserModelInterface
{
    // 通过id获取信息
    public function getInfoById(int $id);
}

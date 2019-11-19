<?php
/**
 * User: YL
 * Date: 2019/11/18
 */

namespace Jmhc\Restful\Console\Commands;

use Illuminate\Console\Command;
use Jmhc\Restful\Console\Commands\Traits\MakeTrait;

/**
 * 创建命令抽象基类
 * @package Jmhc\Restful\Console\Commands
 */
abstract class AbstractMakeCommand extends Command
{
    use MakeTrait;

    /**
     * 文件保存路径
     * @var string
     */
    protected $dir;

    /**
     * 命名空间
     * @var string
     */
    protected $namespace;

    abstract protected function mainHandle();
    abstract protected function getSaveDir();

    public function handle()
    {
        // 设置参数、选项
        $this->setArgumentOption();

        // 获取保存文件夹
        $dir = $this->getSaveDir();
        // 保存文件夹
        $this->dir = app_path($dir);
        // 命名空间
        $this->namespace = $this->getNamespace($dir);

        // 创建文件夹
        $this->createDir($this->dir);

        // 运行
        $this->mainHandle();

        // 运行完成
        $this->runComplete();
    }

    /**
     * 设置参数、请求
     */
    protected function setArgumentOption()
    {}
}

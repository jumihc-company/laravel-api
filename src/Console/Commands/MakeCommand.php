<?php
/**
 * User: YL
 * Date: 2019/10/22
 */

namespace Jmhc\Restful\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

abstract class MakeCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = '%s
    {name : %s name}
    {--m|module= : Module name}
    {--f|force : Whether to overwrite an existing file}';

    /**
     * @var string
     */
    protected $description = 'Generate the %s file';

    /**
     * @var string
     */
    protected $command;

    /**
     * @var string
     */
    protected $argumentName;

    /**
     * @var string
     */
    protected $defaultNamespace = 'App\Http%s\%s';

    /**
     * @var string
     */
    protected $defaultDir = 'Http%s/%s/';

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $dir;

    abstract protected function getBuildContent(string $name);

    public function __construct()
    {
        $this->signature = sprintf(
            $this->signature,
            $this->command,
            $this->argumentName
        );

        $this->description = sprintf(
            $this->signature,
            strtolower($this->argumentName)
        );

        parent::__construct();
    }

    public function handle()
    {
        // 获取保存文件夹
        $dir = $this->getSaveDir();
        // 保存文件夹
        $this->dir = app_path($dir);
        // 命名空间
        $this->namespace = $this->getNamespace($dir);

        // 创建文件夹
        $this->createDir();

        // 构建名称
        $name = $this->getBuildName($this->argument('name'));

        // 保存文件
        $filePath = $this->dir . $name . '.php';
        if (! file_exists($filePath) || $this->option('force')) {
            $content = $this->getBuildContent($name);
            file_put_contents($filePath, $content);
            $this->info($filePath . ' create Succeed!');
        }

        $this->info('Generate Succeed!');
    }

    /**
     * 获取保存文件夹
     * @return string
     */
    protected function getSaveDir()
    {
        $dir = $this->option('module');
        if (! $dir) {
            return sprintf(
                $this->defaultDir,
                '',
                $this->argumentName . 's'
            );
        }

        return sprintf(
            $this->defaultDir,
            '/' . ucfirst($this->filterStr($dir)),
            $this->argumentName . 's'
        );
    }

    /**
     * 创建文件夹
     * @return bool
     */
    protected function createDir()
    {
        return ! is_dir($this->dir) && mkdir($this->dir, 0755, true);
    }

    /**
     * 获取命名空间
     * @param string $dir
     * @return string
     */
    protected function getNamespace(string $dir)
    {
        return 'App\\' . str_replace('/', '\\', rtrim($dir, '/'));
    }

    /**
     * 获取生成名称
     * @param string $name
     * @return string
     */
    protected function getBuildName(string $name)
    {
        $name = Str::singular($this->filterStr($name));
        if (! preg_match(sprintf('/%s$/i', $this->argumentName), $name)) {
            $name .= '_' . $this->argumentName;
        }
        return Str::studly($name);
    }

    /**
     * 过滤字符串
     * @param string $str
     * @return string
     */
    protected function filterStr(string $str)
    {
        return str_replace(['/', '\\'], '', $str);
    }
}

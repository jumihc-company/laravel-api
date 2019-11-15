<?php
/**
 * User: YL
 * Date: 2019/10/22
 */

namespace Jmhc\Restful\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class MakeCommand extends Command
{
    /**
     * 命令描述
     * @var string
     */
    protected $description = 'Generate the %s file';

    /**
     * 实体名称
     * @var string
     */
    protected $entityName;

    /**
     * 默认保存路径
     * @var string
     */
    protected $defaultDir = 'Http/';

    /**
     * 命名空间
     * @var string
     */
    protected $namespace;

    /**
     * 文件保存路径
     * @var string
     */
    protected $dir;

    /**
     * 参数 name
     * @var string
     */
    protected $argumentName;

    /**
     * 选项 dir
     * @var string
     */
    protected $optionDir;

    /**
     * 选项 module
     * @var string
     */
    protected $optionModule;

    /**
     * 选项 force
     * @var bool
     */
    protected $optionForce;

    /**
     * 选项 suffix
     * @var bool
     */
    protected $optionSuffix;

    abstract protected function getBuildContent(string $name);

    public function __construct()
    {
        $this->description = sprintf(
            $this->description,
            strtolower($this->entityName)
        );

        parent::__construct();
    }

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
        $this->createDir();

        // 构建名称
        $name = $this->getBuildName($this->argumentName);

        // 保存文件
        $filePath = $this->dir . $name . '.php';
        if (! file_exists($filePath) || $this->optionForce) {
            $content = $this->getBuildContent($name);
            file_put_contents($filePath, $content);
            $this->info($filePath . ' create Succeed!');
        }

        // 执行额外命令
        $this->extraCommands();

        $this->info('Generate Succeed!');
    }

    /**
     * 设置参数、选项
     */
    protected function setArgumentOption()
    {
        // 命令参数
        $this->argumentName = $this->argument('name');

        // 命令选项
        $this->optionDir = $this->option('dir');
        $this->optionModule = $this->option('module');
        $this->optionForce = $this->option('force');
        $this->optionSuffix = $this->option('suffix');
    }

    /**
     * 获取保存文件夹
     * @return string
     */
    protected function getSaveDir()
    {
        // 路径
        $dir = $this->defaultDir;
        if ($this->optionDir) {
            $dir = $this->getDirStr($this->filterDir($this->optionDir));
        }

        // 模块存在
        if ($this->optionModule) {
            $dir .= $this->filterStr($this->optionModule);
        }

        return $dir . $this->entityName . 's';
    }

    /**
     * 过滤路径
     * @param string $dir
     * @return array
     */
    protected function filterDir(string $dir)
    {
        return array_filter(
            explode(
                '/',
                str_replace('\\', '', $dir)
            )
        );
    }

    /**
     * 获取路径字符串
     * @param array $dir
     * @return string
     */
    protected function getDirStr(array $dir)
    {
        $res = '';
        foreach ($dir as $v) {
            $res .= ucfirst($v) . '/';
        }
        return $res;
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
        // 判断是否添加后缀
        if (! preg_match(sprintf('/%s$/i', $this->entityName), $name) && $this->optionSuffix) {
            $name .= '_' . $this->entityName;
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

    /**
     * 过滤参数名称
     * @param string $name
     * @return string
     */
    protected function filterArgumentName(string $name)
    {
        return Str::singular(preg_replace(
            sprintf('/%s$/i', $this->entityName),
            '',
            $this->filterStr($name)
        ));
    }

    /**
     * 执行额外命令
     */
    protected function extraCommands()
    {
        // 名称
        $name = $this->filterArgumentName($this->argumentName);

        // 创建参数
        $arguments = [
            'name' => $name,
            '--dir' => $this->optionDir,
            '--module' => $this->optionModule,
            '--force' => $this->optionForce,
            '--suffix' => $this->optionSuffix,
        ];

        // 创建模型存在
        if ($this->option('model')) {
            $this->call('jmhc-api:make-model', $arguments);
        }

        // 创建服务存在
        if ($this->option('service')) {
            $this->call('jmhc-api:make-service', $arguments);
        }

        // 创建迁移存在
        if ($this->option('migration')) {
            $this->call('make:migration', [
                'name' => sprintf(
                    'create_%_table',
                    Str::plural(Str::snake($name))
                )
            ]);
        }

        // 创建填充存在
        if ($this->option('seeder')) {
            $this->call('make:seeder', [
                'name' => sprintf(
                    '%sTableSeeder',
                    Str::plural($name)
                )
            ]);
        }
    }

    /**
     * 获取参数
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, $this->name . ' name'],
        ];
    }

    /**
     * 获取选项
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['dir', null, InputOption::VALUE_OPTIONAL, 'File saving path, relative to app directory', $this->defaultDir],
            ['module', 'm', InputOption::VALUE_OPTIONAL, 'Module name'],
            ['force', 'f', InputOption::VALUE_NONE, 'Overwrite existing file'],
            ['suffix', 's', InputOption::VALUE_NONE, sprintf('Add the `%s` suffix', $this->entityName)],
            ['migration', null, InputOption::VALUE_NONE, 'Generate the migration file with the same name'],
            ['seeder', null, InputOption::VALUE_NONE, 'Generate the seeder file with the same name'],
        ];
    }
}

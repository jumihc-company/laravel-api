<?php
/**
 * User: YL
 * Date: 2019/10/18
 */

namespace Jmhc\Restful\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Throwable;

class MakeCommonModelCommand extends Command
{
    /**
     * 命令名称
     * @var string
     */
    protected $name = 'jmhc-api:make-common-model';

    /**
     * 命令描述
     * @var string
     */
    protected $description = 'Generate the common model files';

    /**
     * 默认保存路径
     * @var string
     */
    protected $defaultDir = 'Common/Models/';

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
     * 参数 db
     * @var string
     */
    protected $optionDb;

    /**
     * 参数 prefix
     * @var string
     */
    protected $optionPrefix;

    /**
     * 参数 table
     * @var array
     */
    protected $optionTable;

    /**
     * 参数 dir
     * @var string
     */
    protected $optionDir;

    /**
     * 参数 force
     * @var bool
     */
    protected $optionForce;

    /**
     * 参数 clean
     * @var bool
     */
    protected $optionClear;

    /**
     * 参数 suffix
     * @var bool
     */
    protected $optionSuffix;

    /**
     * 执行操作
     * @return bool
     */
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

        // 数据库链接实例
        $db = app('db.connection');

        // 设置默认值
        $this->optionDb = $this->optionDb ?? $db->getConfig('database');
        $this->optionPrefix = $this->optionPrefix ?? $db->getConfig('prefix');

        // 排除的表
        $excludeTables = array_map(function ($v) {
            return $this->optionPrefix . str_replace($this->optionPrefix, '', $v);
        }, $this->optionTable);

        try {
            // 获取所有表
            $tables = $this->getTables($this->optionDb);

            // 清除所有
            if ($this->optionClear) {
                $this->clearAll($tables);
                $this->info('Clear Succeed!');
                return true;
            }

            // 生成模型
            foreach ($tables as $table) {
                if (in_array($table, $excludeTables)) {
                    continue;
                }
                $this->buildModel($table);
            }

            $this->info('Generate Succeed!');
        } catch (Throwable $e) {
            $this->error($e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        return true;
    }

    /**
     * 设置参数、选项
     */
    protected function setArgumentOption()
    {
        // 命令选项
        $this->optionDb = $this->option('db');
        $this->optionPrefix = $this->option('prefix');
        $this->optionTable = $this->option('table');
        $this->optionDir = $this->option('dir');
        $this->optionForce = $this->option('force');
        $this->optionClear = $this->option('clear');
        $this->optionSuffix = $this->option('suffix');
    }

    /**
     * 获取保存文件夹
     * @return string
     */
    protected function getSaveDir()
    {
        if (! $this->optionDir) {
            return $this->defaultDir;
        }

        return $this->getDirStr($this->filterDir($this->optionDir));
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
     * 获取命名空间
     * @param string $dir
     * @return string
     */
    protected function getNamespace(string $dir)
    {
        return 'App\\' . str_replace('/', '\\', rtrim($dir, '/'));
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
     * 清除所有模型
     * @param array $tables
     */
    protected function clearAll(array $tables)
    {
        // 清除确认
        $this->confirm('Confirm delete all models?', false);

        $files = glob($this->dir . '*.php');
        $tables = array_map(function ($v) {
            return $this->getBuildName($this->optionPrefix, $v) . '.php';
        }, $tables);

        foreach ($files as $file) {
            if (! in_array(basename($file), $tables)) {
                continue;
            }

            unlink($file);
            $this->info($file . ' delete Succeed!');
        }
    }

    /**
     * 获取所有数据表
     * @param string $database
     * @return array
     */
    protected function getTables(string $database = '')
    {
        $sql = ! empty($database) ? 'SHOW TABLES FROM ' . $database : 'SHOW TABLES ';

        $result = DB::select($sql);
        $info   = [];
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }

        return $info;
    }

    /**
     * 生成模型文件
     * @param string $table
     * @return bool
     */
    protected function buildModel(string $table)
    {
        $name = $this->getBuildName($this->optionPrefix, $table);
        $filePath = $this->dir . $name . '.php';

        if (file_exists($filePath) && ! $this->optionForce) {
            return false;
        }

        $content = $this->getBuildContent($name);
        file_put_contents($filePath, $content);

        $this->info($filePath . ' create Succeed!');
        return true;
    }

    /**
     * 获取生成名称
     * @param string $prefix
     * @param string $table
     * @return string
     */
    protected function getBuildName(string $prefix, string $table)
    {
        return Str::studly(Str::singular(str_replace($prefix, '', $table))) . ($this->optionSuffix ? 'Model' : '');
    }

    /**
     * 获取生成内容
     * @param string $name
     * @return string
     */
    protected function getBuildContent(string $name)
    {
        $str = <<< EOF
<?php
namespace %s;

use Jmhc\Restful\Models\BaseModel;

class %s extends BaseModel
{}
EOF;
        return sprintf($str, $this->namespace, $name);
    }

    /**
     * 获取选项
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['db', 'd', InputOption::VALUE_REQUIRED, 'Model source database', app('db.connection')->getConfig('database')],
            ['prefix', 'p', InputOption::VALUE_REQUIRED, 'Data table prefix', app('db.connection')->getConfig('prefix')],
            ['table', 't', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Exclude table names'],
            ['dir', null, InputOption::VALUE_REQUIRED, 'File saving path, relative to app directory', $this->defaultDir],
            ['force', 'f', InputOption::VALUE_NONE, 'Overwrite existing files'],
            ['clear', 'c', InputOption::VALUE_NONE, 'Clear all model files'],
            ['suffix', 's', InputOption::VALUE_NONE, 'Add the `Model` suffix'],
        ];
    }
}

<?php
/**
 * User: YL
 * Date: 2019/10/18
 */

namespace Jmhc\Restful\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class MakeCommonModelCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'jmhc-api:make-common-model
    {--d|db= : The generated database name}
    {--p|prefix= : Datatable prefixes, which are not prefixed when generating models}
    {--t|table=* : Exclude table names}
    {--dir=%s : File saving path, relative to app directory}
    {--f|force : Whether to overwrite an existing file}
    {--c|clear : Clear all model files}';

    /**
     * @var string
     */
    protected $description = 'Generate the common model files';

    /**
     * @var string
     */
    protected $defaultDir = 'Common/Models/';

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $dir;

    /**
     * @var string
     */
    protected $database;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $force;

    public function __construct()
    {
        $this->signature = sprintf($this->signature, $this->defaultDir);

        parent::__construct();
    }

    /**
     * 执行操作
     * @return bool
     */
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

        // 数据库链接实例
        $db = app('db.connection');

        $this->database = $this->option('db') ?? $db->getConfig('database');
        $this->prefix = $this->option('prefix') ?? $db->getConfig('prefix');
        $this->force = $this->option('force');

        // 排除的表
        $excludeTables = array_map(function ($v) {
            return $this->prefix . $v;
        }, $this->option('table'));

        try {
            // 获取所有表
            $tables = $this->getTables($this->database);

            // 清除所有
            if ($this->option('clear')) {
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
     * 获取保存文件夹
     * @return string
     */
    protected function getSaveDir()
    {
        $dir = $this->option('dir');
        if (! $dir) {
            return $this->defaultDir;
        }

        // 过滤路径
        $dir = $this->filterDir($dir);

        $res = '';
        foreach ($dir as $v) {
            $res .= ucfirst($v) . '/';
        }
        return $res;
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
            return $this->getBuildName($this->prefix, $v) . '.php';
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
        $name = $this->getBuildName($this->prefix, $table);
        $filePath = $this->dir . $name . '.php';

        if (file_exists($filePath) && ! $this->force) {
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
        return Str::studly(Str::singular(str_replace($prefix, '', $table))) . 'Model';
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
}

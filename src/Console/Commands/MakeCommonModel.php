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

class MakeCommonModel extends Command
{
    /**
     * @var string
     */
    protected $dir;

    /**
     * @var string
     */
    protected $name = 'jmhc-api:make-common-model';

    /**
     * @var string
     */
    protected $description = 'Generate the common model files';

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

    /**
     * @var string
     */
    protected $author;

    /**
     * 执行操作
     * @return bool
     */
    public function handle()
    {
        // 保存文件夹
        $this->dir = app_path('Common/Models/');
        // 创建文件夹
        $this->createDir();

        // 数据库链接实例
        $db = app('db.connection');

        $this->database = $this->option('db') ?? $db->getConfig('database');
        $this->prefix = $this->option('prefix') ?? $db->getConfig('prefix');
        $this->force = $this->option('force');
        $this->author = $this->option('author') ?? 'YL';

        // 排除的表
        $excludeTables = [];
        if ($this->option('table')) {
            $excludeTables = array_map(function ($v) {
                return $this->prefix . $v;
            }, explode(',', $this->option('table')));
        }

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
        $files = glob($this->dir . '*.php');
        $tables = array_map(function ($v) {
            return $this->getModelName($this->prefix, $v) . '.php';
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
        $name = $this->getModelName($this->prefix, $table);
        $filePath = $this->dir . $name . '.php';

        if (file_exists($filePath) && ! $this->force) {
            return false;
        }

        $content = $this->getBuildContent($this->author, $name);
        file_put_contents($filePath, $content);

        $this->info($filePath . ' create Succeed!');
        return true;
    }

    /**
     * 获取模型名称
     * @param string $prefix
     * @param string $table
     * @return string
     */
    protected function getModelName(string $prefix, string $table)
    {
        return Str::studly(Str::singular(str_replace($prefix, '', $table))) . 'Model';
    }

    /**
     * 获取生成内容
     * @param string $author
     * @param string $name
     * @return string
     */
    protected function getBuildContent(string $author, string $name)
    {
        $str = <<< EOF
<?php
/**
 * User: %s
 * Date: %s
 */

namespace App\Common\Models;

class %s extends BaseModel
{}
EOF;
        return sprintf($str, $author, date('Y/m/d'), $name);
    }

    /**
     * 重写参数
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['db', 'd', InputOption::VALUE_OPTIONAL, 'The generated database name'],
            ['table', 't', InputOption::VALUE_OPTIONAL, 'Exclude table names, multiple use `,` separated'],
            ['prefix', 'p', InputOption::VALUE_OPTIONAL, 'Datatable prefixes, which are not prefixed when generating models'],
            ['force', 'f', InputOption::VALUE_NONE, 'Whether to overwrite an existing file'],
            ['clear', 'c', InputOption::VALUE_NONE, 'Clear all model files'],
            ['author', 'a', InputOption::VALUE_OPTIONAL, 'Author\'s name'],
        ];
    }
}

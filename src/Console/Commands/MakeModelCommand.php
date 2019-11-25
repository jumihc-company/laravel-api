<?php
/**
 * User: YL
 * Date: 2019/10/22
 */

namespace Jmhc\Restful\Console\Commands;

use Illuminate\Support\Str;
use Jmhc\Restful\Console\Commands\Traits\ReplaceModelTrait;
use Jmhc\Restful\Utils\DbHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * 生成模型
 * @package Jmhc\Restful\Console\Commands
 */
class MakeModelCommand extends MakeCommand
{
    use ReplaceModelTrait;

    /**
     * 命令名称
     * @var string
     */
    protected $name = 'jmhc-api:make-model';

    /**
     * 实体名称
     * @var string
     */
    protected $entityName = 'Model';

    /**
     * 模板路径
     * @var string
     */
    protected $stubPath = __DIR__ . '/stubs/model.stub';

    /**
     * 选项 connection
     * @var string
     */
    protected $optionConnection;

    /**
     * 选项 table
     * @var array
     */
    protected $optionTable;

    /**
     * 数据库辅助类
     * @var DbHelper
     */
    protected $dbHelper;

    /**
     * 表前缀
     * @var string
     */
    protected $prefix;

    public function __construct()
    {
        parent::__construct();

        $this->dbHelper = DbHelper::getInstance([
            'name' => $this->optionConnection,
        ]);
        $this->prefix = $this->dbHelper->getPrefix();
    }

    protected function mainHandle()
    {
        if (! empty($this->argumentName)) {
            return $this->buildModel($this->argumentName);
        }

        return $this->buildModels();
    }

    /**
     * 生成所有模型文件
     * @return bool
     */
    protected function buildModels()
    {
        // 获取所有表
        $tables = $this->dbHelper->getAllTables(false);
        foreach ($tables as $table) {
            if (in_array($table, $this->optionTable)) {
                continue;
            }
            $this->buildModel($table);
        }

        return true;
    }

    /**
     * 生成模型文件
     * @param string $name
     * @return bool
     */
    protected function buildModel(string $name)
    {
        // 生成类名称
        $this->class = $this->getClass($name);

        // 保存文件
        $this->saveFilePath = $this->dir . $this->class . '.php';

        // 存在且不覆盖
        if (file_exists($this->saveFilePath) && ! $this->optionForce) {
            return false;
        }

        // 生成操作
        $this->buildHandle();

        // 执行额外命令
        $this->extraCommands();

        return true;
    }

    /**
     * 获取生成内容
     * @return string
     */
    protected function getBuildContent()
    {
        $content = file_get_contents($this->stubPath);

        $table = Str::snake($this->class);
        [$annotation, $fillable, $casts] = $this->getReplaceData($this->dbHelper->getAllColumns($table));

        // 替换
        $this->replaceNamespace($content, $this->namespace)
            ->replaceClass($content, $this->class)
            ->replaceAnnotations($content, $annotation)
            ->replaceTable($content, $table)
            ->replaceFillable($content, $fillable)
            ->replaceCasts($content, $casts);

        return $content;
    }

    /**
     * 获取替换数据
     * @param array $columns
     * @return array
     */
    protected function getReplaceData(array $columns)
    {
        $annotation = '';
        $fillable = '[';
        $casts = '[';

        foreach ($columns as $column) {
            $_dataType = $this->formatDataType($column['data_type']);

            $fillable .= $column['column_name'] . ', ';

            $annotation .= PHP_EOL . sprintf(' * @property %s $%s', $this->formatPropertyType($_dataType), $column['column_name']);

            $casts .= sprintf(
                "'%s' => '%s', ",
                $column['column_name'],
                $_dataType
            );
        }
        return [
            $this->formatAnnotation($annotation),
            rtrim($fillable, ', ') . ']',
            rtrim($casts, ', ') . ']'
        ];
    }

    /**
     * 格式化注解
     * @param string $annotation
     * @return string
     */
    protected function formatAnnotation(string $annotation)
    {
        if (empty($annotation)) {
            return '';
        }

        return PHP_EOL . '/**' . PHP_EOL . $annotation . PHP_EOL . ' * @package ' . $this->namespace . PHP_EOL . ' */';
    }

    /**
     * 格式化数据类型
     * @param string $type
     * @return string|null
     */
    protected function formatDataType(string $type)
    {
        switch ($type) {
            case 'tinyint':
            case 'smallint':
            case 'mediumint':
            case 'int':
            case 'bigint':
                return 'integer';
            case 'decimal':
            case 'float':
            case 'double':
            case 'real':
                return 'float';
            case 'bool':
            case 'boolean':
                return 'boolean';
            default:
                return 'string';
        }
    }

    /**
     * 格式化属性类型
     * @param string $type
     * @return string
     */
    protected function formatPropertyType(string $type)
    {
        switch ($type) {
            case 'integer':
                return 'int';
            case 'date':
            case 'datetime':
                return '\Carbon\Carbon';
            case 'json':
                return 'array';
            default:
                return $type;
        }
    }

    protected function setArgumentOption()
    {
        parent::setArgumentOption();

        $this->optionConnection = $this->option('connection') ?? 'default';
        $this->optionTable = array_map(function ($v) {
            return str_replace($this->prefix, '', $v);
        }, $this->option('table'));
    }

    /**
     * 命令配置
     */
    protected function configure()
    {
        parent::configure();

        $this->addArgument('name', InputArgument::OPTIONAL, $this->entityName . ' name');

        $this->addOption('controller', null, InputOption::VALUE_NONE, 'Generate the controller file with the same name');
        $this->addOption('service', null, InputOption::VALUE_NONE, 'Generate the service file with the same name');

        $this->addOption('connection', 'c', InputOption::VALUE_OPTIONAL, 'Specify database links', 'default');
        $this->addOption('table', 't', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Exclude table names');
    }
}

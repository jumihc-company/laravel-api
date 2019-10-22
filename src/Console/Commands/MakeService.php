<?php
/**
 * User: YL
 * Date: 2019/10/22
 */

namespace Jmhc\Restful\Console\Commands;

class MakeService extends MakeCommand
{
    /**
     * @var string
     */
    protected $command = 'jmhc-api:make-service';

    /**
     * @var string
     */
    protected $argumentName = 'Service';

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

use Jmhc\Restful\Controllers\BaseService;

class %s extends BaseService
{}
EOF;
        return sprintf($str, $this->namespace, $name);
    }
}

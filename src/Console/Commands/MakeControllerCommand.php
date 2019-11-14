<?php
/**
 * User: YL
 * Date: 2019/10/22
 */

namespace Jmhc\Restful\Console\Commands;

class MakeControllerCommand extends MakeCommand
{
    /**
     * @var string
     */
    protected $command = 'jmhc-api:make-controller';

    /**
     * @var string
     */
    protected $argumentName = 'Controller';

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

use Jmhc\Restful\Controllers\BaseController;

class %s extends BaseController
{}
EOF;
        return sprintf($str, $this->namespace, $name);
    }
}

<?php
/**
 * User: YL
 * Date: 2019/10/22
 */

namespace Jmhc\Restful\Console\Commands;

class MakeModel extends MakeCommand
{
    /**
     * @var string
     */
    protected $command = 'jmhc-api:make-model';

    /**
     * @var string
     */
    protected $argumentName = 'Model';

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

class %s
{}
EOF;
        return sprintf($str, $this->namespace, $name);
    }
}

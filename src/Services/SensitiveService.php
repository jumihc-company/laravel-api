<?php
/**
 * User: YL
 * Date: 2020/07/15
 */

namespace Jmhc\Restful\Services;

use DfaFilter\Exceptions\PdsSystemException;
use Jmhc\Restful\Exceptions\ResultException;
use Jmhc\Support\Helper\SensitiveHelper;
use Jmhc\Support\Utils\Helper;

/**
 * 敏感词服务
 * @package Jmhc\Restful\Services
 */
class SensitiveService extends BaseService
{
    /**
     * @var SensitiveHelper
     */
    protected $sensitive;

    public function initialize()
    {
        parent::initialize();

        // 敏感词辅助类
        $this->sensitive = $this->getSensitiveHelper();

        // 敏感词
        $sensitive = $this->getSensitive();
        if (! empty($sensitive)) {
            $this->sensitive->setTree($sensitive);
        }
    }

    /**
     * 验证
     * @param string $str
     * @param string $prefix
     * @throws PdsSystemException
     * @throws ResultException
     */
    public function validate(string $str, string $prefix = '')
    {
        $badWords = $this->sensitive->getBadWord(str_replace(' ', '', $str));
        $badWords = array_unique($badWords);
        if ($badWords) {
            $this->error( sprintf(
                '%s%s%s',
                $prefix,
                jmhc_api_lang_messages_trans('contain_sensitive_words'),
                implode(',', $badWords)
            ));
        }
    }

    /**
     * 获取敏感词辅助类
     * @return SensitiveHelper
     */
    protected function getSensitiveHelper() : SensitiveHelper
    {
        return Helper::sensitive($this->getExceptWords());
    }

    /**
     * 获取排除字符串
     * @return array
     */
    protected function getExceptWords() : array
    {
        return [];
    }

    /**
     * 获取设置的敏感词
     * @return array
     */
    protected function getSensitive() : array
    {
        return [];
    }
}
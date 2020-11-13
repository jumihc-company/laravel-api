<?php
/**
 * User: YL
 * Date: 2020/07/01
 */

namespace Jmhc\Restful\Utils;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * 分页辅助
 * @package Jmhc\Restful\Utils
 */
class PaginateHelper
{
    /**
     * 分页处理
     * @param LengthAwarePaginator $paginate
     * @param array|string[] $fields
     * @return array
     */
    public static function paginate(LengthAwarePaginator $paginate, array $fields = [])
    {
        $res = [
            'current_page' => $paginate->currentPage(),
            'data' => $paginate->getCollection(),
            'first_page_url' => $paginate->url(1),
            'from' => $paginate->firstItem(),
            'last_page' => $paginate->lastPage(),
            'last_page_url' => $paginate->url($paginate->lastPage()),
            'next_page_url' => $paginate->nextPageUrl(),
            'path' => $paginate->path(),
            'page_size' => (int) $paginate->perPage(),
            'prev_page_url' => $paginate->previousPageUrl(),
            'to' => $paginate->lastItem(),
            'total' => $paginate->total(),
        ];

        if ($fields) {
            $res = array_filter($res, function ($k) use ($fields) {
                return in_array($k, $fields);
            }, ARRAY_FILTER_USE_KEY);
        }

        return $res;
    }
}
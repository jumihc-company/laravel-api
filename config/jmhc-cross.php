<?php
/**
 * User: YL
 * Date: 2019/10/17
 */

// 跨域配置
return [
    'Access-Control-Allow-Credentials' => 'true',
    'Access-Control-Allow-Methods'     => 'GET, POST, PATCH, PUT, DELETE',
    'Access-Control-Allow-Headers'     => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
    'Access-Control-Max-Age'           =>  86400, // 1d
];
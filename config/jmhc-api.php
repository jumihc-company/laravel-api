<?php

return [
    // 异常调试模式
    'exception_debug' => env('JMHC_EXCEPTION_DEBUG', true),
    // 异常文件名称
    'db_exception_file_name' => env('JMHC_DB_EXCEPTION_FILE_NAME', 'handle_db.exception'),
    'exception_file_name' => env('JMHC_EXCEPTION_FILE_NAME', 'handle.exception'),
    'error_file_name' => env('JMHC_ERROR_FILE_NAME', 'handle.error'),
    // 是否记录异常请求消息
    'db_exception_request_message' => env('JMHC_DB_EXCEPTION_REQUEST_MESSAGE', false),
    'exception_request_message' => env('JMHC_EXCEPTION_REQUEST_MESSAGE', false),
    'error_request_message' => env('JMHC_ERROR_REQUEST_MESSAGE', false),

    // 单设备登录临时缓存过期时间（秒）
    'sdl_tmp_expire' => env('JMHC_SDL_TMP_EXPIRE', 10),

    // 运行加密配置
    'runtime' => [
        // 运行调试模式
        'debug' => env('JMHC_RUNTIME_DEBUG', true),
        // 运行加密方法
        'method' => env('JMHC_RUNTIME_METHOD', 'AES-128-CBC'),
        // 运行加密向量
        'iv' => env('JMHC_RUNTIME_IV', ''),
        // 运行加密秘钥
        'key' => env('JMHC_RUNTIME_KEY', ''),
    ],

    // 令牌加密配置
    'token' => [
        // 令牌加密方法
        'method' => env('JMHC_TOKEN_METHOD', 'AES256'),
        // 令牌加密向量
        'iv' => env('JMHC_TOKEN_IV', ''),
        // 令牌加密秘钥
        'key' => env('JMHC_TOKEN_KEY', ''),
        // 令牌填充位置
        'pos' => env('JMHC_TOKEN_POS', 5),
        // 令牌填充长度
        'len' => env('JMHC_TOKEN_LEN', 6),
        // 令牌允许刷新时间（秒） 3天
        'allow_refresh_time' => env('JMHC_TOKEN_ALLOW_REFRESH_TIME', 259200),
        // 令牌提示刷新时间（秒） 2天
        'notice_refresh_time' => env('JMHC_TOKEN_NOTICE_REFRESH_TIME', 172800),
    ],

    // 签名配置
    'signature' => [
        // 是否检测签名
        'check' => env('JMHC_SIGNATURE_CHECK', false),
        // 签名秘钥
        'key' => env('JMHC_SIGNATURE_KEY', ''),
        // 是否检测时间戳
        'check_timestamp' => env('JMHC_SIGNATURE_CHECK_TIMESTAMP', true),
        // 签名时间戳超时（秒）
        'timestamp_timeout' => env('JMHC_SIGNATURE_TIMESTAMP_TIMEOUT', 60),
        // 随机数缓存过期时间（秒）
        'nonce_expire' => env('JMHC_SIGNATURE_NONCE_EXPIRE', 60),
    ],

    // 请求锁定配置
    'request_lock' => [
        // 请求锁定驱动
        'driver' => env('JMHC_REQUEST_LOCK_DRIVER', 'redis'),
        // 请求锁定时间（秒）
        'seconds' => env('JMHC_REQUEST_LOCK_SECONDS', 5),
    ],
];

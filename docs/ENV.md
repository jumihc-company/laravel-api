```apacheconfig
# 异常调试模式
JMHC_EXCEPTION_DEBUG=true
# 反射、逻辑、运行异常文件名称
JMHC_EXCEPTION_FILE_NAME=handle.exception
# 数据库查询异常文件名称
JMHC_DB_EXCEPTION_FILE_NAME=handle_db.exception
# 错误文件名称
JMHC_ERROR_FILE_NAME=handle.error
# 是否记录反射、逻辑、运行异常请求消息
JMHC_EXCEPTION_REQUEST_MESSAGE=false
# 是否记录数据库查询异常请求消息
JMHC_DB_EXCEPTION_REQUEST_MESSAGE=false
# 是否记录错误请求消息
JMHC_ERROR_REQUEST_MESSAGE=false
```

```apacheconfig
# 运行调试模式,true:不加密
JMHC_RUNTIME_DEBUG=true
# 运行加密方法
JMHC_RUNTIME_METHOD=AES-128-CBC
# 运行加密向量
JMHC_RUNTIME_IV=
# 运行加密秘钥
JMHC_RUNTIME_KEY=
```

```apacheconfig
# 令牌加密方法
JMHC_TOKEN_METHOD=AES256
# 令牌加密向量
JMHC_TOKEN_IV=
# 令牌加密秘钥
JMHC_TOKEN_KEY=
# 令牌填充位置
JMHC_TOKEN_POS=5
# 令牌填充长度
JMHC_TOKEN_LEN=6
# 令牌允许刷新时间（秒） 3天
JMHC_TOKEN_ALLOW_REFRESH_TIME=259200
# 令牌提示刷新时间（秒） 2天
JMHC_TOKEN_NOTICE_REFRESH_TIME=172800
```

```apacheconfig
# 是否检测签名
JMHC_SIGNATURE_CHECK=false
# 签名秘钥
JMHC_SIGNATURE_KEY=
# 签名时间戳超时（秒）
JMHC_SIGNATURE_TIMESTAMP_TIMEOUT=60
# 验证时间戳
JMHC_SIGNATURE_CHECK_TIMESTAMP=true
```

```apacheconfig
# 请求锁定驱动
JMHC_REQUEST_LOCK_DRIVER=redis
# 请求锁定时间（秒）
JMHC_REQUEST_LOCK_SECONDS=5
```

```apacheconfig
# 单设备登录临时缓存过期时间（秒）
JMHC_SDL_TMP_EXPIRE=10
```

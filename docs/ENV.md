```apacheconfig
# 是否允许保存debug日志
JMHC_LOG_DEBUG=true
# 日志保存路径
JMHC_LOG_PATH=storage/logs
# 日志文件最大内存,0不限制,如（2m,2g）
JMHC_LOG_MAX_SIZE=0
# 目录下最大日志文件数量,0不限制
JMHC_LOG_MAX_FILES=0
```

```apacheconfig
# 是否检测签名
JMHC_CHECK_SIGNATURE=false
# 签名秘钥
JMHC_SIGNATURE_KEY=
# 时间戳过期时间（秒）
JMHC_TIMESTAMP_TIMEOUT=60
```

```apacheconfig
# 请求调试模式,true:不加密
JMHC_REQUEST_DEBUG=true
# 请求加密方法
JMHC_REQUEST_METHOD=AES-128-CBC
# 请求加密iv
JMHC_REQUEST_IV=
# 请求加密key
JMHC_REQUEST_KEY=
```

```apacheconfig
# token加密方法
JMHC_TOKEN_METHOD=AES256
# token加密iv
JMHC_TOKEN_IV=
# token加密key
JMHC_TOKEN_KEY=
# token截取位置
JMHC_TOKEN_POS=
# token截取长度
JMHC_TOKEN_LEN=
# 允许刷新时间（秒） 3天
JMHC_TOKEN_ALLOW_REFRESH_TIME=259200
# 提示刷新时间（秒） 2天
JMHC_TOKEN_NOTICE_REFRESH_TIME=172800
```

```apacheconfig
# 反射、逻辑、运行异常文件名称
JMHC_EXCEPTION_FILE_NAME=handle.exception
# 数据库查询异常文件名称
JMHC_DB_EXCEPTION_FILE_NAME=handle_db.exception
# 错误文件名称
JMHC_ERROR_FILE_NAME=handle.error
```

```apacheconfig
# 请求token名称
JMHC_REQUEST_TOKEN_NAME=token
# 请求version名称
JMHC_REQUEST_VERSION_NAME=version
# 响应header刷新token名称
JMHC_REFRESH_TOKEN_NAME=token
```

```apacheconfig
# 单设备登录临时缓存过期时间（秒）
JMHC_SDL_TMP_EXPIRE=10
```

```apacheconfig
# mongodb链接名称
MONGODB_CONNECTION=mongodb
# mongodb链接地址
MONGODB_HOST=mongo
# mongodb链接端口
MONGODB_PORT=27017
# mongodb数据库名称
MONGODB_DATABASE=mongo
# mongodb用户名
MONGODB_USERNAME=
# mongodb密码
MONGODB_PASSWORD=
# mongodb授权数据库名称
MONGODB_AUTH_DATABASE=admin
```

```apacheconfig
# rabbitmq链接名称
RABBITMQ_CONNECTION=rabbitmq
# rabbitmq工作方式
RABBITMQ_WORKER=default
# rabbitmq链接地址
RABBITMQ_HOST=rabbitmq
# rabbitmq链接端口
RABBITMQ_PORT=5672
# rabbitmq登录用户
RABBITMQ_LOGIN=guest
# rabbitmq登录密码
RABBITMQ_PASSWORD=guest
# rabbitmq队列
RABBITMQ_QUEUE=
# rabbitmq交换机
RABBITMQ_EXCHANGE_NAME=
```
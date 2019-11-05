[TOC]

## 安装配置

使用以下命令安装：
```
composer require jmhc/laravel-api
```
发布文件[可选]：
```php
// 发布所有文件
php artisan vendor:publish --tag=jmhc-api

// 只发布配置文件
php artisan vendor:publish --tag=jmhc-api-config

// 只发布迁移文件
php artisan vendor:publish --tag=jmhc-api-migrations

// 只发布资源文件
php artisan vendor:publish --tag=jmhc-api-resources
```

## 使用说明

> 环境变量值参考：[env](docs/ENV.md)
> 
> restful参考: [restful](docs/RESTFUL.md)

### 快速使用

#### 中间件
- 必须注册全局中间件 `Jmhc\Restful\Middleware\ParamsHandler`
- 可选中间件查看 [中间件列表](### 中间件)

#### 异常处理

- 修改 `App\Exceptions\Handler` 继承的方法为  `Jmhc\Restful\Handlers\ExceptionHandler`
- 其他异常捕获调用父类 `response()`  方法并重写，参考 `Jmhc\Restful\Handlers\ExceptionHandler->response()`

#### 控制器

- 直接继承 `Jmhc\Restful\Controllers\BaseController`

#### 模型

- 可选继承 `Jmhc\Restful\Models\BaseModel` 、 `Jmhc\Restful\Models\BaseMongo` 、 `Jmhc\Restful\Models\BasePivot` 、 `Jmhc\Restful\Models\UserModel`  、`Jmhc\Restful\Models\VersionModel`

#### 服务层(逻辑层)

- 直接继承 `Jmhc\Restful\Services\BaseService`

### 控制器

> 需继承 `Jmhc\Restful\Controllers\BaseController`

- 可使用 `Jmhc\Restful\Traits\RequestInfoTrait` 里的参数
- 可使用 `Jmhc\Restful\Traits\UserInfoTrait` 里的参数、方法
- 可使用 `Jmhc\Restful\Traits\ResourceController` 里的方法

### 模型

#### 普通模型

> 需继承 `Jmhc\Restful\Models\BaseModel`

- 可使用 `Jmhc\Restful\Traits\ModelTrait` 里的方法

#### 中间表模型

> 需继承 `Jmhc\Restful\Models\BasePivot`

- 可使用 `Jmhc\Restful\Traits\ModelTrait` 里的方法

#### mongodb模型

> 需继承 `Jmhc\Restful\Models\BaseMongo`

- 可使用 `Jmhc\Restful\Traits\ModelTrait` 里的方法
- 配置参考：[jmhc-mongodb.php](config/jmhc-mongodb.php)

### 服务层(逻辑层)

> 需继承 `Jmhc\Restful\Services\BaseService`

- 可使用 `Jmhc\Restful\Traits\RequestInfoTrait` 里的参数
- 可使用 `Jmhc\Restful\Traits\UserInfoTrait` 里的参数、方法
- 可使用 `Jmhc\Restful\Traits\ResourceService` 里的方法

```php
class TestController extends BaseController
{
	public function initialize()
    {
        parent::initialize();
        $this->service = TestService::getInstance();
    }
    
    public function index()
    {
    	$this->request->params->a = 'a';
    	// 当初始化实例化service后，方法中有更新$this->request->params时,应当调用服务层updateAttribute方法更新$this->request->params
    	$this->service->updateAttribute()->index();
    }
    
    public function index()
    {
    	// 当初始化实例化service后，方法中无更新$this->request->params
    	$this->service->index();
    }
}
```

### 命令行

#### 创建公用模型文件

> 从数据库查询表生成、删除模型

```php
// 生成模型文件到 app/Common/Models
php artisan jmhc-api:make-common-model
// 生成模型文件到 app/Test/Models
php artisan jmhc-api:make-common-model --dir test/models
// 清除所有模型文件
php artisan jmhc-api:make-common-model -c
...
```

#### 创建控制器

> 创建的控制器默认继承基础控制器 BaseController

```php
// 创建 Test 控制器位于 app/Http/Controllers/TestController.php
php artisan jmhc-api:make-controller test
// 创建 Test 控制器位于 app/Http/Index/Controllers/TestController.php
php artisan jmhc-api:make-controller test -m index
...
```

#### 创建服务层(逻辑层)

> 创建的服务默认继承基础服务 BaseService

```php
// 创建 Test 服务位于 app/Http/Services/TestService.php
php artisan jmhc-api:make-service test
// 创建 Test 服务位于 app/Http/Index/Services/TestService.php
php artisan jmhc-api:make-service test -m index
...
```

#### 创建模型

```php
// 创建 Test 模型位于 app/Http/Models/TestModel.php
php artisan jmhc-api:make-model test
// 创建 Test 模型位于 app/Http/Index/Models/TestModel.php
php artisan jmhc-api:make-model test -m index
...
```

### 中间件

> 用法加粗为必须调用

|   中间件   |   别名   |   用法   |   需要实现的契约或继承模型   |
| ---- | ---- | ---- | ---- |
| `Jmhc\Restful\Middleware\AllowCrossDomain` | `jmhc.allow.cross` | 允许跨域 | --- |
| `Jmhc\Restful\Middleware\ParamsHandler`  | `jmhc.params.handler` | **参数处理** | --- |
| `Jmhc\Restful\Middleware\ConvertEmptyStringsToNull` | `jmhc.convert.empty.strings.to.null` | 转换空字符串为null | --- |
| `Jmhc\Restful\Middleware\TrimStrings` | `jmhc.trim.strings` | 清除字符串空格 | --- |
| `Jmhc\Restful\Middleware\RequestLock` | `jmhc.request.lock` | 请求锁定 | --- |
| `Jmhc\Restful\Middleware\RequestLog` | `jmhc.request.log` | 记录请求日志(debug) | --- |
| `Jmhc\Restful\Middleware\RequestPlatform` | `jmhc.request.platform` | 设置请求平台，参考`Jmhc\Restful\PlatformInfo` | --- |
| `Jmhc\Restful\Middleware\CheckVersion` | `jmhc.check.version` | 检测应用版本 | `Jmhc\Restful\Contracts\Version`<br />`Jmhc\Restful\Models\VersionModel` |
| `Jmhc\Restful\Middleware\CheckSignature` | `jmhc.check.signature` | 验证请求签名 | --- |
| `Jmhc\Restful\Middleware\CheckToken` | `jmhc.check.token` | 检测token，设置用户数据 | `Jmhc\Restful\Contracts\User`<br />`Jmhc\Restful\Models\UserModel` |
| `Jmhc\Restful\Middleware\CheckSdl` | `jmhc.check.sdl` | 单设备登录，需要复写 `Jmhc\Restful\Handlers\ExceptionHandler->sdlHandler()` | --- |


### 队列

#### rabbitmq
> 需要继承  `Jmhc\Restful\Jobs\BaseRabbitmq` 
>
> 详细配置查看：[rabbitmq.conf](https://github.com/vyuldashev/laravel-queue-rabbitmq/blob/master/config/rabbitmq.php)

### 异常处理

> `App\Exceptions\Handler` 继承 `Jmhc\Restful\Handlers\ExceptionHandler`
>
> 其他异常捕获调用父类 `response()`  方法并重写，参考 `Jmhc\Restful\Handlers\ExceptionHandler->response()`

### 服务提供者

#### API服务提供者

>`Jmhc\Restful\Providers\JmhcApiServiceProvider`

- 注册路由中间件
- 注册命令
- 合并配置
- 发布文件

#### 队列任务服务提供者

> `Jmhc\Restful\Providers\JmhcJobServiceProvider`  

- 队列任务开始执行(debug)
- 队列任务执行异常错误日志

#### 契约服务提供者

> `Jmhc\Restful\Providers\JmhcContractServiceProvider`  

- 绑定契约 `Jmhc\Restful\Contracts\User` 实现
- 绑定契约 `Jmhc\Restful\Contracts\Version` 实现

#### 路由服务提供者

> `Jmhc\Restful\Providers\JmhcRouteServiceProvider`  
>
> 默认不启用

- 注册 `base_path('routes')` 下面所有 php 文件到路由

### 验证规则

#### Images

> `Jmhc\Restful\Rules\Images`

验证图片字段后缀地址为 `jpeg` , `jpg` , `png` , `bmp` , `gif` , `svg` , `webp`

如：

```php
1.png // true
1.pn // false
1.png,2.png // true
```

### 模型作用域

#### 主键字段倒序

> `Jmhc\Restful\Scopes\PrimaryKeyDescScope`

`Jmhc\Restful\Models\BaseModel` 已默认注册此全局作用域

### trait介绍

#### Instance.php

> `Jmhc\Restful\Traits\Instance`
>
> 单例类 trait

```php
// 无构造参数使用
T::getInstance()->a();

// 有构造参数使用，c为构造参数名称
T::getInstance([
    'c' => ['a']
])->a();
```

#### ModelTrait.php

> `Jmhc\Restful\Traits\ModelTrait`
>
> 模型辅助 trait

使用类:
- `Jmhc\Restful\Models\BaseModel`
- `Jmhc\Restful\Models\BasePivot`
- `Jmhc\Restful\Models\BaseMongo`

#### RedisHandler.php

> `Jmhc\Restful\Traits\RedisHandler`
>
> redis 辅助 trait

#### RequestInfoTrait.php

> `Jmhc\Restful\Traits\RequestInfoTrait`
>
> 请求信息绑定

使用类:

- `Jmhc\Restful\Controllers\BaseController`
- `Jmhc\Restful\Services\BaseService`

#### ResultThrow.php

> `Jmhc\Restful\Traits\ResultThrow`
>
> 异常抛出辅助

#### UserInfoTrait.php

> `Jmhc\Restful\Traits\UserInfoTrait`
>
> 用户信息绑定

使用类:

- `Jmhc\Restful\Controllers\BaseController`
- `Jmhc\Restful\Services\BaseService`

### 工具类介绍

#### Collection.php

> `Jmhc\Restful\Utils\Collection`
>
> 集合，基于 `Illuminate\Support\Collection`

- 修改`__get` 魔术方法
- 新增`__set` , `__isset` , `__unset` 魔术方法

#### Env.php

> `Jmhc\Restful\Utils\Env`
>
> 获取环境变量

```php
// .env
LOG_A=a
LOG_B=a
LOG_A_B=ab

// 返回log开头环境变量数组
Env::get('log')
// 返回
[
	'a' => 'a',
	'b' => 'b',
	'a_b' => 'ab',
]

// 返回LOG_A环境变量
Env::get('log.a')
// 返回
'a'
```

#### FileSize.php

> `Jmhc\Restful\Utils\FileSize`
>
> 转换文件尺寸

```php
// 返回 2097152 字节
FileSize::get('2m');

// 返回 2147483648 字节
FileSize::get('2g');
```

#### Log.php

> `Jmhc\Restful\Utils\Log`
>
> 文件日志保存

- `debug` 日志受环境变量 `LOG_DEBUG` 控制

#### RequestClient.php

> `Jmhc\Restful\Utils\RequestClient`
>
> 请求客户端，基于 `GuzzleHttp\Client`

复写构造函数：

- 设置不验证 `https`
- 设置 `user-agent` 为谷歌浏览器

#### Sdl.php

> `Jmhc\Restful\Utils\Sdl`
>
> 单设备登录类

#### SmsCache.php

> `Jmhc\Restful\Utils\SmsCache`
>
> 发送短信缓存类

#### Token.php

> `Jmhc\Restful\Utils\Token`
>
> 令牌相关类

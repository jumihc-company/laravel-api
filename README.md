## 目录

- [安装配置](#%E5%AE%89%E8%A3%85%E9%85%8D%E7%BD%AE)
- [使用说明](#%E4%BD%BF%E7%94%A8%E8%AF%B4%E6%98%8E)
	- [快速使用](#%E5%BF%AB%E9%80%9F%E4%BD%BF%E7%94%A8)
		- [中间件](#%E4%B8%AD%E9%97%B4%E4%BB%B6)
		- [异常处理](#%E5%BC%82%E5%B8%B8%E5%A4%84%E7%90%86)
		- [使用路由服务提供者](#%E4%BD%BF%E7%94%A8%E8%B7%AF%E7%94%B1%E6%9C%8D%E5%8A%A1%E6%8F%90%E4%BE%9B%E8%80%85)
		- [控制器](#%E6%8E%A7%E5%88%B6%E5%99%A8)
		- [模型](#%E6%A8%A1%E5%9E%8B)
		- [服务层(逻辑层)](#%E6%9C%8D%E5%8A%A1%E5%B1%82%E9%80%BB%E8%BE%91%E5%B1%82)
	- [控制器](#%E6%8E%A7%E5%88%B6%E5%99%A8-1)
	- [模型](#%E6%A8%A1%E5%9E%8B-1)
	    - [普通模型](#%E6%99%AE%E9%80%9A%E6%A8%A1%E5%9E%8B)
	    - [中间表模型](#%E4%B8%AD%E9%97%B4%E8%A1%A8%E6%A8%A1%E5%9E%8B)
	- [服务层(逻辑层)](#%E6%9C%8D%E5%8A%A1%E5%B1%82%E9%80%BB%E8%BE%91%E5%B1%82-1)
	- [中间件](#%E4%B8%AD%E9%97%B4%E4%BB%B6-1)
	- [异常处理](#%E5%BC%82%E5%B8%B8%E5%A4%84%E7%90%86-1)
	- [服务提供者](#%E6%9C%8D%E5%8A%A1%E6%8F%90%E4%BE%9B%E8%80%85)
	    - [API服务提供者](#api%E6%9C%8D%E5%8A%A1%E6%8F%90%E4%BE%9B%E8%80%85)
	    - [契约服务提供者](#%E5%A5%91%E7%BA%A6%E6%9C%8D%E5%8A%A1%E6%8F%90%E4%BE%9B%E8%80%85)
	    - [路由服务提供者](#%E8%B7%AF%E7%94%B1%E6%9C%8D%E5%8A%A1%E6%8F%90%E4%BE%9B%E8%80%85)
	- [模型作用域](#%E6%A8%A1%E5%9E%8B%E4%BD%9C%E7%94%A8%E5%9F%9F)
	    - [主键字段倒序](#%E4%B8%BB%E9%94%AE%E5%AD%97%E6%AE%B5%E5%80%92%E5%BA%8F)
	- [trait介绍](#trait%E4%BB%8B%E7%BB%8D)
	    - [BuilderAssembleTrait.php](#builderassembletraitphp)
		- [ModelTrait.php](#modeltraitphp)
		- [RequestInfoTrait.php](#requestinfotraitphp)
		- [ResourceControllerTrait.php](#resourcecontrollertraitphp)
		- [ResourceServiceTrait.php](#resourceservicetraitphp)
	    - [ResultThrowTrait.php](#resultthrowtraitphp)
		- [UserInfoTrait.php](#userinfotraitphp)
	- [工具类介绍](#%E5%B7%A5%E5%85%B7%E7%B1%BB%E4%BB%8B%E7%BB%8D)
	    - [Agent.php](#agentphp)
	    - [SdlCache.php](#sdlcachephp)
	    - [Token.php](#tokenphp)

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
```

## 使用说明

> 环境变量值参考：[env](docs/ENV.md)
> 
> restful参考: [restful](docs/RESTFUL.md)

### 快速使用

1. [安装](#%E5%AE%89%E8%A3%85%E9%85%8D%E7%BD%AE)
2. [发布配置[可选]](#%E5%AE%89%E8%A3%85%E9%85%8D%E7%BD%AE)
3. [注册中间件](#%E4%B8%AD%E9%97%B4%E4%BB%B6)
4. [继承异常处理程序](#%E5%BC%82%E5%B8%B8%E5%A4%84%E7%90%86)
5. [使用路由服务提供者](#%E4%BD%BF%E7%94%A8%E8%B7%AF%E7%94%B1%E6%9C%8D%E5%8A%A1%E6%8F%90%E4%BE%9B%E8%80%85)
6. [示例代码](#%E7%A4%BA%E4%BE%8B%E4%BB%A3%E7%A0%81)

#### 中间件
- 必须注册全局中间件 `Jmhc\Restful\Middleware\ParamsHandlerMiddleware`
- 可选中间件查看 [中间件列表](#%E4%B8%AD%E9%97%B4%E4%BB%B6-1)

#### 异常处理

- 修改 `App\Exceptions\Handler` 继承的方法为  `Jmhc\Restful\Handlers\ExceptionHandler`
- 其他异常捕获重写父类 `customResponse()`  方法，内容参考 `response`

#### 使用路由服务提供者

- 修改 `App\Providers\RouteServiceProvider` 继承的方法为  `Jmhc\Restful\Providers\JmhcRouteServiceProvider`
- 路由服务提供者会自动扫描 `routes` 目录生成路由

#### 示例代码
```php
// route
Route::get('tests', 'TestController@index');

// controller
use Jmhc\Restful\Controllers\BaseController;
use \Jmhc\Restful\Traits\ResourceControllerTrait;

class TestController extends BaseController
{
    use ResourceControllerTrait;
}

// service
use \Jmhc\Restful\Services\BaseService;
class TestService extends BaseService
{
    public function index()
    {
        $this->success('this is tests');
    }
}
```

#### 控制器

- 直接继承 `Jmhc\Restful\Controllers\BaseController`

#### 模型

- 可选继承 `Jmhc\Restful\Models\BaseModel` 、 `Jmhc\Restful\Models\BasePivot` 、 `Jmhc\Restful\Models\UserModel`  、`Jmhc\Restful\Models\VersionModel`

#### 服务层(逻辑层)

- 直接继承 `Jmhc\Restful\Services\BaseService`

### 控制器

> 需继承 `Jmhc\Restful\Controllers\BaseController`

- 可使用 `Jmhc\Restful\Traits\RequestInfoTrait` 里的参数
- 可使用 `Jmhc\Restful\Traits\UserInfoTrait` 里的参数、方法

### 模型

#### 普通模型

> 需继承 `Jmhc\Restful\Models\BaseModel`

- 可使用 `Jmhc\Restful\Traits\ModelTrait` 里的方法

#### 中间表模型

> 需继承 `Jmhc\Restful\Models\BasePivot`

- 可使用 `Jmhc\Restful\Traits\ModelTrait` 里的方法

### 服务层(逻辑层)

> 需继承 `Jmhc\Restful\Services\BaseService`

- 可使用 `Jmhc\Restful\Traits\RequestInfoTrait` 里的参数
- 可使用 `Jmhc\Restful\Traits\UserInfoTrait` 里的参数、方法

### 中间件

> 用法加粗为必须调用

|   中间件   |   别名   |   用法   |   需要实现的契约或继承模型   |
| ---- | ---- | ---- | ---- |
| `Jmhc\Restful\Middleware\ParamsHandlerMiddleware`  | `jmhc.params.handler` | **参数处理** | --- |
| `Jmhc\Restful\Middleware\ConvertEmptyStringsToNullMiddleware` | `jmhc.convert.empty.strings.to.null` | 转换空字符串为null | --- |
| `Jmhc\Restful\Middleware\TrimStringsMiddleware` | `jmhc.trim.strings` | 清除字符串空格 | --- |
| `Jmhc\Restful\Middleware\RequestLockMiddleware` | `jmhc.request.lock` | 请求锁定 | --- |
| `Jmhc\Restful\Middleware\RequestLogMiddleware` | `jmhc.request.log` | 记录请求日志(debug) | --- |
| `Jmhc\Restful\Middleware\CheckVersionMiddleware` | `jmhc.check.version` | 检测应用版本 | `Jmhc\Restful\Contracts\VersionModelInterface`<br />`Jmhc\Restful\Models\VersionModel` |
| `Jmhc\Restful\Middleware\CheckSignatureMiddleware` | `jmhc.check.signature` | 验证请求签名 | --- |
| `Jmhc\Restful\Middleware\CheckTokenMiddleware` | `jmhc.check.token` | 检测token，设置用户数据 | `Jmhc\Restful\Contracts\UserModelInterface`<br />`Jmhc\Restful\Models\UserModel` |
| `Jmhc\Restful\Middleware\CheckSdlMiddleware` | `jmhc.check.sdl` | 单设备登录，需要复写 `Jmhc\Restful\Handlers\ExceptionHandler->sdlHandler()` | --- |

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

#### 契约服务提供者

> `Jmhc\Restful\Providers\JmhcContractServiceProvider`  

- 绑定契约 `Jmhc\Restful\Contracts\RequestParamsInterface` 实现
- 绑定契约 `Jmhc\Restful\Contracts\AgentInterface` 实现
- 绑定契约 `Jmhc\Restful\Contracts\UserModelInterface` 实现
- 绑定契约 `Jmhc\Restful\Contracts\VersionModelInterface` 实现

#### 路由服务提供者

> `Jmhc\Restful\Providers\JmhcRouteServiceProvider`  
>
> 默认不启用

- 注册 `base_path('routes')` 下面所有 php 文件到路由

### 模型作用域

#### 主键字段倒序

> `Jmhc\Restful\Scopes\PrimaryKeyDescScope`

`Jmhc\Restful\Models\BaseModel` 已默认注册此全局作用域

### trait介绍

#### BuilderAssembleTrait.php

> `Jmhc\Restful\Traits\BuilderAssembleTrait`
>
> 查询构造组装辅助 trait

#### ModelTrait.php

> `Jmhc\Restful\Traits\ModelTrait`
>
> 模型辅助 trait

#### RequestInfoTrait.php

> `Jmhc\Restful\Traits\RequestInfoTrait`
>
> 请求信息绑定

使用类:

- `Jmhc\Restful\Controllers\BaseController`
- `Jmhc\Restful\Services\BaseService`

#### ResourceControllerTrait.php

> `Jmhc\Restful\Traits\ResourceControllerTrait`
>
> 资源控制器 trait

#### ResourceServiceTrait.php

> `Jmhc\Restful\Traits\ResourceServiceTrait`
>
> 资源服务 trait

#### ResultThrowTrait.php

> `Jmhc\Restful\Traits\ResultThrowTrait`
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

#### Agent.php

> `Jmhc\Restful\Utils\Agent`
>
> 请求 Agent 类

#### SdlCache.php

> `Jmhc\Restful\Utils\SdlCache`
>
> 单设备登录类

#### Token.php

> `Jmhc\Restful\Utils\Token`
>
> 令牌相关类

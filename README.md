<p align="center"><a href="#" target="_blank"><img src="https://sip.npac-ntch.org/sip/img/ntch_logo.svg" alt="國家兩廳院" width="400"></a></p>
<br>
<p align="center">
<a href="#"><img src="https://img.shields.io/badge/php-%3E%3D7.0-blue" alt="PHP Version"></a>
<a href="#"><img src="https://img.shields.io/badge/license-MIT%20%2B%20file%20LICENSE-blue" alt="License"></a>
<a href="#"><img src="https://img.shields.io/badge/downloads-0M-green" alt="Total Downloads"></a>
</p>
<br>

## NTCH 框架
---------- 
NTCH框架是由國家兩廳院資訊組，針對自家開發需求撰寫而成，透過簡單路由引擎加上 MVC 架構概念，做到程式碼的輕易控管。
<br><br>

## 框架安裝及部署
---------- 
### **- 安裝**
待補


### **- 伺服器**
伺服器使用 Nginx，其設定檔（nginx.conf）範例如下：

```bash
# 需自行調整參數
# WEB -> 路徑目錄
# web_a -> 專案名稱

server {
        listen 60000;
        root   /{WEB}/src/{web_a}/public/;
        index  index.html index.htm index.php;
        location /NTCH/ {
            alias /{WEB}/vendor/ntch/framework/src/;
            location ~ \.php$ {
                fastcgi_pass 127.0.0.1:30001;
                fastcgi_index index.php;
                include fastcgi_params;
                fastcgi_param SCRIPT_FILENAME $request_filename;
                fastcgi_param DOCUMENT_ROOT $document_root;
                fastcgi_param PROJECT_ROOT /{WEB}/src/{web_a}/;
            }
        }
        location / {
            try_files $uri /NTCH/Bootstrap.php$is_args$args;
        }
    }
```
<br><br>


## 目錄結構
---------- 
```
WEB
│
└─── src
│    │
│    └─── web_a （project name）
│         │
│         │─── controllers
│         │
│         │─── libraries
│         │
│         │─── models
│         │
│         │─── public
│         │
│         │─── routes
│         │
│         │─── settings
│         │
│         └─── view
│
└─── vendor
│ 
│  composer.json

```
<br><br>


## 學習
---------- 
> ### **router**

資料夾：routes<br>
檔案：router.php<br>

< routes 起手式 >
```php
use Ntch\Framework\WebRestful\Routing\Router;

$router = new Router();
```
< 可用路由的方法 >
```php
$router->controller($uri, $path, $file, $method);

$router->view($uri, $path, $file, $data);

$router->mvc($uri,
    [
        'controller' => [$path, $file],
        'models' => [$modelName],
        'libraries' => [$libraryName]
    ]);


// Example
$router->controller('/hihi/hello/:a/:b/:c', '/', 'test');

$router->view('/welcome/who/:name', '/', 'test2', ["ntch" => "framework"]);

$router->mvc('/test3',
    [
        'controller' => ['/', 'test3'],
        'oracle' => ['ORC', 'oa', 'ob'],
        'mysql' => ['MY', 'ma'],
        'libraries' => ['la', 'lb']
    ]);
```

| 方法         | 說明  |
| :--------   | :---- |
| controller  | 轉向 controller 方法 |
| view        | 轉向 view 方法 |
| mvc         | 需要引入 model 和 library 等物件並轉向 controller 方法 |

＊model 提供的類型再請參考 model 的文件

<br>

| 參數    | 說明  |
| :----  | :---- |
| uri    | 請求的網址，加 '：' 及代表此為參數 |
| path   | 請求導向執行檔案的路徑 |
| file   | 執行檔案名稱 |
| method | 執行檔案中的方法名稱，預設 index |
| data   | 要傳入的參數 |

＊注意：重複符合的 uri ，以上面的為主。<br>
＊建議：常用功能方法往上放，能加速執行速度。

<br><br>

> ### **controller**

資料夾：controllers<br>
檔案：xxx.php<br>

< controllers 起手式 >
```php
use Ntch\Framework\WebRestful\Controllers\Controller;
# 如要導向 view 請把 Router 導入
use Ntch\Framework\WebRestful\Routing\Router;

class xxx extends Controller
{
    public function index()
    {
        $router = new Router();
        $router->view(null, '/', 'test2', array("name" => "Roy"));
    }
}
```

< request 提供的物件 >
```text
# $this->request

[request] => stdClass Object
        (
            [uuid] => 10AEF2B9-D783-2CAA-8E07-6BDC2EF83117
            [method] => GET
            [uri] => Array
                (
                    [a] => 1
                    [b] => 2
                    [c] => 3
                )

            [query] => Array
                (
                    [abc] => 123
                    [xyz] => 456
                )

            [input] => 
            [attribute] => Array
                (
                )

            [authorization] => Array
                (
                )

            [cookies] => Array
                (
                    [php] => omfb5668s9i952d05gdbckp1ej
                )

            [files] => Array
                (
                )

            [client] => Array
                (
                    [ip] => 172.16.60.174
                    [port] => 60505
                )

            [time] => Array
                (
                    [unix] => 1643257668.4144
                    [date] => 2022-01-01
                    [time] => 12:00:00
                )

            [headers] => Array
                (
                )

        )
```
| 參數           | 說明  |
| :----         | :---- |
| uuid          | 每次請求的獨立唯一編號 |
| method        | 請求方法 |
| uri           | router 設定的參數 |
| query         | GET 方法代入的參數 |
| attribute     | POST 方法代入的參數 |
| authorization | herder 中的 token |
| cookies       | 請求帶入的 cookie |
| files         | input 上傳的 file |
| client        | 請求來源 |
| time          | 請求時間 |
| headers       | 請求 header |

<br><br>

> ### **setting**

資料夾：settings<br>
檔案：xxx.ini<br>

  - library 檔名：libraries
  - model 檔名：oracle、mysql、mssql

< settings 起手式 >
```ini
# 檔名：libraries.ini
# 別名 = 路徑（要載入至哪個路徑下的所有檔案）
all = /
la  = /la

# 檔名：oracle.ini
[ORACLE]
type      = server
ip        = 127.0.0.1
port      = 1521
sid       = oracle
user      = Roy
password  = *************
path      = /ORACLE
class     = roy

[test]
type    = table
server  = ORACLE
table   = framework_test
path    = /ORACLE
class   = framework_test

# 檔名：mysql.ini
[MYSQL]
type      = server
ip        = 127.0.0.1
port      = 3306
database  = ROY_DEVELOPER_TEST
user      = Roy
password  = ************* 
path      = /MYSQL
class     = roy

[test]
type    = table
server  = MYSQL
table   = framework_test
path    = /MYSQL
class   = framework_test

# 檔名：mssql.ini
[MSSQL]
type      = server
ip        = 127.0.0.1
port      = 1433
database  = ROY_DEVELOPER_TEST
user      = Roy
password  = ************* 
path      = /MSSQL
class     = roy

[test]
type    = table
server  = MSSQL
table   = framework_test
path    = /MSSQL
class   = framework_test
```

<br><br>

> ### **library**

資料夾：libraries<br>
檔案：xxx.php<br>

< library 起手式 >
```php
# 依據 PSR-4 命名規則，給予路徑
namespace la\lb\lc;

class xxx
{
    public function index()
    {
        echo 'librarys import success！' . PHP_EOL;
    }
}
```

< setting 定義別名 >
```ini
lib = /la
```

< router 檔案引入至 controller 使用 >
```php
$router->mvc('/test',
    [
        'controller' => ['/', 'test'],
        'libraries' => ['lib']
    ]);
```

< controller 使用 >
```php
use la\lb\lc\xxx;

class test extends Controller
{
    public function index()
    {
        $lib = new xxx();
        $lib->index();
    }
}
```

<br><br>

> ### **model**

資料夾：models<br>
檔案：xxx.php<br>

＊提供類型：Oracle、Mysql、Mssql

< model 起手式 >
```php
# 依據需求引入相對應的 model

# Oracle
use Ntch\Framework\WebRestful\Models\OracleModel;

# Mysql
use Ntch\Framework\WebRestful\Models\MysqlModel;

# Mssql
use Ntch\Framework\WebRestful\Models\MssqlModel;


class framework_test extends OracleModel # 依據使用的類型繼承
{

    # modelType = server or table
    public string $modelType = 'table';
    # setting 給的匿名
    public string $modelName = 'ft';

    public function schema()
    {
        $schema['COLUMN_NAME'] = [
            'DATA_TYPE' => '', 
            'DATA_SIZE' => '',
            'NULLABLE' => '',
            'DATA_DEFAULT' => '',
            'KEY_TYPE' => '',
            'COMMENT' => '',
            'SYSTEM_SET' => ''
        ];

        return $schema;
    }
}
```

| 參數           | 說明  | 必填  | 內容  |  
| :----         | :---- | :---- | :---- |
| DATA_TYPE     | 欄位型別 | 是 | 各別資料庫如下說明 |
| DATA_SIZE     | 欄位大小 | 否 | 時間參數依各資料庫 format 格式，提供：年、月、日、時、分、秒 |
| NULLABLE      | 是否為空值 | 否 | 預設為否 |
| DATA_DEFAULT  | 預設值 | 否 | 預設為 null |
| KEY_TYPE      | 鍵值 | 否 | P：主鍵 |
| COMMENT       | 敘述 | 否 |  |
| SYSTEM_SET    | 系統設定，做 CRUD 時會自動代入 | 否 | PRIMARY_KEY：主鍵 , UPDATE_DATE：更新時間 |

<br>

 - Oracle 提供的 DATA_TYPE
   - CHAR
   - NCHAR
   - VARCHAR2
   - NVARCHAR2
   - NCLOB
   - FLOAT
   - NUMBER
   - DATE
  
 - Mysql 提供的 DATA_TYPE
   - char
   - varchar
   - tinyint
   - smallint
   - mediumint
   - bigint
   - int
   - float
   - decimal
   - timestamp
   - datetime
   - date
   - time
   - year

 - Mssql 提供的 DATA_TYPE
   - char
   - varchar
   - nchar
   - nvarchar
   - tinyint
   - smallint
   - bigint
   - int
   - float
   - decimal
   - datetime
   - date

<  setting 定義別名  >
```ini
[ORACLE]
type      = server
ip        = 127.0.0.1
port      = 1521
sid       = oracle
user      = Roy
password  = *************
path      = /ORACLE
class     = roy

[ft]
type    = table
server  = ORACLE
table   = framework_test
path    = /ORACLE
class   = framework_test
```

< router 檔案引入至 controller 使用 >
```php
$router->mvc('/test',
    [
        'controller' => ['/', 'test'],
        'oracle' => ['ORACLE', 'ft']
    ]);
```

＊server 為撈出該 database 所有 table，非必要引入

< controller 使用 >
```php
class test extends Controller
{
    public function index()
    {
        # server 導入
        $oracle = $this->models['oracle']->server['ORACLE']->framework_test;

        # table 導入
        $ft = $this->models['oracle']->table['ft'];

        # ORM 架構 
        # createTable 範例
        $sql = $ft->createTable();

        # commentTable 範例
        $sql = $ft->commentTable();

        # select 範例
        $data = $oracle->select(['OR_NVARCHAR', 'OR_CHAR', 'SUM(OR_FLOAT)'])->
        where(['OR_CHAR' => '1'])->groupby(['OR_NVARCHAR', 'OR_CHAR'])->
        orderby(['OR_NVARCHAR'])->query();

        # insert 範例
        $data = $ft->insert()->value(['NTCH_SIGN' => '99999'])->query();

        # update 範例
        $data = $ft->update()->set(['NTCH_SIGN' => '99999'])->where(['OR_NVARCHAR' => '1639552879135756'])->query();

        # delete 範例
        $data = $ft->delete()->where(['OR_NVARCHAR' => '1639552879135756'])->query();

        # merge 範例 - 僅提供 Oracle 使用
        $data = $ft->merge()->using('IT_8614', 'FRAMEWORK_TEST_B')->on("OR_NVARCHAR", "OR_CHAR")->
                    matched()->update()->set(['NTCH_SIGN' => 'NTCH_SIGN', 'OR_FLOAT' => 'OR_FLOAT'])->
                    not()->insert()->value()->query();

        # commit 範例
        $ft->commit();

        # rollback 範例
        $ft->rollback();

        # 筆數限制 - 第 5 筆開始顯示 8 筆
        # Oracle 範例 - 若兩個方法皆要使用 offset() limit() 順序可顛倒
        $data = $oracle->select()->offset(5)->limit(8)->query();

        # Mysql 範例 - 若兩個方法皆要使用 limit() offset() 順序可顛倒
        $data = $mysql->select()->limit(8)->offset(5)->query();

        # Mssql 範例 - 兩種方法
        $data = $mssql->select()->top(8)->query();
        $data = $mssql->select()->groupby()->offset(5)->fetch(8)->query();
    }
}
```

 - 通用方法
   - createTable
   - commentTable (Mysql 不適用)
   - insert
   - value
   - delete
   - update
   - set
   - select
   - where
   - orderby
   - groupby
   - query
   - commit
   - rollback
   - keyName (指定 key 欄位)

 - Oracle 方法
   - merge
   - using
   - on
   - matched
   - not

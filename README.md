# guolian_test
### 项目目录结构
```
.
├── application
│   ├── cache
│   ├── config              --配置文件目录
│   ├── controllers         --控制器
│   │   └── command         --crontab任务
│   ├── core                --用户核心文件
│   ├── helpers
│   ├── hooks
│   ├── language
│   │   └── english
│   ├── libraries           --第三方类库
│   │   └── PHPExcel
│   │       ├── CachedObjectStorage
│   │       ├── CalcEngine
│   │       ├── Calculation
│   │       │   └── Token
│   │       ├── Cell
│   │       ├── Chart
│   │       │   └── Renderer
│   │       ├── Helper
│   │       ├── Reader
│   │       │   ├── Excel2007
│   │       │   └── Excel5
│   │       │       ├── Color
│   │       │       └── Style
│   │       ├── RichText
│   │       ├── Shared
│   │       │   ├── Escher
│   │       │   │   ├── DgContainer
│   │       │   │   │   └── SpgrContainer
│   │       │   │   └── DggContainer
│   │       │   │       └── BstoreContainer
│   │       │   │           └── BSE
│   │       │   ├── JAMA
│   │       │   │   └── utils
│   │       │   ├── OLE
│   │       │   │   └── PPS
│   │       │   ├── PCLZip
│   │       │   └── trend
│   │       ├── Style
│   │       ├── Worksheet
│   │       │   ├── AutoFilter
│   │       │   │   └── Column
│   │       │   └── Drawing
│   │       ├── Writer
│   │       │   ├── Excel2007
│   │       │   ├── Excel5
│   │       │   ├── OpenDocument
│   │       │   │   └── Cell
│   │       │   └── PDF
│   │       └── locale
│   │           ├── bg
│   │           ├── cs
│   │           ├── da
│   │           ├── de
│   │           ├── en
│   │           │   └── uk
│   │           ├── es
│   │           ├── fi
│   │           ├── fr
│   │           ├── hu
│   │           ├── it
│   │           ├── nl
│   │           ├── no
│   │           ├── pl
│   │           ├── pt
│   │           │   └── br
│   │           ├── ru
│   │           ├── sv
│   │           └── tr
│   ├── logs
│   ├── models              --模型类（数据库）
│   ├── third_party         --第三方模块
│   │   ├── Monolog
│   │   │   ├── Formatter
│   │   │   ├── Handler
│   │   │   │   └── FingersCrossed
│   │   │   └── Processor
│   │   └── vendor
│   │       └── composer
│   └── views               --视图文件
│       └── errors
│           ├── cli
│           └── html
├── bin                     --crontab前缀脚本
├── data
│   └── excel
└── system                  --CI核心系统文件
    ├── core
    │   └── compat
    ├── database
    │   └── drivers
    │       ├── cubrid
    │       ├── ibase
    │       ├── mssql
    │       ├── mysql
    │       ├── mysqli
    │       ├── oci8
    │       ├── odbc
    │       ├── pdo
    │       │   └── subdrivers
    │       ├── postgre
    │       ├── sqlite
    │       ├── sqlite3
    │       └── sqlsrv
    ├── fonts
    ├── helpers
    ├── language
    │   └── english
    └── libraries
        ├── Cache
        │   └── drivers
        ├── Javascript
        └── Session
            └── drivers
```
### 相关数据表SQL
```
CREATE TABLE `gl_integral_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `first_integral` int(11) NOT NULL DEFAULT '0' COMMENT '直接推荐人赠送积分数量',
  `second_integral` int(11) NOT NULL DEFAULT '0' COMMENT '二级推荐人赠送积分数量',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否可用 1可用 2不可用',
  `adddate` datetime NOT NULL COMMENT '添加时间',
  `update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='积分赠送配置表';
![image](https://user-images.githubusercontent.com/30429088/232596391-27a6a755-52bc-483f-9ee7-4e7fe18b5be6.png)


CREATE TABLE `gl_user_change_integrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `change_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '变动类型 1 增加 2减少',
  `change_integral` int(11) NOT NULL DEFAULT '0' COMMENT '变动的积分数量',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '发生积分变动的用户ID',
  `contri_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '贡献此次变动的用户ID',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '变动描述',
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户积分变动记录表';
![image](https://user-images.githubusercontent.com/30429088/232596332-b638d6d9-0bd7-4377-8031-8741329a2c7e.png)


CREATE TABLE `gl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '积分数量',
  `recomend_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '推荐人ID',
  `adddate` datetime NOT NULL COMMENT '添加时间',
  `update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户表';
![image](https://user-images.githubusercontent.com/30429088/232596259-a833ff81-2448-4106-b9d2-9be6d3543d36.png)

```

### 业务控制器实现
#### 1用户注册 Code:application/controllers/Welcome.php:register url:/welcome/register
![image](https://user-images.githubusercontent.com/30429088/232597529-134835eb-9055-47bc-bc2a-74369d09a80d.png)
#### 2用户批量注册 Code:application/controllers/Welcome.php:register_batch url:/welcome/register_batch
![image](https://user-images.githubusercontent.com/30429088/232597670-2463cb8c-8962-4d1b-8aba-4818464a56a7.png)
#### 3用户无限级列表展示 Code:application/controllers/Welcome.php:getSubordinateByUserId url:/welcome/getSubordinateByUserId?user_id=1
![image](https://user-images.githubusercontent.com/30429088/232597950-36dc62f1-6513-412d-8a4a-423b883e8f9c.png)

### 相关数据层model文件
#### application/models/User_integral_model.php





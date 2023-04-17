# guolian_test
## 项目目录结构
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

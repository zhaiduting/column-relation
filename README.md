Column-relation extension for laravel-admin
======
# 安装
`composer require zhaiduting/column-relation`
# 注册扩展
在文件 app\Admin\bootstrap.php 中添加如下代码
```
// Add the following code to the file app\Admin\bootstrap.php

use Encore\Admin\Grid\Column;
use Zhaiduting\ColumnRelation\Relate;

Column::extend('relate', Relate::class);
```
# 使用
在 laravel-admin 的控制器中，可以类似于下面这样使用 relate(..)
```
// Use relate(..) in a controller of admin as follows

$grid = new Grid(new Role());

$grid->column('name', '类别')
     ->relate('topics', ['id', 'title'=>'话题']);
```
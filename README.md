Column-relation extension for laravel-admin
======
# 安装
`composer require zhaiduting/column-relation`
# 注册扩展
在文件 app\Admin\bootstrap.php 中添加如下代码
```
// Add the following code to the file app\Admin\bootstrap.php

use Encore\Admin\Table\Column;
use Zhaiduting\ColumnRelation\Relate;

Column::extend('relate', Relate::class);
```
# 使用
在 laravel-admin 的控制器中，可以类似于下面这样使用 relate(..)
```
// e.g: use relate(..) in the app\Admin\Controllers\RoleController.php as follows

protected function table()
{
    $table = new Table(new Role());

    $table->column('id', __('Id'));
    $table->column('name', '角色名称')
        ->relate('users', ['id', 'name'=> '用户'], function($user){
            $user->name=
                "<a target='_blank' href='". route('users.show', $user->id). "'>".
                "<img class='img-thumbnail' width='30px' src='".
                $user->avatar ."'> ".
                $user->name.
                "</a>";
        });
    $table->column('permissions', '权限')->pluck('name')->label('default');

    return $table;
}
```
![example.gif](https://github.com/zhaiduting/column-relation/blob/master/example.gif)
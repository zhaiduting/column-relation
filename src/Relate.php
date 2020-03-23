<?php

namespace Zhaiduting\ColumnRelation;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;
use Encore\Admin\Widgets\Table;
use Illuminate\Support\Str;

class Relate extends AbstractDisplayer
{
    protected $uid, $models, $pagination, $bag, $html;
    public static $container= '#pjax-container';

    public function display(string $relationShip = '', array $tableHeader = [] , callable $callback = null, int $perPage = 4,  array $where= [])
    {
        $this->make_uid();
        $this->make_models_and_pagination($relationShip, $perPage, $where);
        $this->make_bag($tableHeader, $callback);
        $this->make_html();
        $this->make_script();
        return $this->html;
    }

    protected function make_uid(){
        $this->uid= $this->column->getName(). $this->getKey();
    }
    protected function make_models_and_pagination($relationShip, $perPage, $where){
        $models= $this->row->$relationShip();
        $models= empty($where[0]) ? $models :
            (gettype($where[0]) == 'array'
                ? $models->where($where)
                : $models->where([$where])
            );
        $models= $models
            ->paginate($perPage, ['*'], $this->uid)
            ->appends([
                'page'=>request('page'),
                'per_page'=>request('per_page')
            ])
        ;
        $this->models= $models;
        $this->pagination= str_replace('pagination', 'pagination pagination-sm no-margin pt-2', $this->models->links());
    }
    protected function make_bag($tableHeader, $callback){
        $models= $this->models;
        $fields= []; $headers= [];
        foreach($tableHeader as $k=>$v){
            $k= is_integer($k) ? $v : $k;
            $fields[]= $k;
            $headers[]= $v;
        }
        $this->bag= function() use($models, $fields, $headers, $callback){
            $models= $models->map(function($model) use($fields, $callback){
                if($callback)
                    $callback($model);
                return $model->only($fields);
            });
            return new Table($headers, $models->toArray(), ['my-4', 'column-relation-table']);
        };
    }
    protected function make_html(){
        if($this->models->count()){
            $uid = $this->uid;
            $html= ($this->bag)();
            $html="
                <span id='grid-button-{$uid}' data-inserted='0' data-uid='{$uid}' data-toggle='collapse' data-target='#grid-collapse-{$uid}'>
                   <a href='javascript:void(0)' style='color:inherit'>{$this->value}<i class='px-2 pb-1 fa fa-angle-double-down'></i></a>
                </span>
                <div id='grid-template-{$uid}' style='display:none'>
                    <!--ColumnRelation{$this->uid}Start--> {$html} <!--ColumnRelation{$this->uid}End-->
                </div>
                <div id='grid-collapse-{$uid}' class='collapse'>
                    <column-relation :uid=`{$uid}`></column-relation>
                </div>
            ";
        }else{
            $html= $this->value;
        }
        if($this->models->lastPage() > 1){  //单页面的情况下，没必要添加分页按钮
            $search= '</tr>';
            $replace= "</tr><tr><td colspan='100' class='column-relation-pagination'><i data-split=\"ColumnRelationPagination\"></i>{$this->pagination}</td></tr>";
            $html= Str::replaceLast($search, $replace, $html);
        }
        $this->html= $html;
    }
    protected function make_script(){
        $container= self::$container;
        $script= "
            $('#grid-button-{$this->uid}').on('click', function () {
                if ($(this).data('inserted') == '0') {
                    var bgcolor= $('{$container}').css('background-color')
                    var uid = $(this).data('uid');
                    
                    var collapse = $('#grid-collapse-'+uid);
                    var td= $(`<td colspan='100' style='padding:0; border:0;'></td>`).append(collapse);
                    var tr= $(`<tr style='background-color:\${bgcolor}'></tr>`).append(td);
                    $(this).closest('tr').after(tr);
                    
                    $(this).data('inserted', 1);
                }
                
                $('i', this).toggleClass('fa-angle-double-down fa-angle-up');
            });
        ";
        if(request($this->uid)) {
            //取消内联表格的第一个入场动画
            //保持内联表格为展开状态
            //保证内联表格位于视窗之内
            $script .= "
                (function(){
                    setTimeout(()=>{
                        var button= $('#grid-button-{$this->uid}')
                            .attr('data-toggle', '').trigger('click')
                            .attr('data-toggle', 'collapse');
                        var target= $(button.data('target')).addClass('collapse in');
                    
                        if(!target.offset()) return;
                        var target_top= target.offset().top;
                        var target_height= target.height();
                        var window_height= $(window).height();
                        
                        if(target_top + target_height < window_height)
                            return;
                        var y= window_height - target_height;
                        if(y > 0){
                            $(window).scrollTop(target_top - y/2);
                        }else{
                            $(window).scrollTop(target_top + target_height - window_height + 4);
                        }
                    }, 0);
                })();
            ";
        }
        Admin::script($script);
    }
}

(function(){
    $space= 'vendor/zhaiduting';
    $sym= 'column-relation';
    $file= 'relate';

    Admin::css("/$space/$sym/$file.css");
    Admin::script("
        $(document.body)
            .append('<'+'script'+` src='/$space/$sym/$file.js'>`+'<'+'/script>');
    ");                                                 //pjax 请求导致 Admin::js(..) 失效？

    $dir= ($_SERVER['DOCUMENT_ROOT'] ?: public_path()). "/$space";
    if(!file_exists("$dir/$sym/$file.js")){
        try{
            if(!is_dir($dir))
                mkdir($dir, 0777, true);
            symlink(__DIR__."/../dist", "$dir/$sym");

        }catch (\Exception $e){}
    }
})();

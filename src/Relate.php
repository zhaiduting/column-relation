<?php

namespace Zhaiduting\ColumnRelation;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;
use Encore\Admin\Widgets\Table;

class Relate extends AbstractDisplayer
{
    protected $page_name;
    protected $models, $pagination, $bag, $html;

    public function display(string $relationShip = '', array $tableHeader = [] , callable $callback = null, int $perPage = 4)
    {
        $this->make_page_name();
        $this->make_models_and_pagination($relationShip, $perPage);
        $this->make_bag($tableHeader, $callback);
        $this->make_html();
        $this->make_script();
        return $this->html;
    }

    protected function make_page_name(){
        $this->page_name= $this->column->getName(). $this->getKey();
    }
    protected function make_models_and_pagination($relationShip, $perPage){
        $this->models= $this->row->$relationShip()
            ->paginate($perPage, ['*'], $this->page_name)
            ->appends('page', request('page'));
        $this->pagination= str_replace('pagination', 'pagination pagination-sm no-margin', $this->models->links());
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
            return new Table($headers, $models->toArray());
        };
    }
    protected function make_html(){
        if($this->models->count()){
            $key = $this->column->getName().'-'.$this->getKey();
            $html= ($this->bag)();
            $html="
                <span class='{$this->getElementClass()}' data-inserted='0' data-key='{$key}' data-toggle='collapse' data-target='#grid-collapse-{$key}'>
                   <a href='javascript:void(0)' style='color:inherit'>{$this->value}&nbsp;&nbsp;<i class='fa fa-angle-double-down'></i></a>
                </span>
                <template class='grid-expand-{$key}'>
                    <div id='grid-collapse-{$key}' class='collapse'>
                        <div style='margin-top:20px'>$html</div>
                    </div>
                </template>
            ";
        }else{
            $html= $this->value;
        }
        if($this->models->lastPage() > 1){  //单页面的情况下，没必要添加分页按钮
            $search= '</tr>';
            $replace= "</tr><tr><td colspan='100'>{$this->pagination}</td></tr>";
            $html= str_replace_last($search, $replace, $html);
        }
        $this->html= $html;
    }
    protected function make_script(){
        $script= "
            $('.{$this->getElementClass()}').on('click', function () {
                
                if ($(this).data('inserted') == '0') {
                
                    var key = $(this).data('key');
                    var row = $(this).closest('tr');
                    var html = $('template.grid-expand-'+key).html();
                    var bgcolor= $('.content-wrapper').css('background-color')
            
                    row.after(`<tr style='background-color:\${bgcolor}'><td colspan='100' style='padding:0 !important; border:0;'>\${html}</td></tr>`);
            
                    $(this).data('inserted', 1);
                }
                
                $('i', this).toggleClass('fa-angle-double-down fa-angle-double-up');
            });
        ";
        if(request($this->page_name)) {
            //取消内联表格的第一个入场动画
            //保持内联表格为展开状态
            //保证内联表格位于视窗之内
            $script .= "
                (function(){
                    var button= $('.{$this->getElementClass()}')
                        .attr('data-toggle', '').trigger('click')
                        .attr('data-toggle', 'collapse');
                    var target= $(button.data('target')).addClass('collapse in');
                    
                    if(!target.offset()) return;
                    setTimeout(()=>{
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
                    }, 400);
                })();
            ";
        }
        Admin::script($script);
    }

    protected function getElementClass()
    {
        return "grid-expand-{$this->grid->getGridRowName()}-{$this->page_name}";
    }
}
/*
    jQuery 加载之后再加载本插件
    author email: zhaiduting@163.com
 */

let width=30, height=30;
let gif='data:image/gif;base64,R0lGODlhLAAsAPe+AJnv//n5+TY2Nvz8/P3+/1Dk/+L6/42Njfv+/1fl/13m//f3907j/1Tk/53w/4WFhTg4OCsrK1Lk/8r2/4fs//7+/8L1//L9/+z8/4uLizzh/wbY/+T6/6Xx/4Xs//Ly8gPY//f9/3l5eTs7O5/w/1nl//n+//D8/52dnV/m/57w/6Lw/2no/6Dw/+j7/3Z2dqHw/0BAQKPw/8j2/5Tu/zAwMMn2/yYmJnrq/y4uLsP1/wnZ/4CAgHt7e6mpqYODg3zq/zMzM6Kion5+fqvy/8D1/7Tz/z09PYiIiPT9/2Pn/xjb/y3e/0vj/8H1/4vt/ykpKdD3/8f2/3/r/9f4/67y/9j5/xvc/09PT3FxcVRUVIns/4Hr/5qamoPs/2zo/8X2/5jv/2Xn/6Tx/9T4/6bx/5rv/5WVlW/o/935/9X4/6/y/2Hm/01NTXXp/zng/8T1//z+/3Tp/z/h/3bq/9/6/2fn/8/3//r+/5zv/0hISNP4/9v5/27o/zPf/833/7Hz/5CQkJ+fn1JSUpKSkr30/0VFRary/0JCQvT09Kzy/w/a/zbg/4/t/1rl/8v3/6nx/7z0/873/1vl/xXb/6fx/3Nzc0jj/wzZ/63y/9H3/5Pu/43t/3Dp/25ubrv0/5Hu/5bu/5vv/+78/6ysrL71/+H6/7r0/0pKSrj0/37r/2bn/x7c/9L4/7GxsYrt/7Dy/3jq/3Lp/+b7/+/v72xsbITs/7/1/9n5/8b2/zDf/yQkJADY/0zj/////wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh/wtYTVAgRGF0YVhNUDw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QzA4RjVDM0U0N0RCMTFFOUI2MEFGNEVCNTJFNDZDNDYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QzA4RjVDM0Y0N0RCMTFFOUI2MEFGNEVCNTJFNDZDNDYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpDMDhGNUMzQzQ3REIxMUU5QjYwQUY0RUI1MkU0NkM0NiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpDMDhGNUMzRDQ3REIxMUU5QjYwQUY0RUI1MkU0NkM0NiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgH//v38+/r5+Pf29fTz8vHw7+7t7Ovq6ejn5uXk4+Lh4N/e3dzb2tnY19bV1NPS0dDPzs3My8rJyMfGxcTDwsHAv769vLu6ubi3trW0s7KxsK+urayrqqmop6alpKOioaCfnp2cm5qZmJeWlZSTkpGQj46NjIuKiYiHhoWEg4KBgH9+fXx7enl4d3Z1dHNycXBvbm1sa2ppaGdmZWRjYmFgX15dXFtaWVhXVlVUU1JRUE9OTUxLSklIR0ZFRENCQUA/Pj08Ozo5ODc2NTQz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';
let defaultConfig={
    width:width, height: height, src: gif,
};
export default function(arg){
    let gif= $('#zhaiduting-promise-gif');
    if(!gif.length){
        let config= typeof(arg)==='object'? $.extend(defaultConfig, arg): defaultConfig;
        let html=`
            <div id="zhaiduting-promise-gif"  style="position:fixed;bottom:50%;right:50%;width:${config.width}px;height:${config.height}px;z-index:10000;visibility:hidden">
                <img src="${config.src}" style="position:absolute;width:100%;height:100%;bottom:-0%;right:-50%;visibility:visible">
            </div>
        `;
        $(document.body).append(html)
        gif=$('#zhaiduting-promise-gif')
    }
    if(arg===false || arg===0){ //关闭gif
        return this.then(()=>
            setTimeout(()=>
                gif.hide(), 200
            )
        ).catch(()=>
            setTimeout(()=>
                gif.hide(), 200
            )
        );
    }else{
        gif.show();
    }

    return this;
}
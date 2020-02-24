<template>
    <div>
        <div v-html="html" @click="handleClick($event)"></div>
    </div>
</template>

<script>
    export default {
        name: "ColumnRelation",
        props: {
            uid: ''
        },
        data(){return{
            html: '',
        }},
        methods:{
            fixPagination(str){
                let arr= str.split('<i data-split="ColumnRelationPagination"></i>');
                let ret= arr[0];
                if(arr[1]){
                    ret += arr[1].replace(/<a /g, "<a onclick='return false' ");
                }
                return ret;
            },
            filterHtml(html){
                let start= html.indexOf('<!--ColumnRelation'+ this.uid+ 'Start-->');
                let end= html.indexOf('<!--ColumnRelation'+ this.uid+ 'End-->', start);
                return html.substring(start, end);
            },
            handleClick($event){
                let target= $event.target;
                if(!target.href || !$(target).closest('td').hasClass('column-relation-pagination'))
                    return;
                axios.get(target.href)
                    .gif()
                    .then((res)=>{
                        let html= this.filterHtml(res.data);
                        this.html= this.fixPagination(html);
                    })
                    .gif(false)
                ;
            }
        },
        mounted(){
            let str= $(`#grid-template-${this.uid}`).remove().html();
            this.html= this.fixPagination(str);
        }
    }
</script>

<style scoped lang="stylus">

</style>
<template>
    <div>
        <div v-html="html" @click="handleClick($event)"></div>
    </div>
</template>

<script>
    export default {
        name: "ColumnRelation",
        props: {
            uid: '', rid: '',
        },
        data(){return{
            html: '',
        }},
        methods:{
            fixPagination(str){
                let arr= str.split('<i><b></b></i>');
                let ret= arr[0];
                if(arr[1]){
                    ret += arr[1].replace(/<a /g, "<a onclick='return false' ");
                }
                return ret;
            },
            filterHtml(html){
                // console.dir(html);
                console.log('<!--ColumnRelation'+ this.rid+ 'Start-->');
                console.log('this.uid= ', this.uid)
                let arr= html.split('<!--ColumnRelation'+ this.rid+ 'Start-->');
                // console.log(arr, '<!--ColumnRelation'+ this.rid+ 'Start-->', html.indexOf('<!--ColumnRelation'+ this.rid+ 'Start-->'));
                arr= arr[1].split('<!--ColumnRelation'+ this.rid+ 'End-->');
                return arr[0];
            },
            handleClick($event){
                let target= $event.target;
                if(!$(target).closest('td').hasClass('column-relation-pagination'))
                    return;
                console.log(target.href);
                axios.get(target.href).then((res)=>{
                    console.dir(res.data);
                    let html= this.filterHtml(res.data);
                    this.html= this.fixPagination(html);
                })
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
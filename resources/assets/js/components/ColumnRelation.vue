<template>
    <div v-html="html"></div>
</template>

<script>
    export default {
        name: "ColumnRelation",
        props: {
            templateId: ''
        },
        data(){return{
            html: '',
        }},
        mounted() {
            this.html= $(`#${this.templateId}`).remove().html();
            console.log('mounted');
        }
    }
</script>

<style scoped lang="stylus">

</style>
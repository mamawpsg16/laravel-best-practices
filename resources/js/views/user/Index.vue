<template>
    <div>
        User
    </div>
</template>

<script>
    export default {
        mounted(){
            console.log(this.$route.params);
        },
        // watch:{
        //     'route.params.username'(newValue, oldValue) {
        //         console.log(newValue, oldValue,'newValue, oldValue');
        //     }
        // }
        
    }
</script>

<style lang="scss" scoped>

</style>
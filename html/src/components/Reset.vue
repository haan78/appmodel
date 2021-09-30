<template>
    <el-button @click="test()">Test 33</el-button>
    <el-button @click="test2()">Test2 - Veyeni</el-button>
</template>
<script>
export default {
    data() {
        return {

        };
    },
    created() {
        this.$subutai.http.onStart = () =>{
            console.log("up");
        };

        this.$subutai.http.onStop = () =>{
            console.log("down");
        };
    },
    methods:{
        test2() {
            var inf = this.$subutai.cookie.get();
            console.log(inf);
        },
        test() {
            this.$subutai.http.request("/jres/deneme",{ a:123 }).then(response=>{
                console.log(response);
            }).catch(error=>{
                console.log(error.message);
            });
        }
    }
}
</script>
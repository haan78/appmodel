<template>
  <div>
    <el-button type="success">This is login</el-button>
    <upload @request="request"><span>Tikla</span></upload>
  </div>
  
</template>

<script>
import 'element-plus/lib/theme-chalk/index.css'
import upload from './lib/Upload.vue';
export default {
  components:{upload},
  name: 'Login',
  methods:{
    request(req) {
      req.send("/upload",{"name":"Selam"},response=>{console.log(response)});
    }
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>

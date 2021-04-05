<template>
  <div>
    <el-button type="success">This is login</el-button>
    <upload @request="request" title="Lütfen bir dosya seçiniz"><i class="el-icon-upload"></i>Yükle</upload>
  </div>
  
</template>

<script>
import 'element-plus/lib/theme-chalk/index.css'
import upload from './lib/UploadBtn.vue';
export default {
  components:{upload},
  name: 'Login',
  methods:{
    request(req) {
      req.send("/upload",{"name":"Selam","id":213},
      (response,type)=>{
        console.log( response )
        console.log(type);
      });
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

<template>
  <div>
    <el-button type="success" icon="el-icon-upload" @click="get()">This is login</el-button>
    <upload @request="request" :disabled="false" title="Lütfen bir dosya seçiniz"><i class="el-icon-upload"></i>Yükle</upload>
  </div>
  
</template>

<script>
import 'element-plus/lib/theme-chalk/index.css'
import upload from './lib/UploadBtn.vue';
export default {
  components:{upload},
  name: 'Login',
  methods:{
    get() {
      let self = this;
      self.$subutai.ajax("/server",null,response => {
        console.log(response.data);
      });
    },
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

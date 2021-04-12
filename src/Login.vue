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
  data() {
    return {
      list:[]
    }
  },
  created() {
    console.log(this.$subutai);
    this.$subutai.init();
    console.log(this.$subutai.metaData);
  },
  methods:{
    get() {
      let self = this;
      self.$subutai.ajax("/server",null,response => {
        console.log(response);
      });
    },
    request(req) {
      req.send("/mongo",{"name":"Selam","id":213},
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

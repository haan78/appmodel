<template>
  <div>
    <el-button type="success" icon="el-icon-check">Selam</el-button>
  </div>
  
</template>
<style>
@import "element-plus/theme-chalk/index.css";
</style>
<script>
//import 'element-plus/theme-chalk/index.css'
export default {
  name: 'Main',
  created() {
    console.log(document.cookie);
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

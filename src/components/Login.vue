<template>
  <el-form>
    <el-form-item label="User">
      <el-input v-model="model.user" suffix-icon="el-icon-user"></el-input>
    </el-form-item>
    <el-form-item label="Password">
      <el-input v-model="model.pass" show-password suffix-icon="el-icon-key">
      </el-input>
    </el-form-item>
    <el-form-item label="Validation">
      <el-input v-model="model.captcha" placeholder="Captcha">
        <template #append>
      <img src="captcha" class="img" />
    </template>
      </el-input>
    </el-form-item>
  </el-form>
</template>
<style lang="scss" scoped>
  .img {
    width: 60xp;
    height: 25px;
  }
</style>
<script>
export default {
  data() {
    return {
      rules:{

      },
      model:{
        captcha:null,
        user:null,
        pass:null
      }
    };
  }
}
</script>
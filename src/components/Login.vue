<template>
  {{ message }}
  <el-form :rules="rules" :model="model" ref="FORM" label-position="top" action="/login" method="POST">
    <el-form-item label="Email" prop="email">
      <el-input v-model="model.email" name="email" suffix-icon="el-icon-user"></el-input>
    </el-form-item>
    <el-form-item label="Password" prop="pass">
      <el-input v-model="model.pass" name="pass" show-password suffix-icon="el-icon-key">
      </el-input>
    </el-form-item>
    <el-form-item label="Captcha" prop="captcha">
      <el-input v-model="model.captcha" placeholder="Captcha" name="captcha">
        <template #append>
      <img src="captcha" class="img" />
    </template>
      </el-input>
    </el-form-item>
    <el-button type="primary" style="width:100%; margin-top: 1em;" @click="formEnter()" >Enter</el-button>
    <br/>
    <div class="lnk">
      <el-link href="/register" type="primary" >Register</el-link>&nbsp;/&nbsp;
      <el-link href="/forgot" type="primary" >Forgot Password?</el-link>
    </div>
  </el-form>
</template>
<style lang="scss" scoped>
  .img {
    width: 60xp;
    height: 27px;
    padding-top: 5px;
  }
  .lnk {
    text-align: center;
  }
</style>
<script>
export default {
  data() {
    return {
      message : null,
      rules:{
        user:[
          { required: true, message: 'Email is required', trigger: 'blur' },
          { type: 'email', message: 'Email validation has fail!'}
        ],
        pass:[
          { required: true, message: 'Password name is required', trigger: 'blur' },
          { min: 5, max: 12, message: 'Length should be 5 to 12', trigger: 'blur' }
        ],
        captcha:[
          { required: true, message: 'Please write the code on right here', trigger: 'blur' },
        ]
      },
      model:{
        captcha:null,
        email:null,
        pass:null
      }
    };
  },
  created() {
    this.message = (window["__DATA__"] ? window["__DATA__"].message : "");
  },
  methods:{
    formEnter() {
      let self = this;
      self.$refs.FORM.validate(valid=>{
        if ( valid ) {
          self.$refs.FORM.$el.submit();
        }
      })
    }
  }
}
</script>
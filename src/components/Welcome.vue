<template>
  <div class="container">
    <div class="main">
      <div class="header">
        <h2 class="logo">LOGO HERE</h2>
      </div>
      <div class="body">
        <el-card>
        <Login v-if="mode == 'login'"></Login>
        <Forgot v-if="mode == 'forgot'"></Forgot>
        <Register v-if="mode == 'register'"></Register>
      </el-card>
      </div>
      

      <div class="footer">
        <div class="links">
          <a
            href="javascript:;"
            v-show="mode != 'login'"
            @click="mode = 'login'"
            >Login</a
          >
          <a
            href="javascript:;"
            v-show="mode != 'forgot'"
            @click="mode = 'forgot'"
            >Forgot Password?</a
          >
          <a
            href="javascript:;"
            v-show="mode != 'register'"
            @click="mode = 'register'"
            >Register</a
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import "element-plus/lib/theme-chalk/index.css";
import Login from "./Login";
import Forgot from "./Forgot";
import Register from "./Register";
export default {
  components: { Login, Forgot, Register },
  data() {
    return {
      mode: "login",
    };
  },
  created() {
    this.$subutai.init();
  },
  methods: {
    get() {
      let self = this;
      self.$subutai.ajax("/ajax/server", null, (response) => {
        console.log(response);
      });
    },
    request(req) {
      req.send("/mongo", { name: "Selam", id: 213 }, (response, type) => {
        console.log(response);
        console.log(type);
      });
    },
  },
};
</script>

<style lang="scss">
html, body {
    height: 100%;
    background: cornflowerblue;
}
body {
    margin: 0;
}
#app {
  height: 70%;
}
.container {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;

  .main {
    max-width: 70%;
    min-width: 30%;
    .header {
      text-align: center;
      padding-bottom: 1em;
    }

    .footer {
      text-align: center;
      padding-top: 2em;

      a {
        color: #FFF;
        text-decoration: none;
        padding: 1.5em;
        &:hover {
          color: cyan;
        }        
      }
    }
  }
}
</style>

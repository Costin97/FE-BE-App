<template>
  <body>
    <div v-if="!loggedIn && !signUp" class="container">
      <p class="loginTitle">LogIn</p>
      <form>
        <input
          type="text"
          v-model="username"
          name="username"
          placeholder="Username"
        />
        <input
          type="password"
          name="password"
          placeholder="Password"
          style="margin-bottom: 30px"
          v-model="password"
        />
        <div v-if="isError">
          <p style="color: red; margin: 0; position: relative; bottom: 12px">
            Your password/username is invalid!
          </p>
        </div>
        <div class="buttonsContainer">
          <button @click.prevent="loginMethod" type="submit">Submit</button>
          <button @click="signupTrigger">Sign Up</button>
        </div>
      </form>
    </div>
    <div v-else-if="signUp" class="container">
      <p class="loginTitle">SignUp</p>
      <form>
        <input
          type="text"
          v-model="usernameS"
          name="username"
          placeholder="Username"
        />
        <input
          type="password"
          name="password"
          placeholder="Password"
          style="margin-bottom: 30px"
          v-model="passwordS"
        />

        <div class="buttonsContainer">
          <button @click.prevent="signup">Submit</button>
        </div>
      </form>
    </div>
  </body>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      loggedIn: false,
      signUp: false,
      username: "",
      password: "",
      usernameS: "",
      passwordS: "",
      isError: false,
    };
  },
  methods: {
    setup(t_value, e_time) {
      localStorage.setItem("token", t_value);
      localStorage.setItem("expirationTime", e_time);
      this.$store.commit("setToken", t_value);
      this.$store.commit("setTime", e_time);
      this.$router.push("loggedin");
    },
    signupTrigger() {
      this.signUp = !this.signUp;
    },
    signup() {
      this.signUp = !this.signUp;
      const data = {
        username: this.usernameS,
        password: this.passwordS,
      };

      axios
        .post("http://localhost/loginPage/public/user/signup", data)
        .then(function (res) {
          console.log(res.data);
        })
        .catch(function (e) {
          console.log(e);
        });
    },
    loginMethod() {
      const data = {
        username: this.username,
        password: this.password,
      };

      axios
        .post("http://localhost/loginPage/public/user/login", data)
        .then((response) => {
          if (response.data.token != -1) {
            let token = response.data.token;
            let exp_time = response.data.expirationTime;
            this.setup(token, exp_time);
          } else {
            this.isError = true;
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },
    logoutMethod() {
      this.loggedIn = !this.loggedIn;
    },
  },
};
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Bree+Serif&family=Dancing+Script&display=swap");
</style>

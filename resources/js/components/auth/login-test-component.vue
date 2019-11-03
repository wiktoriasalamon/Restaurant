<template>
  <v-card>
    <v-text-field
        placeholder="Login"
        v-model="credentials.email"
    ></v-text-field>
    <v-text-field
        placeholder="Password"
        v-model="credentials.password"
    ></v-text-field>
    <v-card-actions>
      <v-btn @click="login2">
        Loguj
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  export default {
    name: "login-test-component",
    data: function () {
      return {
        credentials: {
          email: '',
          password: ''
        }
      };
    },
    methods: {
      login2() {
        axios.post(route('api.login'), this.credentials).then((result) => {
          let token = result.data['access_token']
          localStorage.setItem('user-token', token)
          axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
          console.log(localStorage.getItem('user-token'))
          window.location.replace(route('homeTest'));
        }).catch((error) => {
          console.log('error');
          localStorage.removeItem('user-token')
        });
      },
      tryMethod() {
        axios.get(route('homeTest')).then(result => {
          console.log(result);
        }).catch(error => {
          console.log(error);
        });
      }
    }
  }
</script>

<style scoped>

</style>
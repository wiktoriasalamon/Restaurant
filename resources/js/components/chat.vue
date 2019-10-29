<template>
    <div class="input-group">
        <input id="btn-input" type="text" name="message" class="form-control input-sm"
               placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage">

        <span class="input-group-btn">
            <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                Send
            </button>
        <button class="btn btn-primary btn-sm"  @click="test">
                Test
            </button>
        </span>
    </div>
</template>

<script>
  export default {
    props: ['user'],

    data() {
      return {
        newMessage: ''
      }
    },

    methods: {
      sendMessage() {
        this.$emit('messagesent', {
          user: this.user,
          message: this.newMessage
        });

        this.newMessage = ''
      },
      test() {
        axios.get(route('table2.index'))
        // axios.get(route('api.table.index'))
        // axios.get(route('api.dish.index'))
        // axios.get(route('jwt.user'))
          .then(function (response) {
            console.log(response.data)
          }).catch(function (error) {
          console.log(error)
        })
      }
    }
  }
</script>

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
        let id = 2;
        // axios.get(route('api.dish.load',id))
        // axios.get(route('api.kitchen.fetchNoPrepareOrder'))
        //   .then(function (response) {
        //     console.log(response.data)
        //   }).catch(function (error) {
        //   console.log(error)
        // })
        axios.post(route('api.order.statusOrder'),{
          order_id: "5",
          status: "completed",
          name: "nowa2",
          size: "6",
          price: "10",
          category_id: "200",
        }).then(
          response => {
            Vue.toasted.success(response.data.message).goAway(5000);
            // setTimeout(function(){window.location.href=route('home')} , 5000);
          },
          error => {
            if(error.response.status === 422){
              Vue.toasted.error("Podano niepoprawne dane, spróbuj jeszcze raz").goAway(3000);
            }else{
              Vue.toasted.error(error.response.data).goAway(3000);
            }

          },
        );
      },
      addItemToNewOrder(dishId, ammount) {
        let id = 2;
        // axios.get(route('api.dish.load',id))
        //   .then(function (response) {
        //     console.log(response.data)
        //   }).catch(function (error) {
        //   console.log(error)
        // })
        axios.post(route('api.order.statusOrder'),{
          id: "7",
          order_id: "10",
          name: "nowa2",
          size: "6",
          price: "10",
          category_id: "200",
        }).then(
          response => {
            Vue.toasted.success(response.data.message).goAway(5000);
            // setTimeout(function(){window.location.href=route('home')} , 5000);
          },
          error => {
            if(error.response.status === 422){
              Vue.toasted.error("Podano niepoprawne dane, spróbuj jeszcze raz").goAway(3000);
            }else{
              Vue.toasted.error(error.response.data).goAway(3000);
            }

          },
        );
      }
    }
  }
</script>

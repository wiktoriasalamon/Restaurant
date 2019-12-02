<template>
    <div class="input-group">
        <input id="btn-input" type="text" name="message" class="form-control input-sm"
               placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage">

        <span class="input-group-btn">
            <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                Send
            </button>
        <button class="btn btn-primary btn-sm" @click="test">
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
        newMessage: '',
        orderArray: []

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
      //todo usunąć po testach
      test() {
        // axios.get(route('api.order.orderWithStatus','ordered'))
        // // axios.get(route('api.kitchen.fetchNoPrepareOrder'))
        //   .then(function (response) {
        //     console.log(response.data)
        //   }).catch(function (error) {
        //   console.log(error)
        // })
        this.addItemToNewOrder(1,2);
        this.addItemToNewOrder(2,2);
        this.addItemToNewOrder(3,2);
        // this.completeOrder(1);
        this.completeOrderOnline();

      },
      completeOrder(tableId) {
        axios.post(route('api.order.storeNewOrderFromWorker'), {
          table_id: tableId,
          items: this.orderArray,
        }).then(
          response => {
            Vue.toasted.success(response.data.message).goAway(5000);
            // setTimeout(function(){window.location.href=route('home')} , 5000);
            this.orderArray = [];
          },
          error => {
            if (error.response.status === 422) {
              Vue.toasted.error("Podano niepoprawne dane, spróbuj jeszcze raz").goAway(3000);
            } else {
              Vue.toasted.error(error.response.data).goAway(3000);
            }
          },
        );
      },
      completeOrderOnline() {
        axios.post(route('api.order.storeNewOrderOnline'), {
          takeaway: false,
          address: " tu jeson",
          items: this.orderArray,
        }).then(
          response => {
            Vue.toasted.success(response.data.message).goAway(5000);
            // setTimeout(function(){window.location.href=route('home')} , 5000);
            this.orderArray = [];
          },
          error => {
            if (error.response.status === 422) {
              Vue.toasted.error("Podano niepoprawne dane, spróbuj jeszcze raz").goAway(3000);
            } else {
              Vue.toasted.error(error.response.data).goAway(3000);
            }
          },
        );
      },
      addItemToNewOrder(dishId, amount) {
        this.orderArray.push({"dishId": dishId, "amount": amount})
      },
    }
  }
</script>

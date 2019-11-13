<template>
  <v-card>
    <v-data-table></v-data-table>
  </v-card>
</template>

<script>
import { log } from "util";
export default {
  name: "customer-my-orders",
  data() {
    return {
      myOrders: [],
      headers: [
        { text: "Nazwa", value: "name" },
        { text: "Cena", value: "price" }
      ]
    };
  },
  beforeMount() {
    axios
      .get(route("api.order.myOrder"))
      .then(response => {
        const entries = Object.entries(response.data);
        if (response.data) {
          for (let [key, value] of entries) {
            this.myOrders[key] = value;
            console.log(key);
            console.log(value);
          }
        }
      })
      .catch(error => {
        console.error(error);
      });
  },
  methods: {}
};
</script>

<style scoped>
</style>
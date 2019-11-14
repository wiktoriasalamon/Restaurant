<template>
	<v-row class="justify-space-between">
		<v-col>
			<v-simple-table>
				<template v-slot:default>
					<thead>
					<tr>
						<th class="text-left">Statusy zamówień</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td @click="getMyOrders()">Moje zamówienia</td>
					</tr>
					<tr :key="item.id" v-for="item in orderSatuses">
						<td @click="getOrders(item)">{{ item }}</td>
					</tr>
					</tbody>
				</template>
			</v-simple-table>
		</v-col>
		<v-col>
			<v-data-table
				:headers="headers"
				:items="orders"
				:items-per-page="-1"
				class="elevation-1"
			>
				<template slot="item" slot-scope="props">
					<tr>
						<td class="text-xs-left">{{ props.item.table_id }}</td>
						<td class="text-xs-left">{{ props.item.status}}</td>
						<td class="text-xs-left">{{ props.item.worker_id}}</td>
						<td class="text-xs-left">{{ props.item.takeaway}}</td>
						<td class="text-xs-left">
							<v-icon @click="editItem(props.item.token)" small>
								edit
							</v-icon>
							<v-icon @click="showItem(props.item.token)" small>
								visibility
							</v-icon>
							<v-icon @click="deleteItem(props.item.token)" small>
								delete
							</v-icon>
						</td>
					</tr>
				</template>
			</v-data-table>
		</v-col>
	</v-row>
</template>

<script>
  import {notification} from '../../Notifications.js';
  export default {
    name: "worker-order-index",
    data() {
      return {
        menuItems: [],
        headers: [
          {text: 'Numer stolika', value: 'table_id',},
          {text: 'Status', value: 'status'},
          {text: 'Kelner', value: 'status'},
          {text: 'Zamówienie na wynos', value: 'takeaway'},
          {text: 'Akcje', value: ''},
        ],
        orderSatuses: [],
        orders: [],
      }
    },
    beforeMount() {
      this.getOrderStatuses()
    },
    methods: {
      getOrderStatuses() {
        axios.get(route('api.order.fetchOrderStatusTypes')).then(response => {
          this.orderSatuses = response.data
        }).catch(error => {
          console.error(error)
        });
      },
      getOrders(statusName) {
        axios.get(route('api.order.orderWithStatus', statusName)).then(response => {
          this.orders = response.data;
					console.log(response.data)
        }).catch(error => {
          console.error(error)
        });
      },
      getMyOrders(){
        axios.get(route('api.order.myOrder')).then(response => {
          this.orders = response.data
        }).catch(error => {
          console.error(error)
        });
			},
      showItem(token) {
        window.location.href = route('order.show', [token])
      },
      editItem(token) {
				window.location.href = route('order.editWaiter', [token])
      },
      deleteItem(orderToken) {
          axios.delete(route('api.order.delete', {
            token: orderToken
				}))
            .then(response => {
              notification(response.data, 'success');
            }).catch(error => {
            notification("Wystąpił błąd podczas usuwania zamowienia", 'error');
            console.error(error.response);
          });
        },
    }
  }
</script>

<style scoped>

</style>
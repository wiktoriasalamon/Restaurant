<template>
	<v-row>
		<v-col>
			<v-data-table
				:headers="headers"
				:items="menuItems"
				:items-per-page="-1"
				class="elevation-1"
			>
				<template slot="item" slot-scope="props">
					<tr>
						<td class="text-xs-left">{{ props.item.name }}</td>
						<td class="text-xs-left">{{ props.item.price}}</td>
						<td class="text-xs-center">
							<v-btn @click="addToOrder(props.item)">Dodaj do zamówienia</v-btn>
						</td>
					</tr>
				</template>
			</v-data-table>
		</v-col>
		<v-col>
			<v-card>
				<v-card-title>
					Edycja zamówienia
				</v-card-title>
				<v-card-text>
					<h6>Status zamówienia: </h6>

					<v-row>
						<v-select
							:items="statusItems"
							label="Status"
							v-model="orderStatus"
						></v-select>
						<v-btn @click="changeStatus">
							Zmień status
						</v-btn>
					</v-row>
					<v-data-table
						:headers="orderedItemsHeaders"
						:items="orderedItems"
						:items-per-page="-1"
						class="elevation-1"
					>
						<template slot="item" slot-scope="props">
							<tr>
								<td class="text-xs-left">{{ props.item.name }}</td>
								<td class="text-xs-left">{{ props.item.price}}</td>
								<td class="text-xs-left">
									<v-text-field
										v-model="props.item.amount">
									</v-text-field>
								</td>
								<td class="text-xs-center">
									<v-icon @click="deleteItem(props.item.id)">
										delete
									</v-icon>
								</td>
							</tr>
						</template>
					</v-data-table>
					<h5>Suma zamówienia: {{orderSum}}</h5>
				</v-card-text>
				<v-card-actions>
					<v-btn @click="updateOrder">
						Aktualizuj zamówienie
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-col>
	</v-row>
</template>

<script>
  export default {
    name: "worker-order-edit",
    props:['token'],
    data() {
      return {
        menuItems:[],
        headers: [
          { text: 'Nazwa', value: 'name',},
          { text: 'Cena', value: 'price' },
          { text: 'Akcje', value: '' },
        ],
        orderedItemsHeaders:[
          { text: 'Nazwa', value: 'name',},
          { text: 'Cena', value: 'price' },
          { text: 'Ilość', value: '' },
          { text: 'Usuń', value: '' },
        ],
        orderedItems:[],
				statusItems: [],
				orderStatus:'',
				orderSum: ''
      }
    },
    beforeMount(){
      this.getMenuData();
			this.getOrder();
			this.getStatusItems()
    },
    methods: {
      getMenuData(){
        axios.get(route('api.dish.index'))
          .then(response => {
            this.menuItems = response.data;
            console.log(this.menuItems);
            this.menuItems.forEach(item=>{
              item.amount = 0
            })
          }).catch(error => {
          console.error(error)
        })
      },
			getOrder(){
        axios.get(route('api.order.loadOrder', this.token))
          .then(response => {
            this.orderedItems = response.data.dishes;
						this.orderSum = response.data.sum;
            console.log(response)
          }).catch(error => {
          console.error(error)
        })
			},
			getStatusItems(){
        axios.get(route('api.order.fetchOrderStatusTypes'))
          .then(response => {
            this.statusItems = response.data
          }).catch(error => {
          console.error(error)
        })
			},
      addToOrder(dish){
        this.orderedItems.push(dish);
        for(let i=0 ;  i < this.menuItems.length; i++) {
          if(this.menuItems[i].id === dish.id){
            this.menuItems.splice(i, 1);
          }
        }
      },
      deleteItem(id){
        for(let i=0 ;  i < this.orderedItems.length; i++) {
          if(this.orderedItems[i].id === id){
            this.orderedItems[i].amount = 0;
            this.menuItems.push(this.orderedItems[i]);
            this.orderedItems.splice(i, 1);
          }
        }
      },
      updateOrder(){
        let orderArray=[];
        this.orderedItems.forEach(item=>{
          orderArray.push({amount: item.amount, dishId: item.id});
        });
        axios.post(route('api.order.storeNewOrderFromWorker'), {
          table_id: this.tableid,
          items: orderArray,
        }).then(
          response => {
            Vue.toasted.success(response.data).goAway(5000);
            setTimeout(function(){window.location.href=route('order.index')} , 5000);
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
			changeStatus(){
        axios.post(route('api.order.changeStatusOrder'), {
          order_id: this.id,
          status: this.orderStatus,
        }).then(
          response => {
            Vue.toasted.success(response.data).goAway(5000);
          },
          error => {
            if (error.response.status === 422) {
              Vue.toasted.error("Podano niepoprawne dane, spróbuj jeszcze raz").goAway(3000);
            } else {
              Vue.toasted.error(error.response.data).goAway(3000);
            }
          },
        );
			}
    }
  }
</script>

<style scoped>

</style>
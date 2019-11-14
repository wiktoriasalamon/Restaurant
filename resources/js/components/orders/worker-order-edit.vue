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
					<h2>Edycja zamówienia</h2>
				</v-card-title>
				<v-card-text>
					<h5 >Status zamówienia: </h5>

					<v-row class="mx-1" style="margin-bottom: 2rem; ">
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
										@input="changeTotalSum"
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
					<h5 style="margin-top: 2rem;">Suma zamówienia:</h5>
					<v-text-field
						readonly
						v-model="orderSum"
						style="max-width: 5rem">

					</v-text-field>
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
  import {notification} from '../../Notifications.js';

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
            this.menuItems.forEach(item=>{
              item.amount = 0
            });
						console.log(this.menuItems)
          }).catch(error => {
          console.error(error)
        })
      },
			getOrder(){
        axios.get(route('api.order.loadOrder', this.token))
          .then(response => {
            this.orderedItems = response.data.dishes;
						this.orderSum = response.data.sum;
						this.orderStatus = response.data.status;
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
        let add = true;
        for(let i=0; i< this.orderedItems.length; i++){
          if(this.orderedItems[i].id === dish.id){
            this.orderedItems[i].amount++;
            this.changeTotalSum()
						add=false;
						break
					}
				}
        if(add === true){
					dish.amount = 1
          this.orderedItems.push(dish);
          this.changeTotalSum()
				}
        for(let i=0 ;  i < this.menuItems.length; i++) {
          if(this.menuItems[i].id === dish.id){
            this.menuItems.splice(i, 1);
          }
        }
      },
      changeTotalSum(){
        this.orderSum = 0
        for(let i=0; i< this.orderedItems.length; i++){
         		this.orderSum += this.orderedItems[i].amount * this.orderedItems[i].price
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
        this.changeTotalSum()
      },
      updateOrder(){
        let orderArray=[];
        this.orderedItems.forEach(item=>{
          orderArray.push({amount: item.amount, dishId: item.id});
        });
        console.log(this.token);
        axios.post(route('api.order.updateOrderFromWorker'), {
          token: this.token,
          items: orderArray,
        }).then(
          response => {
            notification(response.data, 'success');
            setTimeout(function(){window.location.href=route('order.index')} , 5000);
          },
          error => {
            if (error.response.status === 422) {
              notification("Podano niepoprawne dane, spróbuj jeszcze raz", 'error');
            } else {
              notification(error.response.data, 'error');
            }
          },
        );
      },
			changeStatus(){
        axios.post(route('api.order.changeStatusOrder'), {
          token: this.token,
          status: this.orderStatus,
        }).then(
          response => {
            notification(response.data, 'success');
          },
          error => {
            if (error.response.status === 422) {
              notification("Podano niepoprawne dane, spróbuj jeszcze raz", 'error');
            } else {
              notification(error.response.data, 'error');
            }
          },
        );
			}
    }
  }
</script>

<style scoped>

</style>
<template>
  <v-row class="justify-space-between">
    <v-col>
      <v-simple-table>
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-left">Kategorie</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td @click="setMenuItems(-1)">Wszystkie</td>
            </tr>
            <tr v-for="item in categoryItems" :key="item.id">
              <td @click="setMenuItems(item.id)">{{ item.name }}</td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
    </v-col>
    <v-col>
		<v-card-title>
        	<v-text-field v-model="search" append-icon="search" label="Szukaj" single-line hide-details></v-text-field>
      	</v-card-title>
     	<v-data-table 
		 	v-model="selected"
			:headers="headers"
			:items="menuItems"
			:items-per-page="-1"
			:search="search"
			show-select
			class="elevation-1">
      </v-data-table>

    </v-col>
	 <v-col>
     	<v-data-table 
			:headers="orderedHeaders"
			:items="ordered"
			:items-per-page="-1"
			class="elevation-1">
			<template v-slot:item.changeAmount="{ item }">
				<v-icon @click="minusItem(item)">indeterminate_check_box</v-icon>
				<v-icon @click="plusItem(item)">add_box</v-icon>
			</template>
			 <template v-slot:item.delete="{ item }">
				<v-icon
					
					@click="deleteItem(item)"
					>
					delete
				</v-icon>
			</template>
      </v-data-table>

    </v-col>
  </v-row>
</template>

<script>
import { log } from 'util';
export default {
  name: "customer-order",
  props: ["dishes", "categories"],
  data() {
    return {
      search: "",
	  selected: [],
	  ordered: [],
      menuItems: [],
      headers: [
        { text: "Nazwa", value: "name"},
		{ text: "Cena", value: "price" },
	  ],
	  orderedHeaders: [
		{ text: "Nazwa", value: "name"},
		{ text: "Cena", value: "price" },
		{ text: "Ilość", value: "amount"},
		{ text: "Zmień ilość", value: "changeAmount"},
		{ text: "Usuń", value: "delete"}
	  ],
      categoryItems: [],
	  allMenuItems: [],
};
  },
  beforeMount() {
    this.menuItems = this.dishes;
    this.allMenuItems = this.dishes;
    this.categoryItems = this.categories;
  },

  watch: {
	  selected: function(list) {
		this.ordered = [];
		list.forEach(item => {
			var newItem = {
				id: item.id,
				name: item.name,
				price: item.price,
				amount: 1,
			}
            this.ordered.push(newItem);
        });
	  }
  },
  methods: {
    setMenuItems(id) {
      if (id === -1) {
        this.menuItems = this.allMenuItems;
      } else {
        this.menuItems = [];
        this.allMenuItems.forEach(item => {
          if (item.category_id === id) {
            this.menuItems.push(item);
          }
        });
      }
      return this.menuItems;
	},
	deleteItem(deletedItem) {
		this.selected.forEach(item => {
          if (item.id === deletedItem.id) {
            this.selected = this.arrayRemove(this.selected, item);
          }
        });
	},
	minusItem(item) {
		if(item.amount>1) {
			item.amount = item.amount-1;
		}
	},
	plusItem(item) {
		if(item.amount<15) {
			item.amount = item.amount-1;
		}
	},
	arrayRemove(arr, value) {
		return arr.filter(function(ele){
       return ele != value;
   	});
	},
  }
};
</script>

<style scoped>
</style>
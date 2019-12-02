<template>
	<v-row class="justify-space-around">
<!--		<v-col cols="12" sm="7" md="5" lg="4" xl="3">-->
<!--			<v-simple-table>-->
<!--				<template v-slot:default>-->
<!--					<thead>-->
<!--					<tr>-->
<!--						<th class="text-left">Kategorie</th>-->
<!--					</tr>-->
<!--					</thead>-->
<!--					<tbody>-->
<!--					<tr>-->
<!--						<td @click="setMenuItems(-1)">Wszystkie</td>-->
<!--					</tr>-->
<!--					<tr v-for="item in categoryItems" :key="item.id">-->
<!--						<td @click="setMenuItems(item.id)">{{ item.name }}</td>-->
<!--					</tr>-->
<!--					</tbody>-->
<!--				</template>-->
<!--			</v-simple-table>-->
<!--		</v-col>-->
		<v-col cols="12" sm="7" md="6" lg="6" xl="5">
			<v-select
					class="beige_select"
					:items="categoryItems"
					item-value="id"
					item-text="name"
					label="Kategoria"
					v-model="categoryPicked"
					v-on:change="setMenuItems()"
			></v-select>
			<v-data-table
				:headers="headers"
				:items="menuItems"
				:items-per-page="5"

			>
				<template slot="item" slot-scope="props">
					<tr>
						<td class="text-xs-left">{{ props.item.name }}</td>
						<td class="text-xs-left">{{ props.item.price}}</td>
					</tr>
				</template>
			</v-data-table>
		</v-col>
	</v-row>
</template>

<script>
  export default {
    name: "user-menu",
    props: ['dishes', 'categories'],
    data() {
      return {
        menuItems: [],
        headers: [
          {text: 'Nazwa', value: 'name',},
          {text: 'Cena (zł)', value: 'price'},
        ],
        categoryItems: [],
        allMenuItems: [],
		categoryPicked: -1,
      }
    },
    beforeMount() {
      this.menuItems = this.dishes;
      this.allMenuItems = this.dishes;
      this.categoryItems = [{'id': -1, 'name': 'Wszystkie'}].concat(this.categories)
    },
    methods: {
      setMenuItems() {
      	let id = this.categoryPicked;
        if (id === -1) {
          this.menuItems = this.allMenuItems
        } else {
          this.menuItems = [];
          this.allMenuItems.forEach(item => {
            if (item.category_id === id) {
              this.menuItems.push(item)
            }
          })
        }
        return this.menuItems
      }
    }

  }
</script>

<style >
	.v-data-table__wrapper{
		border-radius: inherit;
	}
	.v-data-table.theme--light{
		background: rgba(227, 203, 177, 0.85) !important;
		border-radius: 20px;
	}
	.v-data-table.elevation-1.theme--light{
		background: rgba(227, 203, 177, 0.85) !important;
		border-radius: 20px;
	}


</style>
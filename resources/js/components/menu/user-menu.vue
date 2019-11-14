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
          {text: 'Cena', value: 'price'},
        ],
        categoryItems: [],
        allMenuItems: [],
      }
    },
    beforeMount() {
      this.menuItems = this.dishes
      this.allMenuItems = this.dishes
      this.categoryItems = this.categories
    },
    methods: {
      setMenuItems(id) {
        if (id === -1) {
          this.menuItems = this.allMenuItems
        } else {
          this.menuItems = []
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

<style scoped>

</style>
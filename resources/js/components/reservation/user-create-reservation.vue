<template>
	<v-row class="justify-center align-center">
		<v-card>
			<v-card-title>
				Formularz rezerwacji
			</v-card-title>
			<v-card-text>
				<v-select
					:items="personSelectItems"
					item-text="text"
					item-value="value"
					label="Wybierz ilość osób"
					outlined
				></v-select>
			</v-card-text>
			<v-card-actions>
				<v-btn>Zarejestruj</v-btn>
			</v-card-actions>
		</v-card>
	</v-row>
</template>

<script>
  export default {
    name: "user-create-reservation",
    data() {
      return {
        personSelectItems: [
					{value: 1, text: 1},
					{value: 2, text: 2},
					{value: 3, text: 3},
					{value: 4, text: 4},
					{value: 5, text: 5},
					{value: 6, text: 6},
        ],
      }
    },
  }
</script>

<style scoped>

</style>
<template>
	<v-row no-gutters class="header">
		<v-col
			cols="12"
		>
			<v-card
				flat
			>
				<v-card-text class="header-text">
					<v-row no-gutters>
						<v-col cols="4"
									 >
							<v-card class="logo">
								<v-card-title>
									W-17
								</v-card-title>
							</v-card>
						</v-col>
						<v-col cols="8"
									>
							<v-row>
								<v-col>
									<v-btn text @click="register">Zarejestruj</v-btn>
								</v-col>
							</v-row>
							<v-row>
								<v-col></v-col>
							</v-row>

						</v-col>
					</v-row>
					<v-row>

					</v-row>
				</v-card-text>
			</v-card>
		</v-col>
	</v-row>
</template>

<script>
  export default {
    name: "ui-header",
		methods:{
      register(){

			}
		}
  }
</script>

<style scoped>
	.v-card__text.header-text{
		color: white;
		background-color: #8A5E4E;
	}
	.v-card.v-card--flat.v-sheet.theme--light{
		border-radius: 0;
	}
	.header{
		max-height: 8rem;
	}
</style>
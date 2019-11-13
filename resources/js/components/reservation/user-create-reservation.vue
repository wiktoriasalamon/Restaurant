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
					v-model="tableSize"
				></v-select>
				<v-menu
					ref="menu"
					v-model="menu"
					:close-on-content-click="false"
					:return-value.sync="date"
					transition="scale-transition"
					offset-y
					min-width="290px"
				>
					<template v-slot:activator="{ on }">
						<v-text-field
							v-model="date"
							label="Wybierz datę"
							append-icon="event"
							readonly
							v-on="on"
						></v-text-field>
					</template>
					<v-date-picker v-model="date" scrollable :min="setMinDate()">
						<v-spacer></v-spacer>
						<v-btn text color="primary" @click="menu = false">Cancel</v-btn>
						<v-btn text color="primary" @click="$refs.menu.save(date)">OK</v-btn>
					</v-date-picker>
				</v-menu>
					<v-menu
						ref="menu2"
						v-model="menu2"
						:close-on-content-click="false"
						:nudge-right="40"
						:return-value.sync="time"
						transition="scale-transition"
						offset-y
						max-width="290px"
						min-width="290px"
					>
						<template v-slot:activator="{ on }">
							<v-text-field
								v-model="time"
								label="Wybierz godzinę"
								append-icon="access_time"
								readonly
								v-on="on"
							></v-text-field>
						</template>
						<v-time-picker
							v-if="menu2"
							v-model="time"
							full-width
							@click:minute="$refs.menu2.save(time)"
							:allowed-hours="allowedHours"
							:allowed-minutes="allowedStep"
							class="mt-4"
							format="24hr"
							scrollable
							:min="setMinTime()"
							max="23:00"
						></v-time-picker>
					</v-menu>
			</v-card-text>
			<v-card-actions>
				<v-btn @click="send">Zarezerwuj</v-btn>
			</v-card-actions>
		</v-card>
	</v-row>
</template>

<script>
  export default {
    name: "user-create-reservation",
		props: ['user'],
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
        time: null,
        menu2: false,
				menu: false,
				date: null,
				minDate: new Date().toISOString().substr(0, 10),
				email: '',
				phone: '',
				tableSize:null
      }
    },
		beforeMount(){
			this.email = this.user.email;
			this.phone = this.user.phone
		},
    methods: {
      allowedStep: m => m % 15 === 0,
      allowedHours: v => v >= 9 && v <=23,
			setMinTime(){
        var d = new Date();
        var h = d.getHours();
        var m = d.getMinutes();
        h+=1;
				if(h+1 <= 23 && d.toISOString().substr(0, 10) === this.date){
          if(m > 0 && m<15){
            return h+":15"
          }else if(m>=15 && m<30){
            return h+":30"
          }else if(m>=30 &&  m<45){
            return h+":45"
          }else{
            h+=1;
            return h+":00"
          }
				}
				else{
				  return "9:00"
				}
			},
			setMinDate(){
        var d = new Date();
        var h = d.getHours();
        var m = d.getMinutes();
        if(h>23 || (h>22 && m > 45)){
          return (d.getDate()+1).toISOString().substr(0, 10)
				}else{
          return this.minDate
				}
			},
			send(){
        if(this.date === null || this.time === null || this.tableSize === null){
          Vue.toasted.error("Musisz wypełnić wszystkie dane!!!").goAway(7000);
				}else{
          axios.post(route('api.reservation.storeAsCustomer'),{
							date: this.date,
							startTime: this.time,
							email: this.email,
							phone: this.phone,
							tableSize: this.tableSize,
					}).then(
            response => {
              Vue.toasted.success(response.data.message).goAway(5000);
              setTimeout(function(){window.location.href=route('home')} , 5000);
            },
            error => {
              if(error.response.status === 422){
                Vue.toasted.error("Podano niepoprawne dane, spróbuj jeszcze raz").goAway(3000);
							}else{
                Vue.toasted.error(error.response.data).goAway(3000);
							}

            },
          );
				}
			}
    },
  }
</script>

<style scoped>

</style>
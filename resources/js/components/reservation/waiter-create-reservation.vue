<template>
	<div>
		<v-row class="justify-center">
			<v-col cols="12" sm="10" md="8" lg="5" xl="4">
				<v-card class="transparent_form">
					<v-card-text>
						<h4>W celu znalezienia wolnych stolików do  rezerwacji wybierz datę:</h4>
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
									outlined
									v-on="on"
								></v-text-field>
							</template>
							<v-date-picker v-model="date" scrollable first-day-of-week="1" locale="pl" :min="minDate">
								<v-spacer></v-spacer>
								<v-btn text color="primary" @click="menu = false">Anuluj</v-btn>
								<v-btn text color="primary" @click="$refs.menu.save(date)">Wybierz</v-btn>
							</v-date-picker>
						</v-menu>
						<v-row class="justify-center">
							<v-btn @click="getAvailableTables(date)" v-bind:loading="searchLoading" :disabled="date == null" class="yellow_form_button" color="secondary">
								Wyszukaj
							</v-btn>
						</v-row>
					</v-card-text>
				</v-card>
			</v-col>
		</v-row>
		<v-row class="justify-space-around">
			<v-col cols="12" sm="10" md="8" lg="5" xl="4">
				<v-row>
					<v-col>
						<v-card class="transparent_form">
							<v-card-title>
								Dostępne stoliki
							</v-card-title>
							<v-card-text>
								<v-data-table
									:headers="headers"
									:items="availableTables"
									:items-per-page="5"
								>
									<template slot="item" slot-scope="props">
										<tr>
											<td class="text-xs-left">{{ props.item.id }}</td>
											<td class="text-xs-left">{{ props.item.size}}</td>
											<td class="text-xs-center">
												<v-btn @click="makeReservation(props.item.id)" >
													Zarezerwuj stolik
												</v-btn>
											</td>
										</tr>
									</template>
								</v-data-table>
							</v-card-text>
						</v-card>
					</v-col>

				</v-row>

			</v-col>
			<v-col cols="12" sm="12" md="12" lg="10" xl="7">
				<v-row>
					<v-col cols="12" sm="12" md="12" lg="7" xl="6">
						<v-card class="transparent_form">
							<v-card-title>
								Wybrane stoliki
							</v-card-title>
							<v-card-text>
								<v-data-table
									:headers="headers"
									:items="choosenTables"
									:items-per-page="5"
								>
									<template slot="item" slot-scope="props">
										<tr>
											<td class="text-xs-left">{{ props.item.id }}</td>
											<td class="text-xs-left">{{ props.item.size}}</td>
											<td class="text-xs-center">
												<v-btn @click="cancelReservationTable(props.item.id)">
													Anuluj
												</v-btn>
											</td>
										</tr>
									</template>
								</v-data-table>
							</v-card-text>
						</v-card>
					</v-col>
					<v-col cols="12" sm="12" md="12" lg="5" xl="4">
						<v-card class="transparent_form">
						<v-card-title>
							Dane do rezerwacji
						</v-card-title>
						<v-card-text>
							<v-form>
								<v-text-field
									:rules="[rules.required, rules.emailRules]"
									label="E-mail"
									outlined
									v-model="form.email">

								</v-text-field>
								<v-text-field
									:rules="[rules.required, rules.phoneMax12]"
									label="Numer telefonu"
									outlined
									v-model="form.phone">
								</v-text-field>
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
											outlined
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
								<v-row class="justify-center">
									<v-btn @click="saveReservation" v-bind:loading="reservationLoading" class="yellow_form_button" color="secondary">
										Zarezerwuj
									</v-btn>
								</v-row>

							</v-form>
						</v-card-text>
					</v-card>
					</v-col>
				</v-row>
			</v-col>
		</v-row>
	</div>
</template>

<script>
  export default {
    name: "waiter-create-reservation",
    data() {
      return {
        menu: false,
        date: null,
        minDate: new Date().toISOString().substr(0, 10),
        availableTables: [],
        headers: [
          { text: 'Numer stolika', value: 'id',},
          { text: 'Rozmiar', value: 'size' },
          { text: 'Akcje' },
        ],
				tablesToReservation: [],
        choosenTables: [],
        time: null,
        menu2: false,
        rules: {
          required: value => !!value || "To pole jest wymagane",
          emailRules: v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'Niepoprawny adres email',
          phoneMax12: value => value.length <= 12 || 'Numer telefonu powinien mieć mniej niż 13 znaków',
        },
				form:{
          email: '',
					phone: ''
				},
				tablesId:[],
				searchLoading: false,
				reservationLoading: false
      }
    },
    methods:{
      allowedStep: m => m % 15 === 0,
      allowedHours: v => v >= 9 && v <=23,
      setMinTime(){
        var d = new Date();
        var h = d.getHours();
        var m = d.getMinutes();
        if(h<22 && d.toISOString().substr(0, 10) === this.date){
          return h+":"+m
				}
      },
      getAvailableTables(date){
        this.searchLoading = true
        axios.get(route('api.reservation.fetchTablesByDate', date))
          .then(response => {
						this.availableTables = response.data.tables
          }).catch(error => {
          console.error(error)
        }).finally(()=>{
          this.searchLoading = false
				})
      },
      makeReservation(id){
				for(let i=0; i< this.availableTables.length ; i++){
				  if(this.availableTables[i].id === id){
						this.choosenTables.push(this.availableTables[i]);
						this.tablesId.push(this.availableTables[i].id);
						this.availableTables.splice(i, 1);
					}
				}
      },
      cancelReservationTable(id){
        for(let i=0; i< this.choosenTables.length ; i++){
          if(this.choosenTables[i].id === id){
            this.availableTables.push(this.choosenTables[i]);
            this.choosenTables.splice(i, 1);
            this.tablesId.splice(i, 1);

          }
        }
      },
			saveReservation(){
        if(this.choosenTables === [] || this.time === null){
          Vue.toasted.error("Musisz wypełnić wszystkie dane!!!").goAway(7000);
        }else{
          this.reservationLoading = true
          axios.post(route('api.reservation.storeAsWorker'),{
            date: this.date,
            startTime: this.time,
            email: this.form.email,
            phone: this.form.phone,
            tables: this.tablesId,
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
          ).finally(()=>{
            this.reservationLoading = false
					})
        }
      }
    }
  }
</script>

<style scoped>

</style>
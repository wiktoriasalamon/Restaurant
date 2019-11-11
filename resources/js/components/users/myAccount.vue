<template>
	<v-row class="justify-space-between">
		<v-col lg="6" md="8" xl="5">
			<v-card>
				<v-card-title>
					Moje konto
				</v-card-title>
				<v-card-text>
					<v-form>
						<v-text-field
							:rules="[rules.required]"
							label="Imię"
							v-model="form.name">
						</v-text-field>
						<v-text-field
							:rules="[rules.required]"
							label="Nazwisko"
							v-model="form.surname">>
						</v-text-field>
						<v-text-field
							:rules="[rules.required, rules.emailRules]"
							label="E-mail"
							v-model="form.email">

						</v-text-field>
						<v-text-field
							:rules="[rules.required, rules.phoneMax12]"
							label="Numer telefonu"
							v-model="form.phone">

						</v-text-field>
						<v-text-field
							:rules="[rules.required]"
							label="Ulica"
							v-model="form.address.street">
						</v-text-field>
						<v-text-field
							label="Numer domu "
							v-model="form.address.houseNumber">
						</v-text-field>
						<v-text-field
							label="Numer mieszkania"
							v-model="form.address.apartmentNumber">
						</v-text-field>
						<v-text-field
							:rules="[rules.required]"
							label="Miejscowość"
							v-model="form.address.city">
						</v-text-field>
						<v-text-field
							:rules="[rules.required, rules.postCodeFormat]"
							label="Kod pocztowy"
							v-model="form.address.postCode">

						</v-text-field>
						<v-btn @click="save">
							Zapisz
						</v-btn>
					</v-form>
				</v-card-text>
			</v-card>
		</v-col>
		<v-col>

		</v-col>
	</v-row>
</template>

<script>
  export default {
    name: "myAccount",
    data() {
      return {
        form: {
          id: '',
          name: '',
          surname: '',
          email: '',
					address:{
            street: '',
            houseNumber: '',
            apartmentNumber: '',
            postCode: '',
            city: '',
					},
          phone: '',
        },
        errors: {
          name: '',
          surname: '',
          email: '',
          address: '',
          phone: '',
        },
        rules: {
          required: value => !!value || "To pole jest wymagane",
          emailRules: v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'Niepoprawny adres email',
          phoneMax12: value => value.length <= 12 || 'Numer telefonu powinien mieć mniej niż 13 znaków',
          postCodeFormat: value => /^\d{2}-\d{3}$/.test(value) || 'Nieprawidłowy format kodu pocztowego'
        },
				userId: ''
      }
    },
    beforeMount(){
      this.getData()
    },
    methods: {
      getData(){
        axios.get(route('api.authenticatedUser'))
          .then(response => {
            const entries = Object.entries(response.data);
            console.log(response);
            if (response.data) {
              for (let [key, value] of entries) {
                	if(key === 'address'){
                	  let address = Object.entries(JSON.parse(value));
                	  for(let [addressKey, addressValue] of address){
                	    console.log(addressValue);
											console.log(addressKey);
                      this.form.address[addressKey] = addressValue
										}
									}
                  this.form[key] = value;
              }
            }
          }).catch(error => {
          console.error(error)
        })
      },
			save(){
        console.log(this.form.city);
				let formAddress = this.form.address;
        formAddress = JSON.stringify(formAddress);
        axios.put(route('api.user.update', this.form.id),{
          name: this.form.name,
          surname: this.form.surname,
          address: formAddress,
          phone: this.form.phone,
          email: this.form.email,
        }).then(
          response => {
            Vue.toasted.success(response.data.message).goAway(5000);
            setTimeout(function(){window.location.href=route('home')} , 5000);
          },
          error => {
            console.log(error.response);
            if (error.response.status === 422) {
              Vue.toasted.error("Podano niepoprawne dane, spróbuj jeszcze raz").goAway(3000);
            } else {
              Vue.toasted.error(error.response.data).goAway(3000);
            }
          })
      }
    }
  }
</script>

<style scoped>

</style>


<template>
	<div class="login-form">
		
		<v-text-field label="e-mail" outlined></v-text-field>
		<v-text-field label="hasło" outlined></v-text-field>
		<a class="a-forgot-password"> Nie pamiętam hasła </a>
		<div class="login-button">
			<v-btn large>Zaloguj się</v-btn>
		</div>
		<a class="a-register">Zarejestruj się</a>
	</div>
</template>

<script>
  export default {
    name: "loginPage"
  }
</script>

<style scoped>
	.login-form {
		display: flex;
		flex-direction: column;
		float: center;
		margin: 20vh 30vw;
		background: rgba(255,255,255,0.5);
		padding: 2vw 5vw;
	}
	.a-forgot-password {
		align-self: flex-end;
	}
	.login-button {
		margin: 2vw 3vw;
		display: flex;
		flex-direction: column;
		float: center;
	}
	.a-register {
		align-self: center
	}
</style>

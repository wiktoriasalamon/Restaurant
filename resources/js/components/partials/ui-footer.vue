<template>
	<v-footer class="footer">
		<v-spacer></v-spacer>
		<div>&copy; 2019RestaurApp</div>
		<v-spacer></v-spacer>
	</v-footer>
</template>

<script>
  export default {
    name: "ui-footer"
  }
</script>

<style scoped>
	.footer{
		position: fixed;
		bottom: 0;
		width: 100%;
		color: white;
		background-color: #8A5E4E;
	}

</style>
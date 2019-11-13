<template>
  <v-card>
    <v-card-title>Dodawanie dania</v-card-title>
    <v-card-text>
      <v-form
        ref="form">

      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-btn @click="cancel">Anuluj</v-btn>
      <v-btn @click="save">Zapisz</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  export default {
    name: "create-dish",
    data() {
      return {

      };
    },
    beforeMount() {

    },
    methods: {
      cancel() {
        window.location.replace(route('worker.index'));
      },
      save() {
        if (this.$refs.form.validate()) {
        }
      }
    }
  }
</script>

<style scoped>

</style>
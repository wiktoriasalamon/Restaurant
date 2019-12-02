<template>
  <v-row class="homepage_title text-center justify-center align-center">
    <v-col>
      <h1 class="header-class">Wydział smaków</h1>
    </v-col>
  </v-row>
</template>

<script>
  export default {
    name: "homepage"
  }
</script>

<style scoped>
  .header-class {
    font-size: 10rem
  }
  @media screen and (max-width: 800px) and (min-width: 500px) {
    .header-class {
      font-size: 7rem
    }
  }
  @media screen and (max-width: 500px) {
    .header-class {
      font-size: 5rem
    }
  }
</style>
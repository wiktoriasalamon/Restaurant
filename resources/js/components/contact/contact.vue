<template>
      <v-card max-width="375" class="mx-auto">
        <v-list two-line>
          <v-list-item>
            <v-list-item-icon>
              <v-icon color="indigo">restaurant</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>{{this.name}}</v-list-item-title>
              <v-list-item-subtitle></v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          <v-list-item>
            <v-list-item-icon>
              <v-icon color="indigo">mail</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>{{this.email}}</v-list-item-title>
              <v-list-item-subtitle></v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          <v-list-item>
            <v-list-item-icon>
              <v-icon color="indigo">phone</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>{{this.phone}}</v-list-item-title>
              <v-list-item-subtitle></v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          <v-list-item>
            <v-list-item-icon>
              <v-icon color="indigo">room</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>{{this.address}}</v-list-item-title>
              <v-list-item-subtitle></v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          <v-list-item>
            <v-list-item-icon>
              <v-icon color="indigo">access_time</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>{{this.time}}</v-list-item-title>
              <v-list-item-subtitle>Godziny otwarcia</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-card>
</template>

<script>
export default {
  name: "contact-page",
  data() {
    return {
      address: "ul. Sienkiewicza 18, Wrocław",
      email: "wydzialsmakow@gmail.com",
      name: "W17 Wydział Smaków",
      time: "12:00 - 23:00",
      phone: "+48 500 400 400",
    };
  },
};
</script>

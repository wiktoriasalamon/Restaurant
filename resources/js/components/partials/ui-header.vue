<template>
  <v-row no-gutters class="header">
    <v-col cols="12">
      <v-row no-gutters class="justify-space-between">
        <v-col cols="3">
          <v-card class="logo">
            <v-card-title>W-17</v-card-title>
          </v-card>
        </v-col>
        <v-col cols="9">
          <v-row>
            <v-col v-if="notLogged" class="text-end">
              <v-btn text @click="register">Zarejestruj</v-btn>
              <v-btn @click="login">Zaloguj się</v-btn>
            </v-col>
            <v-col v-else class="text-end">
              <v-menu offset-y>
                <template v-slot:activator="{ on }">
                  <v-btn
                    color="primary"
                    dark
                    v-on="on"
                  >{{loggedUser.name + " "}}{{loggedUser.surname}}</v-btn>
                </template>
                <v-list>
                  <v-list-item v-for="(item, id) in userMenu" :key="id" @click="goTo(item.link)">
                    <v-list-item-title>{{ item.text }}</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </v-col>
          </v-row>
          <v-row class="menu">
            <v-col>
              <v-toolbar class="menu_links">
                <v-toolbar-items>
                  <v-btn
                    v-for="item in menu"
                    :key="item.id"
                    @click="goTo(item.link)"
                    text
                  >{{item.text}}</v-btn>
                </v-toolbar-items>
              </v-toolbar>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <v-row></v-row>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "ui-header",
  props: ["user", "role"],
  data() {
    return {
      questMenu: [
        { id: 1, text: "Strona główna", link: route("home") },
        { id: 2, text: "Menu", link: route("menu") },
        { id: 3, text: "Zamów online", link: route("order.create.online") },
        { id: 5, text: "Kontakt", link: route("contact") }
      ],
      adminMenu: [
        { id: 1, text: "Strona główna", link: route("home") },
        { id: 2, text: "Dania", link: route("menu.admin") },
        { id: 3, text: "Kategorie dań", link: route("dishCategory.index") },
        { id: 4, text: "Kelnerzy", link: route("worker.index") },
        { id: 5, text: "Rezerwacje", link: route("reservation.index") },
        { id: 6, text: "Zamowienia", link: route("home") },
        { id: 7, text: "Stoliki", link: route("table.index") }
      ],
      waiterMenu: [
        { id: 1, text: "Stoliki", link: route("table.waiterIndex") },
        { id: 2, text: "Zamówienia", link: route("order.index") },
        { id: 3, text: "Rezerwacje", link: route("reservation.index") }
      ],
      userMenu: [
        { id: 1, text: "moje zamówienia", link: route("orders.myOrders") },
        {
          id: 2,
          text: "moje rezerwacje",
          link: route("reservation.indexUser")
        },
        { id: 3, text: "moje konto", link: route("user.myAccount") },
        { id: 4, text: "wyloguj", link: "logout" }
      ],
      customerMenu: [
        { id: 1, text: "Strona główna", link: route("home") },
        { id: 2, text: "Menu", link: route("menu") },
        { id: 3, text: "Zamów online", link: route("order.create.online") },
        { id: 4, text: "Zarezerwuj", link: route("reservation.createUser") },
        { id: 5, text: "Kontakt", link: route("contact") }
      ],
      menu: [],
      notLogged: true,
      loggedUser: ""
    };
  },
  beforeMount() {
    switch (this.role) {
      case "guest":
        this.menu = this.questMenu;
        break;
      case "admin":
        this.menu = this.adminMenu;
        break;
      case "worker":
        this.menu = this.waiterMenu;
        break;
      case "customer":
        this.menu = this.customerMenu;
        break;
    }
    if (this.user !== null) {
      this.notLogged = false;
      this.loggedUser = this.user;
    }
  },

  methods: {
    goTo(route) {
      if (route === "logout") {
        this.logout();
      } else {
        window.location.href = route;
      }
    },
    register() {
      window.location.href = route("register");
    },
    login() {
      window.location.href = route("login");
    },
    logout() {
      axios.post("/logout").then(function() {
        window.location.href = route("login");
      });
    }
  }
};
</script>

<style scoped>
.v-card__text.header-text {
  color: white;
  background-color: #8a5e4e;
}
.v-card.v-card--flat.v-sheet.theme--light {
  border-radius: 0;
}
.header {
  max-height: 9rem;
  background-color: #8a5e4e;
}
.menu {
  margin-bottom: 0;
  margin-top: 0;
}
</style>
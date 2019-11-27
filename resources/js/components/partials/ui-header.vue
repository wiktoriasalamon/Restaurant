<template>
	<v-row no-gutters class="header">
		<v-col cols="12" >
			<v-row no-gutters class="justify-space-between">
				<v-col>
					<v-row class="mx-3">
						<v-col v-if="notLogged" class="text-end">
							<v-btn text @click="register">Zarejestruj</v-btn>
							<v-btn @click="login"  color="#CBA789">Zaloguj się</v-btn>
						</v-col>
						<v-col v-else class="text-end">
							<v-menu offset-y>
								<template v-slot:activator="{ on }">
									<v-btn
										color="#CBA789"
										v-on="on"
									>{{loggedUser.name + " "}}{{loggedUser.surname}}
									</v-btn>
								</template>
								<v-list>
									<v-list-item :key="id" @click="goTo(item.link)" v-for="(item, id) in loggedUserMenu">
										<v-list-item-title>{{ item.text }}</v-list-item-title>
									</v-list-item>
								</v-list>
							</v-menu>
						</v-col>
					</v-row>
					<v-row class="menu">
						<v-col class="hidden-sm-and-down">
							<v-toolbar class="menu_links">
								<v-toolbar-items class="menu_links_full">
									<v-btn
										class="menu_item"
										v-for="item in menu"
										:key="item.id"
										@click="goTo(item.link)"
										text
									>{{item.text}}
									</v-btn>
								</v-toolbar-items>
							</v-toolbar>
						</v-col>
						<v-col class="hidden-md-and-up">
							<v-menu class="responsive_menu" offset-y style="left:0 ;">
								<template v-slot:activator="{ on }">
									<v-btn class="responsive_menu_button" v-on="on">
										<v-icon>menu</v-icon>
									</v-btn>
								</template>
								<v-list class="responsive_menu_list" id="responsive">
									<v-list-item :key="id" @click="goTo(item.link)" v-for="(item, id) in menu">
										<v-list-item-title>{{ item.text }}</v-list-item-title>
									</v-list-item>
								</v-list>
							</v-menu>
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
          {id: 1, text: "Strona główna", link: route("home")},
          {id: 2, text: "Menu", link: route("menu")},
          {id: 3, text: "Zamów online", link: route("order.create.online")},
          {id: 5, text: "Kontakt", link: route("contact")}
        ],
        adminMenu: [
          {id: 1, text: "Strona główna", link: route("home")},
          {id: 2, text: "Dania", link: route("menu.admin")},
          {id: 3, text: "Kategorie dań", link: route("dishCategory.index")},
          {id: 4, text: "Kelnerzy", link: route("worker.index")},
          {id: 7, text: "Stoliki", link: route("table.index")}
        ],
        waiterMenu: [
          {id: 1, text: "Stoliki", link: route("table.waiterIndex")},
          {id: 2, text: "Zamówienia", link: route("order.index")},
          {id: 3, text: "Rezerwacje", link: route("reservation.index")}
        ],
        userMenu: [
          {id: 1, text: "moje zamówienia", link: route("orders.myOrders")},
          {
            id: 2,
            text: "moje rezerwacje",
            link: route("reservation.indexUser")
          },
          {id: 3, text: "moje konto", link: route("user.myAccount")},
          {id: 4, text: "wyloguj", link: "logout"}
        ],
        customerMenu: [
          {id: 1, text: "Strona główna", link: route("home")},
          {id: 2, text: "Menu", link: route("menu")},
          {id: 3, text: "Zamów online", link: route("order.create.online")},
          {id: 4, text: "Zarezerwuj", link: route("reservation.createUser")},
          {id: 5, text: "Kontakt", link: route("contact")}
        ],
				employerMenu: [
          {id: 1, text: "moje konto", link: route("user.myAccount")},
          {id: 2, text: "wyloguj", link: "logout"}
				],
				loggedUserMenu:[],
        menu: [],
        notLogged: true,
        loggedUser: ""
      };
    },
    beforeMount() {
      switch (this.role) {
        case "guest":
          this.menu = this.questMenu;
          this.loggedUserMenu = this.userMenu;
          break;
        case "admin":
          this.menu = this.adminMenu;
          this.loggedUserMenu = this.employerMenu;
          break;
        case "worker":
          this.menu = this.waiterMenu;
          this.loggedUserMenu = this.employerMenu;
          break;
        case "customer":
          this.menu = this.customerMenu;
          this.loggedUserMenu = this.userMenu;
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
        axios.post("/logout").then(function () {
          window.location.href = route("login");
        });
      }
    }
  };
</script>

<style lang="scss" scoped>
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
		margin-top: -10px;
	}

	.menu_links.v-sheet.v-sheet--tile.theme--light.v-toolbar {
		background: none;
		border: none;
		box-shadow: none;
	}

	.menu_links{
		height: 50px !important;
	}

	.menu_item{
		color: white !important;
	}

	.responsive_menu_button{
		width: 100%;
		color: white !important;
		background-color: #8a5e4e !important;
		box-shadow: none;
		border: none;
	}
	#responsive{
		background-color: #8a5e4e !important;
		width: 100%;
		.v-list-item__title{
			color: white !important;
		}
	}
</style>
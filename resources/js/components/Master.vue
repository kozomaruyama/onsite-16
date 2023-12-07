<template>
  <div class="h-100">
    <v-row class="h-100">
      <v-col class="col-auto m-0 ml-2 mt-2 p-0 no-printing h-100">
        <v-navigation-drawer permanentprimary expand-on-hover disable-resize-watcher width="120" permanent>
          <v-list nav dense>
            <v-list-item link @click="setClientPage">
              <v-list-item-icon>
                <v-icon>mdi-account-group</v-icon>
              </v-list-item-icon>
              <v-list-item-title>顧客</v-list-item-title>
            </v-list-item>
            <v-list-item link @click="setProductPage">
              <v-list-item-icon>
                <v-icon>mdi-puzzle</v-icon>
              </v-list-item-icon>
              <v-list-item-title>商品</v-list-item-title>
            </v-list-item>
            <v-list-item link @click="setCompanyPage">
              <v-list-item-icon>
                <v-icon>mdi-office-building-cog</v-icon>
              </v-list-item-icon>
              <v-list-item-title>会社</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-navigation-drawer>
      </v-col>
      <!-- ボディー -->
      <v-col class="m-0 mt-3 p-0">
        <component
          @print='print'
          :selectItems="selectItems"
          :dessertItems="dessertItems"
          v-bind:is="appPage"
        />
      </v-col>
    </v-row>
  </div>
</template>

<script>
    import Company from "./master/Company";
    import Products from "./master/Products";
    import Clients from "./master/Clients";
    export default {
        components: {
            Company,
            Products,
            Clients,
        },
        data: function () {
            return {
                appPage:"",
                fullHeight:0,
                selectItems: [],
                mini: false,
                drawer: true,
                dessertItems: [],
            }
        },
        computed: {
        },
        methods: {
            setClientPage() {
				axios.get('/api/clients')
					.then((res) => {
                        this.dessertItems = res.data;
                        this.appPage = "Clients";
				});
            },
            setProductPage() {
				axios.get('/products')
					.then((res) => {
                        this.dessertItems = res.data;
                        this.appPage = "Products";
				});
            },
            setCompanyPage() {
                this.appPage = "Company";
            },
            print(items) {
            },
        },
        created () {
        },
        mounted() {
        }
    }
</script>
<style lang="scss" scoped>

    @media print {
        .no-printing {
            display: none;
        }
    }

</style>

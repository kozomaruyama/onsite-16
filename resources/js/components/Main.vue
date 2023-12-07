<template>
  <div>
    <v-row>
      <v-col class="col-auto m-0 ml-2 mt-2 p-0 no-printing">
        <v-navigation-drawer permanentprimary expand-on-hover disable-resize-watcher width="120" permanent>
          <v-list nav dense>
            <v-list-item link @click="setPage('Clients')">
              <v-list-item-icon>
                <v-icon>mdi-account-group</v-icon>
              </v-list-item-icon>
              <v-list-item-title>顧客</v-list-item-title>
            </v-list-item>
            <v-list-item link @click="alert('aaa')">
              <v-list-item-icon>
                <v-icon>mdi-puzzle</v-icon>
              </v-list-item-icon>
              <v-list-item-title>商品</v-list-item-title>
            </v-list-item>
            <v-list-item link @click="setPage('Company')">
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
    import Clients from "./master/Clients";
    export default {
        components: {
            Clients,
        },
        data: function () {
            return {
                appPage: "",
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
            setPage(page) {

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

    @page {
        size: portrait;
        // margin: 150mm 220mm;
        // font-family: "Helvetica Neue", Arial, "Hiragino Kaku Gothic ProN", "Hiragino Sans", Meiryo, sans-serif;
        // line-height: 1.5;
        // size: portrait;
    }
</style>

<template>
    <div>
        <!-- メイン -->
        <CyberMain
            :filterItems="filterItems"
            :commandItems="commandItems"
            :printItems="printItems"
            :isNaviVisible="true"
        >
            <!-- サイドメニュー -->
            <template v-slot:navi>
                <v-list nav dense>
                    <v-list-item link @click="setCommonPage">
                        <v-list-item-icon>
                            <v-icon dense>mdi-cog</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>共通</v-list-item-title>
                    </v-list-item>
                    <v-list-item link @click="setCommonPage">
                        <v-list-item-icon>
                            <v-icon dense>mdi-file-cog-outline</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>案件</v-list-item-title>
                    </v-list-item>
                    <v-list-item link @click="setProductPage">
                        <v-list-item-icon>
                            <v-icon dense>mdi-file-cog-outline</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>請求</v-list-item-title>
                    </v-list-item>
                </v-list>
            </template>
            <!-- メイン -->
            <template v-slot:main>
                <component
                    @print='print'
                    :selectItems="selectItems"
                    :dessertItems="dessertItems"
                    v-bind:is="appPage"
                />
            </template>
        </CyberMain>
    </div>
</template>

<script>
    import CyberMain from "../CyberMain";    
    import Common from "./Common";
    // import Clients from "./Clients";

    export default {
        components: {
            CyberMain,
            Common,
        },
        data: function () {
          return {
            appPage:"Common",
            fullHeight:0,
            selectItems: [],
            mini: false,
            drawer: true,
            dessertItems: [],
            filterItems:[],
            commandItems:[],
            printItems:[],

          }
        },
        computed: {
        },
        methods: {
            setCommonPage() {
              axios.get('/api/sys_items').then((res) => {
// alert(JSON.stringify(res.data))
                this.dessertItems = res.data;
                this.appPage = "Common";
              });
            },
            setTestPage() {
                this.appPage = "test";
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
            setTestPage() {
                this.appPage = "test";
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

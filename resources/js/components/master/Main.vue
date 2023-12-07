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
                    <v-list-item link @click="setCompanyPage">
                        <v-list-item-icon>
                            <v-icon dense>mdi-office-building-cog</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>会社</v-list-item-title>
                    </v-list-item>
                    <v-list-item link @click="setClientPage">
                        <v-list-item-icon>
                            <v-icon dense>mdi-account-group</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>元請</v-list-item-title>
                    </v-list-item>
                    <v-list-item link @click="setSubContractPage">
                        <v-list-item-icon>
                            <v-icon dense>mdi-account-group</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>下請</v-list-item-title>
                    </v-list-item>
                    <v-list-item link @click="setProductPage">
                        <v-list-item-icon>
                            <v-icon dense>mdi-puzzle</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>商品</v-list-item-title>
                    </v-list-item>
                    <v-list-item link @click="setPeoplePage">
                        <v-list-item-icon>
                            <v-icon dense>mdi-account-tie</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>担当</v-list-item-title>
                    </v-list-item>
                </v-list>
            </template>
            <!-- メイン -->
            <template v-slot:main>
                <!-- <tableHeader
                    :filterItems="filterItems"
                    :commandItems="commandItems"
                    :printItems="printItems"
                    @search="search"
                    @filter="filter"
                    @command="command"
                    @print="print"
                ></tableHeader>
                <CyberDataTable
                    ref="CyberDataTable"
                    :headers="headers"
                    :items="tableItems"
                    :searchText="searchText"
                    @click_TableRow="click_TableRow"
                ></CyberDataTable> -->
                <component
                    @print='print'
                    :key="refresh"
                    :selectItems="selectItems"
                    :dessertItems="dessertItems"
                    :user="user"
                    :status="clientClass"
                    v-bind:is="appPage"
                />
            </template>
        </CyberMain>
        <!-- 案件編集ダイアログ -->
        <!-- <CyberDialog ref="SubjectDialog" >
            <template v-slot:caption>案件編集</template>
            <template v-slot:main>
            </template>
        </CyberDialog> -->
    </div>
</template>

<script>
    import CyberMain from "../CyberMain";
    import Company from "./Company";
    import Products from "./Products";
    import Clients from "./Clients";
    import Peoples from "./Peoples";
    export default {
        components: {
            CyberMain,
            Company,
            Products,
            Clients,
            Peoples,
        },
        props: ["user"],
        data: function () {
          return {
            appPage:"Company",
            fullHeight:0,
            selectItems: [],
            mini: false,
            drawer: true,
            dessertItems: [],

            filterItems:[],
            commandItems:[],
            printItems:[],
            refresh:false,
            clientClass:1,
          }
        },
        computed: {
        },
        methods: {
            setClientPage() {
              axios.get('/clients/prime').then((res) => {
                  this.dessertItems = res.data;
                  this.refresh = !this.refresh;
                  this.clientClass=1;
                  this.appPage = "Clients";
              });
            },
            setSubContractPage() {
              axios.get('/clients/subcontract').then((res) => {
                  this.dessertItems = res.data;
                  this.refresh = !this.refresh;
                  this.clientClass=11;
                  this.appPage = "Clients";
              });
            },
            setPeoplePage() {
              axios.get('/people').then((res) => {
                  this.dessertItems = res.data;
                  this.appPage = "Peoples";
              });
            },
            setProductPage() {
              axios.get('/products').then((res) => {
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

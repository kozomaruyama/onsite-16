<template>
    <v-stepper v-model="stepNo" outlined>
        <v-stepper-items >
            <v-stepper-content step="1" >
                <v-card-title class="m-0 p-0">
                    明細内容選択
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                        clearable
                        class="m-0 p-0"
                    />
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text  class="m-0 p-0">
                    <v-data-table
                        v-model="selected"
                        :headers="headers"
                        :items="products"
                        :single-select="false"
                        :search="search"
                        item-key="id"
                        show-select
                        class="elevation-1"
                    >
                    <template v-slot:top></template>
                    </v-data-table>
                </v-card-text>
                <v-row class="m-0 p-0">
                    <v-col class="col-auto">
                        <v-btn color="primary" @click="close()" ><v-icon left>mdi-close-thick</v-icon>中止</v-btn>
                    </v-col>
                    <v-col />
                    <v-col class="col-auto">
                        <v-btn color="primary" @click="stepNo = 2">次へ<v-icon right>mdi-arrow-right</v-icon></v-btn>
                    </v-col>
                </v-row>
            </v-stepper-content>
            <v-stepper-content step="2" >
                <v-card-text  class="m-0 p-0">
                    <v-data-table
                        v-model="selected"
                        :headers="headers"
                        :items="selected"
                        :single-select="false"
                        item-key="id"
                        show-select
                        class="elevation-1"
                    >
                        <template v-slot:top></template>
                    </v-data-table>
                </v-card-text>
                <v-row class="m-0 p-0">
                    <v-col class="col-auto">
                        <v-btn color="primary" @click="stepNo = 1"><v-icon left>mdi-arrow-left</v-icon>戻る</v-btn>
                    </v-col>
                    <v-col />
                    <v-col class="col-auto">
                        <v-btn color="primary" @click="close()" ><v-icon left>mdi-close-thick</v-icon>中止</v-btn>
                    </v-col>
                    <v-col />
                    <v-col class="col-auto">
                        <v-btn color="primary" @click="enter()"><v-icon left>mdi-check-underline</v-icon>確定</v-btn>
                    </v-col>
                </v-row>
            </v-stepper-content>
        </v-stepper-items>
        <v-stepper-header outlined>
            <v-stepper-step :complete="stepNo > 1" step="1" >顧客</v-stepper-step>
            <v-divider></v-divider>
            <v-stepper-step step="2">完了</v-stepper-step>
        </v-stepper-header>
    </v-stepper>
</template>

<script>
    export default {
		data: function () {
			return {
                stepNo:1,
				// taskClassItems: [],
				// unitNames: [],
                // newTask:[],
                selected: [],
                headers: [
                    {
                        text: '品名',
                        align: 'start',
                        sortable: false,
                        value: 'name',
                    },
                    { text: '型番', value: 'code' },
                    { text: '寸法', value: 'size' },
                    { text: '重量 (kg)', value: 'weight' },
                    { text: '単価 ', value: 'cost' },
                    { text: '数量 ', value: 'volume' },
                ],
                products:[],
                search: '',
                e1: 1,
			}
		},
        mounted() {
            this.stepNo = 1;
            this.selected = [];
            this.getProducts();
        },
		methods: {
			close() {
				this.$emit('close');
			},
            enter() {
                this.$emit('enter',this.selected);
			},
            getProducts() {
                axios.get('/products')
                .then((res) => {
                    this.products = res.data;
                });
            },
		},
	}
</script>

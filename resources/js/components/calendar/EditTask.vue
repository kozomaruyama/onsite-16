<template>
	<v-container class="container-fluid p-1">
		<v-row no-gutters class="">
			<v-text-field class="d-none" id="index" v-model="values.id"></v-text-field>
			<v-col cols="12" class="px-2 mt-2">
				<v-text-field label="名称" id="name" v-model="values.name" value="null" dense></v-text-field>
			</v-col>
		</v-row>
		<v-row no-gutters class="">
			<v-col cols="12" class="px-2">
				<v-text-field label="仕様" id="breakdown" v-model="values.breakdown" value="null" dense></v-text-field>
			</v-col>
		</v-row>
		<v-row no-gutters class="">
            <v-col cols="12" md="2" class="px-2">
                <v-menu
                    ref="dateMenu1"
                    v-model="dateMenu1"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                    <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="values.start_date"
                        label= "着工日"
                        prepend-icon="mdi-calendar"
                        v-bind="attrs"
                        v-on="on"
                        dense
                    ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="values.start_date"
                        no-title
						locale="ja"
                        @input="dateMenu1 = false"
                    ></v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="12" md="2" class="px-2">
                <v-menu
                    ref="dateMenu2"
                    v-model="dateMenu2"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                    <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="values.end_date"
                        label= "完了日"
                        prepend-icon="mdi-calendar"
                        v-bind="attrs"
                        v-on="on"
                        dense
                    ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="values.end_date"
                        no-title
						locale="ja"
                        @input="dateMenu2 = false"
                    ></v-date-picker>
                </v-menu>
            </v-col>
			<v-col cols="12" md="1" class="mt-2">
				<input type="checkbox" id="isDay" v-model="values.isDay"><label class="mx-1">全日</label>
			</v-col>
			<!-- <v-col cols="12" md="2" class="px-2">
				<v-select label="状態" :items="processNames" item-text="name" item-value="no" id="process" v-model="values.process"  value="0" dense></v-select>
			</v-col> -->
			<v-col cols="12" md="2" class="px-2">
				<v-select label="区分" :items="productClassItems" item-text="name" item-value="no" id="product_class" v-model="values.product_class"  value="0" dense></v-select>
			</v-col>
			<v-col cols="12" md="1" class="px-2">
				<v-text-field label="進捗" id="percentage" v-model="values.percentage" value="0" type="number" min="0" max="100" dense suffix="%"></v-text-field>
			</v-col>
		</v-row>
		<!-- <v-row no-gutters class="">
			<v-col cols="12" md="2" class="px-2">
				<v-text-field label="単価" id="tax" v-model="values.cost" @change="calcTotal" value="0" dense suffix="円"></v-text-field>
			</v-col>
			<v-col cols="12" md="2" class="px-2">
				<v-text-field label="数量" id="expenses" v-model="values.volume" @change="calcTotal" value="0" dense></v-text-field>
			</v-col>
			<v-col cols="12" md="2" class="px-2">
				<v-select :items="unitNames" item-text="name" item-value="no" id="unit" v-model="values.unit" @change = "change_unit" value="0" dense></v-select>
			</v-col>
			<v-col cols="12" md="2" class="px-2">
				<v-text-field label="金額" id="amount" v-model="values.amount"  value="0" dense suffix="円"></v-text-field>
			</v-col>
			<v-col cols="12" md="3" class="px-2 mt-2">
				<input type="checkbox" id="checkbox" v-model="values.isLabel"><label class="mx-1">見出し項目にする</label>
			</v-col>
		</v-row> -->
		<v-row no-gutters class="">
			<v-col cols="12" class="px-2 mt-2">
				<v-text-field label="備考" id="memo" v-model="values.memo" value="null" dense></v-text-field>
			</v-col>
		</v-row>
		<v-row class="m-0 p-0">
            <v-col></v-col>
            <v-col class="col-auto">
                <v-btn depressed class="text-white bg-primary" color="success" @click="enter()">
                    <v-icon left>mdi-content-save-outline</v-icon>登　録
                </v-btn>
            </v-col>
        </v-row>
	</v-container>
</template>

<script>
	import { mapState, mapGetters } from 'vuex'
    export default {
		props: ["values"],
		data: function () {
			return {
                unitNames: [],
				productClassItems: [],
			}
		},
		methods: {
			// calcTotal() {
			// 	this.values.amount = this.values.cost * this.values.volume;
			// },
			// change_unit(event) {
			// 	this.values.unit_name = this.unitNames[event-1]['name'];
			// },
            enter() {
                axios.post('/task', this.values )
                    .then((res) => {
// alert(JSON.stringify(res))
                        if (res.status==200) {
                            // this.$refs.FileManeger.fileUpload();
                            this.$emit('close');
                        }
                });
            },
			close() {
                if (this.values.id == -1) {
                    this.$emit('add',this.values);
                } else {
                    this.$emit('close');
                }
			},
		},
		created() {
            this.unitNames = this.$store.getters.getUnitNames;
			this.productClassItems = this.$store.getters.getProductClasses;
		},
		computed: {
            ...mapGetters([ 'getUnitNames' ]),
			...mapGetters([ 'getProductClasses' ]),
		},

		watch: {
			// 'values.cost': function(val) {
			// 	this.calcTotal();
			// },
			// 'values.volume': function(val) {
			// 	this.calcTotal();
			// },
			// 'values.isLabel': function(val) {
			// 	if (val)  {
			// 		this.values.cost=0;
			// 		this.values.volume=0;
			// 		this.values.unit=0;
			// 	}
			// },
		},
	}
</script>

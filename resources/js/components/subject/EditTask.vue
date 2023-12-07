<template>
	<div>
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
					<!-- <v-text-field label="着工日" id="start_date" v-model="values.start_date" value="null" dense></v-text-field> -->
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
							/>
						</template>
						<v-date-picker
							v-model="values.start_date"
							no-title
							locale="ja"
							@input="dateMenu1 = false"
						/>
					</v-menu>
				</v-col>
				<v-col cols="12" md="2" class="px-2">
					<!-- <v-text-field label="完了日" id="end_date" v-model="values.end_date" value="null" dense></v-text-field> -->
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
							/>
						</template>
						<v-date-picker
							v-model="values.end_date"
							no-title
							locale="ja"
							@input="dateMenu2 = false"
						/>
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
					<v-text-field label="進捗" type="number" max=100 min=0 id="percentage" v-model="values.percentage" value="0" dense suffix="%"></v-text-field>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" md="2" class="px-2">
					<v-text-field label="数量" type="number" id="expenses" v-model="values.volume" @change="calcTotal" value="0" dense></v-text-field>
				</v-col>
				<v-col cols="12" md="2" class="px-2">
					<v-select :items="unitNames" item-text="name" item-value="no" id="unit" v-model="values.unit" @change = "change_unit" value="0" dense></v-select>
				</v-col>
				<v-col cols="12" md="2" class="px-2">
					<v-text-field label="単価" type="number" id="tax" v-model="values.cost" @change="calcTotal" value="0" dense suffix="円"></v-text-field>
				</v-col>
				<v-col cols="12" md="2" class="px-2">
					<v-text-field label="金額" type="number" id="amount" v-model="values.amount" readonly value="0" dense suffix="円"></v-text-field>
				</v-col>
				<v-col cols="12" md="3" class="px-2 mt-2">
					<input type="checkbox" id="checkbox" v-model="values.isLabel"><label class="mx-1">見出し項目にする</label>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" class="px-2 mt-2">
					<v-text-field label="備考" id="memo" v-model="values.memo" value="null" dense></v-text-field>
				</v-col>
			</v-row>
			<v-row class="m-0 p-0">
				<v-col v-if="mode=='edit'" class="col-auto">
					<v-btn depressed small fab @click="del()">
						<v-icon color="grey">mdi-delete-outline</v-icon>
					</v-btn>
				</v-col>
				<v-col></v-col>
				<v-col class="col-auto">
					<v-btn depressed small fab @click="createProduct()">
						<v-icon color="grey">mdi-puzzle-plus</v-icon>
					</v-btn>
				</v-col>
				<v-col class="col-auto">
					<v-btn depressed class="text-white bg-primary" @click="enter()">
						<v-icon left>mdi-check-underline</v-icon>確定
					</v-btn>
				</v-col>
			</v-row>
		</v-container>
		<MsgBox ref="MsgBox" @enter="msgBox_enter">
			<template v-slot:caption></template>
			<template v-slot:main>
				<v-container>
					<v-row align-content="center" >
						<v-col align="center">
							<div class="text-h6">{{ Msgbox_message }}</div>
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>
	</div>
</template>

<script>
	import MsgBox from "../msgbox";
	import { mapState, mapGetters } from 'vuex'
	export default {
		components: {
			MsgBox,
		},
		props: ["values","mode"],
		data: function () {
			return {
				unitNames: [],
				productClassItems: [],
				dateMenu1:false,
				dateMenu2:false,
				Msgbox_message:"",
			}
		},
		methods: {
			calcTotal() {
				this.values.amount = Math.round(this.values.cost * this.values.volume);
			},
			change_unit(event) {
				this.values.unit_name = this.unitNames[event-1]['name'];
			},
			enter() {
// alert(JSON.stringify(this.values) )
				if (this.mode == 'add') {
						this.$emit('add',this.values);
				} else {
						this.$emit('enter', this.values);
						// this.$emit('close');
				}
			},
			del(){
				this.$emit('del');
				this.$emit('close');
			},
			createProduct() {
				this.Msgbox_message = '「' + this.values.name + '」を商品情報に登録しますか？'
				this.$refs.MsgBox.open('add')
			},
			msgBox_enter(para) {
				axios.post('/products', this.values).then((res) => {
					this.Msgbox_message = '「' + this.values.name + '」が商品情報に登録されました。'
					this.$refs.MsgBox.open('ok')
				})
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
			'values.cost': function(val) {
				this.calcTotal();
			},
			'values.volume': function(val) {
				this.calcTotal();
			},
			'values.isLabel': function(val) {
				if (val)  {
					this.values.cost=0;
					this.values.volume=0;
					this.values.unit=0;
				}
			},
		},
	}
</script>

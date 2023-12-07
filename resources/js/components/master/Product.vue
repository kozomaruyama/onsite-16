<template>
	<div>
    <v-container fluid class="bg-peimary px-5">
		<v-row no-gutters class="">
			<v-text-field class="d-none" id="index" v-model="values.id"></v-text-field>
			<v-col cols="12" md="2" class="px-2 mt-2">
				<v-text-field label="コード" id="code" v-model="values.code" dense ></v-text-field>
			</v-col>
			<v-col cols="12" md="2" class="px-2 mt-2">
				<v-select :items="productClasses" item-text="name" item-value="no" label="分類" id="class" v-model="values.class" dense></v-select>
			</v-col>
			<v-col cols="12" md="5" class="px-2 mt-2">
				<v-text-field label="名称" id="name" v-model="values.name" dense ></v-text-field>
			</v-col>
			<v-col cols="12" md="3" class="px-2 mt-2">
				<v-text-field label="カナ" id="kana" v-model="values.kana" dense ></v-text-field>
			</v-col>
		</v-row>
		<v-row no-gutters class="">
			<v-col cols="12" class="px-2 mt-2">
				<v-text-field label="規格・詳細" id="breakdown" v-model="values.breakdown" dense ></v-text-field>
			</v-col>
		</v-row>
		<v-row no-gutters class="">
			<v-col cols="12" md="2" class="px-2 mt-2">
				<v-text-field label="単価" id="cost" v-model="values.cost" prefix="¥"  dense></v-text-field>
			</v-col>
			<v-col cols="12" md="1" class="px-2 mt-2">
				<v-text-field id="cost_1" v-model="values.cost_1" readonly prefix="(¥" suffix=")" dense ></v-text-field>
			</v-col>
			<v-col cols="12" md="2" class="px-2 mt-2">
				<v-text-field label="数量" id="volume" v-model="values.volume" dense ></v-text-field>
			</v-col>
            <v-col cols="12" md="1" class="px-2 mt-2">
                <v-select :items="unitNames" item-text="name" item-value="no" id="unit" v-model="values.unit" value="0" dense></v-select>
            </v-col>
			<v-col cols="12" md="2" class="px-2 mt-2">
				<v-text-field label="サイズ" id="size" v-model="values.size" dense ></v-text-field>
			</v-col>
			<v-col cols="12" md="2" class="px-2 mt-2">
				<v-text-field label="重量" id="weight" v-model="values.weight" dense ></v-text-field>
			</v-col>
			<v-col cols="12" md="2" class="px-2 mt-4">
				<input type="checkbox" id="checkbox" v-model="values.isLabel"><label class="mx-1">見出し項目にする</label>
			</v-col>
		</v-row>
		<v-row no-gutters class="">
			<v-col cols="12" md="2" class="px-2 mt-2">
				<v-text-field label="原価" id="cost" v-model="values.cost_2" prefix="¥"  dense></v-text-field>
			</v-col>
		</v-row>
		<v-row class="m-0 p-0">
            <v-col class="col-auto">
                <v-btn v-if="mode=='edit'" depressed small fab @click="del()">
                    <v-icon>mdi-delete-outline</v-icon>
                </v-btn>
            </v-col>
			<v-col></v-col>
			<v-col class="col-auto">
				<v-btn depressed class="text-white bg-primary" color="success" @click="enter()">
					<v-icon left>mdi-content-save-outline</v-icon>登　録
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
							<div class="text-h6">削除してよろしいですか？</div>
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>
	</div>
</template>
<script>
	import { mapState, mapGetters } from 'vuex'
	import MsgBox from "../msgbox";
	export default {
		components: {
			MsgBox,
		},
		props: ["values","mode"],
		data: function () {
			return {
				productClasses: [],
				productClassItems: [],
				taxClassItems: [],
				unitNames: [],
			}
		},
		methods: {
			close() {
				this.$emit('close');
			},
			enter() {
				var product = Object.assign({}, this.values)    // clientObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
                switch (this.mode) {
                    case "edit":
                        axios.put('/products/' + product.id, product).then((res) => {
							this.$emit('enter',{ value:res.data, mode:"upd" });
							// this.$emit('close');
						});
						break;
					case "create":
						axios.post('/products', product).then((res) => {
							this.$emit('enter',{ value:res.data, mode:"add" });
						});
						break;
					case "group":
						axios.post('/products/group', { targetItems: this.selectItems, updateValues: product } )
							.then((res) => {
								this.$emit('close');
						});
						break;
				}
			},
			del() {
				this.$refs.MsgBox.open('del')
			},
			msgBox_enter(para) {
				if (para='del') {
					axios.delete('/products/' + this.values.id).then((res) => {
						if (res.data.status) {
							this.$emit('del');
						} else {
							if (res.data.value.errorInfo[0]=="23000") {
								this.$emit('error', "他の情報で参照されているため、削除できません。" );
							} else {
								this.$emit('error', "削除できませんでした。(" + res.data.value.errorInfo[0] + ")" );
							}
						}
					})
				}
			},
		},

		watch: {
			values: {
				handler: function(newVal, oldVal) {
					if (isNaN(newVal.cost)) 
					{
						this.values.cost_1  = 0;
					} else {
						this.values.cost_1 = Math.round((newVal.cost * 0.26) / 75)/10;
					}
				},
				deep : true,
				immediate: false
			},
		},
		computed: {
			...mapGetters([ 'getProductClasses' ]),
			...mapGetters([ 'getUnitNames' ]),
		},
		created() {
			this.productClasses = this.$store.getters.getProductClasses;
			this.unitNames = this.$store.getters.getUnitNames;
		},
		mounted() {
		}
	}
</script>


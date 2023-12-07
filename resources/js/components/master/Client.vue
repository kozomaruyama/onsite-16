<template>
	<div>
		<v-container fluid class="bg-peimary px-5">
			<v-row no-gutters class="">
				<v-text-field class="d-none" id="index" v-model="values.id"></v-text-field>
				<v-col cols="12" md="1" class="px-2 mt-2">
					<v-text-field label="コード" id="code" v-model="values.code"></v-text-field>
				</v-col>
				<v-col cols="12" md="1" class="px-2 mt-2">
					<v-select :items="clientClassItems" item-text="name" item-value="no" label="分類" v-model="values.class" :rules="[rules.required]" ></v-select>
				</v-col>
				<v-col cols="12" md="5" class="px-2 mt-2">
					<v-text-field label="名称" id="name" v-model="values.name" :rules="[rules.required]"></v-text-field>
				</v-col>
				<v-col cols="12" md="3" class="px-2 mt-2">
					<v-text-field label="カナ" id="kana" v-model="values.kana" ></v-text-field>
				</v-col>
				<v-col cols="12" md="2" class="px-2 mt-2">
					<v-text-field label="略称" id="kana" v-model="values.nickname" :rules="[rules.maxLength8]"></v-text-field>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" md="1" class="px-2 mt-2">
					<v-text-field label="〒" id="zip" v-model="values.zip" ></v-text-field>
				</v-col>
				<v-col cols="12" md="8" class="px-2 mt-2">
					<v-text-field label="住所" id="address1" v-model="values.address1" ></v-text-field>
				</v-col>
				<v-col cols="12" md="3" class="px-2 mt-2">
					<v-text-field label="代表" id="delegate" v-model="values.delegate" ></v-text-field>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" md="4" class="px-2 mt-2">
					<v-text-field label="電話" id="tel1" v-model="values.tel1" ></v-text-field>
				</v-col>
				<v-col cols="12" md="4" class="px-2 mt-2">
					<v-text-field label="FAX" id="fax" v-model="values.fax" ></v-text-field>
				</v-col>
				<v-col cols="12" md="4" class="px-2 mt-2">
					<v-text-field label="eMail" id="mail" v-model="values.mail" ></v-text-field>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" md="1" class="px-2 mt-2">
					<v-text-field label="締切日" type="number" min="1" max="31" v-model="values.bill_closing" :rules="[rules.required]" @change="billClosing_changed"></v-text-field>
				</v-col>
				<!-- <v-col cols="12" md="1" class="px-2 mt-2">
					<v-text-field label="請求日" type="number"  min="1" max="31" v-model="values.bill_issue"  ></v-text-field>
				</v-col> -->
				<v-col cols="12" md="1" class="px-2 mt-2">
					<v-text-field label="支払日" type="number"  min="1" max="31" v-model="values.bill_payment" :rules="[rules.required]" ></v-text-field>
				</v-col> 
				<v-col cols="12" md="2" class="px-2 mt-2">
					<v-select :items="billPaymentClassItems" item-text="name" item-value="no" v-model="values.bill_payment_class" ></v-select>                          
				</v-col>
				<!-- <v-col cols="12" md="1" class="px-2 mt-2">
					<v-text-field label="支払率" type="number" min="0" max="100"  v-model="values.pay_ratio" suffix="％" :rules="[rules.required]"></v-text-field>
				</v-col>
				<v-col cols="12" md="2" class="p-0 mx-2 mt-2">
					<v-select label="支払区分" :items="scheduleClassItems" item-text="name" item-value="no" v-model="values.pay_status" ></v-select>                          
				</v-col>			 -->
				<!-- <v-col cols="12" md="2" class="px-2 mt-2">
					<v-text-field label="架け（￥）" id="pay_amount" v-model="values.pay_amount" ></v-text-field>
				</v-col> -->
				<v-col cols="12" md="2" class="px-2 mt-2">
					<v-select :items="taxClassItems" item-text="name" item-value="no" label="消費税" id="tax_state" v-model="values.tax_state"></v-select>
				</v-col>
				<v-col cols="auto" class="pl-2 mt-6 mx-2">
					<v-sheet
						class="mx-auto rounded-circle"
						height="30"
						width="30"
						:color="values.color"
						@click="$refs.ColorSelecter.open()"
					/>
				</v-col>
			</v-row>
			<v-row no-gutters class="">	
				<!-- <v-col cols="12" md="2" class="px-2 mt-2">
					<v-select :items="colors" item-text="name" item-value="name" label="背景色" v-model="values.color"></v-select>
				</v-col> -->
			</v-row>
	
			<v-row no-gutters class="">
					<v-col cols="12" md="3" class="px-2">
							<v-text-field label="見積書コメントタイトル１" id="message1_title" v-model="values.message1_title" ></v-text-field>
					</v-col>
					<v-col cols="12" md="9" class="px-2">
							<v-text-field label="見積書コメント１" id="message1" v-model="values.message1" ></v-text-field>
					</v-col>
			</v-row>
			<v-row no-gutters class="">
					<v-col cols="12" md="3" class="px-2">
							<v-text-field label="見積書コメントタイトル２" id="message2_title" v-model="values.message2_title" ></v-text-field>
					</v-col>
					<v-col cols="12" md="9" class="px-2">
							<v-text-field label="見積書コメント２" id="message2" v-model="values.message2" ></v-text-field>
					</v-col>
			</v-row>
			<v-row no-gutters class="">
					<v-col cols="12" md="3" class="px-2">
							<v-text-field label="見積書コメントタイトル３" id="message3_title" v-model="values.message3_title" ></v-text-field>
					</v-col>
					<v-col cols="12" md="9" class="px-2">
							<v-text-field label="見積書コメント３" id="message3" v-model="values.message3" ></v-text-field>
					</v-col>
			</v-row>
			<v-row no-gutters class="">
					<v-col cols="12" md="3" class="px-2">
							<v-text-field label="注文書コメントタイトル" v-model="values.message4_title" ></v-text-field>
					</v-col>
					<v-col cols="12" md="9" class="px-2">
							<v-text-field label="注文書コメント" v-model="values.message4" ></v-text-field>
					</v-col>
			</v-row>
	
			<v-row class="m-0 p-0">
				<v-col class="col-auto">
						<v-btn v-if="mode=='edit'" depressed small fab @click="$refs.MsgBox.open('del')">
								<v-icon color="grey">mdi-delete-outline</v-icon>
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
		<MsgBox ref="ColorSelecter">
			<template v-slot:caption>カラーパレット</template>
			<template v-slot:main>
				<v-container >
					<v-row align-content="center" class="p-2">
						<v-col v-for="(color, index) in colors" :key="index"  class="col-auto px-2 mt-2 text-center">
							<!-- <span v-for="(color, index) in colors" :key="index" class="p-2"> -->
								<v-avatar	:color="color.name" size="30" @click="color_selected(color.name)" class=""/>
							<!-- </span> -->
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>
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
		<MsgBox ref="MsgBox_err" @enter="msgBoxErr_enter">
			<template v-slot:caption></template>
			<template v-slot:main>
				<v-container>
					<v-row v-for="(errMessage,index) in errMessages" :key="index"  align-content="center" >
						<v-col align="center">
							<div class="text-h6">{{ errMessage }}</div>
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>
	</div>
</template>
<script>
	import { mapState, mapGetters } from 'vuex'
	import commFunctions from '../../functions/commFunctions';
	import MsgBox from "../msgbox";
	const { getStatusFlag,setStatusFlag } = commFunctions();	
	export default {
		components: {
			MsgBox,
		},
		props: ["values","mode","class"],
		data: function () {
			return {
				clientClasses: [],
				clientClassItems: [],
				taxClassItems: [],
				scheduleClassItems: [],
				billPaymentClassItems: [],
				colors:[],
				isNextMonth:false,
				rules: {
					required: value => !!value || '省略不可.',
          notNull: value => isNaN(value) || '数値のみ',
          maxLength8: value => value.length <= 8 || '8文字以内',
        },
				errMessages:[],
			}
		},

		methods: {
			close() {
				this.$emit('close');
			},
			enter() {
				var client = Object.assign({}, this.values)    // clientObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
				switch (this.mode) {
					case "edit":
// alert(JSON.stringify(client))
						axios.put('/clients/' + client.id, client).then((res) => {
							if (res.data.status) {
// alert(JSON.stringify(res.data.value))
								this.$emit('enter',{ value:res.data.value, mode:"upd" });
							} else {
								this.$emit('error', "登録できませんでした。(" + res.data.value.errorInfo[0] + ")" );
							}							
						});
						break;
					case "create":
						axios.post('/clients', client).then((res) => {
							if (res.data.status) {
								this.$emit('enter',{ value:res.data.value, mode:"create" });
							} else {
								this.errMessages = res.data.value
								this.$refs.MsgBox_err.open()
// alert(JSON.stringify(res.data.value[0]))
								this.$emit('error', "登録できませんでした。(" + res.data.value.errorInfo[0] + ")" );
							}
						});
						break;
					case "group":
						axios.post('/api/clients/group', { targetItems: this.selectItems, updateValues: client } )
							.then((res) => {
								this.$emit('close');
						});
						break;
				}
			},
			msgBox_enter(para) {
				if (para='del') {
					axios.delete('/clients/' + this.values.id).then((res) => {
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
			billClosing_changed(e) {
				this.values.bill_issue = e;
			},
			color_selected(e) {
				this.values.color = e;
				this.$refs.ColorSelecter.close()
// alert(JSON.stringify(e))
			},
		},
		computed: {
			...mapGetters([ 'getClientClasses2' ]),
			...mapGetters([ 'getTaxClasses' ]),
			...mapGetters([ 'getColors' ]),
			...mapGetters([ 'getScheduleClasses' ]),	
			...mapGetters([ 'getBillPaymentClass' ]),	
		},
		watch: {
			values: {
				handler(newValue, oldValue) {
					if (getStatusFlag(newValue.status,1)=="1") {
						this.isNextMonth = true
					} else {
						this.isNextMonth = false
					}
				},
				deep: true
			},
			isNextMonth: function(newStatus, oldStatus) {
				if (newStatus) {
					this.values.status = setStatusFlag(this.values.status,1,"1")
				}	else {
					this.values.status = setStatusFlag(this.values.status,1,"0")
				}
			},
		}, 
		created() {
			this.clientClassItems = this.$store.getters.getClientClasses2;
			this.taxClassItems = this.$store.getters.getTaxClasses;
			this.colors = this.$store.getters.getColors;
			this.scheduleClassItems = this.$store.getters.getScheduleClasses;		
			this.billPaymentClassItems = this.$store.getters.getBillPaymentClass;		
		},
		mounted() {
		}
	}
</script>


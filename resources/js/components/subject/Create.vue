<template>
	<div>
		<v-stepper v-model="e1" outlined class="">
			<v-stepper-items >
				<v-stepper-content step="1" >
					<v-row class="m-2 mb-0 pt-3">
						<v-col cols="12" md="4" class="p-0 mx-2 mt-1 ">
							<v-select class="m-0 p-0" dense @change="client_selected" :items="clients" item-text="name" item-value="id" label="元請" v-model="subject.client_id" ></v-select>
						</v-col>
						<!-- <v-col cols="12" md="4" class="p-0 mx-2 mt-1 ">
								<v-select class="m-0 p-0" dense :items="subContracts" item-text="name" item-value="id" label="下請" v-model="subject.subcontract_id" @change="getPeople"></v-select>
						</v-col>
						<v-col cols="12" md="2" class="p-0 mx-2 mt-1">
								<v-select class="m-0 p-0" :items="people" item-text="name" item-value="id" label="担当(下請)" v-model="subject.person_id" dense ></v-select>
						</v-col>                 -->
						<!-- <v-col cols="12" md="2" class="p-0 mx-2 mt-1">
								<v-select class="m-0 p-0" dense :items="subjectClassItems" item-text="name" item-value="no" label="区分" id="subject_class" v-model="subject.class" ></v-select>
						</v-col> -->
						<!-- <v-col cols="12" md="2" class="p-0 mx-2 mt-1">
								<v-select class="m-0 p-0" dense :items="processNames" item-text="name" item-value="no" label="状態"  v-model="subject.process" ></v-select>
						</v-col> -->
					</v-row>
					<v-row class="mx-2 my-0">
						<v-col cols="12" md="2" class="p-0 mx-2 mt-4">
								<v-select class="m-0 p-0"  :items="taxClassItems" item-text="name" item-value="no" label="消費税"  v-model="subject.tax_state"></v-select>
						</v-col>
						<v-col cols="12" md="1" class="p-0 mx-2 mt-0">
								<v-text-field type="number" max=100 min=0 label="支払率" v-model="subject.pay_ratio" :rules="[rules.required]" suffix="％" ></v-text-field>
						</v-col>
						<v-col cols="12" md="2" class="p-0 mx-2 mt-0">
								<v-select label="支払区分" :items="scheduleClassItems" item-text="name" item-value="no" v-model="subject.pay_status" ></v-select>                          
						</v-col>

						<v-col cols="12" md="2" class="p-0 mx-2 my-0 mt-4">
							<v-menu
								:nudge-right="40"
								transition="scale-transition"
								offset-y
								min-width="auto"
							>
							<template v-slot:activator="{ on }">
								<v-text-field
									v-model="subject.start_date"
									:rules="[rules.required]"
									dense
									label="着工日"
									prepend-icon="mdi-calendar"
									v-on="on"
								></v-text-field>
							</template>
							<v-date-picker
								v-model="subject.start_date"
								no-title
								locale="ja"
								@input="menu_start_date = false"
							></v-date-picker>
							</v-menu>
						</v-col>
						<v-col cols="12" md="2" class="p-0 mx-2 my-0 mt-4">
							<v-menu
								:nudge-right="40"
								transition="scale-transition"
								offset-y
								min-width="auto"
							>
							<template v-slot:activator="{ on }">
								<v-text-field
									v-model="subject.end_date"
									:rules="[rules.required]"
									dense
									label="完了日"
									prepend-icon="mdi-calendar"
									v-on="on"
								></v-text-field>
							</template>
							<v-date-picker
								v-model="subject.end_date"
								no-title
								locale="ja"
								@input="menu_end_date = false"
							></v-date-picker>
							</v-menu>
						</v-col>



					</v-row>
					<v-row class="mx-2 my-0">
						<v-col cols="12" class="px-2 m-0">
							<v-text-field class="m-0 p-0" label="案件名称" id="name" v-model="subject.name" :rules="[rules.required]" ></v-text-field>
						</v-col>
					</v-row>
					<v-row class="mx-2 my-0">
							<v-col cols="12" class="px-2 m-0">
									<v-text-field class="m-0 p-0" label="詳細内容" id="breakdown" v-model="subject.breakdown" ></v-text-field>
							</v-col>
					</v-row>
					<v-container >
							<v-row class="">
									<v-col class="col-auto">
											<v-btn color="primary" @click="close_dialog()"><v-icon left>mdi-close-thick</v-icon>中止</v-btn>
									</v-col>
									<v-col />
									<v-col class="col-auto">
											<!-- <v-btn color="primary" @click="e1 = 2">次へ<v-icon right>mdi-arrow-right</v-icon></v-btn> -->
											<v-btn color="primary" @click="next()">次へ<v-icon right>mdi-arrow-right</v-icon></v-btn>
									</v-col>
							</v-row>
					</v-container>
				</v-stepper-content>
				<v-stepper-content step="2">
						<v-container class="m-0 p-0">
						<v-card-title class="m-0 p-0">
								明細内容選択
								<v-spacer></v-spacer>
								<v-text-field
								v-model="search"
								dense
								append-icon="mdi-magnify"
								label="Search"
								single-line
								hide-details
								class="m-0 p-0"
								></v-text-field>
						</v-card-title>
						<v-divider></v-divider>
						<v-card-text  class="m-0 p-0">
								<v-data-table
										v-model="selected"
										dense
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
										<v-btn color="primary" @click="e1 = 1"><v-icon left>mdi-arrow-left</v-icon>戻る</v-btn>
								</v-col>
								<v-col />
								<v-col class="col-auto">
										<v-btn color="primary" @click="close_dialog()" ><v-icon left>mdi-close-thick</v-icon>中止</v-btn>
								</v-col>
								<v-col />
								<v-col class="col-auto">
										<v-btn color="primary" @click="addTasks()">次へ<v-icon right>mdi-arrow-right</v-icon></v-btn>
								</v-col>
						</v-row>
						</v-container>
				</v-stepper-content>
				<v-stepper-content step="3" outlined>
							<v-row class="m-2 mb-0 pt-3">
								<!-- <v-col cols="12" md="2" class="p-0 mx-2 mt-0 ">
										<v-select class="m-0 p-0" dense :items="clients" item-text="name" item-value="id" label="顧客" id="client_name" v-model="subject.client_id"></v-select>
								</v-col>
								<v-col cols="12" md="2" class="p-0 mx-2 mt-0">
										<v-select class="m-0 p-0" dense :items="subjectClassItems" item-text="name" item-value="no" label="案件分類" id="subject_class" v-model="subject.class"></v-select>
								</v-col> -->

								<!-- <v-col cols="12" md="2" class="p-0 mx-2 mt-0">
										<v-select class="m-0 p-0" dense :items="taxClassItems" item-text="name" item-value="no" label="消費税" id="tax_state" v-model="subject.tax_state"></v-select>
								</v-col>
								<v-col cols="12" md="2" class="p-0 mx-2 mt-1">
										<v-text-field type="number" max=100 min=0 label="架け" id="pay_ratio" v-model="subject.pay_ratio" suffix="％" dense></v-text-field>
								</v-col> -->
								<v-col cols="12" md="2" class="p-0 mx-2 mt-1 ">
										<v-select class="m-0 p-0" dense @change="client_selected" :items="clients" item-text="name" item-value="id" label="元請け" id="client_name" v-model="subject.client_id" ></v-select>
								</v-col>
								<!-- <v-col cols="12" md="2" class="p-0 mx-2 mt-1 ">
										<v-select class="m-0 p-0" dense :items="subContracts" item-text="name" item-value="id" label="下請け" v-model="subject.subcontract_id" ></v-select>
								</v-col>                 -->
								<v-col cols="12" md="2" class="p-0 mx-2 mt-1">
										<v-select class="m-0 p-0" dense :items="subjectClassItems" item-text="name" item-value="no" label="案件分類" id="subject_class" v-model="subject.class" ></v-select>
								</v-col>
								<v-col cols="12" md="2" class="p-0 mx-2 mt-0">
										<v-select class="m-0 p-0" dense :items="processNames" item-text="name" item-value="no" label="状態" id="process" v-model="subject.process" ></v-select>
								</v-col>
						</v-row>
						<v-row no-gutters>
								<!-- <v-col cols="12" md="2" class="p-0 mx-2 mt-4">
										<v-select class="m-0 p-0"  :items="processNames" item-text="name" item-value="no" label="状態" id="process" v-model="subject.process" ></v-select>
								</v-col> -->
								<v-col cols="12" md="2" class="p-0 mx-2 mt-4">
										<v-select class="m-0 p-0"  :items="taxClassItems" item-text="name" item-value="no" label="消費税" id="tax_state" v-model="subject.tax_state"></v-select>
								</v-col>
								<v-col cols="12" md="1" class="p-0 mx-1 mt-4">
										<v-text-field type="number" max=100 min=0 label="支払率" id="pay_ratio" v-model="subject.pay_ratio" :rules="[rules.required]" suffix="％" dense></v-text-field>
								</v-col>
								<v-col cols="12" md="2" class="p-0 mx-1 mt-4">
										<v-select label="支払区分" :items="scheduleClassItems" item-text="name" item-value="no" v-model="subject.pay_status" dense></v-select>                                        
								</v-col>                
						</v-row>
						<v-row no-gutters>
								<v-col cols="12" class="px-2 m-0">
										<v-text-field class="m-0 p-0" dense label="案件名称" id="name" v-model="subject.name" :rules="[rules.required]" ></v-text-field>
								</v-col>
						</v-row>
						<v-row  no-gutters>
								<v-col cols="12" class="px-2 m-0">
										<v-text-field class="m-0 p-0" dense label="詳細内容" id="breakdown" v-model="subject.breakdown" ></v-text-field>
								</v-col>
						</v-row>
						<v-row  no-gutters>
								<v-col cols="12" md="2" class="p-0 mx-2 my-0 ">
										<v-menu
										v-model="menu_start_date"
										:nudge-right="40"
										transition="scale-transition"
										offset-y
										min-width="auto"
										>
												<template v-slot:activator="{ on }">
														<v-text-field
														v-model="subject.start_date"
														:rules="[rules.required]"
														dense
														label="着工日"
														prepend-icon="mdi-calendar"
														v-on="on"
														></v-text-field>
												</template>
												<v-date-picker
														v-model="subject.start_date"
														no-title
														locale="ja"
														@input="menu_start_date = false"
												></v-date-picker>
										</v-menu>
								</v-col>
								<v-col cols="12" md="2" class="p-0 mx-2 my-0 ">
										<v-menu
												v-model="menu_end_date"
												:nudge-right="40"
												transition="scale-transition"
												offset-y
												min-width="auto"
										>
										<template v-slot:activator="{ on }">
												<v-text-field
														v-model="subject.end_date"
														:rules="[rules.required]"
														dense
														label="完了日"
														prepend-icon="mdi-calendar"
														v-on="on"
												></v-text-field>
										</template>
										<v-date-picker
												v-model="subject.end_date"
												no-title
												locale="ja"
												@input="menu_end_date = false"
										></v-date-picker>
										</v-menu>
								</v-col>
						</v-row>
						<v-row  no-gutters>
								<v-col cols="12" class="px-2 m-0">
										<v-text-field class="m-0 p-0" dense label="現場名" id="site_name" v-model="subject.site_name" ></v-text-field>
								</v-col>
						</v-row>
						<v-row  no-gutters>
								<v-col cols="12" class="px-2 m-0">
										<v-text-field class="m-0 p-0" dense label="現場住所" id="site_address" v-model="subject.site_address" ></v-text-field>
								</v-col>
						</v-row>



						<!-- <v-row no-gutters class="">
								<v-col cols="12" md="3" class="px-2">
										<v-text-field label="見積書コメントタイトル１" dense id="message1_title" v-model="subject.message1_title" ></v-text-field>
								</v-col>
								<v-col cols="12" md="9" class="px-2">
										<v-text-field label="見積書コメント１" dense id="message1" v-model="subject.message1" ></v-text-field>
								</v-col>
						</v-row>
						<v-row no-gutters class="">
								<v-col cols="12" md="3" class="px-2">
										<v-text-field label="見積書コメントタイトル２" dense id="message2_title" v-model="subject.message2_title" ></v-text-field>
								</v-col>
								<v-col cols="12" md="9" class="px-2">
										<v-text-field label="見積書コメント２" dense id="message2" v-model="subject.message2" ></v-text-field>
								</v-col>
						</v-row>
						<v-row no-gutters class="">
								<v-col cols="12" md="3" class="px-2">
										<v-text-field label="見積書コメントタイトル３" dense id="message3_title" v-model="subject.message3_title" ></v-text-field>
								</v-col>
								<v-col cols="12" md="9" class="px-2">
										<v-text-field label="見積書コメント３" dense id="message3" v-model="subject.message3" ></v-text-field>
								</v-col>
						</v-row> -->

						<v-data-table
								v-model="selected"
								:headers="headers"
								:items="selected"
								:single-select="false"
								:search="search"
								item-key="id"
								show-select
								class="elevation-1"
						>
								<template v-slot:top>
								</template>
						</v-data-table>
						<v-row class="mt-3">
								<v-col class="col-auto">
										<v-btn color="primary" @click="e1 = 2"><v-icon left>mdi-arrow-left</v-icon>戻る</v-btn>
								</v-col>
								<v-col />
								<v-col class="col-auto">
										<v-btn color="primary" @click="close_dialog()" ><v-icon left>mdi-close-thick</v-icon>中止</v-btn>
								</v-col>
								<v-col />
								<v-col class="col-auto">
										<v-btn color="success" @click="addNew()"><v-icon left>mdi-pencil-plus</v-icon>生成</v-btn>
								</v-col>
						</v-row>
				</v-stepper-content>
			</v-stepper-items>
			<v-stepper-header outlined>
				<v-stepper-step :complete="e1 > 1" step="1" >
					案件
					「着工日」「現場名称」「現場住所」をセットしてください。
				</v-stepper-step>
				<v-divider></v-divider>
				<v-stepper-step :complete="e1 > 2" step="2">
					作業
					「作業内訳」を選んで下さい。後で入力する場合は「次へ」をタップしてください。
				</v-stepper-step>
				<v-divider></v-divider>
				<v-stepper-step step="3">
					完了
				</v-stepper-step>
			</v-stepper-header>
		</v-stepper>
		<MsgBox ref="MsgBox" @enter="msgBox_enter">
			<template v-slot:caption></template>
			<template v-slot:main>
				<v-container>
					<v-row align-content="center" >
						<v-col align="center">
							<div class="text-h6">{{errMessage}}</div>
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>
	</div>
</template>

<script>
    // import BtnGrp from "./ButtonGroup";
		import MsgBox from "../msgbox";
    import calcFunction from '../../functions/calcFunctions'
    const { calcTax, calcTotal } = calcFunction()
    import dbCompanyFunctions from '../../functions/dbCompanyFunctions'
    const { getCompany } = dbCompanyFunctions()
    import moment from 'moment';
    import { mapState, mapGetters } from 'vuex'
    export default {
			props: ["addNewDate"],
			components: {
				MsgBox,
			},
			data: function () {
			return {
				company_id: 0,
				company:[],
				processNames: [],
				taskClassItems: [],
				scheduleClassItems:[],
				subjectProcess: [],
				e1: 1,
				menu_start_date: false,
				menu_end_date: false,
				subject: [],
				tasks: [],
				subContracts:[],
				clients: [],
				client: [],
				products: [],
				search: '',
				singleSelect: false,
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
				people:[],
				rules: {
					required: value => !!value || '省略不可.',
					notNull: value => isNaN(value) || '数値のみ',
				},
				errMessage:"",
			}
    },
    watch: {
			'subject.start_date': function(newVal, oldVal) {
				if (newVal==="") {
					this.subject.start_date = oldVal
				}else if (newVal > this.subject.end_date) {
					this.subject.end_date = newVal
				}
			},
			'subject.end_date': function(newVal, oldVal) {
				if (newVal==="") {
					this.subject.end_date = oldVal
				}else if (newVal < this.subject.start_date) {
					this.subject.start_date = newVal
				}
			},          
    },
    computed: {
    },
    beforeMount() {
			this.e1 = 1
    },
    methods: {
			next() {
				if (this.e1==1 && this.inputCheck())  {
					this.e1=2;
				} else if (this.e1==2) {
					this.e1=3;
				}
			},
			getClients() {
				axios.get('/clients/prime').then((res) => {
					this.clients = res.data;
				});
			},
			getSubContracts() {
				axios.get('/clients/subcontract').then((res) => {
					this.subContracts = res.data;
				});
			},
			getProducts() {
				axios.get('/products').then((res) => {
					this.products = res.data;
				});
			},
			addTasks() {
				this.tasks = [];
				this.selected.forEach(product => {
					this.tasks.push({
						class : 1,
						procss: product.class,
						subject_id : 0,
						name : product.name,
						breakdown : product.breakdown,
						unit : product.unit,
						cost : product.cost,
						volume : product.volume,
						amount : product.cost * product.volume,
						isLabel : product.isLabel,
						// renain_ratio : 100,
					});
				});
				this.e1 = 3
			},
			addNew() {
				if (this.inputCheck()) {
					let total = this.tasks.reduce((sum, element) => {
						return sum + Number(element.amount);
					},0);
					// let tax = calcTax(total,this.subject.tax_state);
					this.subject.expenses = total;
					this.subject.tax = calcTax(total,this.subject.tax_state);
					this.subject.bill_amount = calcTotal(total,this.subject.tax_state);
					var subjectObject = Object.assign({}, this.subject )    // clientObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
					axios.post('/subject/store', { subject: subjectObject, tasks: this.tasks }).then((res) => {
						// this.subject.push(res.data);
						// this.list(this.subject.client_id);
						// this.$emit('refresh',{ value:res.data, mode:"add" });
						// this.close_dialog();						
						this.$emit('enter', res.data);
					});
				}
			},
			list() {
					this.$emit('list');
			},
			client_selected(event) {
				this.client = this.clients.find((v) => v.id === event);
// alert(JSON.stringify(this.client))
				this.subject.tax_state = this.client.tax_state;
				this.subject.pay_ratio = this.client.pay_ratio;
				this.subject.pay_status = this.client.pay_status;      
				this.subject.message1_title = this.client.message1_title;
				this.subject.message2_title = this.client.message2_title;
				this.subject.message3_title = this.client.message3_title;
				this.subject.message4_title = this.client.message4_title;
				this.subject.message1 = this.client.message1;
				this.subject.message2 = this.client.message2;
				this.subject.message3 = this.client.message3;
				this.subject.message4 = this.client.message4;
// alert(JSON.stringify(this.subject))
				// this.getPeople();
			},
			close_dialog() {
				this.$emit('close_dialog');
			},
			reset() {
				this.e1 = 1;
			},
			getPeople() {
// alert(JSON.stringify(this.subject.subcontract_id))
				axios.get('/people/byclient/' + this.subject.subcontract_id).then((res) => {
					this.people = res.data;
					if (this.people.length>0) {
					//     this.subject.person_id = 0;
					// } else {
							this.subject.person_id = this.people[0].id;
					}
				});                
			},
			inputCheck() {
				if (this.subject.client_id === undefined) {
					this.errMessage = "元請が選択されていません。"
				// } else if (this.subject.subcontract_id === undefined) {
				// 	this.errMessage = "下請が選択されていません。"
				// } else if (this.subject.person_id === undefined) {
				// 	this.errMessage = "担当が選択されていません。"
				} else if (this.subject.name === undefined || this.subject.name === "") {
					this.errMessage = "案件名が入力されていません。"
				} else {
					this.errMessage =""
				}
				if (this.errMessage == "") {
					return true
				} else {
					this.$refs.MsgBox.open('ok')
					return false
				}
			},
			msgBox_enter(){
			}
    },
    mounted() {
			this.getClients();
			this.getSubContracts();
			this.getProducts();
			if (this.addNewDate==0) {
				this.addNewDate = moment(new Date()).format('YYYY-M-D')
			}
			this.subject = {
				'class':1,
				'process':10,
				'start_date':this.addNewDate,
				'end_date':this.addNewDate,
			};
    },
    computed: {
			...mapGetters([ 'getSubjectClasses' ]),
			...mapGetters([ 'getTaskClasses' ]),
			...mapGetters([ 'getScheduleClasses' ]),
			...mapGetters([ 'getProcessNames' ]),
			...mapGetters([ 'getTaxClasses' ]),
    },
    created() {
			this.subjectClassItems = this.$store.getters.getSubjectClasses;
			this.taskClassItems = this.$store.getters.getTaskClasses;
			this.scheduleClassItems = this.$store.getters.getScheduleClasses;
			// this.processItems = this.$store.getters.getSubjectProcess;
			this.processNames = this.$store.getters.getProcessNames;
			this.taxClassItems = this.$store.getters.getTaxClasses;
			this.company_id=this.$store.getters.getCompanyId[0].company_id;
    },
  }
</script>

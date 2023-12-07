<template>
	<v-container fluid class="bg-peimary m-0 p-0" >
	  <v-card
			class="mx-auto m-0 p-0 mt-2"
			outlined
		>
			<div class="m-0 p-0 mt-2">【基本情報】</div>
			<v-row no-gutters class="m-0 p-0 mt-4">
				<v-text-field class="d-none" id="index" v-model="values.id"></v-text-field>
				<!-- <v-text-field class="d-none" id="client_id" v-model="values.client_id"></v-text-field> -->
				<v-col cols="12" md="2" class="px-2">
					<v-select :items="subjectClasses" item-text="name" item-value="no" label="区分" v-model="values.class" dense :disabled="disabled_sw"></v-select>
				</v-col>
				<v-col cols="12" md="2" class="px-2">
					<v-select v-if="values.process<20 || role<=2" label="状態" :items="processNames" item-text="name" item-value="no" id="process" v-model="values.process" dense></v-select>
					<v-select v-else label="状態" :items="processNames" item-text="name" item-value="no" id="process" v-model="values.process" dense disabled></v-select>
				</v-col>                
				<v-col cols="12" md="2" class="px-2">
					<v-text-field label="コード" id="code" v-model="values.code" dense></v-text-field>
				</v-col>
				<v-col cols="12" md="3" class="px-2">
					<v-text-field label="発注番号" id="order_code" v-model="values.order_code" dense></v-text-field>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" md="4" class="px-2">
					<v-select :items="mainContracts" item-text="name" item-value="id" label="元請" v-model="values.client_id" dense :disabled="disabled_sw" @change="setClientInfo()"></v-select>
				</v-col>
				<v-col cols="12" md="4" class="px-2">
						<v-select :items="subContracts" item-text="name" item-value="id" label="下請" v-model="values.subcontract_id" dense :disabled="disabled_sw" @change="getPeople()"></v-select>
				</v-col>        
				<v-col cols="12" md="2" class="px-2">
						<v-select :items="people" item-text="name" item-value="id" label="担当" v-model="values.person_id" dense :disabled="disabled_sw"></v-select>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" md="10" class="px-2">
					<v-text-field label="名称" id="name" v-model="values.name" dense style="ime-mode" type="text"></v-text-field>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" md="10" class="px-2">
					<v-text-field label="仕様" id="breakdown" v-model="values.breakdown" dense  type="text"></v-text-field>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" md="6" class="px-2">
					<v-text-field label="現場名" id="site_name" v-model="values.site_name" dense type="text"></v-text-field>
				</v-col>
				<v-col cols="12" md="6" class="px-2">
					<v-text-field label="現場住所" id="site_address" v-model="values.site_address" dense type="text"></v-text-field>
				</v-col>
			</v-row>
			<v-row v-if="status==1" no-gutters class="" >
				<v-col cols="12" md="2" class="px-2" >
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
							label= "着工(予定)"
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
							label= "完了（予定）"
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
				<v-col cols="12" md="2" class="p-0 mx-2 mt-0">
					<v-select label="請求区分" :items="scheduleClassItems" item-text="name" item-value="no" v-model="values.pay_status" dense :disabled="disabled_sw"></v-select>              
				</v-col>            
				<v-col cols="12" md="1" class="px-2">
					<v-text-field v-if="values.class==1 " label="請求率" type="number" max=100 min=0 id="pay_ratio" v-model="values.pay_ratio" suffix="％" dense :disabled="disabled_sw"></v-text-field>
					<v-text-field v-else label="請求率" type="number" max=100 min=0 id="pay_ratio" v-model="values.pay_ratio" suffix="％" dense disabled></v-text-field>
				</v-col>
			</v-row>
			<!-- <div v-if="values.process<30 || role<=2"> -->
				<div v-if="values.v_tasks.length==0">
					<v-row no-gutters class="">
						<v-col cols="12" md="2" class="px-2">
							<v-text-field type="number" label="請負金額" id="expenses" v-model="values.expenses" dense :disabled="disabled_sw"></v-text-field>
						</v-col>
						<v-col cols="12" md="2" class="px-2">
							<v-text-field type="number" label="値引き額" id="discount" v-model="values.discount" dense :disabled="disabled_sw"></v-text-field>
						</v-col>
						<v-col cols="12" md="2" class="px-2">
							<v-text-field type="number" label="消費税額" id="tax" v-model="tax" dense :disabled="disabled_sw"></v-text-field>
						</v-col>
						<v-col cols="12" md="1" class="px-2">
							<v-select label="税区分" :items="taxClassItems" item-text="name" item-value="no" id="tax_state" v-model="values.tax_state" dense :disabled="disabled_sw"></v-select>
						</v-col>
						<v-col cols="12" md="2" class="px-2">
							<v-text-field type="number" label="請求額" id="bill_amount" v-model="bill_amount" dense :disabled="disabled_sw"></v-text-field>
						</v-col>
						<v-col cols="12" md="2" class="px-2">
							<v-text-field type="number" label="支払額（税別）" v-model="values.amount_subcontract" dense :disabled="disabled_sw"></v-text-field>
						</v-col>                        
					</v-row>
				</div>
				<div v-else>
					<v-row no-gutters class="">
						<v-col cols="12" md="2" class="px-2">
							<v-text-field label="請負金額" type="number" id="expenses" readonly v-model="expenses" dense :disabled="disabled_sw"></v-text-field>
						</v-col>
						<v-col cols="12" md="2" class="px-2">
							<v-text-field label="値引き" type="number" id="discount" v-model="values.discount" dense :disabled="disabled_sw"></v-text-field>
						</v-col>
						<v-col cols="12" md="2" class="px-2">
							<v-text-field label="消費税" type="number" id="tax" readonly v-model="tax" dense :disabled="disabled_sw"></v-text-field>
						</v-col>
						<v-col cols="12" md="1" class="px-2">
							<v-select label="税区分" id="tax_state" v-model="values.tax_state" :items="taxClassItems" item-text="name" item-value="no" dense :disabled="disabled_sw"></v-select>
						</v-col>
						<v-col cols="12" md="2" class="px-2">
							<v-text-field label="請求額" type="number" id="bill_amount" readonly v-model="bill_amount" dense :disabled="disabled_sw"></v-text-field>
						</v-col>
						<v-col cols="12" md="2" class="px-2">
							<v-text-field type="number" label="支払額（税別）" v-model="values.amount_subcontract" dense :disabled="disabled_sw"></v-text-field>
						</v-col>    
					</v-row>
				</div>
			<!-- </div> -->

			<!-- <div v-else>
				<v-row no-gutters class="" disabled>
					<v-col cols="12" md="2" class="px-2">
						<v-text-field type="number" label="施工費" id="expenses" v-model="values.expenses" dense disabled></v-text-field>
					</v-col>
					<v-col cols="12" md="2" class="px-2">
						<v-text-field type="number" label="値引き額" id="discount" v-model="values.discount" dense disabled></v-text-field>
					</v-col>
					<v-col cols="12" md="2" class="px-2">
						<v-text-field type="number" label="消費税額" id="tax" v-model="tax" dense disabled></v-text-field>
					</v-col>
					<v-col cols="12" md="1" class="px-2">
						<v-select label="税区分" :items="taxClassItems" item-text="name" item-value="no" id="tax_state" v-model="values.tax_state" dense disabled></v-select>
					</v-col>
					<v-col cols="12" md="2" class="px-2">
						<v-text-field type="number" label="請求額" id="bill_amount" v-model="bill_amount" dense disabled></v-text-field>
					</v-col>
					<v-col cols="12" md="2" class="px-2">
							<v-text-field type="number" label="支払額（税別）" v-model="values.amount_subcontract" dense></v-text-field>
						</v-col>    
				</v-row>
			</div> -->
		</v-card>
		
		<v-card
			class="mx-auto m-0 p-0 mt-2"
			outlined
		>
			<v-row no-gutters class="mt-2">
				<v-expansion-panels accordion flat class="m-0 p-0" v-model="panel">
					<v-expansion-panel>
						<v-expansion-panel-header class="m-0 p-0">
							【ファイル情報】
						</v-expansion-panel-header>
						<v-expansion-panel-content class="m-0 p-0">
							<FileManeger ref="FileManeger" :image_class="1" :link_id="values.id" />
						</v-expansion-panel-content>
					</v-expansion-panel>
				</v-expansion-panels>
			</v-row>
		</v-card>
		<v-card
			class="mx-auto m-0 p-0 mt-2"
			outlined
		>
			<v-row no-gutters class="mt-2">
				<v-expansion-panels accordion flat class="m-0 p-0" v-model="panel">
					<v-expansion-panel>
						<v-expansion-panel-header class="m-0 p-0">
							【明細情報】
						</v-expansion-panel-header>
						<v-expansion-panel-content class="m-0 p-0">
							<CyberTable
								ref="taskList"
								:key="values.v_tasks.id"
								:headers="task_headers"
								:details="values.v_tasks"
								:show_select="true"
								:show_add="true"
								:show_copy="true"
								:visible_group="true"
								:disabled="table_disabled"
								@click_TableRow="click_TableRow"
								@add="create_task"
								@copy="task_copy"
								@group="task_group"
							/>
						</v-expansion-panel-content>
					</v-expansion-panel>
				</v-expansion-panels>
			</v-row>
		</v-card>
		<v-card
			class="mx-auto m-0 p-0 mt-2"
			outlined
		>
			<v-row no-gutters class="mt-2">
				<v-expansion-panels accordion flat class="m-0 p-0">
					<v-expansion-panel>
						<v-expansion-panel-header class="m-0 p-0">
							【その他情報】
						</v-expansion-panel-header>
						<v-expansion-panel-content class="m-0 p-0">
							<v-row no-gutters class="">
								<v-col cols="12" md="3" class="px-2">
									<v-text-field label="見積書コメントタイトル１" id="message1_title" v-model="values.message1_title" dense></v-text-field>
								</v-col>
								<v-col cols="12" md="9" class="px-2">
									<v-text-field label="見積書コメント１" id="message1" v-model="values.message1" dense></v-text-field>
								</v-col>
							</v-row>
							<v-row no-gutters class="">
								<v-col cols="12" md="3" class="px-2">
									<v-text-field label="見積書コメントタイトル２" id="message2_title" v-model="values.message2_title" dense></v-text-field>
								</v-col>
								<v-col cols="12" md="9" class="px-2">
									<v-text-field label="見積書コメント２" id="message2" v-model="values.message2" dense></v-text-field>
								</v-col>
							</v-row>
							<v-row no-gutters class="">
								<v-col cols="12" md="3" class="px-2">
									<v-text-field label="見積書コメントタイトル３" id="message3_title" v-model="values.message3_title" dense></v-text-field>
								</v-col>
								<v-col cols="12" md="9" class="px-2">
									<v-text-field label="見積書コメント３" id="message3" v-model="values.message3" dense></v-text-field>
								</v-col>
							</v-row>
							<v-row no-gutters class="">
								<v-col cols="12" md="3" class="px-2">
									<v-text-field label="注文書コメントタイトル" v-model="values.message4_title" dense></v-text-field>
								</v-col>
								<v-col cols="12" md="9" class="px-2">
									<v-text-field label="注文書コメント" v-model="values.message4" dense></v-text-field>
								</v-col>
							</v-row>
							<v-row no-gutters class="">
								<v-col cols="12"  class="px-2">
									<v-text-field label="備考" v-model="values.memo" dense></v-text-field>
								</v-col>
							</v-row>
						</v-expansion-panel-content>
					</v-expansion-panel>
				</v-expansion-panels>
			</v-row>
		</v-card>
		<v-row class="m-0 p-0">
			<v-col v-if="smode=='upd'" class="col-auto">
				<v-btn depressed small fab @click="del()">
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

		<!-- タスク編集ダイアログ -->
		<CyberDialog ref="TaskDialog" >
			<template v-slot:caption>{{caption}}</template>
			<template v-slot:main>
				<EditTask
					ref="EditTask"
					:values="task"
					:mode="mode"
					@enter="enter_task"
					@close="close_task"
					@add="add_task"
					@del="remove_task"
				/>
			</template>
		</CyberDialog>
		<!-- タスク追加ダイアログ -->
		<CyberDialog ref="TaskCreateDialog" >
			<template v-slot:caption>{{caption}}</template>
			<template v-slot:main>
				<CreateTask
					ref="CreateTask"
					:key="create_key"
					@enter="enter_CreateTask"
				/>
			</template>
		</CyberDialog>
		<!-- 一括編集ダイアログ -->
		<CyberDialog ref="GroupDialog" >
			<template v-slot:caption>一括編集</template>
			<template v-slot:main>
				<Group @enter="enter_GroupDialog" />
			</template>
		</CyberDialog>

		<MsgBox ref="MsgBox" @enter="msgBox_enter">
			<template v-slot:caption></template>
			<template v-slot:main>
				<v-col align="center">
					<div class="text-h6">{{errMessage}}</div>
				</v-col>
			</template>			
		</MsgBox>
	</v-container>
</template>
<script>
    import { mapState, mapGetters } from 'vuex'
    import EditTask from "./EditTask";
    import CyberTable from "../CyberTable";
    import CyberDialog from "../CyberDialog";
    import CreateTask from "../task/Create";
    import Group from "../task/Group";
    import FileManeger from "../FileManeger";
    import CyberDate from "../CyberDate";
    import MsgBox from "../msgbox";
    import commFunctions from '../../functions/commFunctions';
    const { numToComma, commaToNum, swStatusFlag, getStatusFlag, calcTax,} = commFunctions();
    export default {
		components: {
			EditTask,
			CyberTable,
			CyberDialog,
			FileManeger,
			CreateTask,
			Group,
			CyberDate,
			MsgBox,
		},
		props: ["values","smode","role","client","status"],
		data: function () {
			return {
				panel:[],
				items: 5,
				selectTableRowNo:-1,
				create_key:false,
				subjectClasses:[],
				taxClassItems:[],
				scheduleClassItems:[],
				processNames:[],
				mode:'edit',
				task: [],
				task_headers : [
					{name:"name",label:"名称",isLabelItem: 1},
					{name:"breakdown",label:"規格・仕様",isLabelItem: 1},
					{name:"cost",label:"単価",isLabelItem: 0},
					{name:["volume","unit_name"],label:"数量",isLabelItem: 0},
					{name:"amount",label:"金額",isLabelItem: 0},
					{name:"memo",label:"備考",isLabelItem: 0},
				],
				commandItems: [
					{ title: '一　括' ,name:'group', icon:'mdi-pencil-box-multiple-outline' },
				],
				task:[],
				caption:"",
				dateMenu1:false,
				dateMenu2:false,
				dateMenu3:false,
				mainContracts: [],
				subContracts: [],
				people:[1],
				errMessage:"",
			}
		},
		created() {
				//
			this.subjectClasses = this.$store.getters.getSubjectClasses;
			this.taxClassItems = this.$store.getters.getTaxClasses;
			this.scheduleClassItems = this.$store.getters.getScheduleClasses;
			this.processNames = this.$store.getters.getProcessNames;
	
		},
		mounted() {
// alert(this.status)
			this.getMainContractor();
			this.getSubContracts();
			this.getPeople();
			this.$refs.FileManeger.fileDownload();
		},
		watch: {
			'values.class': function(newValue,oldValue) {
				if (newValue!=1 ) {
						this.values.pay_ratio = 100;
				}
			},
			'values.pay_ratio': function(newValue,oldValue) {
				if (isNaN(newValue) ) {
					this.values.pay_ratio = 0;
				}
			},
			'values.start_date': function(newVal, oldVal) {
				if (newVal==="") {
					this.values.start_date = oldVal
				}else if (newVal > this.values.end_date) {
					this.values.end_date = newVal
				}
			},
			'values.end_date': function(newVal, oldVal) {
				if (newVal==="") {
					this.values.end_date = oldVal
				}else if (newVal < this.values.start_date) {
					this.values.start_date = newVal
				}
			}, 
			'values.process': function(newVal, oldVal) {
			},
		},
		computed: {
			...mapGetters([ 'getSubjectClasses' ]),
			...mapGetters([ 'getTaxClasses' ]),
			...mapGetters([ 'getScheduleClasses' ]),
			...mapGetters([ 'getProcessNames' ]),
			url: function() {
				return function(n) {
					if(this.images[n]===null){
						return;
					} else {
						return URL.createObjectURL(this.images[n]);
					}
				}
			},
			table_disabled: function() {
				return this.values.process >= 30
			},
			expenses: function() {
				let amount = 0;
				let items = this.values.v_tasks;
				if (items.length>0) {
					items.forEach(item => {
							amount += item.cost * item.volume;
					});
					this.values.expenses = amount;
				} else {
					amount = this.values.expenses;
				}
				return Math.round(amount);
			},
			tax: function() {
				if (this.expenses > 0) {
						let amount = this.expenses - this.values.discount;
						let totals = calcTax(amount,this.values.tax_state)
						this.values.tax = totals.tax;
				} else {
						this.values.tax = 0;
				}
				return this.values.tax ;
			},
			bill_amount: function() {
				let amount = this.expenses - this.values.discount;
				if (this.values.tax_state == 2) {
						amount += this.tax;
				}
				this.values.bill_amount = amount;
				return amount;
			},
			disabled_sw: function() {
				if (this.values.process<30 || this.role<=2) {
					return false
				} else {
					return true;
				}
				// return this.values.process > 9;
			} 
		},
		methods: {
			enter() {
				const subject = Object.assign({},this.values)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
				const tasks = Object.assign({},this.values.v_tasks)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応               
				if (this.smode == 'add') {
					const para = { subject: subject, tasks: tasks }
					if (this.inputCheck()) {
						axios.post('/subject/store', para).then((res) => {
							if (res.status==200) {
								this.$emit('enter',{ value:res.data, mode:this.smode });
							}
						});
					}
				} else {
					const para = { subject: subject, tasks: tasks, mode:'with' }
					axios.post('/subject', para ).then((res) => {
						if (res.status==200) {
							this.$refs.FileManeger.fileUpload();
							this.$emit('enter',{ value:res.data, mode:this.smode });
						}
					});
				}
			},
			del() {
				this.errMessage="削除してよろしいですか？"
				this.$refs.MsgBox.open('del')
			},
			msgBox_enter(para) {
// alert(JSON.stringify(this.values))
				if (para='del') {
					axios.get('/schedule/subjectId/'+this.values.id).then((res)=>{
// alert(res.data.length)
						if (res.data.length==1) {
							axios.delete('/subject/' + this.values.id).then((res) => {
								if (res.data.status) {
									this.$emit('remove');
								} else {
									if (res.data.value.errorInfo[0]=="23000") {
										this.$emit('error', "他の情報で参照されているため、削除できません。" );
									} else {
										this.$emit('error', "削除できませんでした。(" + res.data.value.errorInfo[0] + ")" );
									}
								}
							})
						} else if (res.data.length==2) {
							axios.delete('/schedule/' + this.values.schedule_id).then((res) => {
								if (res.data.status) {
									this.$emit('remove');
								} else {
									if (res.data.value.errorInfo[0]=="23000") {
										this.$emit('error', "他の情報で参照されているため、削除できません。" );
									} else {
										this.$emit('error', "削除できませんでした。(" + res.data.value.errorInfo[0] + ")" );
									}
								}
							})
						}
					})
				}
			},
			click_TableRow(e) {
				this.selectTableRowNo = e.rowNo;
				this.task = JSON.parse(JSON.stringify(e.value))
				this.mode = "edit"
				this.caption ="明細-編集"
				this.$refs.TaskDialog.open({
					isFullscreen:false
				});
			},
			create_task() {
				this.create_key = !this.create_key
				this.caption = "商品選択"
				this.$refs.TaskCreateDialog.open({
					isFullscreen:true
				});
			},
			enter_CreateTask(e) {
				e.forEach(val => {
					let newVal =
					{
						'id':-1,
						'class' : 1,
						'product_class' : val.class,
						'subject_id' : this.values.id,
						'name' : val.name,
						'breakdown' : val.breakdown,
						'volume' : val.volume,
						'unit' : val.unit,
						'cost' : val.cost,
						'amount' : val.volume * val.cost,
						'start_date' : this.values.start_date,
						'end_date' : this.values.end_date,
						'isLabel' : val.isLabel,
					}
					this.values.v_tasks.push(newVal)
				})
				this.$refs.TaskCreateDialog.close()
			},
			task_copy(e) {
				this.mode = "add"
				this.caption ="明細-追加";
				this.selectTableRowNo = e.rowNo;
				this.task = { ...e.val };
				this.task.id=-1;
				this.$refs.TaskDialog.open({
					isFullscreen:false
				});
			},
			task_group(e) {
				this.selectItems = e;
				this.$refs.GroupDialog.open({
					isFullscreen:true
				});
			},
			enter_GroupDialog(e) {
				for (let key in e) {
					for (let i = 0; i<this.selectItems.length; i++) {
						if (this.selectItems[i]) {
							this.values.v_tasks[i][key] = e[key]
							if (key=='cost' || key=='volume') {
								this.values.v_tasks[i]['amount'] = this.values.v_tasks[i]['cost'] * this.values.v_tasks[i]['volume'];
							}
						}
					}
				}
				this.$refs.GroupDialog.close();
			},
			enter_task(val) {
				this.values.v_tasks.splice(this.selectTableRowNo  , 1,val);
				this.$refs.TaskDialog.close();
			},
			add_task(e) {
				this.values.v_tasks.push(e);
				this.$refs.TaskDialog.close();
			},
			close_task() {
				this.$refs.TaskDialog.close();
			},
			remove_task() {
				this.$refs.taskList.remove();
			},
			getMainContractor() {
				axios.get('/clients/prime').then((res) => {
					this.mainContracts = res.data;
				});
			},
			getSubContracts() {
				axios.get('/clients/subcontract').then((res) => {
					this.subContracts = res.data;
				});
			},
			getPeople() {
				axios.get('/people/byclient/' + this.values.subcontract_id).then((res) => {
					this.people = res.data;
				});                
			},
			setClientInfo() {
				const client = this.mainContracts.find((item) => item.id === this.values.client_id);
				if (this.values.class==1) {
					this.values.pay_status = client.pay_status;
					this.values.pay_ratio = client.pay_ratio;
				}
				this.values.tax_state = client.tax_state;
				this.values.message1_title = client.message1_title;
				this.values.message2_title = client.message2_title;
				this.values.message3_title = client.message3_title;
				this.values.message4_title = client.message4_title;
				this.values.message1 = client.message1;
				this.values.message2 = client.message2;
				this.values.message3 = client.message3;
				this.values.message4 = client.message4;
			},
			inputCheck() {
				if (this.values.client_id === undefined || this.values.client_id === 0) {
					this.errMessage = "元請が選択されていません。"
				} else if (this.values.name === undefined || this.values.name === "") {
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
		},
    }
</script>
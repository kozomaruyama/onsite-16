<template>
	<div>
		<CyberDataTable
			ref="CyberDataTable"
			:filterItems="filterItems"
			:commandItems="commandItems"
			:printItems="printItems"
			:headers="headerItems"
			:items="tableItems"
			:searchText="searchText"
			:show_select="false"
			:visible_group="true"
			:visible_print="false"
			:visible_add="true"
			:visible_copy="false"		
			:visible_account="visibleAccount"	
			@search="search"
			@filter="filter"
			@command="command"
			@print="print"
			@click_TableRow="click_TableRow"
			@add="add"
			@copy="copy"
			@create_account="create_account"
			@edit_account="edit_account"
		>
			<template v-slot:title>担当一覧</template>
		</CyberDataTable>
		<!-- 編集ダイアログ -->
		<CyberDialog ref="EditDialog" >
			<template v-slot:caption>{{caption}}</template>
			<template v-slot:main>
				<!-- 担当者情報 -->
				<People 
					:values="people" 
					:key="ref_key" 
					:mode="mode"  
					@enter="enter_people" 
					@remove="remove_people" 
					@close="close" 
					@error="error" 
				/>
			</template>
		</CyberDialog>
		<!-- アカウント -->
		<CyberDialog ref="AccountDialog">
				<template v-slot:caption>{{caption}}</template>
				<template v-slot:main>
					<Account 
						:key="ref_key_account" 
						:account="account" 
						:mode="mode" 
						:user="user"
						@enter="enter_account" 
						@remove="remove_account" 
						@error="error" 
					/>
				</template>
		</CyberDialog>
		<MsgBox ref="MsgBox" :style="ok" @enter="msgBox_enter">
			<template v-slot:caption></template>
			<template v-slot:main>
				<v-container>
					<v-row align-content="center" >
						<v-col align="center">
							<div class="text-h6">権限がありません</div>
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>	

	</div>
</template>
<script>
	import CyberDataTable from "../CyberDataTable";
	import CyberDialog from "../CyberDialog";
	import People from "./People";
	import Account from "./Account";
	import MsgBox from "../msgbox";
	export default {
		components: {
			CyberDataTable,
			CyberDialog,
			People,
			Account,
			MsgBox,
		},
		props: ["dessertItems","user"],
		data: function () {
			return {
				ref_key: true,
				ref_key_account: true,
				searchText: "",
				selectItems: [],
				headerItems: [
					{ text: 'コード', value: 'code', groupable:false },
					{
						text: '氏名',
						align: 'start',
						sortable: true,
						value: 'name',
					},
					{ text: '区分', value: 'client_class' },
					{ text: '所属', value: 'belong' },
					{ text: '連絡先', value: 'tel1', groupable:false},
					{ text: 'メール', value: 'mail', groupable:false},
					{ text: '', value: 'actions', sortable: false, align: 'right'  },
				],
				tableItems: [],
				// フィルターアイテム
				filterItems: [],
				//
				subjectDialog:false,
				AccountDialog:false,
				// コマンドアイテム
				commandItems: [
					{ title: '新規' ,name:'create'},
					{ title: '一括' ,name:'groupUpdate' },
				],
				// 印刷アイテム
				printItems: [
					{ title: '商品一覧',name:'productsList' },
				],
				people:[],
				selectTableRowNo:-1,
				caption:"",
				mode:"edit",
				account:[],
				visibleAccount:true,

			}
		},
		created() {
			this.tableItems = this.dessertItems;
		},
		mounted() {
// alert(JSON.stringify(this.dessertItems))
		},
		methods: {
			click_TableRow(e) {
// alert(JSON.stringify(e.value.role))
// 				if (e.value.role>3 || e.value.role == -1) {
					this.selectTableRowNo = e.rowNo;	
					if (e.value.role<this.user.role && e.value.role!=-1) {
						this.$refs.MsgBox.open('msg')
					} else {
						axios.get('/people/' + e.value.id).then((res) => {
							if (res.data=="") {
								alert("他の利用者により削除されています。")
								this.$refs.dataTable.refrash();
							} else {
								this.ref_key = !this.ref_key;
								this.people=res.data;
								this.mode = "edit"
								this.caption ="担当者-編集"
								this.$refs.EditDialog.open({
									isFullscreen:false
								});
							}
						});
					}
				// } else {
				// 	this.$refs.MsgBox.open('msg')
				// }
			},
		
			add() {
// alert(JSON.stringify(this.user))
				this.people={'color':'black'};
				this.mode = "create"
				this.caption ="担当者-新規"
				this.$refs.EditDialog.open({
					isFullscreen:false
				});
			},
			copy(e) {
					// this.selectTableRowNo = e.rowNo;
					// var newTask = { ...e.val };
					// this.create(newTask);
			},
			enter_people(e) {
// alert(JSON.stringify(e))
				switch (e.mode) {
					case "create":
						// this.subject.push(e.value);
// alert(JSON.stringify(e.value))
							// this.tableItems.splice(this.selectTableRowNo, 0, e.value);
							this.tableItems.push(e.value);
							this.$refs.EditDialog.close();
						return;
					case "upd":
// alert(JSON.stringify(e))
						this.tableItems.splice(this.selectTableRowNo  , 1, e.value);
						this.$refs.EditDialog.close();
						return;
				}
			},
			remove_people(e) {
				this.tableItems.splice(this.selectTableRowNo  , 1);
				this.$refs.EditDialog.close();
			},
			close() {
				this.$refs.EditDialog.close();
			},
			error(vals) {
				let error = ""
				vals.forEach(function(val){
					error += val + "\n"
				})	
				alert(error)
			},
			command(val){
					// switch (val) {
					//     case 'create':
					//         this.selectTableRowNo = this.tableItems.length
					//         this.create([]);
					//         break;
					//     case 'group':
					//         break;
					// }
			},
			create_account(e) {
// alert(JSON.stringify(this.user))
// alert(JSON.stringify(e.value))		
				// if (e.value.role != -1 && e.value.role < this.user.role ) {
				// if (this.user.role > 2 && this.user.person_id != e.value.id) {
				if (this.user.role > 2) {
					this.$refs.MsgBox.open('msg')
				} else {				
					let role = 21
					if (e.value.class>10) {
						if (e.value.class>20) {
							// 自社
							role = 5
						} else {
							// 下請
							role = 11
						}
					}
					this.ref_key_account = !this.ref_key_account;
					this.selectTableRowNo = e.rowNo
					this.account={id:"",name:"",password:"",person_id:e.value.id,role:role};
					this.mode = "create"
					this.caption ="(" + e.value.name + ") アカウント登録"
					this.$refs.AccountDialog.open({
						width:"600px",
						isFullscreen:false
					});
				}
			},
			edit_account(e) {
// alert(e.value.role + "," + this.user.role )				
				if (e.value.role < this.user.role) {
					this.$refs.MsgBox.open('msg')
				} else {
// alert(JSON.stringify(e.value))
// alert(JSON.stringify(this.user))
// alert(e.value.id + "," + this.user.person_id);
					if (e.value.id == this.user.person_id || this.user.role < 3) {
						this.selectTableRowNo = e.rowNo
						axios.get('/account/' + e.value.id).then((res) => {
							if (res.data=="") {
								alert("他の利用者により削除されています。")
								this.$refs.dataTable.refrash();
							} else {
								this.ref_key_account = !this.ref_key_account;
								this.account=res.data;
								this.mode = "edit"
								this.caption ="(" + e.value.name + ") アカウント編集"
								this.$refs.AccountDialog.open({
									width:"600px",
									isFullscreen:false
								});
							}
						});
					} else {
						this.$refs.MsgBox.open('msg')
					}
				}
			},
			msgBox_enter(para) {
			},				
			enter_account(e) {
				let item = this.tableItems[this.selectTableRowNo]
				switch (e.mode) {
					case "add":
						item.account_status = 0
						item.role = e.value.role
						this.tableItems.splice(this.selectTableRowNo , 1, item);
						this.$refs.AccountDialog.close();
						return;
					// case "del":
					// 	item.account_status = null
					// 	item.role = -1
					// 	this.tableItems.splice(this.selectTableRowNo  , 1, item);
					// 	this.$refs.AccountDialog.close();
					// 	return;
					case "upd":
						this.$refs.AccountDialog.close();
						return;
				}
			},
			remove_account() {
				let item = this.tableItems[this.selectTableRowNo]
				item.account_status = null
				item.role = -1
				this.tableItems.splice(this.selectTableRowNo  , 1, item);
				this.$refs.AccountDialog.close();				
			},
			search() {},
			filter() {},
			print(e) {
// alert(JSON.stringify(e));
            },
			edit() {},
			itemSelected() {},
			remove() {},
			group() {}  ,
		},
	}
</script>

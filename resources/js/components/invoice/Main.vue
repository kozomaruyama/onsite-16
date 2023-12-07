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
				<CyberSideMenu
					class="p-0 pr-2"
					:items="sideMenu_items"
					@click_category="click_sideMenu_category"
					@click_item="click_sideMenu_item"
				/>
			</template>
			<!-- メイン -->
			<template v-slot:main>
				<!-- データテーブル -->
				<CyberDataTable
					ref="CyberDataTable"
					:filterItems="filterItems"
					:commandItems="commandItems"
					:printItems="printItems"
					:print2Items="print2Items"
					:headers="headerItems"
					:items="tableItems"
					:searchText="searchText"
					:show_expand=true
					:visible_print2="true"
					@search="search"
					@filter="filter"
					@print="print"
					@click_TableRow="click_invoice"
					@item_expanded="item_expanded"
					@select_TableRow="select_TableRow"
				>
					<template v-slot:title>請求管理{{ headerTitle }}</template>
					<template v-slot:command>
							<v-btn class="mx-2" depressed small fab color="yellow darken-2" @click="locate">
									<v-icon>mdi-calculator-variant-outline</v-icon>
							</v-btn>
					</template>
					<template>
						<CyberTable
							ref="masterList"
							:key="masterList_key"
							:headers="master_headers"
							:details="master_details"
							:printItems="print3Items"
							:show_select="true"
							:visible_print="true"
							@click_TableRow="click_master"
							@reOrder="reOrder_masterList"
							@print="print"
						/>
					</template>
				</CyberDataTable>
			</template>
		</CyberMain>
		<!-- 請求編集ダイアログ -->
		<!-- <CyberDialog ref="EditInvoice">
			<template v-slot:caption>請求編集</template>
			<template v-slot:main>
				<EditInvoice
					:invoice="invoice"
					@enter="enter_EditInvoice"
					@close="$refs.EditInvoice.close()"
					@remove="remove_EditInvoice"
				/>
			</template>
		</CyberDialog> -->
		<!-- 請求マスタ編集ダイアログ -->
		<CyberDialog ref="EditMasterDialog">
			<template v-slot:caption>請求マスタ編集</template>
			<template v-slot:main>
				<EditMaster
					ref="EditMaster"
					:key = "EditMaster_key"
					:invoice_master="invoice_master"
					:invoice_details="invoice_details"
					:bill_total="bill_total"
					:subject="subject"
					@close="close_EditMaster"
					@enter="entrt_EditMaster"
					@remove="remove_EditMaster"
				/>
			</template>
		</CyberDialog>
		<!-- 締処理ダイアログ -->
		<CyberDialog ref="ClosinDdialog" >
			<template v-slot:caption>締処理</template>
			<template v-slot:main>
				<Closing
					:clients="clients"
					:key="closing_key"
					@enter="enter_CreateInvoice"
					@close_dialog="$refs.ClosinDdialog.close()"
				/>
			</template>
		</CyberDialog>
		<!--  -->
		<CyberDialog ref="GroupDialog" >
			<template v-slot:caption>一括編集</template>
			<template v-slot:main />
		</CyberDialog>
	</div>
</template>

<script>
	import CyberMain from "../CyberMain";
	import CyberDialog from "../CyberDialog";
	import CyberHeader from "../CyberHeader";
	import CyberDataTable from "../CyberDataTable";
	import CyberTable from "../CyberTable";
	import CyberSideMenu from "../CyberSideMenu";
	import Closing from "./Closing";
	import EditInvoice from "./EditInvoice";
	import EditMaster from "./EditMaster";
	import commFunctions from '../../functions/commFunctions';
	const { zeroPadding } = commFunctions();
	export default {
		components: {
			CyberMain,
			CyberDialog,
			CyberHeader,
			CyberDataTable,
			CyberTable,
			CyberSideMenu,
			Closing,
			EditInvoice,
			EditMaster,
		},
		props: ["invoices","clients","calendars"],
		// props: ["invoices","clients"],
		data: function () {
			return {
				//
				masterList_key:true,
				closing_key: true,
				EditMaster_key: true,
				//
				headerTitle:"",
				tableItems:[],
				commandItems_editMaster:[],
				searchText:[],
				invoice:[],
				invoice_masters:[],
				invoice_master:[],
				invoice_details:[],
				bill_total:0,
				subject:[],
				// clients:[],
				products:[],
				selectTableRowNo:-1,
				expandedTableRowNo:-1,
				selectMasterRowNo:-1,
				//
				header_customer: [
					{ text: '締切日', value: 'closing_date' },
					{ text: '請求日', value: 'issue_date' , sortable: false, groupable:false},
					{ text: '入金日', value: 'payment_date' , sortable: false, groupable:false},
					{ text: '請求額', value: 'bill_amount' , groupable:false, align:'end' },
                    { text: '', value: 'actions', sortable: false, align: 'end'  },
					{ text: '', value: 'data-table-expand' },
				],
				//
				header_date : [
					{ text: '顧客', value: 'client_name' },
					{ text: '締切日', value: 'closing_date' },
					{ text: '請求日', value: 'issue_date' , sortable: false, groupable:false},
					{ text: '入金日', value: 'payment_date' , sortable: false, groupable:false},
					{ text: '請求額', value: 'bill_amount' , groupable:false, align:'end' },
                    { text: '', value: 'actions', sortable: false, align: 'end'  },
					{ text: '', value: 'data-table-expand' },
				],
				//
				headerItems:[],
				// フィルターアイテム
				filterItems: [],
				// コマンドアイテム
				commandItems: [
					{ title: '締処理' ,name:'closing', icon:'mdi-calculator-variant-outline'},
					{ title: '一　括' ,name:'group', icon:'mdi-pencil-box-multiple-outline' },
				],
				// 印刷アイテム
				printItems: [
					{ title: '請求書',name:'invoice', icon:'mdi-receipt-outline'},
				],
				// 印刷２アイテム
				print2Items: [
					{ title: '請求書',name:'invoice', icon:'mdi-receipt-outline'},
				],
				// 印刷３アイテム
				print3Items: [
					{ title: '請求明細',name:'detail', icon:'mdi-receipt-outline'},
				],
				// ダイアログ
				dialog: false,
				//
				sideMenu_items: [],
				master_headers: [
					{ name:'name',label:'案件' },
					{ name:"breakdown",label:"規格・仕様" },
					{ name:"subject_bill_amount",label:"単価" },
					{ name:"ratio",label:"数量(%)" },
					{ name:"bill_amount",label:"金額" },
					{ name:"note",label:"備考" },
				],
				master_details:[],
                masterList_selected:[],
		    }
		},
		computed: {
			display_height: function() {
				return this.inner_height-60;
			}
		},
		methods: {
			locate: function() {
				location.href='/bill';
			} ,   	
			init() {
// alert(JSON.stringify(this.invoices));
				this.headerItems = this.header_date;
				// this.tableItems = this.invoices;
				let chileItems = [];
				this.calendars.forEach(calendar => {
					chileItems.unshift(
						{ text: calendar.year+"年"+calendar.month+"月", icon: 'mdi-label', category:'date', key:calendar.year+"-"+zeroPadding(calendar.month,2), value:calendar })
					// { text: calendar.year+"年"+calendar.month+"月", icon: 'mdi-label', category:'date', key:calendar.year+"-"+calendar.month, value:calendar })
				});
				this.sideMenu_items.push({
					text: '年月',
					icon: 'mdi-clock',
					key: 'date',
					action: 'mdi-ticket',
					active: true,
					items: chileItems,
				})
				chileItems = [];
				this.clients.forEach(client => {
					chileItems.push({
						text: client.name,
						icon: 'mdi-label',
						key:client.id,
						category:'client',
						value:client
					})
				});
				this.sideMenu_items.push({
					text: '顧客',
					icon: 'mdi-account',
					key:'client',
					action: 'mdi-silverware-fork-knife',
					items: chileItems,
				})
// alert(JSON.stringify(this.category_items));
			},
			// async fetchUsers (item) {
			// 	switch (item.class) {
			// 		case 'date':
			// 			let day = getDate(new Date('2020','12','1'));
			// 			for (let i = 0; i <12; i++ ) {
			// 				item.children.push(
			// 				{
			// 					id: 0,
			// 					name: day.nengetu,
			// 					start: day.getusyo,
			// 					end: day.getumatu,
			// 					class: 'date_items',
			// 					children: this.treeItems3
			// 				})
			// 				day = getDate(day.zengetu);
			// 			}
			// 			break;
			// 		case 'customer':
			// 			return fetch('/api/clients')
			// 				.then(res => res.json())
			// 				.then(json => {
			// 					// item.children.push(...json)
			// 					json.forEach(client => {
			// 						item.children.push( {
			// 							id: client.id,
			// 							name: client.name,
			// 							class: 'client_items',
			// 							children: this.treeItems4
			// 						})
			// 					});
			// 				})
			// 				.catch(err => console.warn(err))
			// 			break;
			// 		case 'date_items':
			// 			item.children =[];
			// 			const sql = { sql: "SELECT * FROM v_invoices WHERE closing_date BETWEEN '" + getFormatDate(item.start) + "' AND '" + getFormatDate(item.end) +"'" };
			// 			return fetch('/api/invoices/sql', {
			// 					method: 'POST',
			// 					body: JSON.stringify(sql),
			// 					headers: {
			// 						'Content-Type': 'application/json',
			// 						'X-CSRF-TOKEN': '{{ csrf_token() }}' //Laravelの場合これが必要
			// 					}
			// 				})
			// 				.then(res => res.json())
			// 				.then(json => {
			// 						json.forEach(invoice => {
			// 							item.children.push( {
			// 								id: invoice.id,
			// 								name: invoice.client_name,
			// 							})
			// 						});
			// 				})
			// 				.catch(err => console.warn(err))
			// 			break;
			// 		default:
			// 			break;
			// 	}
			// },

			click_invoice(e) {
// alert(e.rowNo)
// 				this.selectTableRowNo = e.rowNo;
// 				axios.get('/invoices/' + e.value.id).then((res) => {
// 					this.invoice = res.data.invoice;
// 					this.invoice_masters = res.data.invoice_masters;
// 					if (this.invoice=="") {
// 						alert("他の利用者により削除されています。")
// 					} else {
// 						this.$refs.EditInvoice.open({
//                             isFullscreen:false
// 						});
// 					}
// 				});
			},
			click_master(e) {
				this.selectMasterRowNo = e.rowNo;
				axios.get('/invoice_masters/' + e.value.id).then((res) => {
// alert(JSON.stringify(res.data.master))
					if (res.data.master=="") {
						alert("他の利用者により削除されています。")
						// this.$refs.subjectTable.refrash();
					} else {
						// this.headerTitle
						this.invoice_master = res.data.master;
						this.invoice_details = res.data.master.v_invoice_details;
						this.subject = res.data.subject;
						this.bill_total = res.data.billl_total;
						// this.$refs.EditMaster.refresh();this.
						this.EditMaster_key = !this.EditMaster_key;
						this.$refs.EditMasterDialog.open({
							isFullscreen:true
						});
					}
				});
			},
			search(val) {
				this.searchText=val;
			},
			filter(val) {
				let filters = new Object();
				for (var i = 0 ; i<val.length ; i++) {
					if (val[i].value.length>0) {
						filters[val[i].name] = val[i].value.join()
					}
				}
				axios.post('/subject/filter', filters ).then((res) => {
					this.tableItems = res.data;
				});
			},
            closing() {
                this.closing_key = !this.closing_key ;
                this.$refs.ClosinDdialog.open({
                    isFullscreen:true
                });
            },
			print(e) {
// alert(JSON.stringify(e.items));
				localStorage.setItem('selectItems', JSON.stringify(e.items));
				localStorage.setItem('printName', JSON.stringify(e.name));
				window.open("/print", '_blank');
			},
			click_sideMenu_category(e) {
// alert(JSON.stringify(e))
				this.selectTableRowNo = -1;
				this.headerTitle=""
				this.headerItems = this.header_date;
				axios.post('/invoices/extract', { category:"", key:"" })
					.then((res) => {
						this.headerTitle = "";
						this.tableItems = res.data;
				});
			},
			click_sideMenu_item(e) {
// alert(JSON.stringify(e))
				this.tableItems=[];
				if (e.category=='client') {
					this.headerItems = this.header_customer;
				} else {
					this.headerItems = this.header_date;
				}
				axios.post('/invoices/extract', { category:e.category, key:e.key } )
					.then((res) => {
						if (res.status==200) {
							this.headerTitle = "（" + e.label + "）";
							this.tableItems = res.data;
							// this.$emit('enter',{ category:e.category, key:e.key });
						}
				});
			},
			select_TableRow(e) {
					this.$refs.masterList.setSelectAll({isSelected:e.isSelected,count:this.master_details.length});
			},
			item_expanded(e) {
				this.master_details= [];
				this.expandedTableRowNo = e.rowNo;
				if (e.status) {
					axios.get('/invoice_masters/' +e.value.id).then((res) => {
						this.master_details = res.data;
						this.select_TableRow(e);
					});
				};
			},
			entrt_EditMaster(e) {
					this.tableItems.splice(this.expandedTableRowNo  , 1, e.invoice);
					this.master_details.splice(this.selectMasterRowNo  , 1, e.master);
					this.$refs.EditMasterDialog.close()
			},
			close_EditMaster() {
                this.init();
				this.$refs.EditMasterDialog.close();
			},
			enter_EditInvoice(e) {
				this.tableItems.splice(this.selectTableRowNo  , 1, e);
				this.$refs.EditInvoice.close();
			},
			remove_EditInvoice(e) {
// alert(JSON.stringify(e))
				this.tableItems.splice(this.selectTableRowNo  , 1);
				const hasItems = this.tableItems.some((item) => item.closing_date.substr(0,7) === e.closing_date.substr(0,7));
				if (hasItems == false) {
// alert(JSON.stringify(this.sideMenu_items[0].items))
						const index = this.sideMenu_items[0].items.findIndex(({key}) => key === e.closing_date.substr(0,7))
						this.sideMenu_items[0].items.splice(index  , 1);
				}
				let isHave = this.tableItems.some(function(val){
						return val.closing_date.substring(0,7) == e.closing_date.substring(0,7)
				})
				this.$refs.EditInvoice.close();
			},
			enter_CreateInvoice(e) {
                e.newVal.forEach(val => {
				    this.tableItems.unshift(val);
                });
                if (this.sideMenu_items[0].items.some((item) => item.key == e.date.ym)==false) {
                    this.sideMenu_items[0].items.unshift(
                        {
                            text: e.date.year+"年"+e.date.month+"月",
                            icon: 'mdi-label',
                            category:'date',
                            key:e.date.ym,
                            value:e.date
                        });
                };
				this.$refs.ClosinDdialog.close();
			},
			reOrder_masterList(e) {
				const masters = Object.assign({},e)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
				axios.post('/api/invoice_masters/reOrder', { 'masters' :masters} )
					.then((res) => {
						if (res.status==200) {
							// this.$emit('enter',{ value:res.data, mode:"upd" });
						}
				});
			},
			remove_EditMaster(e) {
// alert(JSON.stringify(this.tableItems[this.expandedTableRowNo]))
				this.tableItems[this.expandedTableRowNo].amount -= e.bill_amount
// alert(JSON.stringify(e))
				this.master_details.splice(this.selectMasterRowNo  , 1);
				this.$refs.EditMasterDialog.close();
			},
//             command(val) {
// alert(JSON.stringifi(val))
//             }
		},
		created () {
			this.init();
		},
		mounted() {
			// window.addEventListener('resize', this.getWindowSize);
			// this.getWindowSize();
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

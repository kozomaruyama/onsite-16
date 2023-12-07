<template>
    <div>
        <!-- メイン -->
        <CyberMain
            :filterItems="filterItems"
            :commandItems="commandItems"
            :isNaviVisible="true"
        >
            <!-- サイドメニュー -->
            <template v-slot:navi>
				<CyberSideMenu
                    ref="SideMenu"
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
					ref="ClientTble"
					:filterItems="filterItems"
					:commandItems="commandItems"
					:headers="category_headers"
					:items="groupItems"
					:searchText="searchText"
                    :show_expand=true
                    :show_select="showSelect"
					:printDetailItems="printDetailItems"
                    :visible_printDetail="visiblePrintDetail"
                    :visible_calc="visibleCalc"
                    :visible_marge="visibleMarge"
                    :visible_edit="visibleEdit"
                    :visible_add="visibleAdd"
                    :visible_xls="visibleXls"
					@filter="filter"
					@print="print"
					@xls="xls"
                    @edit="edit_invoice"
					@item_expanded="item_expanded"
					@click_TableRow="click_TableRow"
                    @select_TableRow="select_TableRow"
                    @select_TableRow_all="select_TableRow_all"
                    @marge="bill_marge"
				>
                    <template v-slot:title>{{ header_title }}</template>
                    <template v-slot:command>
                        <!-- <v-menu offset-y  >
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon class="mx-3" v-bind="attrs" v-on="on">mdi-printer</v-icon>
                            </template>
                            <v-list>
                                <span v-for="(printItem, index) in print4Items" :key="index">
                                    <v-divider v-if="printItem.name=='divider'"></v-divider>
                                    <v-list-item v-else min-height dense link @click="Print3(printItem.name)">
                                        <v-icon left>{{ printItem.icon }}</v-icon>{{ printItem.title }}
                                    </v-list-item>
                                </span>
                            </v-list>
                        </v-menu> -->
                    </template>
					<template>
						<CyberTable
							ref="scheduleTable"
                            :key="masterList_key"
							:headers="detail_headers"
							:details="bills"
                            :printItems="print3Items"
                            :show_select="isShowSelect"
                            :show_add="false"
                            :visible_calc="visibleCalc"
                            :visible_print="visiblePrint2"
                            @click_TableRow="click_master"
                            @print="print"
                            @calc="scheduleTable_calc"
                            @add="create_bill"
						/>
					</template>
				</CyberDataTable>
			</template>
        </CyberMain> 
		<!-- 請求編集ダイアログ -->
		<CyberDialog ref="EditInvoice">
			<template v-slot:caption>請求編集</template>
			<template v-slot:main>
				<EditInvoice
					:invoice="invoice"
                    :isVisible_delete="isVisible_delete"
					@enter="enter_EditInvoice"
					@close="$refs.EditInvoice.close()"
					@remove="remove_EditInvoice"
				/>
			</template>
		</CyberDialog>        
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
                    :fraction_total="fraction_total"
                    :subject="subject"
                    @close="close_EditMaster"
                    @enter="entrt_EditMaster"
                    @remove="remove_EditMaster"
                />
			</template>
		</CyberDialog> 
        <!-- 印刷ダイアログ -->
        <CyberDialog ref="PrintDialog" >
            <template v-slot:caption>印刷</template>
            <template v-slot:main>
                <PrintParam :key="reset_key"/>
            </template>
        </CyberDialog>                 
    </div>
</template>
<script>
    import xlsx from "xlsx-js-style";
    import moment from 'moment'
    import { mapState, mapGetters } from 'vuex'
    import CyberMain from "../CyberMain";
    import CyberDataTable from "../CyberDataTable";
    import CyberTable from "../CyberTable";
    import CyberSideMenu from "../CyberSideMenu";
    import CyberDialog from "../CyberDialog";
    import EditInvoice from "../invoice/EditInvoice";
    import EditMaster from "../invoice/EditMaster";
    import PrintParam from "./PrintParam";    
    import commFunctions from '../../functions/commFunctions';
    import { LITERAL_TYPES, tsConstructorType } from '@babel/types';
    const { getFormatDate,zeroPadding } = commFunctions();    
    export default {
        components: {
            CyberMain,
            CyberDataTable,
            CyberTable,
            CyberSideMenu,
            CyberDialog,
            EditInvoice,
            EditMaster,
            PrintParam,
        },
        props: ["clients","subcontracters","calendars","invoice_calendars","person"],
        data: function() {
            return{
                isShowSelect:true,
                groupItems:[],
                masterList_key:true,
                ScheduleClassItems: [],
                commandItems:[],
                // 印刷アイテム
				printDetailItems: [
					{ title: '請求書',name:'invoice', icon:'mdi-receipt-outline'},
				],
				// 印刷３アイテム
				print3Items: [
					{ title: '請求明細',name:'detail', icon:'mdi-receipt-outline'},
				],
                reset_key:false,
                category_headers:[],
                closing_headers: [
                    { text: '', value: 'status', sortable: false,  width:'20px'  },
                    {
                        text: '顧客',
                        align: 'start',
                        sortable: true,
                        // value: 'main_nick',
                        value: 'nickname',
                        groupable:false
                    },
                    { text: '請求日', value: 'bill_issue' , sortable: false, groupable:false},
                    { text: '', value: 'actions', sortable: false, align: 'end' },
					{ text: '', value: 'data-table-expand' },
                ],
				client_headers: [
					{ text: '締切日', value: 'closing_date' },
					{ text: '請求日', value: 'issue_date' , sortable: false, groupable:false},
					{ text: '入金日', value: 'payment_date' , sortable: false, groupable:false},
					{ text: '請求額', value: 'bill_amount' , groupable:false, align:'end' },
                    { text: '', value: 'actions', sortable: false, align: 'end'  },
					{ text: '', value: 'data-table-expand' },
				],
				//
				calendar_headers : [
					{ text: '顧客', value: 'client_nickname' },
					{ text: '締切日', value: 'closing_date' },
					// { text: '請求日', value: 'issue_date' , sortable: false, groupable:false},
					{ text: '入金日', value: 'payment_date' , sortable: false, groupable:false},
					{ text: '請求額', value: 'bill_amount' , groupable:false, align:'end' },
					{ text: '調整額', value: 'adjust_amount' , groupable:false, align:'end' },
                    { text: '', value: 'actions', sortable: false, align: 'end'  },
					{ text: '', value: 'data-table-expand' },
				],

                detail_headers:[],

                bill_headers: [
                    { name:'day',label:'日' },
					{ name:"name",label:"案件名" },
					{ name:"start_date",label:"開始" },
					{ name:"bill_amount",label:"請求額" },
					{ name:"main_nick",label:"元請" },
					{ name:"schedule_class",label:"区分" },
					{ name:"pay_ratio",label:"出来高" },
					// { name:"status",label:"" },
                ],
                invoice_headers: [
                    { name:'name',label:'案件' },
					{ name:"breakdown",label:"規格・仕様" },
					{ name:"subject_bill_amount",label:"単価" },
					{ name:"tax",label:"税" },
					{ name:"ratio",label:"数量(%)" },
					{ name:"amount",label:"請求額" },
					{ name:"adjust_amount",label:"調整額" },
					{ name:"memo",label:"備考" },
                ],
 
                searchText:"",
                // フィルターアイテム
                filterItems: [],  
                //
                title:"",
                // 
                bills:[],
                // 
                header_title:"",
                mainTitle:"締め処理",
                subTitle:" ",
                // 
                mode:"date",
                // 
                sideMenu_items: [],
                // 
                categoryKey:null,
                // 
                client_id:0,
                closing:null,
                visiblePrintDetail:false,
                visiblePrint2:false,
                visibleXls:false,
                visibleMarge:false,
                visibleEdit:false,
                visibleAdd:false,
                subject:[],
                invoice:[],
                invoice_masters:[],
                invoice_master:[],
                invoice_details:[],
                bill_total:0,
                fraction_total:0,
                selectMasterRowNo:-1,
                EditMaster_key:true,
                expandedTableRowNo:-1,
                tableItems:[],
                // bill_YMs:[],
                showSelect:false,
                activeSideMenuItem:null,
                // 
                isVisible_delete:false,
                // 
                bill_create_keys:[],
                EditPay_key:true,
                pay:[],
                smode:"edit",
            }
        },
        created() {
            this.ScheduleClassItems = this.$store.getters.getScheduleClasses;  
            // this.filterItems.push({lavel:"区分", name:"scheduleClasses", value:this.ScheduleClassItems });
            this.init();
        },
        mounted() {
            this.category = "all"
            this.categoryKey = moment().format('YYYY-MM') 
            this.subTitle = moment().format('YYYY年MM月')    
            this.closing = moment().format('YYYY-MM') 
            this.click_sideMenu_category("all")
            this.$refs.SideMenu.expand(0);  
            // this.click_sideMenu_item({category:this.category,key:this.categoryKey,label:"月次",value:this.categoryKey});
        },
        computed: {
            ...mapGetters([ 'getScheduleClasses' ]),
        },
        methods: {
            locate: function() {
                location.href='/invoice';
            } ,           
            click_sideMenu_category(e) {  
// alert(JSON.stringify(e))
                this.$refs.ClientTble.init();              
				this.selectTableRowNo = -1;  
                this.groupItems = []     
                this.bills = []                  
                this.mode = e.key
                this.showSelect =false;
                this.header_title = e.text;
// alert(JSON.stringify(this.mode))
                const closing = moment().format('YYYY-MM');
                this.visiblePrintDetail=false
                if (this.mode == 'all') {
                    this.category_headers = this.closing_headers;  
                    this.detail_headers = this.bill_headers;
                    this.visibleCalc=true
                    this.visibleEdit=false
                    this.visibleAdd = false;
                    this.visibleMarge=false
                    this.visibleXls=false
                    this.showSelect =false;
                    const key=moment().format('YYYY-MM')
// alert(JSON.stringify(key))
                    axios.get('/bill/all/'+ key).then((res) => {
// alert(JSON.stringify(res.data))
                        this.groupItems = res.data;
                        this.closing = key;
                        this.categoryKey =  key;
                    });                                   
                } else if (this.mode == 'bill') {
                    this.showSelect =false;
                    this.visibleAdd = false;
                } else if (this.mode == 'date') {
                    this.visibleAdd = false;
                    this.detail_headers = this.invoice_headers;
                    this.category_headers = this.calendar_headers;  
                    axios.get('/bill/'+ closing).then((res) => {
                        // alert(JSON.stringify(res.data)) 
                        e.items=[]
                        res.data.forEach(calendar => {
                            e.items.unshift(
                                { text: calendar.year+"年"+calendar.month+"月", icon: 'mdi-label', category:'date', key:calendar.year+"-"+zeroPadding(calendar.month,2), value:calendar }
                            )
                        });

                    });
                } else if (this.mode == 'client') {
                    this.visibleAdd = false;
                    this.category_headers = this.client_headers;  
                }    
            },
            click_sideMenu_item(e) {                 
                this.activeSideMenuItem = e;
                this.$refs.ClientTble.init();
                this.groupItems = []     
                this.bills = [] 
                this.header_title = "請求一覧（" + e.label + "）";   
                if (this.mode == 'all') {
                    this.visibleCalc=true
                    this.visibleEdit=false
                    this.visibleAdd = false;
                    this.visibleMarge=false
                    this.visiblePrintDetail=false
                    this.visibleXls=false
                    this.showSelect =false;
                    axios.get('/bill/' + e.category + '/'+ e.key).then((res) => {
                        this.groupItems = res.data;
                        this.closing = e.key;
                        this.categoryKey =  e.key;
                    });
                } else {
                    this.visibleCalc=false
                    this.visibleEdit=true
                    this.visibleAdd = false;
                    this.visibleMarge=true 
                    this.visiblePrintDetail=true 
                    this.visibleXls=true 
                    this.showSelect =true;   
                    this.printItems = this.print1Items                                                                   
                    axios.post('/invoices/extract', { category:e.category, key:e.key } )
                        .then((res) => {
// alert(JSON.stringify(res.data))
                            if (res.status==200) {
                                this.headerTitle = "（" + e.label + "）";
                                this.groupItems = res.data;
                                this.tableItems = res.data;
                            }
                    });
                }  
            },
			init() {
                let chileItems = [];
                this.tableItems = this.invoices;
                this.groupItems = this.clients;
                this.category_headers = this.closing_headers;  
                // 
                this.sideMenu_items.push({
                    text: '締め一覧',
                    icon: 'mdi-calculator-variant-outline',
                    key: 'all',
                    action: 'mdi-ticket',
                    active: true,
                    items: [],
                })
                
                this.sideMenu_items.push({
                    text: '請求一覧（年月別）',
                    icon: 'mdi-calendar',
                    key: 'date',
                    action: 'mdi-ticket',
                    active: true,
                    items: [],
                })

                // 
                chileItems = [];
                this.sideMenu_items.push({
                    text: '請求一覧（顧客別）',
                    icon: 'mdi-account-tie',
                    key: 'client',
                    action: 'mdi-ticket',
                    active: true,
                    items: chileItems,
                })
				this.clients.forEach(client => {
					chileItems.push({
                        text: client.nickname,
                        icon: 'mdi-label',
                        key:client.id,
                        category:'client',
                        value:client
                    })
				});     

            },
            
            scheduleTable_calc(e) {
// alert(JSON.stringify(e))
                axios.post('/bill/close', {client_id:this.client_id, closing:this.closing, values:e})
                    .then((res) => { 
// alert(JSON.stringify(res.data))
                        this.$refs.SideMenu.expand(1);  
                        const ym = res.data
                        const year=Number(ym.substring(0, 4));
                        const month=Number(ym.substring(5, 2));
                        // this.activeSideMenuItem = {"category":"all","key":res.data,"label":"月次","value":res.data}
                        this.activeSideMenuItem = {"category":"date","key":ym,"label": year + "年"+ month +"月","value":{"year":year,"month":month}}
                        this.click_sideMenu_item(this.activeSideMenuItem);
                });
            },
            bill_marge(e) {     
// alert(JSON.stringify(e))     
                e.sort((a, b) => { 
                    return a.subject_id - b.subject_id 
                });
                var invoices = Object.assign({},e)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
                axios.post('/invoice_master/marge',  {invoices:invoices} )
					.then((res) => {
                        this.click_sideMenu_item(this.activeSideMenuItem);
					}
				);
            },
            select_TableRow_all(e) {          
                if (this.bills.length > 0) {
                    this.$refs.scheduleTable.setSelectAll({isSelected:e.isSelected,count:this.bills.length });                                      
                }
            },
            select_TableRow(e) {             
                if (this.bills.length > 0) {
                    this.$refs.scheduleTable.setSelectAll({isSelected:e.isSelected,count:this.bills.length });    
                    this.$emit('select_TableRow',{ value:res.data, mode:this.smode });
                
                }
            },
            item_expanded(e) {
                this.expandedTableRowNo = e.rowNo;
                let today = moment().format('YYYY-MM');
                if (e.status) {
                    if (this.mode == 'all') {  
                        axios.post('/bill/details', { id:e.value.id, ym:this.categoryKey, pay_ratio:e.value.pay_ratio }).then((res) => {
                            this.isShowSelect = this.categoryKey <= today; 
                            this.bills = res.data;
                            this.client_id = e.value.id;
                            this.$refs.scheduleTable.setSelectAll({isSelected:e.isSelected,count:this.bills.length });
                        })
                    } else if (this.mode == 'date' || this.mode == 'client') { 
                        this.isShowSelect = false;                       
                        axios.get('/invoice_masters/' +e.value.id).then((res) => {
                            this.bills = res.data;
                            this.client_id = e.value.id;
                            this.$refs.scheduleTable.setSelectAll({isSelected:e.isSelected,count:this.bills.length });
                        })
                    } else {
                        axios.post('/bill/details', { id:this.categoryKey, ym:e.value.closing }).then((res) => {
                            this.isShowSelect = false; 
                            this.closing = e.value.closing;
                            this.bills = res.data;
                        })
                    }             
                }
            },
            click_TableRow(e) {
                this.bills = []
                e.slot.expand(!e.slot.isExpanded)
            },
            edit_invoice(e) {
                this.selectTableRowNo = e.rowNo;
                axios.get('/invoice/' + e.value.id).then((res) => { 
// alert(JSON.stringify(res.data.status))
					this.invoice = res.data.invoice;
					this.invoice_masters = res.data.invoice_masters;
                    this.isVisible_delete = res.data.status;
					if (this.invoice=="") {
						alert("他の利用者により削除されています。")
					} else {
						this.$refs.EditInvoice.open({
                            isFullscreen:false
						});
					}
				});
            },
            enter_EditInvoice(e) {
// alert(JSON.stringify(e))
				this.tableItems.splice(this.selectTableRowNo  , 1, e);
				this.$refs.EditInvoice.close();                
            },
            remove_EditInvoice(e) {
                this.tableItems.splice(this.selectTableRowNo  , 1);
                const hasItems = this.tableItems.some((item) => item.closing_date.substr(0,7) === e.closing_date.substr(0,7));
                if (hasItems == false) {
                    const index = this.sideMenu_items[0].items.findIndex(({key}) => key === e.closing_date.substr(0,7))
                    this.sideMenu_items[0].items.splice(index  , 1);
                }
                let isHave = this.tableItems.some(function(val){
                    return val.closing_date.substring(0,7) == e.closing_date.substring(0,7)
                })
				this.$refs.EditInvoice.close();                
            },
            click_master(e) {
// alert(JSON.stringify(e.value))
                this.selectMasterRowNo = e.rowNo;
                if (this.mode == 'client' || this.mode == 'date' ) {
                    // this.selectMasterRowNo = e.rowNo;
                    // axios.get('/invoice_masters/' +e.value.id).then((res) => {
                    axios.get('/invoice_masters/read/' + e.value.id).then((res) => {
// alert(JSON.stringify(res.data.master.v_invoice_details))
                        if (res.data.master=="") {
                            alert("他の利用者により削除されています。")
                        } else {
                            this.invoice_master = res.data.master;
                            this.invoice_details = res.data.master.v_invoice_details;
                            this.subject = res.data.subject;
                            this.bill_total = res.data.billl_total;
                            this.fraction_total = res.data.fraction_total;
                            this.EditMaster_key = !this.EditMaster_key;
                            this.$refs.EditMasterDialog.open({
                                isFullscreen:false
                            });
                        }
                    });
                }
            },
            entrt_EditMaster(e) {
// alert(JSON.stringify(e.master))
                this.tableItems.splice(this.expandedTableRowNo  , 1, e.invoice);
                // this.bills.splice(this.selectMasterRowNo  , 1, e.master);
                this.bills[this.selectMasterRowNo].adjust_amount=e.master.adjust_amount;
                this.bills[this.selectMasterRowNo].memo=e.master.memo;
                this.$refs.EditMasterDialog.close()                
            },
            close_EditMaster() {
            },
            remove_EditMaster(e) {
                this.tableItems[this.expandedTableRowNo].amount -= e.bill_amount
                this.tableItems[this.expandedTableRowNo].bill_amount -= e.bill_amount
				this.bills.splice(this.selectMasterRowNo  , 1);
				this.$refs.EditMasterDialog.close();                
            },
            create_bill() {
// alert(JSON.stringify(this.bill_create_keys))  
                // this.invoice_master = res.data.master;
                // this.invoice_details = res.data.master.v_invoice_details;
                // this.subject = res.data.subject;
                // this.bill_total = res.data.billl_total;
                // this.fraction_total = res.data.fraction_total;
                this.pay = {name:"",volume:0,cost:0,amount:0,memo:""};
                this.smode = "add"
                this.EditMaster_key = !this.EditMaster_key;
                this.$refs.EditPayDialog.open({
                    isFullscreen:false
                });
            },

            enter_ScheduleDialog() {},
            serch() {},
            copy() {},
            filter() {},
            add() {},
            group() {},
			print(e) {
                localStorage.setItem('selectItems', JSON.stringify(e.items));            
				localStorage.setItem('printName', JSON.stringify(e.name));
				localStorage.setItem('person', JSON.stringify(this.person));
				window.open("/print", '_blank');
			},

            Print3(name) {
                localStorage.setItem('printName', JSON.stringify(name));
                this.reset_key = !this.reset_key;
                this.$refs.PrintDialog.open({
                    isFullscreen:false,
                    width:"500px"
                });                
            },   
            xls(e) {
                axios.post('/invoices', { targetItems : [e.id]} ).then((res) => {
                    const invoice = res.data.invoice;
                    const masters = res.data.masters;
                    const details = res.data.details;
// alert(JSON.stringify(invoice))
                    let workbook = xlsx.utils.book_new();
                    let invoice_sheet = xlsx.utils.json_to_sheet([invoice]);
                    let masters_sheet = xlsx.utils.json_to_sheet(masters);
                    let details_sheet = xlsx.utils.json_to_sheet(details);
                    xlsx.utils.book_append_sheet(workbook, invoice_sheet, "invoice");
                    xlsx.utils.book_append_sheet(workbook, masters_sheet, "masters");
                    xlsx.utils.book_append_sheet(workbook, details_sheet, "details");
                    xlsx.writeFile(workbook, "invoice_data.xlsx");
                });
            }
        },
    }
</script>
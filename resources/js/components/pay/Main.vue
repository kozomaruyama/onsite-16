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
                    :items="sideMenu_categorys"
                    @click_category="click_sideMenu_category"
                    @click_item="click_sideMenu_item"
                />                
            </template>
            <!-- メイン -->
            <template v-slot:main>
                <CyberDataTable
                    ref="MasterTble"
                    :headers="master_headers"
                    :items="master_Items"
                    :printDetailItems="printDetailItems"
                    show_expand=true
                    visible_add="true"
                    :visible_print="false"
                    :visible_printDetail="true"
                    @add="add_master"
                    @item_expanded="item_expanded"
                    @click_TableRow="click_master"
                    @print="print"
                >
                    <template v-slot:title>支払一覧{{ header_title }}</template>
                    <template>
						<CyberTable
                            :headers="detail_headers"
                            :details="detail_Items"
                            show_add="true"
                            @click_TableRow="click_detail"
                            @add="add_detail"
						/>
					</template>
                </CyberDataTable>
            </template>
        </CyberMain> 
  
		<!-- 明細編集ダイアログ -->
		<CyberDialog ref="EditDeilDialog">
			<template v-slot:caption>{{ caption }}</template>
			<template v-slot:main>
                <EditDetail
                    ref="EditDetil"
                    :value="detail"
                    :mode="mode"
                    @enter="enter"
                    @remove="remove"
                />
			</template>
		</CyberDialog> 
        <!-- 印刷ダイアログ -->
        <CyberDialog ref="PrintDialog" >
            <!-- <template v-slot:caption>印刷</template>
            <template v-slot:main>
                <PrintParam :key="reset_key"/>
            </template> -->
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
    import EditDetail from "./Detail";
    // import PrintParam from "./PrintParam";    
    import commFunctions from '../../functions/commFunctions';
    import { LITERAL_TYPES, tsConstructorType } from '@babel/types';
    const { getFormatDate,zeroPadding,getDate } = commFunctions();  

    let selectRowNo = -1;
    //   
    const define_headers = 
    { 
        date_master:
        [
            { value:'nickname',text:'顧客' },
            { value:"amount_total",text:"支払金額" },
            { value:"closing_date",text:"締切日" },
            // { value:"pay_date",text:"支払日" },
            { text: '', value: 'actions', sortable: false, align: 'end' },
            { text: '', value: 'data-table-expand' },
        ],
        subcontracter_master:
        [
            { value:"closing_date",text:"締切日" },
            { value:"pay_date",text:"支払日" },
            { value:"amount_total",text:"支払金額" },
            { text: '', value: 'actions', sortable: false, align: 'end' },
            { text: '', value: 'data-table-expand' },
        ],
        detail:
        [
            { name:'name',label:'案件' },
            { name:"cost",label:"単価" },
            { name:"volume",label:"数量" },
            { name:"unit_name",label:"" },
            { name:"amount",label:"金額" },
            { name:"memo",label:"備考" },
        ]
    };
    const sideMenuItems = 
    {
        date:{
            text: '支払処理（年月別）',
            icon: 'mdi-calendar',
            key: 'date',
            action: 'mdi-ticket',
            active: true,
            items: [],
        },
        subcontracter:{
            text: '支払処理（顧客別）',
            icon: 'mdi-account-tie',
            key: 'subcontracter',
            action: 'mdi-ticket',
            active: true,
            items: [],
        }
    };

    let activeMasterInfo=null;
    let master_active_rowNo=-1;

    let selectCategoryItem=[];

    export default {
        components: {
            CyberMain,
            CyberDataTable,
            CyberTable,
            CyberSideMenu,
            CyberDialog,
            // EditPay,
            EditDetail,
            // PrintParam,
        },
        props: ["clients","subcontracters","calendars","invoice_calendars","person"],
        data: function() {
            return{
                // フィルターアイテム
                filterItems: [],  

                commandItems:[],

				// 印刷アイテム
				printDetailItems: [
                    { title: '支払明細書',name:'Pay', icon:'mdi-receipt-outline' },
				],

                // 
                sideMenu_categorys: [],
                sideMenu_items: [],

                // 
                mode:"add",

                master_headers:[],
                detail_headers:[],

                master_Items:[],
                
                detail_Items:[],

                detail:[],

                header_title:"",

                caption:"明細編集",


            }
        },
        created() {
            this.ScheduleClassItems = this.$store.getters.getScheduleClasses;  
            this.init();
        },
        mounted() {
            this.click_sideMenu_category({key:"date"})
        },
        computed: {
            ...mapGetters([ 'getScheduleClasses' ]),
        },
        watch:{
            'detail_Items': function(newValue,oldValue) {
                let total = 0;
                newValue.forEach(function(val) {
                    total += Number(val.amount);
                });
                this.master_Items[master_active_rowNo].amount_total = total;
            },
        },
        methods: {
            locate: function() {
                location.href='/invoice';
            } ,  
            
			init() {
                this.sideMenu_categorys = sideMenuItems;
            },

            click_sideMenu_category(e) {
// alert(JSON.stringify(e))  
                let items = [];  
                const closing = moment().format('YYYY-MM');
                if (e.key=='date') {
                    this.header_title = "（年月別）";
                    axios.get('/pay/' + e.key + "/" + closing ).then((res) => {
                        res.data.forEach(value => {
                            items.unshift({ 
                                label: value.year+"年"+value.month+"月", 
                                text: value.year+"年"+value.month+"月", 
                                icon: 'mdi-label', 
                                category:'date_master', 
                                key:value.year+"-"+zeroPadding(value.month,2), 
                                value:value }
                            )
                        });
                        this.sideMenu_categorys[e.key].items = items;
                        this.click_sideMenu_item(this.sideMenu_categorys.date.items[0])
                    });
                } else if (e.key=='subcontracter') {
                    this.header_title = "（顧客別）";
                    axios.get('/pay/subcontracter').then((res) => {
                        res.data.forEach(value => {
                            items.unshift({ 
                                label: value.nickname, 
                                text: value.nickname, 
                                icon: 'mdi-label', 
                                category:'subcontracter_master', 
                                key:value.id, 
                                value:value }
                            )
                        });
                        this.sideMenu_categorys[e.key].items = items;
                    });
                }
                this.master_headers=[];
                this.master_Items=[];
            },
            click_sideMenu_item(e) { 
// alert(JSON.stringify(e))
                selectCategoryItem = e;
                this.$refs.MasterTble.init();                      
                this.master_headers = define_headers[e.category];
                this.header_title = "（" + e.label + "）";
                axios.get('/pay/' + e.category + "/" + e.key ).then((res) => {  
                    this.master_Items = res.data;
                });
            },

            scheduleTable_calc(e) {

            },

            select_TableRow_all(e) {          

            },
            select_TableRow(e) {             

            },
            item_expanded(e) {
// alert(JSON.stringify(e));
                master_active_rowNo=e.rowNo;
                activeMasterInfo = {client_id:e.value.client_id,closing_date: e.value.closing_date, pay_date:e.value.pay_date}
                this.detail_headers = define_headers['detail']
                // const params = { client_id:e.value.client_id ,pay_date: e.value.pay_date };
                axios.post('/pay/details/' + e.value.category, activeMasterInfo ).then((res) => {
                    this.detail_Items = res.data;
                });
            },
            click_TableRow(e) {

            },

            click_master(e) {
                e.slot.expand(!e.slot.isExpanded)
            },

            click_detail(e) {
                this.caption="明細編集"
                selectRowNo = e.rowNo;
                axios.get('/pay/read/' + e.value.id).then((res) => {
                    this.detail = res.data;
                    this.mode = "edit"
                    this.$refs.EditDeilDialog.open({
                        isFullscreen:false
                    });
                });
            },
            add_master() {
                if (selectCategoryItem.category=='date_master') {
                    const today =selectCategoryItem.key + "-01";
                    activeMasterInfo = {
                        client_id:0, 
                        // closing_date:today,
                        // pay_date:today,
                        pay_date_obj:selectCategoryItem.value
                    };
                } else {
                    const today = getDate();
                    activeMasterInfo = {
                        client_id:selectCategoryItem.key, 
                        // closing_date:today.ymd,
                        // pay_date:today.ymd,
                        pay_date_obj:{year:0,month:0}
                    };
                }
                this.add_detail()
            },
            add_detail:function() {
                this.caption="明細追加"
// alert(JSON.stringify(activeMasterInfo));
                // if (activeMasterInfo.client_id==0) {
                // }
                this.detail = {
                    class:30,
                    client_id:activeMasterInfo.client_id,
                    closing_date:activeMasterInfo.closing_date,
                    pay_date:activeMasterInfo.pay_date,
                    name:"",
                    volume:0,
                    unit:20,
                    cost:0,
                    amount:0,
                    memo:"",
                    pay_date_obj:activeMasterInfo.pay_date_obj,
                };
                this.mode = "add"

                // this.EditMaster_key = !this.EditMaster_key;
                this.$refs.EditDeilDialog.open({
                    isFullscreen:false
                });
            },
            enterMaster(e) {
// alert(JSON.stringify(e.value))
                if (this.mode=="add") {
                    this.master_Items.push(e.value)
                } else {
                    this.master_Items.splice(selectRowNo  , 1, e.value);
                }
                this.$refs.EditMasterDialog.close()  
                this.click_sideMenu_category({key:"date"})
            },
            removeMaster(e) {
                this.detail_Items.splice(selectRowNo  , 1);
                this.$refs.EditDeilDialog.close()  
                this.click_sideMenu_category({key:"date"})
            },
            enter(e) {
// alert(JSON.stringify(e.value))
                if (this.mode=="add") {
                    this.detail_Items.push(e.value)
                } else {
                    this.detail_Items.splice(selectRowNo  , 1, e.value);
                }
                this.$refs.EditDeilDialog.close() 
                this.click_sideMenu_item(selectCategoryItem) 
            },
            remove(e) {
                this.detail_Items.splice(selectRowNo  , 1);
                this.$refs.EditDeilDialog.close()  
                this.click_sideMenu_item(selectCategoryItem) 
            },
            serch() {},
            copy() {},
            filter() {},
            add() {},
            group() {},
			print(e) {
// alert(JSON.stringify(e.items[0]))
                localStorage.setItem('selectItems', JSON.stringify({ id:e.items[0].client_id,closing_date:e.items[0].closing_date,pay_date:e.items[0].pay_date }));
				localStorage.setItem('printName', JSON.stringify(e.name));
				localStorage.setItem('person', JSON.stringify(this.person));
				window.open("/print", '_blank');
			},


            xls(e) {

            }
        },
    }
</script>
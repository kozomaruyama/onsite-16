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
                    ref="CyberDataTable"
                    :filterItems="filterItems"
                    :commandItems="commandItems"
                    :printItems="printHeaderItems"
                    :printDetailItems="printDetailItems"
                    :printHeaderItems="printHeaderItems"
                    :headers="headers"
                    :items="tableItems"
                    :searchText="searchText"
                    :show_select="false"
                    :visible_add="true"
                    :visible_copy="false"
                    :visible_group="false"
                    :visible_printHeader="true"
                    :visible_printDetail="true"
                    @search="search"
                    @filter="filter"
                    @add="add"
                    @copy="copy"
                    @group="group"
                    @print="print"
                    @click_TableRow="click_TableRow"
                    @select_TableRow="select_TableRow"
                >
                    <template v-slot:title>案件一覧{{ header_title }}</template>
                </CyberDataTable>
            </template>
        </CyberMain>
        <!-- 案件編集ダイアログ -->
        <CyberDialog ref="SubjectDialog">
            <template v-slot:caption>{{ editSubject_caption }}</template>
            <template v-slot:main>
                <EditSubject
                    :key="edit_key"
                    :values="subject"
                    :smode="mode"
                    :role="role"
                    :client="select_client"
                    :status="1"
                    @click_task="click_task"
                    @enter="enter_SubjectDialog"
                    @remove="remove_subject"
                    @add_task="add_task"
                    @error="error" 
                />
            </template>
        </CyberDialog>
        <!-- 案件追加ダイアログ -->
        <!-- <CyberDialog ref="CreateSubjectDialog">
            <template v-slot:caption>案件追加</template>
            <template v-slot:main>
                <Create
                    :key="create_key"
                    :addNewDate=0
                    @close_dialog="close_CreateSubjectDialog"
                    @enter="add_subject"
                />
            </template>
        </CyberDialog> -->
        <!-- 一括編集ダイアログ -->
        <CyberDialog ref="GroupDialog" >
            <template v-slot:caption>一括編集</template>
            <template v-slot:main>
                <!-- 案件情報 -->
                <Group :selectItems="selectItems" @enter="enter_GroupDialog" />
            </template>
        </CyberDialog>
        <!-- 印刷ダイアログ -->
        <CyberDialog ref="PrintDialog" >
            <template v-slot:caption>印刷</template>
            <template v-slot:main>
                <PrintParam :key="reset_key"/>
            </template>
        </CyberDialog>   
        <MsgBox ref="MsgBox">
			<template v-slot:caption></template>
			<template v-slot:main>
				<v-container>
					<v-row align-content="center" >
						<v-col align-content="center">
							<div class="text-h6">{{ err_message }}</div>
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>
        <AlartBox ref="AlartBox">
			<template v-slot:main>
                <div class="text-h6">{{ alarmText }}</div>
			</template>			
		</AlartBox>     
    </div>
</template>

<script>
	import { mapState, mapGetters } from 'vuex'
    import CyberMain from "../CyberMain";
    import CyberSideMenu from "../CyberSideMenu";
    import CyberDataTable from "../CyberDataTable";
    import CyberDialog from "../CyberDialog";
    import EditSubject from "./EditSubject";
    // import Create from "./Create";
    import Group from "./Group";
	import PrintParam from "./PrintParam";    
    import MsgBox from "../msgbox";
    import commFunctions from '../../functions/commFunctions';
    import AlartBox from "../AlartBox";
    const { zeroPadding,getFormatDate } = commFunctions();
    const def_headers= [
        { text: '日', value: 'day' },
        { text: '元請', value: 'client_nick' },
        { text: '下請', value: 'subcontract_nick' },
        {
            text: '案件名',
            align: 'start',
            sortable: true,
            value: 'subject',
            width:'280',
            groupable:false
        },
        { text: '架払', value: 'schedule_class' ,  groupable:false, align:'end'  },
        { text: '請求日', value: 'bill_issue' ,  groupable:false, align:'end'  },
        { text: '請負金額', value: 'amount' ,  groupable:false, align:'end'  },
        { text: '出来高', value: 'pay_ratio'},
        { text: '出来高金額', value: 'bill_amount' , groupable:false, align:'start' },
        { text: '', value: 'actions', sortable: false, align: 'end'  },
        // { text: '', value: 'data-table-expand' },
    ];
    const def_headers_client= [
        { text: '日付', value: 'start_date' },
        { text: '下請', value: 'subcontract_nick' },
        {
            text: '案件名',
            align: 'start',
            sortable: true,
            value: 'subject',
            width:'280',
            groupable:false
        },
        { text: '架払', value: 'schedule_class' ,  groupable:false, align:'end'  },
        { text: '請求日', value: 'bill_issue' ,  groupable:false, align:'end'  },
        { text: '請負金額', value: 'amount' ,  groupable:false, align:'end'  },
        { text: '出来高', value: 'pay_ratio'},
        { text: '出来高金額', value: 'bill_amount' , groupable:false, align:'start' },
        { text: '', value: 'actions', sortable: false, align: 'end'  },
        // { text: '', value: 'data-table-expand' },
    ];

    export default {
        components: {
            CyberMain,
            CyberSideMenu,
            CyberDataTable,
            CyberDialog,
            EditSubject,
            // Create,
            Group,
            PrintParam,
            MsgBox,
            AlartBox,
        },
        props: ["subjects", "role", "searchText"],
        data: function () {
          return {
            name: "",
            isNaviVisible:true,
            // create_key: true,
            category_value:null,
            tableItems:[],
            mode:"",
            subject:[],
            task:[],
            selectItems:[],
            selectTableRowNo:-1,

            headers:[],

            // 
            header_title:"",

            // フィルターアイテム
            filterItems: [],

            // コマンドアイテム
            commandItems: [
                { title: '新規' ,name:'create' , icon:'mdi-folder-plus-outline'},
                { title: '一括' ,name:'group', icon:'mdi-pencil-box-multiple-outline' },
            ],

            // 印刷アイテム
            printDetailItems: [
                { title: '見積書',name:'quotation', icon:'mdi-receipt-outline' },
                // { title: '発注書',name:'order', icon:'mdi-receipt-outline' },
            ],
            // ヘッダー部印刷アイテム
            printHeaderItems: [
                { title: '受注管理',name:'ManageSheet', icon:'mdi-receipt-outline' },
            ],
            // 印刷３アイテム
            print3Items: [
                // { title: '請求管理表',name:'ManageSheet', icon:'mdi-receipt-outline' },
            ],
            reset_key:false,
            err_message:"",
            editSubject_caption:"",

            // 
            sideMenu_items: [],
            selected_sideMenu_item:[],
            category_key:'date',
            category_item:null,

            select_client : null,

            edit_key:false,
            alarms:[],
            alarmText:"",
            timerIds:[],
          }
        },


        methods: {
            setAleam() {
                this.timerIds.forEach(function(id) {
                    clearTimeout(id);
                })
                this.alarms=this.$store.getters.getAlarms;  
// alert(JSON.stringify(this.alarms))
                this.timerIds = []
                for (let i=0;i< this.alarms.length;i++) {
                    const timestamp1 = new Date( this.alarms[i].start_time).getTime() - this.alarms[i].alarm_interval*60*1000;
                    const timestamp2 = new Date().getTime();
                    const seconds = timestamp1 - timestamp2
                    if (seconds>0 && this.alarms[i].status==1) {
// alert(this.alarms[i].alerm_interval);
                        let id = setTimeout((value) => {
                            this.alarmText=value.name
                            this.$refs.AlartBox.open()
                        }, seconds, this.alarms[i])
                        this.timerIds.push(id);
                    }
                }
            },
            init(){

                this.sideMenu_items.push({
                    text: '案件一覧（年月別）',
                    icon: 'mdi-calendar',
                    key: 'date',
                    action: 'mdi-ticket',
                    active: true,
                })
                this.sideMenu_items.push({
                    text: '案件一覧（顧客別）',
                    icon: 'mdi-account-tie',
                    key: 'prime',
                    action: 'mdi-ticket',
                    active: true,
                })
                // this.sideMenu_items.push({
                //     text: '下請',
                //     icon: 'mdi-account-hard-hat',
                //     key: 'subcontract',
                //     action: 'mdi-ticket',
                //     active: true,
                // })

   

                //
                let dateItems = []; 
                axios.get('/subject/ym').then((res) => {
                    res.data.forEach(ym => {
                        dateItems.unshift({ 
                            text: ym.year+"年"+ym.month+"月", 
                            label: ym.year+"年"+ym.month+"月", 
                            icon: 'mdi-label', 
                            category:'date', 
                            key:ym.year+"-"+zeroPadding(ym.month,2), 
                            value:ym 
                        })
                    })
                    let sideMenu_items = this.sideMenu_items.find((item) => item.key === 'date');
                    sideMenu_items.items = dateItems;
// alert(JSON.stringify(dateItems[0].key))
                    this.click_sideMenu_item(dateItems[0])
                });

                // 
                let primeItems = [];
                axios.get('/clients/prime').then((res) => {
                    res.data.forEach(client => {
                        primeItems.push({ 
                            text: client.nickname, 
                            icon: 'mdi-label', 
                            category:'prime', 
                            key:client.id, 
                            value:client 
                        })
                    })
                    let sideMenu_items = this.sideMenu_items.find((item) => item.key === 'prime');
                    sideMenu_items.items = primeItems;
                });
    
                // 
                // let subItems = [];
                // axios.get('/clients/subcontract').then((res) => {
                //     res.data.forEach(client => {
                //         subItems.push({ 
                //             text: client.nickname, 
                //             icon: 'mdi-label', 
                //             category:'subcontract', 
                //             key:client.id, 
                //             value:client 
                //         })
                //     })
                //     let sideMenu_items = this.sideMenu_items.find((item) => item.key === 'subcontract');
                //     sideMenu_items.items = subItems;
                // });

                // 
                this.setAleam()
                this.$refs.SideMenu.expand([0]);  
                this.click_sideMenu_category({key:"date"})
            },
            click_sideMenu_category(e) {
// alert(JSON.stringify(e))
                this.headers=[]
                this.header_title="";
                // this.$refs.CyberDataTable.tableItems=[]
                this.category_key = e.key
                if (e.key=="date") {
                    e.items[0].label=e.items[0].text
                    this.click_sideMenu_item(e.items[0])
                } else {
                    this.header_title = "（顧客別）"
                }
				// this.selectTableRowNo = -1;
				// this.headerTitle=""
				// this.headerItems = this.header_date;
				// axios.post('/invoices/extract', { category:"", key:"" })
				// 	.then((res) => {
				// 		this.headerTitle = "";
				// 		this.tableItems = res.data;
				// });
			},
			click_sideMenu_item(e) {
// alert(JSON.stringify(e))
                this.header_title = "（" + e.label + "）"
                this.selected_sideMenu_item=e;
                this.category_item=e.category
                this.category_value=e.key
                if (e.category=="date") {
                    this.select_client = null;
                    const para = {
                        para1:1, 
                        year:e.key.substr(0,4), 
                        month:e.key.substr(5,2),
                        client:0 
                    }
                    axios.post('/schedule/mansheet', para).then((res) => {
// alert(JSON.stringify(res.data))
                        this.tableItems = res.data;
                        this.headers = def_headers
                    })
                } else {
                    let sideMenu_item = this.sideMenu_items[1].items.find((item) => item.key === e.key);
                    this.select_client = sideMenu_item.value;
                    const para = { para1:2,year:0,month:0,client:e.key }
                    axios.post('/schedule/mansheet', para).then((res) => {
                        this.tableItems = res.data;
                        this.headers = def_headers_client
                    })
                }
			}, 
            add() {
                this.edit_key = !this.edit_key;
// alert(JSON.stringify(select_client))
                let start_date = getFormatDate();;
                this.subject = {
                    'id':-1,
                    'class':1,
                    'start_date':start_date,
                    'end_date':start_date,
                    'expenses':0,
                    'discount':0,
                    'amount_subcontract':0,
                    'process':10,
                    'v_tasks':[],
                }
                if (this.selected_sideMenu_item.category=="date") {
                    // start_date = this.selected_sideMenu_item.key + "-01";
                } else {
                    // client_id = this.selected_sideMenu_item.key;
                    this.subject.client_id = this.select_client.id;
                    this.subject.pay_ratio = this.select_client.pay_ratio;
                    this.subject.pay_status = this.select_client.pay_status;
                    this.subject.tax_state = this.select_client.tax_state;
                    this.subject.message1_title = this.select_client.message1_title;
					this.subject.message2_title = this.select_client.message2_title;
					this.subject.message3_title = this.select_client.message3_title;
					this.subject.message4_title = this.select_client.message4_title;
					this.subject.message1 = this.select_client.message1;
					this.subject.message2 = this.select_client.message2;
					this.subject.message3 = this.select_client.message3;
					this.subject.message4 = this.select_client.message4;
                }
                this.mode ="add";
                this.editSubject_caption="案件追加"
                this.$refs.SubjectDialog.open({
                    isFullscreen:true
                });
            },                       
            click_TableRow(e) {
                this.edit_key = !this.edit_key;
// alert(JSON.stringify(e))
                this.selectTableRowNo = e.rowNo;
                axios.get('/subject/' + e.value.subject_id).then((res) => {
                    this.subject = res.data;
                    this.subject.schedule_id=e.value.schedule_id;
// alert(JSON.stringify(this.subject))
                    this.mode ="upd";
                    if (this.subject=="") {
                        alert("他の利用者により削除されています。")
                        this.$refs.subjectTable.refrash();
                    } else {
                        this.editSubject_caption="案件編集"
                        this.$refs.SubjectDialog.open({
                            isFullscreen:true
                        });
                    }
                });
            },
            copy(e) {
                this.edit_key = !this.edit_key;
                this.selectTableRowNo = e.rowNo;
                // this.subject = JSON.parse(JSON.stringify(e.val))
                // this.mode = "add"
                // this.$refs.SubjectDialog.open({
                //     isFullscreen:true
                // });
                axios.get('/subject/' + e.val.id).then((res) => {
                    this.subject = res.data;
                    this.subject.process = 1;
                    this.mode ="add";
                    if (this.subject=="") {
                        alert("他の利用者により削除されています。")
                        this.$refs.subjectTable.refrash();
                    } else {
                        this.editSubject_caption="案件コピー"
                        this.$refs.SubjectDialog.open({
                            isFullscreen:true
                        });
                    }
                });
            },
            group() {
                this.selectItems = this.$refs.CyberDataTable.getSelectTableRows();
                this.$refs.GroupDialog.open({
                    isFullscreen:true
                });
            },
            select_TableRow(e) {
// alert(JSON.stringify(e));
            },
            // close_SubjectDialog() {
            //     this.$refs.SubjectDialog.close();
            // },
            // close_CreateSubjectDialog() {
            //     this.$refs.CreateSubjectDialog.close();
            // },
            enter_SubjectDialog(e) {
// alert(JSON.stringify(e.mode));
// alert(JSON.stringify(e.value));
                if (e.mode=="add") {
                    if (e.value.process<50)  {
                        this.tableItems.unshift(e.value);
                    }
                } else {
// alert(JSON.stringify(e.value));
                    if (e.value.process<50)  {
                        this.tableItems.splice(this.selectTableRowNo  , 1, e.value);
                    } else {
                        this.tableItems.splice(this.selectTableRowNo  , 1);
                    }
                }
                this.$refs.SubjectDialog.close();
            },
            enter_GroupDialog(e) {
                let tableItems = this.tableItems;
                let index = -1;
                e.filter(function(filterIiem) {
                    index = tableItems.findIndex(({id}) => id === filterIiem.id);
                    tableItems.splice(index , 1, filterIiem);
                })
                this.$refs.CyberDataTable.clearSelectTableRows();
                this.$refs.GroupDialog.close();
            },
            // add_subject(val) {
            //     const sideMenu_item = this.sideMenu_items.find((item) => item.key === this.category_key);
            //     let chileItems = sideMenu_item.items
            //     const key = val['start_date'].substr( 0, 7 );
            //     if (chileItems.some((item) => item.key === key)==false) {
            //         let ym = {'year':key.substr( 0, 4 ),'month':Number(key.substr( 5, 2 ))}
            //         chileItems.unshift({ 
            //             text: ym.year+"年"+ym.month+"月", 
            //             icon: 'mdi-label', 
            //             category:this.category_key, 
            //             key:key, 
            //             value:ym 
            //         })
            //     } ;
            //     this.tableItems.unshift(val);
            //     this.$refs.CreateSubjectDialog.close();
            // },
            add_task() {

            },
            remove_subject() {
// alert(JSON.stringify(this.tableItems[this.selectTableRowNo]))
                const key = this.tableItems[this.selectTableRowNo]['start_date'].substr( 0, 7 );
// alert(JSON.stringify(key))
                this.tableItems.splice(this.selectTableRowNo  , 1);
                if (this.tableItems,length == 0) {
                    const sideMenu_item = this.sideMenu_items.find((item) => item.key === this.category_key);
                    let chileItems = sideMenu_item.items
                    const id = chileItems.findIndex((item) => item.key === key)
                    chileItems.splice(id,1)
// alert(JSON.stringify(chileItems))
                }
                this.$refs.SubjectDialog.close();
            },
            error(e) {
                this.err_message = e
                this.$refs.MsgBox.open('ok')
            },            
            click_task(task) {
// alert(JSON.stringify(task));
                this.task = task;
                this.$refs.TaskDialog.open({
                    isFullscreen:false
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
            print(e) {
// alert(JSON.stringify(e));
// alert(JSON.stringify(this.category_value));
                // let selected = this.$refs.CyberDataTable.getSelectTableRows();
                if (e.name=="ManageSheet") {
                    e.items = { 
                        key:this.category_key, 
                        item:{
                            id:this.selected_sideMenu_item.key,
                            value:this.selected_sideMenu_item.label
                        } 
                    } ;
                }
                localStorage.setItem('selectItems', JSON.stringify(e.items));
                localStorage.setItem('printName', JSON.stringify(e.name));
                window.open("/print", '_blank');
            },
            Print3(name) {
                localStorage.setItem('printName', JSON.stringify(name));
                this.reset_key = !this.reset_key;
                this.$refs.PrintDialog.open({
                    isFullscreen:false,
                    width:"500px"
                });                
                // const items = {'year':moment(this.value).year(),'month':moment(this.value).month()};
                // localStorage.setItem('selectItems', JSON.stringify(items));                
				// window.open("/print", '_blank');

            },           
        },
        computed: {
			...mapGetters([ 'getSubjectClasses' ]),
			...mapGetters([ 'getProcessNames' ]),
        },
        created () {
            // this.filterItems.push({lavel:"区分", name:"subjectClasses", value:this.$store.getters.getSubjectClasses});
            // this.filterItems.push({lavel:"状態", name:"processNamees", value:this.$store.getters.getProcessNames});
            this.tableItems = this.subjects; 
            this.init();          
        },
        mounted() {
        }
    }
</script>
<style lang="scss" scoped>



</style>

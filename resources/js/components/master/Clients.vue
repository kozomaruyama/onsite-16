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
            @search="search"
            @filter="filter"
            @command="command"
            @print="print"
            @click_TableRow="click_TableRow"
            @add="add"
            @del="del"
        >
        <template v-slot:title>{{ status_text }}一覧</template>
        </CyberDataTable>
        <!-- 編集ダイアログ -->
        <CyberDialog ref="EditDialog" >
            <template v-slot:caption>{{caption}}</template>
            <template v-slot:main>
                <!-- タスク情報 -->
                <Client 
                    :values="client" 
                    :mode="mode"
                    :key="create_key"
                    @enter="enter" 
                    @error="error" 
                    @del="del"
                />
            </template>
        </CyberDialog>
		<MsgBox ref="MsgBox" :style="style" @enter="msgBox_enter">
			<template v-slot:caption></template>
			<template v-slot:main>
				<v-container>
					<v-row align-content="center" >
						<v-col align="center">
							<div class="text-h6">{{msgBox_text}}</div>
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>        
	</div>
</template>
<script>
	import { mapState, mapGetters } from 'vuex'
	import CyberDataTable from "../CyberDataTable";
    import CyberDialog from "../CyberDialog";
    import Client from "./Client";
    import MsgBox from "../msgbox";
	import dbCompanyFunctions from '../../functions/dbCompanyFunctions'
	const { getCompany } = dbCompanyFunctions()    
	export default {
		components: {
			CyberDataTable,
            CyberDialog,
            Client,
            MsgBox,
		},
        props: ["dessertItems","status"],
		data: function () {
			return {
                printItems:[],
				searchText: "",
				selectItems: [],
				headerItems: [
					{
						text: '名称',
						align: 'start',
						sortable: true,
						value: 'name',
                        groupable:false
					},
					{ text: '分類', value: 'client_class' },
					{ text: '電話', value: 'tel1', groupable:false },
					{ text: '締日', value: 'bill_closing' },
					{ text: '支払日', value: 'bill_payment_text' },
					// { text: '支払率', value: 'pay_ratio' },
					// { text: '支払区分', value: 'schedule_class' },
                    { text: '', value: 'actions', sortable: false, align: 'right'  },
					// { text: '', value: 'actions', sortable: false, align: 'right'  },
				],
				tableItems: [],
				// フィルターアイテム
				filterItems: [],
                //
                editDialog:false,
                // コマンドアイテム
                commandItems: [
                    { title: '新規' ,name:'create'},
                    { title: '一括' ,name:'groupUpdate' },
                ],
                // 印刷アイテム
                PrintItems: [
                    { title: this.status_text + '一覧',name:'productsList' },
                ],
                client:[],
                mode:"edit",
                style:"ok",
                selectTableRowNo:-1,
                status_text:"",
                caption:"",
                clientClassItems:[],
				company:[],    
                msgBox_text:"",  
                create_key:false,
			}
		},
		created() {
            if (this.status==1) {
                this.status_text="元請"
            } else {
                this.status_text="下請"
            }
            this.tableItems = this.dessertItems;
            this.clientClassItems = this.$store.getters.getClientClasses;
			const company_id=this.$store.getters.getCompanyId[0].company_id;
			getCompany(company_id, (company) => {
				this.company = company;
			});            
		},
		mounted() {
		},
		methods: {
			add() {
                this.client={
                    'class':1, 
                    'pay_status':10,
                    'pay_ratio':60,
                    'tax_state':2,
                    'bill_payment_class':2,
                    'message1_title':this.company.message1_title,
                    'message2_title':this.company.message2_title,
                    'message3_title':this.company.message3_title,
                    'message1':this.company.message1,
                    'message2':this.company.message2,
                    'message3':this.company.message3,
                };
                this.create_key = !this.create_key
                this.mode = "create"
                this.caption = this.status_text + "-新規"
				this.$refs.EditDialog.open({
                    isFullscreen:false
				});
			},
            del() {
                this.tableItems.splice(this.selectTableRowNo  , 1);
                this.$refs.EditDialog.close();
            },
			enter(e) {
                e.value.client_class = this.clientClassItems.find(val => val.no == e.value.class).name;
                if (e.mode=="create") {
                    this.tableItems.push(e.value);
                    this.$refs.EditDialog.close();
                } else {
                    this.tableItems.splice(this.selectTableRowNo  , 1, e.value);
                    this.$refs.EditDialog.close();
                }
            },
            error(e) {
                this.msgBox_text = e
                this.$refs.MsgBox.open('err')
            },
			msgBox_enter(para) {
				if (para='err') {

				}
			},            
            print() {},
			remove() {},
			edit() {},
			close() {},
			itemSelected(  ) {},
			group() {}  ,


            search(){},
            filter(){},
            command(val){
                switch (val) {
                    case 'create':
                        this.client = [];
                        this.mode = "create"
                        this.caption=this.status_text + "-新規"
                        this.$refs.EditDialog.open({
                            isFullscreen:false
                        });
                        break;
                    case 'group':
                        this.$refs.GroupDialog.open({
                            isFullscreen:false
                        });
                        break;
                }
            },
            click_TableRow(e){
                this.selectTableRowNo = e.rowNo;
                axios.get('/clients/' + e.value.id).then((res) => {
// alert(JSON.stringify(res))
                    this.client = res.data;
                    if (this.client=="") {
                        alert("他の利用者により削除されています。")
                        // this.$refs.subjectTable.refrash();
                    } else {
                        this.mode = "edit"
                        this.caption=this.status_text + "-編集"
                        this.$refs.EditDialog.open({
                            isFullscreen:false
                        });
                    }
                });
            },

            search(val) {
                this.searchText=val;
            },

		},
        computed: {
			...mapGetters([ 'getClientClasses' ]),
		},       
	}
</script>

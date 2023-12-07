<template>
    <div>
        <v-container fluid class="bg-peimary px-5">
            <v-row no-gutters class="mt-2">
                <v-text-field class="d-none" id="index" v-model="values.id"></v-text-field>
                <v-text-field class="d-none" id="client_id" v-model="values.client_id"></v-text-field>
                <v-col cols="12" md="2" class="px-2">
                    <v-text-field label="コード" id="code" v-model="values.code" dense readonly></v-text-field>
                </v-col>
                <!-- <v-col cols="12" md="2" class="px-2">
                    <v-select :items="subjectClasses" item-text="name" item-value="no" label="区分" id="class" v-model="values.class" dense></v-select>
                </v-col> -->
                <v-col cols="12" md="3" class="px-2">
                    <v-text-field label="発注番号" id="order_code" v-model="values.order_code" dense readonly></v-text-field>
                </v-col>
            </v-row>
            <!-- <v-row no-gutters class="">
                <v-col cols="12" md="2" class="px-2">
                    <v-select :items="mainContracts" item-text="name" item-value="id" label="元請" v-model="values.client_id" disabled dense></v-select>
                </v-col>
                <v-col cols="12" md="2" class="px-2">
                    <v-select :items="people" item-text="name" item-value="id" label="担当" v-model="values.person_id" dense></v-select>
                </v-col>            
                <v-col cols="12" md="2" class="px-2">
                    <v-select :items="subContracts" item-text="name" item-value="id" label="施工" v-model="values.subcontract_id" disabled dense></v-select>
                </v-col>
            </v-row> -->
            <v-row no-gutters class="">
                <v-col cols="12" md="10" class="px-2">
                    <v-text-field label="名称" id="name" v-model="values.name" dense ></v-text-field>
                </v-col>
            </v-row>
            <v-row no-gutters class="">
                <v-col cols="12" md="10" class="px-2">
                    <v-text-field label="仕様" id="breakdown" v-model="values.breakdown" dense ></v-text-field>
                </v-col>
            </v-row>
            <v-row no-gutters class="">
                <v-col cols="12" md="6" class="px-2">
                    <v-text-field label="現場名" id="site_name" v-model="values.site_name" dense ></v-text-field>
                </v-col>
                <v-col cols="12" md="6" class="px-2">
                    <v-text-field label="現場住所" id="site_address" v-model="values.site_address" dense ></v-text-field>
                </v-col>
            </v-row>
            <v-row no-gutters class="">
                <v-col cols="12" md="3" class="px-2">
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
                            label= "着工日"
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
                <v-col cols="12" md="3" class="px-2">
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
                            label= "完了日"
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
                <!-- <v-col cols="12" md="1" class="mt-2">
                    <input type="checkbox" id="checkbox" v-model="values.isDay"><label class="mx-1">全日</label>
                </v-col>
                <v-col cols="12" md="2" class="px-2">
                    <v-menu
                        ref="dateMenu3"
                        v-model="dateMenu3"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            v-model="values.bill_arriving"
                            label= "請求日"
                            prepend-icon="mdi-calendar"
                            v-bind="attrs"
                            v-on="on"
                            dense
                        ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="values.bill_arriving"
                            no-title
                            locale="ja"
                            @input="dateMenu3 = false"
                        ></v-date-picker>
                    </v-menu>
                </v-col> -->
                <!-- <v-col cols="12" md="2" class="px-2">
                    <v-select label="状態" :items="processNames" item-text="name" item-value="no" id="process" v-model="values.process" dense></v-select>
                </v-col>        -->
                <!-- <v-col cols="12" md="1" class="px-2">
                    <v-text-field v-model="values.percentage" label="進捗" suffix="％" type="number" min="0" max="100" dense readonly></v-text-field>
                </v-col> -->
            </v-row>

            <!-- <v-row>
                <v-simple-table dense>
                    <template v-slot:default>
                    <thead>
                        <tr>
                        <th class="text-left w-25">
                            案件名
                        </th>
                        <th class="text-left w-25">
                            出来高
                        </th>
                        <th class="text-left">
                        </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in values.v_tasks" :key="item.id">
                            <td>{{ item.name }}</td>
                            <td><v-text-field v-model="item.percentage" suffix="％" type="number" min="0" max="100"></v-text-field></td>
                            <td><v-slider hint="Im a hint" v-model="item.percentage" min="0" max="100" ></v-slider></td>
                        </tr>
                    </tbody>
                    </template>
                </v-simple-table>
            </v-row> -->

            <FileManeger ref="FileManeger" :image_class="1" :link_id="values.id"></FileManeger>

            <v-divider></v-divider>

            <v-row class="m-0 p-0">
                <!-- <v-col class="col-auto">
                    <v-btn depressed small fab @click="del()">
                        <v-icon>mdi-delete-outline</v-icon>
                    </v-btn>
                </v-col> -->
                <v-col></v-col>
                <v-col class="col-auto">
                    <v-btn depressed class="text-white bg-primary" color="success" @click="enter()">
                        <v-icon left>mdi-content-save-outline</v-icon>登　録
                    </v-btn>
                </v-col>
            </v-row>

        </v-container>
    </div>
</template>
<script>
    import { mapState, mapGetters } from 'vuex'
    import FileManeger from "../FileManeger";
    import commFunctions from '../../functions/commFunctions';
    const { numToComma, commaToNum, swStatusFlag, getStatusFlag, calcTax } = commFunctions();
    export default {
        components: {
            FileManeger,
        },
        props: ["values","schedule"],
        data: function () {
            return {
                subjectClasses:[],
                taxClassItems:[],
                processNames:[],

				// コマンドアイテム
				commandItems: [
                    { title: '一　括' ,name:'group', icon:'mdi-pencil-box-multiple-outline' },
                    { title: '' ,name:'divider' },
                    { title: '削除' ,name:'delete', icon:'mdi-delete-outline' },
				],
				// 印刷アイテム
				printItems: [
					{ title: '請求書',name:'invoice', icon:'mdi-receipt-outline'},
				],
                //
                dateMenu1:false,
                dateMenu2:false,
                dateMenu3:false,
                //
                mainContracts: [],
                subContracts: [],
                people:[],            
            }
        },
        created() {
            //
            this.subjectClasses = this.$store.getters.getSubjectClasses;
            this.taxClassItems = this.$store.getters.getTaxClasses;
            this.processNames = this.$store.getters.getProcessNames;
        },
        mounted() {
            this.$refs.FileManeger.fileDownload();
            this.getMainContractor();
            this.getSubContracts();   
            this.getProple(this.values.client_id); 
// alert(JSON.stringify(this.values))       
        },
        watch: {
//             'values.v_tasks': {
//                 handler( newVal, oldVal ){
//                     let total = 0
//                     values.v_tasks.forEach(task => {
//                         total += task.percentage
//                     })
// alert(JSON.stringify(total / values.v_tasks))
//                 },
//                 deep: true                        
//             },
        },
        computed: {
            ...mapGetters([ 'getSubjectClasses' ]),
            ...mapGetters([ 'getTaxClasses' ]),
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
            expenses: function() {
                let amount = 0;
                let items = this.values.v_tasks;
                if (items.length>0) {
                    items.forEach(item => {
                        amount += item.cost * item.volume;
                    });
                } else {
                    amount = this.values.expenses;
                }
                return amount;
            },
            tax: function() {
                if (this.expenses > 0) {
                    let amount = this.expenses - this.values.discount;
    // alert(JSON.stringify(amount));
                    let totals = calcTax(amount,this.values.tax_state)
                    this.values.tax = totals.tax;
                } else {
                    this.values.tax = 0;
                }
                return this.values.tax ;
    // alert(this.values.tax);
                // this.values.bill_amount = totals.total;
            },
            bill_amount: function() {
                let amount = this.expenses - this.values.discount;
    // alert(this.values.discount);
                if (this.values.tax_state == 2) {
                    amount += this.tax;
                }
                this.values.bill_amount = amount;
                return amount;
            },
            percentage: function() {
                let total = 0
                let count = 0
                this.values.v_tasks.forEach(task => {
                    total += task.percentage
                    ++count
                })
                return total / count
            },
        },
        methods: {
            command(val) {
                switch (val) {
                    case 'group':
alert("タスクのグループ編集を実装予定")
                        // this.selectItems = this.$refs.CyberDataTable.getSelectTableRows();
                        // this.$refs.GroupDialog.open({
                        //     isFullscreen:true
                        // });
                        break;
                    case 'delete':
                        this.del();
                        break;
                }
            },
            print(val) {
                switch (val) {
                    case 'invoice':
alert("請求書の印刷機能を実装予定")
                        break;
                }
            },
            enter() {
                const subject = Object.assign({},this.values)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
                // subject.percentage = this.percentage
                const tasks = Object.assign({},this.values.v_tasks)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
// alert(JSON.stringify(subject))
// alert(JSON.stringify(tasks))
                axios.post('/subject', { subject: subject, tasks: tasks, mode:'with' } )
                    .then((res) => {
// alert(JSON.stringify(res.data))
                        if (res.status==200) {
//                             axios.put('/schedule/update_percentage/' + schedule.id, schedule).then((res) => {
                                this.$refs.FileManeger.fileUpload();
                                this.$emit('enter',{ value:res.data, mode:"upd" });

                            // });
                        }
                });
            },
            // del() {
            //     if (window.confirm('削除して宜しいですか？')) {
            //         // axios.delete('/api/subjects/' + this.values.id).then((res) => {
            //         axios.delete('/subject/' + this.values.id).then((res) => {
            //             this.$emit('remove');
            //             // this.$emit('close');
            //         });
            //     }
            // },
            click_TableRow(e) {
                this.task = e.value;
                this.$refs.TaskDialog.open({
                    isFullscreen:false
                });
            },
            task_add() {
                this.task = {
                    'id':'-1',
                    'subject_id':this.values.id,
                    'cost':0,
                    'amount':0,
                    'volume':0,
                    'start_date':this.values.start_date,
                    'end_date':this.values.end_date,
                };
                this.$refs.TaskDialog.open({
                    isFullscreen:false
                });
            },
            getMainContractor() {
                axios.get('/clients/prime').then((res) => {
                    this.mainContracts = res.data;
                    this.mainContracts.unshift({'id':0,'name':'','color':'grey'}); 
                });
            },
            getSubContracts() {
                axios.get('/clients/sub').then((res) => {
                    this.subContracts = res.data;
                    this.subContracts.unshift({'id':0,'name':'','color':'grey'}); 
                });
            },
            getProple(client_id) {
                axios.get('/people/byclient/' + client_id).then((res) => {
                    this.people = res.data;
                });                
            }            
        },
    }
</script>


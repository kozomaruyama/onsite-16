<template>
    <div>
        <v-container fluid class="bg-peimary px-5">
            <v-row no-gutters class="mt-2">
                <v-text-field class="d-none" v-model="value.id" />
            </v-row>
            <v-row no-gutters class="mt-0">
                <v-col v-if="mode=='add'" cols="12" md="4" class="p-0 mx-2 mt-1 ">
                    <v-select class="m-0 p-0" dense @change="subContracter_selected" :items="subContracters" item-text="name" item-value="id" label="下請" v-model="value.client_id" ></v-select>
                </v-col>
                <v-col cols="12" md="2" class="p-0 mx-2 my-0 mt-0">
                    <v-menu
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                v-model="value.closing_date"
                                dense
                                label="締切日"
                                prepend-icon="mdi-calendar"
                                v-on="on"
                            ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="value.closing_date"
                            no-title
                            locale="ja"
                            @input="menu_start_date = false"
                        ></v-date-picker>
                    </v-menu>
                </v-col>
                <v-col cols="12" md="2" class="p-0 mx-2 my-0 mt-0">
                    <v-menu
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                v-model="value.pay_date"
                                dense
                                label="支払日"
                                prepend-icon="mdi-calendar"
                                v-on="on"
                            ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="value.pay_date"
                            no-title
                            locale="ja"
                            @input="menu_start_date = false"
                        ></v-date-picker>
                    </v-menu>
                </v-col>
            </v-row>
            <v-row no-gutters class="">
                <v-col cols="12" md="10" class="px-2">
                    <v-text-field label="名称" id="name" v-model="value.name" dense></v-text-field>
                </v-col>
            </v-row>
            <v-row no-gutters class="">
                <v-col cols="12" md="2" class="px-2">
                    <v-text-field type="number" label="数量" v-model="value.volume" dense></v-text-field>
                </v-col>
                <v-col cols="12" md="2" class="px-2">
                    <v-select :items="unitNames" item-text="name" item-value="no" v-model="value.unit" value="0" dense></v-select>
                </v-col>

                <v-col cols="12" md="2" class="px-2">
                    <v-text-field type="number" label="単価" v-model="value.cost" dense></v-text-field>
                </v-col>
                <v-col cols="12" md="2" class="px-2">
                    <v-text-field type="number" label="金額" readonly v-model="amount" dense></v-text-field>
                </v-col>
                <v-col cols="12" md="2" class="p-0 mx-2 my-0 mt-0">
                    <v-menu
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                v-model="value.start_date"
                                dense
                                label="開始日"
                                prepend-icon="mdi-calendar"
                                v-on="on"
                            ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="value.start_date"
                            no-title
                            locale="ja"
                            @input="menu_start_date = false"
                        ></v-date-picker>
                    </v-menu>
                </v-col>
                <!-- <v-col cols="12" md="2" class="p-0 mx-2 my-0 mt-0">
                    <v-menu
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                v-model="value.end_date"
                                dense
                                label="終了日"
                                prepend-icon="mdi-calendar"
                                v-on="on"
                            ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="value.end_date"
                            no-title
                            locale="ja"
                            @input="menu_start_date = false"
                        ></v-date-picker>
                    </v-menu>
                </v-col> -->
            </v-row>
            <v-row no-gutters class="">
                <v-col cols="12"  class="px-2">
                    <v-text-field label="備考" v-model="value.memo" dense></v-text-field>
                </v-col>
            </v-row>
            <v-row no-gutters class="">
				<v-col v-if="value.class>=30" cols="12" md="3" class="px-2 mt-2">
					<input type="checkbox" v-model="separate" @change="separate_changed"><label class="mx-1">欄外項目</label>
				</v-col>
            </v-row>
    
            <v-divider></v-divider>
    
            <v-row class="m-0 p-0">
                <!-- <v-col v-if="mode=='edit' && value.class>=30" class="col-auto"> -->
                <v-col v-if="mode=='edit'" class="col-auto">
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
      
        </v-container>
		<MsgBox ref="MsgBox" @enter="msgBox_enter">
			<template v-slot:caption></template>
			<template v-slot:main>
				<v-container>
					<v-row align-content="center" >
						<v-col align-content="center">
							<div class="text-h6">削除してよろしいですか？</div>
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>
		<MsgBox ref="errMsgBox">
			<template v-slot:caption></template>
			<template v-slot:main>
                <v-col align="center">
                    <div class="text-h6">{{ errMessage }}</div>
                </v-col>
			</template>			
		</MsgBox>
    </div>
</template>
<script>
    import { mapState, mapGetters } from 'vuex'
    import CyberDialog from "../CyberDialog";
    import CyberDate from "../CyberDate";
    import MsgBox from "../msgbox";
    import commFunctions from '../../functions/commFunctions';
    const { numToComma, commaToNum, swStatusFlag, getStatusFlag, calcTax, getBusinessDay,} = commFunctions();
    let pay_date=null;
    export default {
        components: {
            CyberDialog,
            CyberDate,
            MsgBox,
        },
        props: ["value","mode"],
        data: function () {
            return {
                selectTableRowNo:-1,
                create_key:false,
                subjectClasses:[],
                taxClassItems:[],
                scheduleClassItems:[],
                processNames:[],
                mode:'edit',
                unitNames: [],
                separate:false,
                subContracters:[],
                rules: {
					required: value => !!value || '省略不可.',
					notNull: value => isNaN(value) || '数値のみ',
				},
                errMessage:"",
            }
        },
        created() {
            //
            // this.subjectClasses = this.$store.getters.getSubjectClasses;
            // this.taxClassItems = this.$store.getters.getTaxClasses;
            // this.scheduleClassItems = this.$store.getters.getScheduleClasses;
            // this.processNames = this.$store.getters.getProcessNames; 
            this.unitNames = this.$store.getters.getUnitNames;
            this.getSubContracters();
            // pay_date = this.value.pay_date;
            
// alert(JSON.stringify(this.value))
        },
        mounted() {
            // this.getMainContractor();
            // this.getSubContracts();
            // this.getPeople();
            // this.$refs.FileManeger.fileDownload();
        },
        watch: {
            'value.class': function(newValue,oldValue) {
                if (newValue==40 ) {
                    this.separate = true;
                } else {
                    this.separate = false;
                }
            },
        },
        computed: {
            ...mapGetters([ 'getUnitNames' ]),
            // ...mapGetters([ 'getSubjectClasses' ]),
            // ...mapGetters([ 'getTaxClasses' ]),
            // ...mapGetters([ 'getScheduleClasses' ]),
            // ...mapGetters([ 'getProcessNames' ]),

            amount: function() {
                let amount = this.value.cost * this.value.volume;
                if (this.value.unit==20) {
                    amount = amount / 100;
                }
                this.value.amount = amount;
                return Math.round(amount);
            },

        },
        methods: {
            getSubContracters: function() {
				axios.get('/clients/subcontract').then((res) => {
					this.subContracters = res.data;
				});
			},
            subContracter_selected(e) {
                const subContracter = this.subContracters.find((s) => s.id == [e]);
                this.value.pay_date = getBusinessDay(this.value.pay_date_obj.year,this.value.pay_date_obj.month,subContracter.bill_payment,subContracter.bill_payment_class - 1)
                this.value.closing_date = getBusinessDay(this.value.pay_date_obj.year,this.value.pay_date_obj.month,subContracter.bill_closing)
//                 alert(JSON.stringify(this.value))
// alert(JSON.stringify(this.value.pay_date))
            },
            enter() {
                const pay = Object.assign({},this.value)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
// alert(JSON.stringify(pay))
                if (this.inputCheck()) {
                    if (this.mode == 'add') {
                        axios.post('/pay', pay).then((res) => {
                            if (res.data.status) {
                                this.$emit('enter',{ value:res.data.value, mode:"create" });
                            } else {
                                this.$emit('error', "登録できませんでした。(" + res.data.value.errorInfo[0] + ")" );
                            }
                        });
                    } else {
                        axios.put('/pay/' + pay.id, pay).then((res) => {       
    // alert(JSON.stringify(res))
                            if (res.data.status) {
                                this.$emit('enter',{ value:res.data.value, mode:"upd" });
                            } else {
                                this.$emit('error', "登録できませんでした。(" + res.data.value.errorInfo[0] + ")" );
                            }							
                        });
                        // this.$emit('enter', this.value);
                            // this.$emit('close');
                    }
                }
            },
            inputCheck() {
// alert(this.value.name)
                if (this.value.client_id === undefined || this.value.client_id === 0) {
					this.errMessage = "下請が選択されていません。"
                // } else if  (this.value.closing_date === undefined || this.value.closing_date === "") {
				// 	this.errMessage = "締切日が入力されていません。"
                // } else if  (this.value.pay_date === undefined || this.value.pay_date === "") {
				// 	this.errMessage = "支払日が入力されていません。"
                } else if  (this.value.name === undefined || this.value.name === "") {
					this.errMessage = "名称が入力されていません。"
				} else {
					this.errMessage =""
				}
				if (this.errMessage == "") {
					return true
				} else {
					this.$refs.errMsgBox.open('ok')
					return false
				}
			},
            del() {
                this.message="削除してよろしいですか？"
                this.$refs.MsgBox.open('del')
            },
            separate_changed() {
                if (this.separate) {
                    this.value.class = 40;
                } else {
                    this.value.class = 30;
                }
            },
            msgBox_enter(para) {
				if (para='del') {
					axios.delete('/pay/' + this.value.id).then((res) => {
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
			},

        },
    }
</script>
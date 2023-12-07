<template>
    <div>
        <v-container fluid class="py-0">
            <v-row class="mt-2">
                <v-col>
                    <div class="m-1">案件名称：　{{ subject.name }}</div>
                    <div class="m-1">現場住所：　{{ subject.site_address }}</div>
                    <div class="m-1">　　名称：　{{ subject.site_name }}</div>
                    <div class="m-1">工事期間：　{{ subject.start_date }} ～ {{ subject.end_date }}</div>
                </v-col>
            </v-row>
            <v-row no-gutters class="mt-2">
                <v-text-field class="d-none" v-model="invoice_master.id"></v-text-field>
                <v-text-field class="d-none" v-model="invoice_master.subject_id"></v-text-field>
                <!-- <v-col cols="12" md="2" class="px-2 mt-2">
                    <v-text-field label="締切日" id="closing_date" v-model="invoice_master.closing_date" dense></v-text-field>
                </v-col> -->
                <!-- <v-col cols="12" md="2" class="px-2 mt-2">
                    <v-text-field label="総額" v-model="invoice_master.expenses" type="number" dense readonly></v-text-field>
                </v-col>
                <v-col cols="12" md="1" class="px-2 mt-2">
                    <v-text-field label="出来高" v-model=" invoice_master.ratio" type="number" min="0" max="100" dense :readonly="invoice_details.length>0"></v-text-field>
                </v-col>
                <v-col cols="12" md="2" class="px-2 mt-2">
                    <v-text-field label="値引き" v-model="discount" type="number" dense readonly></v-text-field>
                </v-col>
                <v-col cols="12" md="1" class="px-2 mt-2">
                    <v-select label="税区分" :items="taxClassItems" item-text="name" item-value="no" id="tax_state" v-model="invoice_master.tax_state" @change = "change_taxState" dense></v-select>
                </v-col> -->
                <!-- <v-col v-if="invoice_master.tax_state==3" cols="12" md="2" class="px-2 mt-2">
                    <v-text-field label="消費税" v-model="invoice_master.tax" type="number" dense ></v-text-field>
                </v-col> -->
                <v-col  cols="12" md="2" class="px-2 mt-2">
                    <v-text-field label="消費税" v-model="invoice_master.tax" type="number" dense ></v-text-field>
                </v-col>
                <v-col cols="12" md="2" class="px-2 mt-2">
                    <v-text-field label="調整額の見出し" v-model="invoice_master.adjust_title" dense ></v-text-field>
                </v-col>
                <v-col cols="12" md="2" class="px-2 mt-2">
                    <v-text-field label="調整額" v-model="invoice_master.adjust_amount" type="number" dense ></v-text-field>
                </v-col>
                <v-col cols="12" md="2" class="px-2 mt-2">
                    <v-text-field label="請求額" v-model="total" type="number" dense readonly></v-text-field>
                </v-col>
            </v-row>
            <v-row no-gutters class="mt-2">
                <v-col cols="12" class="px-2 mt-2">
                    <v-text-field label="備考" v-model="invoice_master.memo" dense ></v-text-field>
                </v-col>            
            </v-row>
            <v-row><v-col cols="12" class="px-2">【明細】</v-col></v-row>
            <CyberSheet :headers="headers" :details="invoice_details" />
            <v-row class="m-0 p-0">
                <v-col class="col-auto">
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
            <CyberDialog ref="EditDetail">
                <template v-slot:caption>明細編集</template>
                <template v-slot:main>
                    <EditDetail :detail="detail" :task="task" :status="GroupDetail_status" @enter="enter_EditDetail" @close="close_EditDetail" @del="del_EditDetail" />
                </template>
            </CyberDialog>
            <CyberDialog ref="GroupDetail">
                <template v-slot:caption>明細一括</template>
                <template v-slot:main>
                    <Group :key="group_key" :status="GroupDetail_status" @enter="enter_GroupDialog" />
                </template>
            </CyberDialog>
        </v-container>
        <MsgBox ref="MsgBox" @enter="msgBox_enter">
			<template v-slot:caption></template>
			<template v-slot:main>
				<v-container>
					<v-row align-content="center" >
						<v-col align="center">
							<div class="text-h6">削除してよろしいですか？</div>
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>
    </div>
</template>
<script>
	import { mapState, mapGetters } from 'vuex'
    import CyberTable from "../CyberTable";
    import CyberSheet from "../CyberSheet";
    import CyberDialog from "../CyberDialog";
    import CyberCommand from "../CyberCommand";
    import EditDetail from "./EditDetail";
    import Group from "./Group";
    import MsgBox from "../msgbox";
    import { functionExpression } from '@babel/types';
	export default {
		components: {
            CyberTable,
            CyberSheet,
            CyberDialog,
            CyberCommand,
            EditDetail,
            Group,
            MsgBox,
		},
		props: [
            'invoice_master',
			'invoice_details',
            'subject',
            'bill_total',
            'fraction_total',
		],
		data: function () {
			return {
                group_key:true,
				subjectClassItems: [],
				taskClassItems: [],
				taxClassItems: [],
                headers: [
					{ name:"name",label:"名称",isLabelItem: 1,isEdit: 0 },
					{ name:"breakdown",label:"規格",isLabelItem: 0,isEdit: 0  },
					{ name:"volume",label:"数量",isLabelItem: 0,isEdit: 0  },
					{ name:"cost",label:"単価",isLabelItem: 0,isEdit: 0  },
					{ name:"amount",label:"金額",isLabelItem: 0,isEdit: 0  },
					{ name:"yield_ratio",label:"出来高(%)",isLabelItem: 0,isEdit: 0  },
					{ name:"yield_amount",label:"出来高(¥)",isLabelItem: 0,isEdit: 0  },
					{ name:"memo",label:"備考",isLabelItem: 0,isEdit: 1  },
                ],
                details:[],
                selectTableRowNo:-1,
                detail:[],
                task:[],
                selectItems:[],
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
                GroupDetail_status:true,
			}
		},
		methods: {
			close() {
                this.$emit('close');
			},
			enter() {
// alert(JSON.stringify(this.invoice_details))
                // let adjust = Number(this.bill_total) + this.invoice_master.bill_amount
                // if (adjust  > this.subject.bill_amount)  {
                //     this.invoice_master.adjust_amount = 
                //         this.invoice_master.adjust_amount - (adjust - this.subject.bill_amount) 
                //     this.invoice_master.bill_amount = this.invoice_master.bill_amount  - (adjust - this.subject.bill_amount) 
                // } 
				var master = Object.assign({},this.invoice_master)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
				var details = Object.assign({},this.invoice_details)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
// alert(JSON.stringify(master))
                axios.post('/invoice_master/update', { master: master, details: details } )
                    .then((res) => {
// alert(JSON.stringify(res.data))
						this.$emit('enter',res.data);
					}
				);
			},
			del() {
                this.$refs.MsgBox.open('del')
				// if (window.confirm('削除して宜しいですか？')) {
				// 	axios.delete('/invoice_masters/' + this.invoice_master.id)
				// 		.then((res) => {
				// 			this.$emit('remove', res.data);
				// 		}
                //     );
				// }
			},
            msgBox_enter(para) {
				if (para='del') {
					axios.delete('/invoice_masters/' + this.invoice_master.id).then((res) => {
						if (res.data.status) {
							this.$emit('remove', res.data);
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
			change_taxState(val) {
// alert(this.invoice_master.amount)
                var amount = this.invoice_master.amount	- this.invoice_master.discount;
                if (val==-1) { val = this.invoice_master.tax_state }
				switch (val) {
                    case 1:
                        this.invoice_master.tax = Math.round((amount * 10) / 110) ;
						break;
					case 2:
                        this.invoice_master.tax = Math.round((amount * 10) / 100) ;
						break;
					default:
                        this.invoice_master.tax = 0;
				}
                // this.invoice_master.tax_state = val;
				this.recalcBillAmount();
				return
			},
			recalcBillAmount() {
				if (this.invoice_master.tax_state == 1) {
                    this.invoice_master.bill_amount = Number(this.invoice_master.amount)
                                        - Number(this.invoice_master.discount);
                                        + Number(this.invoice_master.adjust_amount);
				} else {
					this.invoice_master.bill_amount = Number(this.invoice_master.amount)
                                        - Number(this.invoice_master.discount)
                                        + Number(this.invoice_master.adjust_amount)
                                        + Number(this.invoice_master.tax);
				}
			},
            click_detail(e) {
                // 選択された行を変数に保存
                this.selectTableRowNo = e.rowNo;
                // 値渡し
                this.detail = JSON.parse(JSON.stringify(e.value))
                // 請求明細編集ダイアログの表示
                this.$refs.EditDetail.open({
                    isFullscreen:false
                });
            },
            print(){
            },
            enter_EditDetail(val) {
                const remain_ratio = Number(this.invoice_details[this.selectTableRowNo].yield_ratio) + Number(this.invoice_details[this.selectTableRowNo].remain_ratio);
// alert(JSON.stringify(remain_ratio))
                if (val.yield_ratio>remain_ratio) {
                    val.yield_ratio = remain_ratio;
                }
                val.remain_ratio= remain_ratio - val.yield_ratio;
                this.invoice_details.splice(this.selectTableRowNo  , 1, val);
                this.$refs.EditDetail.close();
            },
            close_EditDetail() {
                this.$refs.EditDetail.close();
            },
            del_EditDetail() {
                this.invoice_details.splice(this.selectTableRowNo  , 1);
                this.$refs.EditDetail.close();
            },
            clickCommand(e) {
                switch (e) {
                    case 'delete':
                        this.del();
                        break;
                }
            },
            group(e) {
// alert(JSON.stringify(e))
                for (let i=0; i<e.length; i++) {
                    if (e[i]) {
                        this.selectItems.push(this.invoice_details[i])
                    }
                }
// alert(JSON.stringify(this.subject.pay_ratio))
                this.group_key = !this.group_key
                if (this.subject.pay_ratio==0) {
                    this.GroupDetail_status = true;
                } else {
                    this.GroupDetail_status = false;
                }
                this.$refs.GroupDetail.open({
                    isFullscreen:false
                });
            },
            enter_GroupDialog(e) {
                let fraction_total = Number(this.fraction_total);
// alert(JSON.stringify(this.selectItems))
                this.selectItems.forEach(val=>{
                    const no = 1;
                    const remain_ratio = Number(val.remain_ratio) + Number(val.yield_ratio)

// alert(remain_ratio)
                    // val.remain_ratio = Number(val.remain_ratio) + Number(val.yield_ratio)
                    for (let key in e) {
                        if (e[key]>remain_ratio) {
                            val[key] = remain_ratio;
                        } else {
                            val[key] = e[key];
                        }
                        if (key=="yield_ratio") {
                            const remainder = ((val.amount * val.yield_ratio) % 100) / 100;
                            fraction_total += remainder;
                            val.remain_ratio = remain_ratio - Number(val[key]);
                            val.fraction_total = fraction_total % 1;
                            val.yield_amount = Math.floor((val.amount * val.yield_ratio) /  100) + Math.floor(fraction_total)
                            fraction_total -= Math.floor(fraction_total)
                        }
                    }
                })
                this.invoice_master.fraction = fraction_total
                this.selectItems=[];
                this.$refs.GroupDetail.close();
            },
		},
        watch: {
            detail_total: function( newVal, oldVal) {
                this.invoice_master.bill_amount = newVal;
            }
        },
		computed: {
			...mapGetters([ 'getTaskClasses' ]),
			...mapGetters([ 'getTaxClasses' ]),
			...mapGetters([ 'getTaxClassItems' ]),
			detail_total: function() {
				var total = 0;
                if (this.invoice_details.length>0) {
                    this.invoice_details.forEach(function(detail){
                        total +=  Number(detail.yield_amount);
                    });
                    // this.invoice_master.amount = total;
                } else {
                    // total = Math.round((this.invoice_master.amount * this.invoice_master.ratio)/100);
                    total = Math.round((this.subject.expenses * this.invoice_master.ratio)/100);
                }
                return total;
			},
			// detail_total_comma: function() {
			// 	return this.detail_total.toLocaleString();
			// },
			ratio: function() {
                if (this.invoice_details.length>0) {
                    // this.invoice_master.ratio =  (Math.round((this.detail_total / (this.subject.expenses - this.subject.discount)) * 10000) / 100).toFixed(2);
                    this.invoice_master.ratio =  (Math.round((this.detail_total / this.subject.expenses) * 10000) / 100).toFixed(2);
                    // this.invoice_master.ratio =  (Math.round((this.invoice_master.amount / this.subject.expenses) * 10000) / 100).toFixed(2);
                }
// alert(this.invoice_master.ratio)
				return this.invoice_master.ratio;
			},
			discount: function() {
                this.invoice_master.discount = Math.round((this.subject.discount * this.ratio) / 100);
				return this.invoice_master.discount;
			},
			sub_total: function() {
				return this.detail_total - this.discount ;
				// return this.invoice_master.amount - this.discount;
			},
			tax: function() {
// alert("sss");
				var tax_amount = 0;
				if (this.invoice_master.tax_state == 1) {
                    tax_amount =  Math.round((this.sub_total * 10) / 110);
				} else if (this.invoice_master.tax_state == 2) {
                    tax_amount =  Math.round(this.sub_total / 10);
				}
                this.invoice_master.tax = tax_amount;
                return tax_amount;
			},
			total: function() {
                // let total = this.sub_total + Number(this.invoice_master.adjust_amount);
                let total = this.sub_total ;
                // let total = this.sub_total 
                if (this.invoice_master.tax_state == 2) {
                    // total += Number(this.invoice_master.tax);
                }
                this.invoice_master.bill_amount = total;
				return total;
			}
		},
		created() {
			this.subjectClassItems = this.$store.getters.getSubjectClasses;
			this.taskClassItems = this.$store.getters.getTaskClasses;
			this.taxClassItems = this.$store.getters.getTaxClasses;
		},
	}
</script>

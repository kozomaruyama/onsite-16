<template>
    <div>
        <!-- <v-container class="container-fluid p-3"> -->
        <v-container fluid class="">
            <v-row no-gutters class="">
                <v-col cols="12" md="2" class="px-2 mt-2">
                <v-menu
                        ref="dateMenu0"
                        v-model="dateMenu0"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            v-model="invoice.closing_date"
                            label= "締切日"
                            prepend-icon="mdi-calendar"
                            v-bind="attrs"
                            v-on="on"
                            dense
                        ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="invoice.closing_date"
                            no-title
                            locale="ja"
                            @input="dateMenu0 = false"
                        ></v-date-picker>
                    </v-menu>
                </v-col>
                <!-- <v-col cols="12" md="2" class="px-2 mt-2">
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
                            v-model="invoice.issue_date"
                            label= "請求日"
                            prepend-icon="mdi-calendar"
                            v-bind="attrs"
                            v-on="on"
                            dense
                        ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="invoice.issue_date"
                            no-title
                            @input="dateMenu1 = false"
                            locale="ja"
                        ></v-date-picker>
                    </v-menu>
                </v-col> -->
                <v-col cols="12" md="2" class="px-2 mt-2">
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
                            v-model="invoice.payment_date"
                            label= "入金日"
                            prepend-icon="mdi-calendar"
                            v-bind="attrs"
                            v-on="on"
                            dense
                        ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="invoice.payment_date"
                            no-title
                            @input="dateMenu2 = false"
                            locale="ja"
                        ></v-date-picker>
                    </v-menu>
                </v-col>
                <!-- <v-col cols="12" md="3" class="px-2 mt-2">
                    <v-text-field label="請求額" v-model="invoice.bill_amount" type="number" dense></v-text-field>
                </v-col> -->
                <!-- <v-col cols="12" md="3" class="px-2 mt-2">
                    <v-text-field label="調整額" v-model="invoice.adjust_amount" type="number" dense></v-text-field>
                </v-col> -->
            </v-row>
            <!-- <v-row no-gutters class="">
                <v-col cols="12" md="3" class="px-2">
                    <v-text-field label="見積書コメントタイトル１" id="message1_title" v-model="invoice.message1_title" ></v-text-field>
                </v-col>
                <v-col cols="12" md="9" class="px-2">
                    <v-text-field label="見積書コメント１" id="message1" v-model="invoice.message1" ></v-text-field>
                </v-col>
            </v-row>
            <v-row no-gutters class="">
                <v-col cols="12" md="3" class="px-2">
                    <v-text-field label="見積書コメントタイトル２" id="message2_title" v-model="invoice.message2_title" ></v-text-field>
                </v-col>
                <v-col cols="12" md="9" class="px-2">
                    <v-text-field label="見積書コメント２" id="message2" v-model="invoice.message2" ></v-text-field>
                </v-col>
            </v-row>
            <v-row no-gutters class="">
                <v-col cols="12" md="3" class="px-2">
                    <v-text-field label="見積書コメントタイトル３" id="message3_title" v-model="invoice.message3_title" ></v-text-field>
                </v-col>
                <v-col cols="12" md="9" class="px-2">
                    <v-text-field label="見積書コメント３" id="message3" v-model="invoice.message3" ></v-text-field>
                </v-col>
            </v-row> -->
            <v-row class="m-0 p-0">
                <v-col v-if=(isVisible_delete) class="col-auto">
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
    import MsgBox from "../msgbox";
	export default {
        components: {
			MsgBox,
		},
		props: [
			'invoice','isVisible_delete',
		],
        data: function() {
            return {
                dateMenu0:false,
                dateMenu1:false,
                dateMenu2:false,
                // コマンドアイテム
				commandItems: [
                    // { title: '一　括' ,name:'group', icon:'mdi-pencil-box-multiple-outline' },
                    // { title: '' ,name:'divider' },
                    { title: '削除' ,name:'delete', icon:'mdi-delete-outline' },
				],
				// 印刷アイテム
				printItems: [
					{ title: '請求書',name:'invoice', icon:'mdi-receipt-outline'},
				],
            }
        },
		methods: {
			enter() {
                axios.put('/invoices/' + this.invoice.id, this.invoice).then((res) => {
                    this.$emit('enter',this.invoice);
                });
			},
            del() {
                this.$refs.MsgBox.open('del')
				// if (window.confirm('削除して宜しいですか？')) {
				// 	axios.delete('/invoices/' + this.invoice.id).then((res) => {
                //         this.$emit('remove',this.invoice);
				// 	});
				// }
            },
			msgBox_enter(para) {
				if (para='del') {
					axios.delete('/invoices/' + this.invoice.id).then((res) => {
						if (res.data.status) {
							this.$emit('remove',this.invoice);
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

<template>
    <v-container fluid class="">
		<!-- <v-btn depressed small fab @click="del()">
			<v-icon>mdi-delete-outline</v-icon>
		</v-btn> -->
        <!-- <v-row no-gutters class="">
            <v-text-field class="d-none" v-model="detail.id"></v-text-field>
            <v-col cols="12" md="2" class="px-2 mt-2">
                <v-text-field label="コード" id="code" v-model="invoice.code" ></v-text-field>
            </v-col>
            <v-col cols="12" md="10" class="px-2 mt-2">
                <v-text-field v-model="detail.name" dense></v-text-field>
            </v-col>
        </v-row> -->
        <!-- <v-row no-gutters class="">
            <v-col cols="12" md="3" class="px-2 mt-2">
                <v-text-field label="数量" v-model="detail.volume"  @focus="active='volume'" dense></v-text-field>
            </v-col>
            <v-col cols="12" md="3" class="px-2 mt-2">
                <v-text-field label="単価" v-model.lazy="detail.cost" @focus="active='cost'" dense></v-text-field>
            </v-col>
            <v-col cols="12" md="3" class="px-2 mt-2">
                <v-text-field label="金額" v-model.lazy="detail.amount" @focus="active='amount'" dense></v-text-field>
            </v-col>
        </v-row> -->
        <v-row v-if="status" no-gutters class="">
            <v-col cols="12" md="3" class="px-2 mt-2">
                <v-text-field label="支払率" type="number" max=100 min=0 v-model.lazy="detail.yield_ratio" @focus="active='ratio'" dense></v-text-field>
            </v-col>
            <!-- <v-col cols="12" md="3" class="px-2 mt-2">
                <v-text-field label="支払額" type="number" v-model.lazy="detail.yield_amount" @focus="active='amount'" dense></v-text-field>
            </v-col> -->
        </v-row>
        <v-row no-gutters class="">
            <v-col cols="12" class="px-2 mt-2">
                <v-text-field label="備考" v-model.lazy="detail.memo" dense></v-text-field>
            </v-col>
        </v-row>
		<v-row class="m-0 p-0">
            <v-col class="col-auto">
                <v-btn depressed small fab @click="del()">
                    <v-icon color="grey">mdi-delete-outline</v-icon>
                </v-btn>
            </v-col>
            <v-col></v-col>
            <v-col class="col-auto">
                <v-btn depressed class="text-white bg-primary" @click="enter()">
                    <v-icon left>mdi-check-underline</v-icon>確定
                </v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
    export default {
		props: ['detail','task','status'],
        data: function () {
            return {
                active:"",
            }
        },
        created () {
        },
        computed: {
        },
        watch: {
            'detail.volume': function(val) {
                if (this.active=="volume") {
                    this.detail.amount = this.detail.cost * val;
                    this.detail.yield_amount = Math.round( (this.detail.amount * this.detail.yield_ratio) /100);
                }
			},
            'detail.cost': function(val) {
                if (this.active=="cost") {
                    this.detail.amount = this.detail.volume * val;
                    this.detail.yield_amount = Math.round( (this.detail.amount * this.detail.yield_ratio) /100);
                }
			},
            'detail.amount': function(val) {
                if (this.active=="amount") {
                    this.detail.yield_amount = Math.round( (val * this.detail.yield_ratio) /100);
                }
			},
            'detail.yield_ratio': function(val) {
                if (this.active=="ratio") {
                    this.detail.yield_amount =Math.round( (this.detail.amount * val) /100);
                }
			},
            'detail.yield_amount': function(val) {
                if (this.active=="amount") {
				    this.detail.yield_ratio = Math.round((val / this.detail.amount)*10000)/100 ;
                }
			},
        },
        methods: {
            enter() {
                this.$emit('enter', this.detail);
				// var detail = Object.assign({},this.detail)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
				// axios.post('/invoice_detail/update', detail )
				// 	.then((res) => {
				// 		this.$emit('close');
				// 	}
				// );
            },
            del() {
                this.$emit('del');
                // this.$emit('close');
				// if (window.confirm('削除して宜しいですか？')) {
                //     this.$emit('delete',this.detail);
				// 	// axios.delete('/invoice_details/' + this.detail.id).then((res) => {
				// 	// });
				// }
            },

        },
    }
</script>

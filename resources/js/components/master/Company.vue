<template>
	<v-app fulid class="">
		<div  class="m-4 p-3">
			<v-row>
					<h3>会社</h3>
			</v-row>
			<v-divider></v-divider>
			<v-row no-gutters class="">
				<v-text-field class="d-none" id="index" v-model="company.id"></v-text-field>
				<v-col cols="12" md="2" class="px-2 mt-2">
					<v-text-field label="コード" id="code" v-model="company.code" ></v-text-field>
				</v-col>
				<v-col cols="12" md="5" class="px-2 mt-2">
					<v-text-field label="名称" id="name" v-model="company.name" ></v-text-field>
				</v-col>
				<v-col cols="12" md="3" class="px-2 mt-2">
					<v-text-field label="カナ" id="kana" v-model="company.kana" ></v-text-field>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" md="2" class="px-2 mt-2">
					<v-text-field label="〒" id="post" v-model="company.post" ></v-text-field>
				</v-col>
				<v-col cols="12" md="3" class="px-2 mt-2">
					<v-text-field label="住所１" id="address1" v-model="company.address1" ></v-text-field>
				</v-col>
				<v-col cols="12" md="3" class="px-2 mt-2">
					<v-text-field label="住所２" id="address2" v-model="company.address2" ></v-text-field>
				</v-col>
				<v-col cols="12" md="2" class="px-2 mt-2">
					<v-text-field label="住所３" id="address3" v-model="company.address3" ></v-text-field>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" md="3" class="px-2 mt-2">
					<v-text-field label="電話" id="tel1" v-model="company.tel1" ></v-text-field>
				</v-col>
				<v-col cols="12" md="3" class="px-2 mt-2">
					<v-text-field label="FAX" id="fax1" v-model="company.fax1" ></v-text-field>
				</v-col>
				<v-col cols="12" md="3" class="px-2 mt-2">
					<v-text-field label="登録番号" v-model="company.invoice_code" ></v-text-field>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" md="2" class="px-2 mt-2">
					<v-text-field label="銀行" id="transfer_bank" v-model="company.transfer_bank" ></v-text-field>
				</v-col>
				<v-col cols="12" md="2" class="px-2 mt-2">
					<v-text-field label="支店" id="transfer_brach" v-model="company.transfer_brach" ></v-text-field>
				</v-col>
				<v-col cols="12" md="2" class="px-2 mt-2">
					<v-text-field label="口座種別(1:普通,2:当座)" id="transfer_type" v-model="company.transfer_type" ></v-text-field>
				</v-col>
				<v-col cols="12" md="2" class="px-2 mt-2">
					<v-text-field label="口座番号" id="transfer_code" v-model="company.transfer_code" ></v-text-field>
				</v-col>
				<v-col cols="12" md="2" class="px-2 mt-2">
					<v-text-field label="口座名義" id="transfer_name" v-model="company.transfer_name" ></v-text-field>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" md="3" class="px-2 mt-2">
					<v-text-field label="見積書コメントタイトル１" id="message1_title" v-model="company.message1_title" ></v-text-field>
				</v-col>
				<v-col cols="12" md="9" class="px-2 mt-2">
					<v-text-field label="見積書コメント１" id="message1" v-model="company.message1" ></v-text-field>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" md="3" class="px-2 mt-2">
					<v-text-field label="見積書コメントタイトル２" id="message2_title" v-model="company.message2_title" ></v-text-field>
				</v-col>
				<v-col cols="12" md="9" class="px-2 mt-2">
					<v-text-field label="見積書コメント２" id="message2" v-model="company.message2" ></v-text-field>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" md="3" class="px-2 mt-2">
					<v-text-field label="見積書コメントタイトル３" id="message3_title" v-model="company.message3_title" ></v-text-field>
				</v-col>
				<v-col cols="12" md="9" class="px-2 mt-2">
					<v-text-field label="見積書コメント３" id="message3" v-model="company.message3" ></v-text-field>
				</v-col>
			</v-row>
			<v-row no-gutters class="">
				<v-col cols="12" class="px-2 mt-2">
					<v-textarea label="見積書コメント４" v-model="company.message4" ></v-textarea>
				</v-col>
			</v-row>
			<v-row class="m-0 p-0">
				<v-col></v-col>
				<v-col class="col-auto">
					<v-btn depressed class="text-white bg-primary" color="success" @click="update()">
						<v-icon left>mdi-content-save-outline</v-icon>登　録
					</v-btn>
				</v-col>
			</v-row>
		</div>
	</v-app>
</template>
<script>
	import dbCompanyFunctions from '../../functions/dbCompanyFunctions'
	const { getCompany,updateCompany } = dbCompanyFunctions()
	export default {
		data: function () {
			return {
					company_id: 0,
					company: [],
				}
			},
		methods: {
			cancel() {
				this.read();
			},
			read(){
				getCompany(this.company_id, (company) => {
					this.company = company;
				});
						// axios.get('/api/company/' + this.company_id).then((res) => {
						//     this.company = res.data;
						// });
			},
			update() {
				updateCompany(this.company);
                // axios.put('/api/company/' + this.company.id, this.company).then((res) => {
                // });
			},
		},
		computed: {
		},
		watch: {
		},
		created() {
			this.company_id=this.$store.getters.getCompanyId[0].company_id;
		},
		mounted() {
			this.read();
		}
	}
</script>

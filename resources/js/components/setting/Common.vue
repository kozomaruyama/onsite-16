<template>
	<v-app fulid class="">
        <div  class="ml-4 p-0">
        <!-- <v-row>
            <h3>共通</h3>
        </v-row> -->
        <v-row>
			<v-col cols="12" md="1" class="px-2 mt-2">
				<v-select :items="listItems" item-text="text" item-value="value" label="分類"></v-select>
			</v-col>
        </v-row>
		<!-- <v-divider></v-divider> -->
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
    // import dbCompanyFunctions from '../../functions/dbCompanyFunctions'
    // const { getCompany,updateCompany } = dbCompanyFunctions()
	export default {
		data: function () {
			return {
                company_id: 0,
				listItems:[
					{ text: "商品区分", value: 4 },
					{ text: "顧客区分", value: 5 },
					{ text: "社員区分", value: 6 },
					{ text: "案件区分", value: 7 },
					{ text: "請求区分", value: 10 },
				],
                // company: [],
            }
        },
		methods: {
			cancel() {
				this.read();
			},
            read(){
                // axios.get('/api/company/' + this.company_id).then((res) => {
                //     this.company = res.data;
                // });
            },
			update() {
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

<template>
	<PDF ref="pdf" :PaperOrientation="PaperOrientation" :clerk="clerk" @changeDate="changeDate" @changeClerk="changeClerk">
		<v-simple-table class="sheet" >
			<template v-slot:default >
				<div v-for="subject in subjects" :key="subject.id" class="pagebreak_before">
					<v-simple-table class="sheet" >
						<template v-slot:default>
							<tr>
								<th colspan="7">
									<img width="100%" src="/img/line.png"/>
								</th>
							</tr>
							<tr>
								<th class="pl-6 text-left align-middle "  width="300" >
									<h3>御見積書</h3>
								</th>
								<th class="　" width="50"></th>
								<th class="　" width="150"></th>
								<th class="　"></th>
								<th class="　" width="100"></th>
								<th class="　"></th>
								<th class="text-right" width="50" style="font-size:0.7em">
									<div>{{ order_date }}</div>
								</th>
							</tr>
							<tr>
								<th class="">
									<h5 class="m-0 p-0">{{ subject.client }}　御中</h5>
									<v-divider class="m-0 p-0"></v-divider>
								</th>
								<th class=" "></th>
								<th class=" "></th>
								<th class=" "></th>
								<th class=" "></th>
								<th class=" "></th>
							</tr>
							<tr>
								<th class="align-baseline" style="font-size:0.7em">
									<span>平素は格別のお引き立てを賜り厚く御礼申し上げます。</span><br>
									<span>下記の通り御見積りさせて頂きます。</span><br>
									<span>御査収の程、宜しくお願い申し上げます。</span>
								</th>
								<th class=" "></th>
								<th class=" "></th>
								<th class=" "></th>
								<th class=" "></th>
								<th class=" "></th>
								<th class="" style="font-size:0.7em">
									<img src="/img/logo.png" width="187" height="25" />
								</th>
							</tr>
							<tr>
								<th class="" rowspan="3"></th>
								<th class="" rowspan="3"></th>
								<th class="text-right align-bottom pr-2" rowspan="3" style="border-left:1px solid; border-top:1px solid; border-bottom:1px solid ">
									<h5 class="m-0 p-0 text-center">合計金額</h5>
									<div v-if="subject.tax_state==2" class="m-0 p-0 text-center " style="font-size:0.7em">[消費税別]</div>
									<!-- <span v-else class="m-0 p-0"></span> -->
								</th>
								<th class="text-left align-bottom pl-2" rowspan="3" style="border-right:1px solid; border-top:1px solid; border-bottom:1px solid ">
									<h3 v-if="subject.tax_state==2">¥{{ (subject.bill_amount - subject.tax).toLocaleString() }}</h3>
									<h3 v-else>¥{{ subject.bill_amount.toLocaleString() }}</h3>
								</th>
								<th class="" rowspan="3"></th>
								<th class="" style="font-size:0.7em">
									<div class="text-right">〒　</div>
								</th>
								<th class=" "  style="font-size:0.7em" >
									<div class="text-left">{{ company.post }}</div>
								</th>
							</tr>

							<tr>
								<th class=" " style="font-size:0.7em">
									<div class="text-right">住所　</div>
								</th>
								<th class="" style="font-size:0.7em" colspan="3">
									<div class="text-left">{{ company.address1 }}</div>
								</th>
							</tr>
							<tr>
								<th class=""  style="font-size:0.7em">
									<div class="text-right">TEL　</div>
								</th>
								<th class=" "  style="font-size:0.7em" >
									<div class="text-left">{{ company.tel1 }}</div>
								</th>
							</tr>
							
							<tr>
								<th class=""></th>
								<th class=""></th>
								<th class=""></th>
								<th class=""></th>
								<th class=""></th>
								<th class=""  style="font-size:0.7em">
									<div class="text-right">FAX　</div>
								</th>
								<th class=" "  style="font-size:0.7em" >
									<div class="text-left">{{ company.fax1 }}</div>
								</th>
							</tr>

							<tr>
								<th class=""></th>
								<th class=""></th>
								<th class=""></th>
								<th class=""></th>
								<th class=""></th>
								<th class=""  style="font-size:0.7em">
									<div class="text-right">担当　</div>
								</th>
								<th class=" "  style="font-size:0.7em" >
									<div class="text-left">{{ clerk }}</div>
								</th>
							</tr>



							<tr>
								<th class=" "></th>
								<th class=" "></th>
								<th class="text-right" style="font-size:0.7em">
									工事名称：
								</th>
								<th class=" " style="font-size:0.7em" >
									<span>{{ subject.name }}</span>
								</th>
								<th class=" "></th>
								<th class="text-right"  rowspan="4" colspan="2">
									<img src="/img/box.png" width="230" height="63">
								</th>
							</tr>
							
							<tr>
								<th class=" "></th>
								<th class=" "></th>
								<th class="text-right" style="font-size:0.7em" >
									施工場所：
								</th>
								<th class=" " style="font-size:0.7em" >
									<span>{{ subject.site_address }}</span>
								</th>
								<th class=" "></th>
								<th class=" "></th>
							</tr>
							
							<tr>
								<th class=" "></th>
								<th class=" "></th>
								<th class="text-right" style="font-size:0.7em" >
									<span>{{ subject.message2_title }}</span>
								</th>
								<th class=" " style="font-size:0.7em" >
									<span>{{  subject.message2 }}</span>
								</th>
								<th class=" "></th>
								<th class=" "></th>
							</tr>
							
							<tr>
								<th class=" "></th>
								<th class=" "></th>
								<th class=" text-right" style="font-size:0.7em" >
									<span >{{ subject.message3_title }}</span>
								</th>
								<th class=" " style="font-size:0.7em" >
									<span>{{ subject.message3 }}</span>
								</th>
								<th class=" "></th>
								<th class=" "></th>
							</tr>



						</template>
					</v-simple-table>
					<table class="mt-2">
						<thead >
							<tr height="10px " >
								<th width="25px" class="border text-center px-1" style="font-size:0.7em">No</th>
								<th width="300px" class="border text-center px-1" style="font-size:0.7em">名称</th>
								<th width="370px" class="border text-center px-1" style="font-size:0.7em">規格・仕様</th>
								<th width="100px" class="border text-center px-1" style="font-size:0.7em">数量</th>
								<th width="80px" class="border text-center px-1" style="font-size:0.7em">単価</th>
								<th width="100px" class="border text-center px-1" style="font-size:0.7em">金額</th>
								<th width="200px" class="border text-center px-1" style="font-size:0.7em">備考</th>
							</tr>
						</thead>
						<tbody>
							<tr height="25px" v-for="(item) in subject.v_tasks" :key="item.id" >
								<td class="border text-right px-1" style="font-size:0.7em">{{ item.no }}</td>
								<td class="border px-1" style="font-size:0.7em">{{ item.name }}</td>
								<td class="border px-1" style="font-size:0.7em">{{ item.breakdown }}</td>
								<td class="border text-right px-1" style="font-size:0.7em" v-if="item.isLabel == 0">{{ item.volume + ' ' + unitNames[item.unit-1].name }}</td><td v-else class="border text-right px-1" ></td>
								<td class="border text-right px-1" style="font-size:0.7em" v-if="item.cost !== null && item.isLabel == 0">{{ item.cost.toLocaleString() }}</td><td v-else class="border text-right px-1" ></td>
								<td class="border text-right px-1" style="font-size:0.7em" v-if="item.isLabel == 0">{{ item.amount.toLocaleString() }}</td><td v-else class="border text-right px-1" ></td>
								<td class="border px-1" style="font-size:0.7em">{{ item.memo }}</td>
							</tr>
							<tr v-if="subject.v_tasks.length>0" >
								<td class="border p-1" style="font-size:0.7em"></td>
								<td class="border p-1" style="font-size:0.7em">明細計</td>
								<td class="border p-1" style="font-size:0.7em"></td>
								<td class="border text-right p-1" style="font-size:0.7em"></td>
								<td class="border text-center p-1" style="font-size:0.7em"></td>
								<td class="border text-right p-1" style="font-size:0.7em">{{ subject.expenses.toLocaleString() }}</td>
								<td class="border p-1" style="font-size:0.7em"></td>
							</tr>
							<tr v-else>
								<td class="border p-1" style="font-size:0.7em">1</td>
								<td class="border p-1" style="font-size:0.7em">{{ subject.name }}</td>
								<td class="border p-1" style="font-size:0.7em">{{ subject.breakdown }}</td>
								<td class="border text-center p-1" style="font-size:0.7em">1 式</td>
								<td class="border text-center p-1" style="font-size:0.7em"></td>
								<td class="border text-right p-1" style="font-size:0.7em">{{ subject.expenses.toLocaleString() }}</td>
								<td class="border p-1" style="font-size:0.7em"></td>
							</tr>
							<tr v-if="subject.discount>0">
								<td class="border p-1" style="font-size:0.7em"></td>
								<td class="border p-1 red--text" style="font-size:0.7em">値引き</td>
								<td class="border p-1" style="font-size:0.7em"></td>
								<td class="border text-right p-1 red--text"  style="font-size:0.7em">1 式</td>
								<td class="border text-center p-1" style="font-size:0.7em"></td>
								<td class="border text-right p-1 red--text" style="font-size:0.7em">▲{{ subject.discount.toLocaleString() }}</td>
								<td class="border p-1" style="font-size:0.7em"></td>
							</tr>
							<tr>
								<td class="border p-1" style="font-size:0.7em" rowspan="3" colspan="3">
									<div v-html="htmlText(company.message4)"></div>
								</td>
								<td class="border text-center p-1" style="font-size:0.7em" colspan="2">小　　計</td>
								<td class="border text-right p-1" style="font-size:0.7em">{{
																		(subject.expenses - subject.discount).toLocaleString() }}</td>
								<td class="border p-1"></td>
							</tr>
							<tr>
								<td class="border text-center p-1" style="font-size:0.7em" v-if="subject.tax_state==1" colspan="2">内消費税</td>
								<td class="border text-right p-1" style="font-size:0.7em" v-if="subject.tax_state==1">({{ subject.tax.toLocaleString() }})</td>
								<td class="border text-center p-1r" style="font-size:0.7em" v-if="subject.tax_state==2" colspan="2">消 費 税</td>
								<td class="border text-right p-1" style="font-size:0.7em" v-if="subject.tax_state==2">{{ subject.tax.toLocaleString() }}</td>
								<td class="border text-center p-1" style="font-size:0.7em" v-if="subject.tax_state>=3" colspan="2">-</td>
								<td class="border text-right p-1" style="font-size:0.7em" v-if="subject.tax_state>=3"></td>
								<td class="border p-1" style="font-size:0.7em"></td>
							</tr>
							<tr>
								<td class="border text-center p-1" style="font-size:0.7em" colspan="2">合　　計</td>
								<td class="border text-right p-1" style="font-size:0.7em">{{ subject.bill_amount.toLocaleString() }}</td>
								<td class="border p-1" style="font-size:0.7em"></td>
							</tr>
						</tbody>
					</table>
					<hr width="100%" style="border-top: 1px dashed ;" class="no-printing my-10">
				</div>
			</template>
		</v-simple-table>
	</PDF>
</template>
<script>
    import moment from 'moment';
	import { mapState, mapGetters } from 'vuex'
	import PDF from "../PDF";
	import commFunctions from '../../functions/commFunctions'
    const { isDate, getDate } = commFunctions();
	import dbCompanyFunctions from '../../functions/dbCompanyFunctions'
	const { getCompany } = dbCompanyFunctions()
	export default {
		components: {
			PDF,
		},
		props: ["selectItems","person"],
		data () {
			return {
				PaperOrientation: 2,
				unitNames: [],
				subjects: [],
				tasks: [],
				company: [],
				printDate: new Date(),
				clerk:""
			}
		},
		methods: {
			getRecords(company) {
				this.company = company;		
				const ids = this.selectItems.map(item => item.subject_id);				
				axios.post('/subject/print', { ids : ids} )
					.then((res) => {
// alert(JSON.stringify(res.data))
                        this.subjects = res.data;
					});
			},
			changeDate(val) {
				this.printDate = new Date(val);
			},
			changeClerk(val) {
				this.clerk = val;
			},
			htmlText(msg){
				if( !!(msg) ){
					return msg.replace(/\r?\n/g, '<br>')
				}
			},
		},
		computed: {
			...mapGetters([ 'getUnitNames' ]),
			order_date: function() {
					return  getDate(this.printDate).wareki;
			},
		},
		created() {
            this.unitNames = this.$store.getters.getUnitNames;
// alert(JSON.stringify(this.unitNames))
		},
		mounted() {
			getCompany(2,this.getRecords);
			this.clerk = this.person.name;
		}
	}
</script>

<style lang="scss" scoped>
	.detail {
		height: 5px;
	}
	.pagebreak {
		break-after: page;
	}
	@page {
		size: A4 landscape!important;
		margin-top: 20mm!important;
		margin-bottom: 10mm!important;
		margin-left: 10mm!important;
		margin-right: 10mm!important;

	}
	@media screen {
		.sheet {
			margin-top: 10px;
		}
	}
	@media print{
		.no-printing {
			display: none;
		}
	}
</style>

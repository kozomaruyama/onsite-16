<template>
	<div>
		<PDF ref="pdf" :PaperOrientation="PaperOrientation" :clerk="clerk" @changeDate="changeDate">
			<v-simple-table class="sheet" >
				<template v-slot:default >
					<div v-for="subject in subjects" :key="subject.id" class="pagebreak">
						<v-simple-table class="sheet" >
							<template v-slot:default>
								<tr>
									<th colspan="7">
										<img width="100%" src="/img/line.png"/>
									</th>
								</tr>										
								<table class=" p-1">
									<thead >
										<tr height="10px" >
											<th class=" text-center px-1" colspan="40" rowspan="2" style="font-size:26px">注文書</th>
										</tr>
										<tr height="10px" >
											<th class=" text-right px-1" colspan="7"  style="font-size:0.7em">{{ order_date }}</th>
										</tr>
										<tr height="10px" >
											<th class=" text-left px-1" colspan="15" >
											<span style="font-size:14px">{{ company.name }}　御中</span>
											<v-divider class="m-0 p-0"></v-divider>
											</th>
											<th class=" text-center px-1" colspan="32"  style="font-size:0.7em"></th>
										</tr>
										<tr height="10px" >
											<th class=" text-left px-1" colspan="10" style="font-size:0.7em">下記の通り注文請書を提出いたします。</th>
											<th class=" text-center px-1" colspan="37" style="font-size:0.7em"></th>
										</tr>
										<tr height="10px" >
											<th class=" text-left px-1" colspan="5" style="font-size:20px">総計金額</th>
											<th class=" text-right px-1" colspan="10" style="font-size:20px">{{ subject.bill_amount.toLocaleString() }}</th>
											<th class=" text-center px-1" colspan="12"  style="font-size:0.7em"></th>
											<th class=" text-center px-1" colspan="20"  rowspan="5" style="font-size:0.7em">
												<container class="">
													<v-row dense class="m-0 p-0 ">
														<v-col class=" m-0 p-0 col-3 border-end"></v-col>
														<v-col class=" m-0 p-0 d-flex justify-content-end col-2 border-top ">住　　所　</v-col>
														<v-col class=" m-0 p-0 d-flex justify-content-start border-top border-end">{{ subject.client_zip }}</v-col>
													</v-row>
													<v-row dense class="m-0 p-0">
														<v-col class=" m-0 p-0 col-3 border-end"></v-col>
														<v-col class=" m-0 p-0 col-2 d-flex justify-content-end">　　</v-col>
														<v-col class=" m-0 p-0 d-flex align-left border-end">{{ subject.client_address1 }}</v-col>
													</v-row>
													<v-row dense class="m-0 p-0">
														<v-col class=" m-0 p-0 col-3 border-end"></v-col>
														<v-col class=" m-0 p-0 col-2 d-flex justify-content-end ">　　</v-col>
														<v-col class=" m-0 p-0 d-flex align-left border-end">{{ subject.client_address2 }}</v-col>
													</v-row>
													<v-row dense class="m-0 p-0">
														<v-col class=" m-0 p-0 col-3 border-end"></v-col>
														<v-col class=" m-0 p-0 col-2 d-flex justify-content-end ">　　</v-col>
														<v-col class=" m-0 p-0 d-flex align-left border-end">{{ subject.client_address3 }}</v-col>
													</v-row>
													<v-row dense class="m-0 p-0">
														<v-col class=" m-0 p-0 col-3 border-end"></v-col>
														<v-col class=" m-0 p-0 col-2 d-flex justify-content-end ">氏　　名　</v-col>
														<v-col class=" m-0 p-0 d-flex align-left border-end">{{ subject.client }}</v-col>
													</v-row>
													<v-row dense class="m-0 p-0">
														<v-col class=" m-0 p-0 col-3 border-end"></v-col>
														<v-col class=" m-0 p-0 col-2 d-flex justify-content-end ">代　　表　</v-col>
														<v-col class=" m-0 p-0 d-flex align-left border-end">{{ subject.delegate }}</v-col>
													</v-row>
													<v-row dense class="m-0 p-0 ">
														<v-col class=" m-0 p-0 col-3 border-end"></v-col>
														<v-col class=" m-0 p-0 col-2 d-flex justify-content-end ">TEL/FAX　</v-col>
														<v-col class=" m-0 p-0 d-flex justify-content-start border-end ">{{ subject.client_tel1 }}</v-col>
													</v-row>
													<v-row dense class="m-0 p-0 ">
														<v-col class=" m-0 p-0 col-3 border-end"></v-col>
														<v-col class=" m-0 p-0 col-2 d-flex justify-content-end border-bottom">　　　</v-col>
														<v-col class=" m-0 p-0 d-flex justify-content-start border-end border-bottom">{{ subject.client_fax }}</v-col>
													</v-row>
												</container>
												<!-- <table>
													<tr>
														<th class="border"></th>
														<th class="border"></th>
													</tr>
													<tr>
														<th class="border"></th>
														<th class="border"></th>
													</tr>
													<tr>
														<th class="border"></th>
														<th class="border"></th>
													</tr>
													<tr>
														<th class="border"></th>
														<th class="border"></th>
													</tr>
													<tr>
														<th class="border"></th>
														<th class="border"></th>
													</tr>
												</table> -->
											</th>
											<!-- <th class="border text-left px-1" colspan="10" style="font-size:10px">住所 {{ subject.client_zip }}</th> -->
										</tr>
										<tr height="10px" >
											<th class="" colspan="27">　</th>
											<th class="" colspan="20">　</th>
											<!-- <th class="border text-left " colspan="10" style="font-size:10px">　　{{ subject.client_address1 }}</th> -->
										</tr>
										<tr height="10px" >
											<th class=" text-left px-1" colspan="27">
													<span style="font-size:10px">工事件名：{{ subject.name }}</span>
													<v-divider class="m-0 p-0"></v-divider>
											</th>
											<!-- <th class="border text-center px-1" colspan="10"  style="font-size:0.7em"></th>
											<th class="border text-center px-1" colspan="10"  style="font-size:0.7em"></th> -->
										</tr>
										<tr height="10px" >
											<th class=" text-left px-1" colspan="17">
													<span style="font-size:10px">工事場所：{{ subject.site_address }}</span>
													<v-divider class="m-0 p-0"></v-divider>
											</th>
											<th class=" text-left px-1" colspan="10">
													<span v-if="subject.period!='～'" style="font-size:10px">工事期間：{{ subject.period }}</span>
													<span v-else style="font-size:10px">工事期間：</span>
													<v-divider class="m-0 p-0"></v-divider>
											</th>
											<!-- <th class="border text-center px-1" colspan="20"  style="font-size:0.7em"></th> -->
										</tr>
										<tr height="10px" >
											<th class=" text-left px-1" colspan="17">
													<span style="font-size:10px">{{ subject.message3_title + subject.message3 }}</span>
													<v-divider class="m-0 p-0"></v-divider>
											</th>
											<th class=" text-left px-1" colspan="10">
													<span style="font-size:10px">{{  subject.message4_title + subject.message4 }}</span>
													<v-divider class="m-0 p-0"></v-divider>
											</th>
											<!-- <th class="border text-center px-1" colspan="20"  style="font-size:0.7em"></th> -->
										</tr>
										<tr height="10px" >
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
											<th width="25px"></th>
										</tr>
									</thead>
								</table>
								<!-- <tr>
										<th class="text-center" colspan="2">
												<h3>注文書</h3>
										</th>
										<th class="" colspan="4"></th>
										<th class="text-right" colspan="3" style="font-size:0.6em">
												2021/04/30
										</th>
										<th class=""></th>
									</tr>
									<tr >
											<th class="" colspan="2">
													<h5 class="m-0 p-0">{{ company.name }}　御中</h5>
													<v-divider class="m-0 p-0"></v-divider>
											</th>
											<th class="" colspan="4"></th>
											<th class="" rowspan="2" style="font-size:0.7em">
													<div class="text-right">社名　</div>
													<div class="text-right">〒　</div>
													<div class="text-right">住所　</div>
													<div class="text-right">TEL　</div>
													<div class="text-right">FAX　</div>
											</th>
											<th class="" rowspan="2" style="font-size:0.7em" colspan="3">
													<div class="text-left">{{ subject.client_name  }}</div>
													<div class="text-left">{{ company.post }}</div>
													<div class="text-left">{{ company.address1 }}</div>
													<div class="text-left">{{ company.tel1 }}</div>
													<div class="text-left">{{ company.fax1 }}</div>
											</th>
									</tr>
									<tr >
											<th class="" colspan="2" style="font-size:0.6em">
													<span>下記の通り注文請書を提出いたします。</span><br>
											</th>
											<th class="" colspan="4">
											</th>
									</tr>
									<tr >
											<th class="">
													<h5>総計金額</h5>
											</th>
											<th >
													<h5>¥{{ subject.bill_amount.toLocaleString() }}</h5>
											</th>
											<th class="" colspan="4" style="font-size:0.7em">
											</th>
											<th class=""></th>
											<th class="" rowspan="2" colspan="3">
													<img src="/img/box.png" width="187" height="63">
											</th>
									</tr>
									<tr >
											<th class="text-right" style="font-size:0.7em" >
													<span >工事名称　：　</span><br>
													<span >施工場所　：　</span><br>
													<span >{{ subject.message2_title + '　：　' }}</span><br>
													<span >{{ subject.message3_title + '　：　'  }}</span><br>
											</th>
											<th class="" colspan="3" style="font-size:0.7em" >
													<span>{{ subject.name }}</span><br>
													<span>{{ subject.site_address }}</span><br>
													<span>{{ subject.message2 }}</span><br>
													<span>{{ subject.message3 }}</span><br>
											</th>
											<th class=""></th>
											<th class=""></th>
											<th class=""></th>
									</tr> -->
							</template>
						</v-simple-table>
						<table>
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
												<td class="border p-1" style="font-size:0.7em">値引き</td>
												<td class="border p-1" style="font-size:0.7em"></td>
												<td class="border text-right p-1" style="font-size:0.7em">1 式</td>
												<td class="border text-center p-1" style="font-size:0.7em"></td>
												<td class="border text-right p-1 red--text" style="font-size:0.7em">▲{{ subject.discount.toLocaleString() }}</td>
												<td class="border p-1" style="font-size:0.7em"></td>
										</tr>
										<tr>
												<td class="border p-1" style="font-size:0.7em" rowspan="3" colspan="3"></td>
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
	</div>
</template>
<script>
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
                const ids = this.selectItems.map(item => item.id);
				axios.post('/subject/print', { ids : ids} )
					.then((res) => {
						this.subjects = res.data;

					});
			},
            trnWareki(val) {
                if (isDate(val,'-')==false) {
                    return getDate().wareki;
                } else {
                    return getDate(new Date(val)).wareki;
                }
            },
            changeDate(val) {
                this.printDate = new Date(val);
            },
		},
		computed: {
			...mapGetters([ 'getUnitNames' ]),
            order_date: function() {
                return  getDate(this.printDate).wareki;
            }
		},
		created() {
			this.unitNames = this.$store.getters.getUnitNames;
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
			margin-top: 30px;
		}
	}
	@media print{
		.no-printing {
			display: none;
		}
	}
</style>

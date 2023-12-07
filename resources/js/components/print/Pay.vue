<template>
	<PDF ref="pdf" :PaperOrientation="PaperOrientation" :clerk="clerk" :fileName="fileName" @changeDate="changeDate" @changeClerk="changeClerk">
		<v-simple-table class="sheet" >
			<template v-slot:default >
				<div>
					<div id="print_master">
            <v-divider class="border border-3 border-dark"/>
						<v-simple-table class="sheet" dense>
							<template v-slot:default>
								<tr>
                  <th class="text-center border border-2 border-dark" colspan="2" >
                    <h3 class="m-0 p-0 py-1">支払明細書</h3>
                  </th>
                  <th class=" " ></th>
                  <th class=" " ></th>
                  <th class=" " >　</th>
                  <th class=" " ></th>
                  <th class="text-right" width="50" style="font-size:0.6em">
                    <span>{{ order_date }}</span><br>
                  </th>
                </tr>
                <tr>
                  <th class="border-dark" colspan="2" style="border-bottom:1px solid;">
                    <h5 class="m-0 p-0 pt-2" >{{ client.name }}　御中</h5>
                  </th>
                  <th class=" " ></th>
                  <th class=" " ></th>
                  <th class=" " ></th>
                  <th class=" " ></th>
                  <th class=" " ></th>
                </tr>
                <tr>
                  <th class="align-baseline " colspan="2" style="font-size:0.6em">
                    <span>平素は格別のお引き立てを賜り厚く御礼申し上げます。</span><br>
                    <span>下記の通り支払明細を明記させて頂きます。</span><br>
                    <span>ご確認の程、宜しくお願い致します。</span>
                  </th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class="" width="250" >
                    <span style="font-size:0.8em">{{ company.name }}</span><br>
                    <span style="font-size:0.6em">〒{{ company.post }}  {{ company.address1 }}</span><br>
                    <span style="font-size:0.6em">TEL:{{ company.tel1 }} FAX:{{ company.fax }}</span>
                  </th>
                </tr>		
                <tr>
                  <th class="text-center border-dark" width="100" style="font-size:0.6em;border-bottom:1px solid;" >
                    品名
                  </th>
                  <th class="text-center border-dark" style="font-size:0.6em;border-bottom:1px solid;">{{ cloding_ym }}分御支払明細</th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class="text-right" rowspan="4" colspan="2">
                    <img src="/img/box.png" width="230" height="63">
                  </th>
                </tr>	
                <tr>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                </tr>			
                <tr>
                  <th class="text-center border border-dark" width="80">
                    御支払金額
                  </th>
                  <th class="text-center border border-dark" width="200">{{ total.toLocaleString() }}</th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                </tr>		
                <tr>
                  <th class="text-center border-dark" width="80" style="font-size:0.6em;border-bottom:1px solid;">
                    御支払日
                  </th>
                  <th class="text-center border-dark" style="font-size:0.6em;border-bottom:1px solid;">{{ payDate }}</th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                </tr>		
                <tr>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                  <th class=""></th>
                </tr>		
							</template>
						</v-simple-table>
            <table class="mt-2">
              <thead >
                <tr height="10px " >
                  <th width="25px" class="border text-center px-1" style="font-size:0.7em">No</th>
                  <th width="300px" class="border text-center px-1" style="font-size:0.7em">名称</th>
                  <th width="100px" class="border text-center px-1" style="font-size:0.7em">数量</th>
                  <th width="80px" class="border text-center px-1" style="font-size:0.7em">単価</th>
                  <th width="100px" class="border text-center px-1" style="font-size:0.7em">金額</th>
                  <th width="200px" class="border text-center px-1" style="font-size:0.7em">備考</th>
                </tr>
              </thead>
              <tbody>

                <tr height="25px" v-for="(detail, index) in details_1" :key="index">
                  <td class="border text-right px-1" style="font-size:0.7em">{{ index+1 }}</td>
                  <td class="border text-left px-1" style="font-size:0.7em">{{ detail.name }}</td>
                  <td class="border text-right px-1" style="font-size:0.7em">{{ Number(detail.volume).toLocaleString() + detail.unit_name }}</td>
                  <td class="border text-right px-1" style="font-size:0.7em">{{ Number(detail.cost).toLocaleString() }}</td>
                  <td class="border text-right px-1" style="font-size:0.7em">{{ Number(detail.amount).toLocaleString() }}</td>
                  <td class="border text-left px-1" style="font-size:0.7em">{{ memo(detail.start_date,detail.memo) }}</td>
                </tr>

                <!-- <tr height="25px">
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-left px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-left px-1" style="font-size:0.7em"></td>
                </tr>
                <tr height="25px">
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-left px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-left px-1" style="font-size:0.7em"></td>
                </tr> -->
                <tr height="25px" v-for="i of counter_1" :key="i">
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-left px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-left px-1" style="font-size:0.7em"></td>
                </tr>
    
                <tr height="25px" style="border-top:double;border-color: lavender">
                  <td class="border text-center px-1" style="font-size:0.7em" colspan="4" >小計</td>
                  <td class="border text-right px-1" style="font-size:0.7em">{{ Number(master_1.amount_total).toLocaleString() }}</td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <!-- <td class="text-right px-1" style="font-size:0.7em;border: solid 1px;border-top: double" colspan="4" >小計</td>
                  <td class="text-right px-1" style="font-size:0.7em;border: solid 1px;border-top: double"></td>
                  <td class="text-right px-1" style="font-size:0.7em;border: solid 1px;border-top: double"></td> -->
                </tr>
                <tr height="25px" style="border-top:double;border-bottom:double;border-color: lavender">
                  <td class="border text-center px-1" style="font-size:0.7em" colspan="4" >消費税</td>
                  <td class="border text-right px-1" style="font-size:0.7em">{{ Number(calc_Tax_1.tax).toLocaleString() }}</td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                </tr>

                <tr height="25px" v-for="(detail, index) in details_2" :key="index">
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-left px-1" style="font-size:0.7em">{{ detail.name }}</td>
                  <td class="border text-right px-1" style="font-size:0.7em">{{ Number(detail.volume).toLocaleString() }}</td>
                  <td class="border text-right px-1" style="font-size:0.7em">{{ Number(detail.cost).toLocaleString() }}</td>
                  <td class="border text-right px-1" style="font-size:0.7em">({{ Number(detail.amount).toLocaleString() }})</td>
                  <td class="border text-left px-1" style="font-size:0.7em">{{ detail.memo }}</td>
                </tr>

                <tr height="25px" v-for="i of counter_2" :key="i">
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-left px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-left px-1" style="font-size:0.7em"></td>
                </tr>


                <tr height="25px" style="border-top:double;border-bottom:double;border-color: lavender;">
                  <td class="border text-center px-1" style="font-size:0.7em" colspan="4" >小計</td>
                  <td class="border text-right px-1" style="font-size:0.7em">({{ Number(master_2.amount_total).toLocaleString() }})</td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <!-- <td class="text-right px-1" style="font-size:0.7em;border: solid 1px;border-top: double" colspan="4" >小計</td>
                  <td class="text-right px-1" style="font-size:0.7em;border: solid 1px;border-top: double"></td>
                  <td class="text-right px-1" style="font-size:0.7em;border: solid 1px;border-top: double"></td> -->
                </tr>
                <tr height="25px" style="border-top:double;border-bottom:double;border-color: lavender">
                  <td class="border text-center px-1" style="font-size:0.7em" colspan="4" >消費税</td>
                  <td class="border text-right px-1" style="font-size:0.7em">({{ Number(calc_Tax_2.tax).toLocaleString() }})</td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                </tr>                
                <tr height="25px">
                  <td class="border text-center px-1" style="font-size:0.7em" colspan="4" >合計</td>
                  <td class="border text-right px-1" style="font-size:0.7em">{{ total }}</td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                </tr>                
              </tbody>
            </table>
					</div>
				</div>
			</template>
		</v-simple-table>
	</PDF>
</template>
<script>
import { mapState, mapGetters } from 'vuex'
import PDF from "../PDF";
import dbCompanyFunctions from '../../functions/dbCompanyFunctions'
const { getCompany } = dbCompanyFunctions()
import commFunctions from '../../functions/commFunctions'
const { isDate, getDate,calcTax,getFormatYM,getFormatYMD,getFormatMD } = commFunctions();
export default 
{
	components: {
		PDF,
	},
	props: ["selectItems","person"],
	data: function () {
		return {
			PaperOrientation: 1,
			fileName:"",
			unitNames: [],
      client:[],
      master_1:[],
      master_2:[],
			details_1: [],
			details_2: [],
			// bill: [],
			company: [],
			printDate: new Date(),
			clerk:"",
      counter_1:0,
      counter_2:0,
		}
	},
	methods: {
		getRecords(company) {
			this.company = company;
      axios.post('/pay/details', this.selectItems).then((res) => {
        this.client=  res.data.client
				this.details_1 = res.data.detail_1
				this.details_2 = res.data.detail_2
        this.master_1=res.data.master_1
        this.master_2=res.data.master_2
				this.fileName = "pay_" + this.details_1[0].id
        if (this.details_1.length<20) {
          this.counter_1 = 20 - this.details_1.length
        } else {
          this.counter_1 = 0
        }
        if (this.details_2.length<5) {
          this.counter_2 = 5 - this.details_2.length
        } else {
          this.counter_2 = 0
        }
      })
		},
		changeDate(val) {
			this.printDate = new Date(val);
		},
		changeClerk(val) {
			this.clerk = val;
		},
	},
	computed: {
		...mapGetters([ 'getUnitNames' ]),
		wareki: function() {
			return function (key) {
				var date = new Date(key);
				var wa = new Intl.DateTimeFormat('ja-JP-u-ca-japanese',
          { era:'long', year: 'numeric', month: 'long' }).format(date)
				return wa;
			};
		},
		order_date: function() {
			return  getDate(this.printDate).wareki;
		},
    calc_Tax_1: function() {
      return calcTax(this.master_1.amount_total,2)
    },
    calc_Tax_2: function() {
      return calcTax(this.master_2.amount_total,2)
    },
    tax: function() {
      const tax = Number(this.calc_Tax_1.tax)
                - Number(this.calc_Tax_2.tax)
      return tax
    },
    total: function() {
      const total = Number(this.master_1.amount_total)
                  - Number(this.master_2.amount_total)
                  + this.tax
      return total
    },
    cloding_ym: function() {
      return getFormatYM(this.master_1.closing_date)
      // const day = new Date(this.master_1.closing_date)
      // const year = day.getFullYear();
      // const month  = day.getMonth() + 1;
      // return year + '年' + month + '月'
    },
    payDate: function() {
      return getFormatYMD(this.selectItems.pay_date)
    },
		memo: function() {
			return function (date,text) {
        if (text==null) {
          return getFormatMD(date);
        } else {
          return getFormatMD(date) + '　' + text;
        }
			};
		},
    },
  created() {
      this.unitNames = this.$store.getters.getUnitNames;
  },
  mounted() {
    getCompany(2,this.getRecords);
    this.clerk = this.person.name;
  },
}
</script>
<style lang="scss" scoped>
  @page {
    size: auto!important;
    margin-top: 10mm!important;
  }
</style>
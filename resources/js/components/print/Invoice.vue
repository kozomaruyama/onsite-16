<template>
  <PDF ref="pdf" :PaperOrientation="PaperOrientation" :clerk="clerk" :fileName="fileName" @changeDate="changeDate" @changeClerk="changeClerk">
    <v-simple-table class="sheet" >
      <template v-slot:default >
        <div v-for="invoice in (invoices)" :key="invoice.id" class="pagebreak">
          <div id="print_master">
            <v-simple-table class="sheet">
              <template v-slot:default>
                  <tr>
                    <th colspan="7">
                      <img width="100%" src="/img/line.png"/>
                    </th>
                  </tr>
                  <tr>
                    <th class="pl-6 text-left align-middle "  width="300" >
                      <h3>請　求　書</h3>
                    </th>
                    <th class=" " width="50">　</th>
                    <th class=" " width="150"></th>
                    <th class=" " ></th>
                    <th class=" " width="100">　</th>
                    <th class=" " ></th>
                    <th class=" text-right" width="50" style="font-size:0.7em">
                      <span>{{ order_date }}</span><br>
                    </th>
                  </tr>
                  <!-- <tr>
                    <th class=" text-left border" colspan="2" style="font-size:0.7em">
                      <img src="/img/logo.png" width="187" height="25" />
                    </th>
                  </tr> -->
                  <tr>
                    <th class="">
                      <h5>{{ invoice.client_name }}　御中</h5>
                    </th>
                    <th class=" " ></th>
                    <th class=" " ></th>
                    <th class=" " ></th>
                    <th class=" " ></th>
                    <th class=" " ></th>
                    <th class=" " ></th>
                  </tr>
                  <tr>
                    <th class="align-baseline "  >
                    <th class="align-baseline "  style="font-size:0.7em">
                      <span>平素は格別のお引き立てを賜り厚く御礼申し上げます。</span><br>
                      <span>下記の通り御請求させて頂きます。</span><br>
                      <span>御査収の程、宜しくお願い申し上げます。</span>
                    </th>
                    <th class=""></th>
                    <th class=""></th>
                    <th class=""></th>
                    <th class=""></th>
                    <th class=""></th>
                    <th class="" style="font-size:0.7em">
                      <img src="/img/logo.png" width="187" height="25" />
                    </th>
                  </tr>

                  <tr>
                    <th clasßs=""></th>
                    <th class=""></th>
                    <th class=""></th>
                    <th class=""></th>
                    <!-- <th class="" style="border-top:1px solid; border-left:1px solid "></th>
                    <th class="" style="border-top:1px solid; border-right:1px solid "></th> -->
                    <th class=""></th>
                    <!-- <th class="" style="font-size:0.7em"> -->
                    <th class="" >
                      <div class="text-right">　</div>
                    </th>
                    <th class=" "  style="font-size:0.7em" >
                      <div v-if="company.invoice_code!=null" class="text-left">(登録番号:{{ company.invoice_code }})</div>
                    </th>                    

                  </tr>

                  <tr>
                    <th class="" rowspan="3"></th>
                    <th class="" rowspan="3"></th>
                    <th class="text-right align-bottom pr-2" rowspan="3" style="border-left:1px solid; border-top:1px solid; border-bottom:1px solid ">
                      <h4>ご請求金額</h4>
                    </th>
                    <th class="text-left align-bottom pl-2" rowspan="3" style="border-right:1px solid; border-top:1px solid; border-bottom:1px solid ">
                      <h4>¥{{ (Number(invoice.bill_amount)+Number(invoice.adjust_amount)).toLocaleString() }}</h4>
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
                    <th class=""  style="font-size:0.7em">
                      <div class="text-right">住所　</div>
                    </th>
                    <th class=" "  style="font-size:0.7em" >
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
                    <th class="  text-center " colspan="2" style="font-size:0.7em">
                        ■　{{ wareki(invoice.closing_date) }}　請求分■
                    </th>
                    <th class=" "></th>
                    <th class=" "></th>
                    <th class=" " rowspan="4" >
                      <img src="/img/box.png" width="187" height="63">
                    </th>
                  </tr>
                  <tr>
                    <th class=" "></th>
                    <th class=" "></th>
                    <th class=" text-right" style="font-size:0.7em" >
                      <span >御支払日　：　</span>
                    </th>
                    <th class=" " style="font-size:0.7em" >
                      <span>{{ invoice.payment_date }}</span>
                    </th>
                    <th class=" "></th>
                    <th class=" "></th>
                  </tr>
                  <tr>
                    <th class=" "></th>
                    <th class=" "></th>
                    <th class="  text-right" style="font-size:0.7em" >
                      <span >振込先　：　</span>
                    </th>
                    <th class=" " style="font-size:0.7em" >
                      <span>{{company.transfer_bank + '　' + company.transfer_brach}}</span>
                    </th>
                    <th class=" "></th>
                    <th class=" "></th>
                  </tr>
                  <tr>
                    <th class=" "></th>
                    <th class=" "></th>
                    <th class="  text-right" style="font-size:0.7em" >
                      <span ></span><br>
                    </th>
                    <th class=" " style="font-size:0.7em" >
                      <span v-if="company.transfer_type==1">普通 {{ company.transfer_code + '　' + company.transfer_name}}</span>
                      <span v-else>当座 {{ company.transfer_code + '　' + company.transfer_name}}</span><br>
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
                  <th width="100px" class="border text-center px-1" style="font-size:0.7em">単位</th>
                  <th width="80px" class="border text-center px-1" style="font-size:0.7em">単価</th>
                  <th width="100px" class="border text-center px-1" style="font-size:0.7em">金額</th>
                  <th width="200px" class="border text-center px-1" style="font-size:0.7em">備考</th>
                </tr>
              </thead>
              <tbody>
                <tr height="25px" v-for="(invoice_master) in invoice.v_invoice_masters" :key="invoice_master.id" >
                  <td class="border text-right px-1" style="font-size:0.7em">{{ invoice_master.no }}</td>
                  <td class="border px-1" style="font-size:0.7em">{{ invoice_master.name }}</td>
                  <td class="border px-1" style="font-size:0.7em">{{ invoice_master.breakdown }}</td>
                  <td class="border text-right px-1" style="font-size:0.7em">{{ invoice_master.ratio.toLocaleString() }}</td>
                  <td class="border text-center px-1" style="font-size:0.7em">%</td>
                  <td class="border text-right px-1" style="font-size:0.7em">{{ (invoice_master.expenses - invoice_master.discount_total).toLocaleString() }}</td>
                  <td class="border text-right px-1" style="font-size:0.7em">{{ invoice_master.bill_amount.toLocaleString() }}</td>
                  <td v-if="invoice_master.tax_state==1" class="border px-1" style="font-size:0.7em">（消費税込）{{ invoice_master.memo }}</td>
                  <td v-else class="border px-1">{{ invoice_master.memo }}</td>
                </tr>
                <!-- <tr height="25px" v-if="invoice.discount!=0">
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-center px-1 text-danger" style="font-size:0.7em">値引き</td>
                  <td class="border px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border textcenter px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1" style="font-size:0.7em"></td>
                  <td class="border text-right px-1 text-danger" style="font-size:0.7em" >▲{{ invoice.discount.toLocaleString() }}</td>
                  <td class="border px-1"></td>
                </tr> -->
                <tr>
                  <td class="border-start p-1" style="font-size:0.7em" colspan="4"></td>
                  <td class="border text-center p-1" style="font-size:0.7em" colspan="2">小　　計</td>
                  <td class="border text-right p-1" style="font-size:0.7em">{{ (invoice.amount - invoice.discount + invoice.adjust_amount).toLocaleString() }}</td>
                  <!-- <td class="border text-right p-1" style="font-size:0.7em">{{ (invoice.amount_end).toLocaleString()}}</td> -->
                  <td class="border p-1" style="font-size:0.7em"></td>
                </tr>

                <tr v-if="company.invoice_code!=null">
                  <td class="border-start p-1" style="font-size:0.7em"></td>
                  <td class="" style="font-size:0.7em"></td>
                  <td class="text-right p-1" style="font-size:0.7em">10%内税対象：{{ invoice.taxAmount_1.toLocaleString() }}　　内税：{{ invoice.tax_1.toLocaleString() }}</td>
                  <td class="" style="font-size:0.7em" ></td>
                  <td class="border text-center p-1" style="font-size:0.7em" colspan="2">(内消費税)</td>
                  <td class="border text-right p-1" style="font-size:0.7em" v-if="invoice.tax_1 !=0">({{ invoice.tax_1.toLocaleString() }})</td>
                  <td class="border text-right p-1" style="font-size:0.7em" v-else>0</td>
                  <td class="border p-1" style="font-size:0.7em">あ</td>
                </tr>

                <tr v-if="invoice.tax_2 !=0">
                  <td class="border-start p-1" style="font-size:0.7em"></td>
                  <td class="" style="font-size:0.7em"></td>
                  <td class="text-right p-1" style="font-size:0.7em">10%外税対象：{{ invoice.taxAmount_2.toLocaleString() }}　　外税：{{ invoice.tax_2.toLocaleString() }}</td>
                  <td class="" style="font-size:0.7em" ></td>
                  <td class="border text-center p-1" style="font-size:0.7em" colspan="2">消費税</td>
                  <td class="border text-right p-1" style="font-size:0.7em" >{{ invoice.tax_2.toLocaleString() }}</td>
                  <td class="border p-1" style="font-size:0.7em"></td>
                </tr>
                <tr v-else>
                    <td class="border-start border-bottom p-1" style="font-size:0.7em" rowspan="3" colspan="4"></td>
                    <td class="border text-center p-1" style="font-size:0.7em" colspan="2">あ</td>
                    <td class="border text-right p-1 text-white" style="font-size:0.7em" ></td>
                    <td class="border p-1" style="font-size:0.7em">　</td>
                </tr>
                <!-- <tr v-if="invoice.adjust_amount!=0">
                    <td class="border text-center p-1" style="font-size:0.7em" colspan="2">調整額</td>
                    <td class="border text-right p-1" style="font-size:0.7em">{{ invoice.adjust_amount.toLocaleString() }}</td>
                    <td class="border p-1" style="font-size:0.7em"></td>
                </tr> -->
                <!-- <tr v-else> -->
                <tr>
                    <td class="border text-center p-1" style="font-size:0.7em" colspan="2"></td>
                    <td class="border text-right p-1 text-white" style="font-size:0.7em">-</td>
                    <td class="border p-1" style="font-size:0.7em"></td>
                </tr>
                <tr>
                  <td class="border text-center p-1" style="font-size:0.7em" colspan="2">合　　計</td>
                  <td class="border text-right p-1" style="font-size:0.7em">{{ (invoice.bill_amount ).toLocaleString() }}</td>
                  <td class="border p-1" style="font-size:0.7em"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <hr width="100%" style="border-top: 1px dashed ;" class="no-printing my-10">
          <!-- 明細 -->
          <div v-for="master in invoice.v_invoice_masters" :key="master.id" >
            <div v-if="master.v_invoice_details.length>0">
              <table class="print_details pagebreak_before">
                  <thead>
                    <tr height="10px " >
                      <th width="25px" class="text-center px-1" style="font-size:2ex" colspan="10" >【請求明細書】</th>
                    </tr>
                    <tr height="10px " >
                      <th width="25px" class="text-left px-1" style="font-size:0.7em" colspan="10">現場名：{{ master.site_name }}</th>
                    </tr>
                    <tr height="10px " >
                        <th width="25px" class="border text-center px-1" style="font-size:0.7em">No</th>
                        <th width="300px" class="border text-center px-1" style="font-size:0.7em">名称</th>
                        <th width="370px" class="border text-center px-1" style="font-size:0.7em">規格・仕様</th>
                        <th width="100px" class="border text-center px-1" style="font-size:0.7em">数量</th>
                        <th width="100px" class="border text-center px-1" style="font-size:0.7em">単位</th>
                        <th width="80px" class="border text-center px-1" style="font-size:0.7em">単価</th>
                        <th width="100px" class="border text-center px-1" style="font-size:0.7em">金額</th>
                        <th width="100px" class="border text-center px-1" style="font-size:0.7em">出来高(%)</th>
                        <th width="100px" class="border text-center px-1" style="font-size:0.7em">出来高(¥)</th>
                        <!-- <th width="50px" class="border-top text-center px-1" style="font-size:0.7em"></th> -->
                        <th width="200px" class="border text-center px-1" style="font-size:0.7em">備考</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr height="25px" v-for="detail in master.v_invoice_details" :key="detail.id" >
                        <td class="border text-right px-1" style="font-size:0.7em">{{ detail.no }}</td>
                        <td class="border px-1" style="font-size:0.7em">{{ detail.name }}</td>
                        <td class="border px-1" style="font-size:0.7em">{{ detail.breakdown }}</td>
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0">{{ detail.volume.toLocaleString()  }}</td><td class="border" v-else></td>
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0">{{ detail.unit_name }}</td><td class="border" v-else></td>
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0">{{ detail.cost.toLocaleString() }}</td><td class="border" v-else></td>
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0">{{ detail.amount.toLocaleString() }}</td><td class="border" v-else></td>
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0 && detail.yield_ratio != 0">{{ detail.yield_ratio }}</td><td class="border" v-else></td>
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0 && detail.yield_amount != 0">{{ detail.yield_amount.toLocaleString() }}</td><td class="border" v-else></td>
                        <!-- <td class="border order-right-0 px-1" style="font-size:0.7em" v-if="detail.isLabel==0 && detail.remain_ratio>0">{{ detail.remain_ratio }}</td><td class="border bordre-right-0 px-1" v-else>済</td> -->
                        <td class="border border-left-0 px-1" style="font-size:0.7em" v-if="detail.isLabel==0">
                          
                          {{ detail.memo }}
                        </td>
                        <td class="border border-left-0" v-else></td>
                    </tr>
                    <tr>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-center p-1" style="font-size:0.7em">明細計</td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right p-1" style="font-size:0.7em">{{ master.amount.toLocaleString() }}</td>
                        <td class="border p-1"></td>
                    </tr>
                    <tr v-if="master.discount!=0">
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-center p-1 text-danger" style="font-size:0.7em">値引き</td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1 text-danger" style="font-size:0.7em">▲{{ master.discount_total.toLocaleString() }}</td>
                        <td class="border text-right px-1 text-danger" style="font-size:0.7em">{{ master.ratio.toLocaleString() }}</td>
                        <td class="border text-right p-1 text-danger" style="font-size:0.7em">▲{{ master.discount.toLocaleString() }}</td>
                        <td class="border p-1"></td>
                    </tr>
                    <tr v-if="master.adjust_amount!=0">
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-center p-1" style="font-size:0.7em">調整分</td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right p-1 " style="font-size:0.7em">{{ master.adjust_amount.toLocaleString() }}</td>
                        <td class="border p-1"></td>
                    </tr>
                    <tr>
                        <td class="border text-center p-1" style="font-size:0.7em" colspan="8">小　　計</td>
                        <td class="border text-right p-1" style="font-size:0.7em" >{{ (master.amount-master.discount+master.adjust_amount).toLocaleString() }}</td>
                        <td class="border p-1"></td>
                    </tr>
                    <tr v-if="master.tax_state==1">
                        <td class="border text-center p-1" style="font-size:0.7em" colspan="8">(内消費税)</td>
                        <td class="border text-right p-1" style="font-size:0.7em" >({{ master.tax.toLocaleString() }})</td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                    </tr>
                    <tr v-if="master.tax_state==2">
                        <td class="border text-center p-1r" style="font-size:0.7em" colspan="8">消 費 税</td>
                        <td class="border text-right p-1" style="font-size:0.7em" >{{ master.tax.toLocaleString() }}</td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                    </tr>
                    <tr>
                        <td class="border text-center p-1" style="font-size:0.7em" colspan="8">合　　計</td>
                        <td class="border text-right p-1" style="font-size:0.7em">{{ (master.bill_amount+master.adjust_amount).toLocaleString() }}</td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                    </tr>
                  </tbody>
              </table>
              <hr width="100%" style="border-top: 1px dashed ;" class="no-printing my-10">
            </div>
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
const { isDate, getDate } = commFunctions();
export default {
    components: {
        PDF,
    },
    props: ["selectItems","person"],
    data: function () {
      return {
        PaperOrientation: 2,
        fileName:"",
        unitNames: [],
        invoices: [],
        company: [],
        printDate: new Date(),
        clerk:"",
      }
    },
    methods: {
        getRecords(company) {
            this.company = company;
// alert(JSON.stringify(this.selectItems))
            var items = this.selectItems.map(item => item.id);
// alert(JSON.stringify(items))
            axios.post('/invoices', { targetItems : items} ).then((res) => {
              this.invoices = res.data;
// alert(JSON.stringify(this.invoices))
                this.fileName = "bill_" + this.invoices[0].id
            });
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
    .pagebreak_before {
        break-before: page;
    }
    @page {
        margin: 14mm;
        font-family: "Helvetica Neue", Arial, "Hiragino Kaku Gothic ProN", "Hiragino Sans", Meiryo, sans-serif;
        line-height: 1.5;
        size:landscape;
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

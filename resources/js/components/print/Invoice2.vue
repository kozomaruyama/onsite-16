<template>
  <PDF ref="pdf" :PaperOrientation="PaperOrientation" :clerk="clerk" :fileName="fileName" @changeDate="changeDate" @changeClerk="changeClerk">
    <v-simple-table class="sheet" >
      <template v-slot:default >
        <div>
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
                    <th class=""></th>
                    <th class="" style="font-size:0.7em">
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
                      <!-- <h4>¥{{ (Number(invoice.bill_amount)+Number(invoice.tax_2)+Number(invoice.tax_3)).toLocaleString() }}</h4> -->
                      <h4>¥{{ Number(invoice.bill_amount).toLocaleString() }}</h4>
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
                    <th class="text-center " colspan="2" style="font-size:0.7em">
                        ■　{{ wareki(invoice.closing_date) }}　請求分■
                    </th>
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
                    <th class=" text-right" style="font-size:0.7em" >
                      <span >御支払日　：　</span>
                    </th>
                    <th class=" " style="font-size:0.7em" >
                      <span>{{ invoice.payment_date }}</span>
                    </th>
                    <th class=" "></th>
                    <th class="text-right" rowspan="4" colspan="2">
                      <img src="/img/box.png" width="230" height="63">
                    </th>
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
                      <span v-if="company.transfer_type==1">普通 {{ company.transfer_code}}</span>
                      <span v-else>当座 {{ company.transfer_code}}</span><br>
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
                      {{ company.transfer_name}}
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
                  <th v-if="invoice.print_style==1" width="370px" class="border text-center px-1" style="font-size:0.7em">住所</th>
                  <th v-else width="370px" class="border text-center px-1" style="font-size:0.7em">規格・仕様</th>
                  <th width="100px" class="border text-center px-1" style="font-size:0.7em">数量</th>
                  <th width="100px" class="border text-center px-1" style="font-size:0.7em">単位</th>
                  <th width="80px" class="border text-center px-1" style="font-size:0.7em">単価</th>
                  <th width="100px" class="border text-center px-1" style="font-size:0.7em">金額</th>
                  <th width="200px" class="border text-center px-1" style="font-size:0.7em">備考</th>
                </tr>
              </thead>
              <tbody>
                <tr height="25px" v-for="(invoice_master) in masters" :key="invoice_master.id" >
                  <td v-if="invoice.print_style==1" class="border text-right px-1" style="font-size:0.7em">{{ invoice_master.code }}</td>
                  <td v-else class="border text-right px-1" style="font-size:0.7em">{{ invoice_master.no }}</td>
                  <td class="border px-1" style="font-size:0.7em">{{ invoice_master.name }}</td>
                  <td v-if="invoice.print_style==1" class="border px-1" style="font-size:0.7em">{{ invoice_master.site_address }}</td>
                  <td v-else class="border px-1" style="font-size:0.7em">{{ invoice_master.breakdown }}</td>
                  <td v-if="invoice_master.ratio==0" class="border text-right px-1" style="font-size:0.7em"></td>
                  <td v-else class="border text-right px-1" style="font-size:0.7em">{{ invoice_master.ratio.toLocaleString() }}</td>
                  <td v-if="invoice_master.ratio==0" class="border text-right px-1" style="font-size:0.7em"></td>
                  <td v-else class="border text-center px-1" style="font-size:0.7em">%</td>
                  <td v-if="invoice_master.expenses==0" class="border text-right px-1" style="font-size:0.7em"></td>
                  <td v-else class="border text-right px-1" style="font-size:0.7em">{{ (invoice_master.expenses - invoice_master.discount_total).toLocaleString() }}</td>
                  <td v-if="invoice_master.tax_state==3" class="border text-right px-1" style="font-size:0.7em">{{ (Number(invoice_master.amount)-Number(invoice_master.discount)+Number(invoice_master.adjust_amount)).toLocaleString() }}</td>
                  <td v-else class="border text-right px-1" style="font-size:0.7em">{{ (Number(invoice_master.amount)-Number(invoice_master.discount)+Number(invoice_master.adjust_amount)).toLocaleString() }}</td>
                  <td v-if="invoice_master.tax_state==1" class="border px-1" style="font-size:0.7em">（消費税込）{{ invoice_master.memo }}</td>
                  <td v-else class="border px-1" style="font-size:0.7em">{{ invoice_master.memo }}</td>
                </tr>
                <tr>
                  <td class=" " style="font-size:0.7em" colspan="4"></td>
                  <td class="border text-center p-1" style="font-size:0.7em" colspan="2">小　　計</td>
                  <td class="border text-right p-1" style="font-size:0.7em">{{ (invoice.amount - invoice.discount + invoice.adjust_amount).toLocaleString() }}</td>
                  <!-- <td class="border text-right p-1" style="font-size:0.7em">{{ (invoice.bill_amount).toLocaleString() }}</td> -->
                  <td class="border p-1" style="font-size:0.7em"></td>
                </tr>

                <tr v-if="invoice.tax_1 !=0">
                  <td class=" " style="font-size:0.7em" colspan="4"></td>
                  <td class="border text-center p-1" style="font-size:0.7em" colspan="2">(内消費税)</td>
                  <td class="border text-right p-1" style="font-size:0.7em">({{ invoice.tax_1.toLocaleString() }})</td>
                  <td class="border p-1" style="font-size:0.7em"></td>
                </tr>
                <tr v-if="invoice.tax_2 !=0" >
                  <td class=" " style="font-size:0.7em" colspan="4"></td>
                  <td class="border text-center p-1" style="font-size:0.7em" colspan="2">消費税(10%)</td>
                  <td class="border text-right p-1" style="font-size:0.7em">{{ invoice.tax_2.toLocaleString() }}</td>
                  <td class="border p-1" style="font-size:0.7em"></td>
                </tr>
                <tr v-if="invoice.tax_3 !=0" >
                  <td class=" " style="font-size:0.7em" colspan="4"></td>
                  <td class="border text-center p-1" style="font-size:0.7em" colspan="2">消費税</td>
                  <td class="border text-right p-1" style="font-size:0.7em">{{ invoice.tax_3.toLocaleString() }}</td>
                  <td class="border p-1" style="font-size:0.7em"></td>
                </tr>

                <tr>
                  <td class=" " style="font-size:0.7em" colspan="4"></td>
                  <td class="border text-center p-1" style="font-size:0.7em" colspan="2">合　　計</td>
                  <!-- <td class="border text-right p-1" style="font-size:0.7em">{{ (Number(invoice.bill_amount) + Number(invoice.tax_2)+Number(invoice.tax_3) ).toLocaleString() }}</td> -->
                  <td class="border text-right p-1" style="font-size:0.7em">{{ Number(invoice.bill_amount).toLocaleString() }}</td>
                  <td class="border p-1" style="font-size:0.7em"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <hr width="100%" style="border-top: 1px dashed ;" class="no-printing my-10">
          <!-- 明細 -->
          <div v-for="master in masters" :key="master.id" class="">
            <div v-if="master.details.length>0 && master.tax_state!=4">
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
                        <th width="200px" class="border text-center px-1" style="font-size:0.7em">備考</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr height="25px" v-for="detail in master.details" :key="detail.id" >
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0" >{{ detail.no }}</td><td class="border" v-else></td>
                        <td class="border px-1" style="font-size:0.7em">{{ detail.name }}</td>
                        <td class="border px-1" style="font-size:0.7em">{{ detail.breakdown }}</td>
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0">{{ detail.volume.toLocaleString() }}</td><td class="border" v-else></td>
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0">{{ detail.unit_name }}</td><td class="border" v-else></td>
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0">{{ detail.cost.toLocaleString() }}</td><td class="border" v-else></td>
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0">{{ detail.amount.toLocaleString() }}</td><td class="border" v-else></td>
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0 && detail.yield_ratio != 0">{{ detail.yield_ratio }}</td><td class="border" v-else></td>
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0 && detail.yield_amount != 0">{{ Number(detail.yield_amount).toLocaleString() }}</td><td class="border" v-else></td>
                        <td class="border border-left-0 px-1" style="font-size:0.7em" v-if="detail.isLabel==0">
                          <!-- <span v-if="detail.memo==null">{{ remain_ratio(detail.remain_ratio)}}</span>
                          <span v-else>{{ remain_ratio(detail.remain_ratio) +  detail.memo}}</span> -->
                          {{ detail.memo}}
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
                        <td class="border text-right px-1" style="font-size:0.7em">{{ Number(master.expenses).toLocaleString() }}</td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right p-1" style="font-size:0.7em">{{ Number(master.amount).toLocaleString() }}</td>
                        <td class="border p-1"></td>
                    </tr>
                    <tr v-if="master.discount!=0">
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-center p-1 text-danger" style="font-size:0.7em">値引き</td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1 text-danger" style="font-size:0.7em">▲{{ Number(master.discount_total).toLocaleString() }}</td>
                        <td class="border text-right px-1 text-danger" style="font-size:0.7em">{{ Number(master.ratio).toLocaleString() }}</td>
                        <td class="border text-right p-1 text-danger" style="font-size:0.7em">▲{{ Number(master.discount).toLocaleString() }}</td>
                        <td class="border p-1"></td>
                    </tr>
                    <tr v-if="master.adjust_amount!=0">
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-center p-1" style="font-size:0.7em">{{ master.adjust_title }}</td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right px-1" style="font-size:0.7em"></td>
                        <td class="border text-right p-1 " style="font-size:0.7em">{{ Number(master.adjust_amount).toLocaleString() }}</td>
                        <td class="border p-1"></td>
                    </tr>
                    <tr>
                        <td class="border text-center p-1" style="font-size:0.7em" colspan="8">小　　計</td>
                        <td class="border text-right p-1" style="font-size:0.7em" >{{ (Number(master.amount)-Number(master.discount)+Number(master.adjust_amount)).toLocaleString() }}</td>
                        <td class="border p-1"></td>
                    </tr>
                    <tr v-if="master.tax_state==1">
                        <td class="border text-center p-1" style="font-size:0.7em" colspan="8">(内消費税)</td>
                        <td class="border text-right p-1" style="font-size:0.7em" >({{ Number(master.tax1).toLocaleString() }})</td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                    </tr>
                    <tr v-if="master.tax_state==2">
                        <td class="border text-center p-1r" style="font-size:0.7em" colspan="8">消 費 税</td>
                        <td class="border text-right p-1" style="font-size:0.7em" >{{ Number(master.tax2).toLocaleString() }}</td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                    </tr>
                    <tr v-else-if="master.tax_state==3 && master.tax3!=0">
                        <td class="border text-center p-1r" style="font-size:0.7em" colspan="8">消 費 税</td>
                        <td class="border text-right p-1" style="font-size:0.7em" >{{ Number(master.tax3).toLocaleString() }}</td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                    </tr>
                    <tr>
                        <td class="border text-center p-1" style="font-size:0.7em" colspan="8">合　　計</td>
                        <!-- <td class="border text-right p-1" style="font-size:0.7em">{{ (Number(master.bill_amount)+Number(master.tax2)+Number(master.tax3)).toLocaleString() }}</td> -->
                        <td class="border text-right p-1" style="font-size:0.7em">{{ (Number(master.bill_amount)+Number(master.tax3)).toLocaleString() }}</td>
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
        invoice: [],
        masters: [],
        details: [],
        company: [],
        printDate: new Date(),
        clerk:"",
      }
    },
    methods: {
        getRecords(company) {
            this.company = company;
            var items = this.selectItems.map(item => item.id);
            axios.post('/invoices', { targetItems : items} ).then((res) => {
              this.invoice = res.data.invoice;
              this.masters = res.data.masters;
// alert(JSON.stringify(this.masters))
              let newMasters = [];
              let no=1;
              let tax = 0;
              this.masters.forEach(function(master){
                master.no = no;
                newMasters.push(master);
                no++;
                if (master.tax_state==3 && master.tax!=0) {
                  let addTaxRow = Object.assign({}, master)
                  addTaxRow.tax_state = 3;
                  addTaxRow.no = no;
                  addTaxRow.breakdown = "消費税"
                  addTaxRow.expenses = 0
                  addTaxRow.ratio = 0
                  addTaxRow.cost = 0
                  addTaxRow.amount = master.tax
                  addTaxRow.adjust_amount = 0
                  addTaxRow.discount = 0
                  addTaxRow.discount_total = 0
                  addTaxRow.tax2 = 0
                  addTaxRow.tax3 = master.tax
                  addTaxRow.bill_amount = master.tax,
                  newMasters.push(addTaxRow);
                  no++;
                  tax += master.tax;
                }
              });
              this.masters = newMasters;
// alert(JSON.stringify(this.invoice))
              this.details = res.data.details;
              // this.invoice.tax_3 += tax;
              this.fileName = "bill_" + this.invoice.id
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
        remain_ratio: function() {
          return (ratio)=>{
            if (ratio==0) {
              return ""
            } else {
              return "（残：" + ratio + "%）"
            }
          }
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
    .pagebreak_before {
        break-before: page;
    }

    // @page {
    //     margin: 14mm;
    //     font-family: "Helvetica Neue", Arial, "Hiragino Kaku Gothic ProN", "Hiragino Sans", Meiryo, sans-serif;
    //     line-height: 1.5;
    //     size:landscape;
    //     // size: landscape !important;
    // }
    @page {
      size: auto!important;
      // margin: 0mm!important;
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

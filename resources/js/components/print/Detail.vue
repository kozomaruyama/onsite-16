<template>
  <PDF ref="pdf" :PaperOrientation="PaperOrientation">
    <v-simple-table class="sheet" >
      <template v-slot:default >
        <!-- <hr width="100%" style="border-top: 1px dashed ;" class="no-printing my-10"> -->
        <!-- 明細 -->
        <div v-for="master in masters" :key="master.id" class="pagebreak_before">
            <h6 class="text-center">【請求明細書】</h6>
            <div style="font-size:0.7em">現場名：{{ master.site_name }}</div>
            <table>
            <thead >
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
                <tr height="25px" v-for="detail in master.v_invoice_details" :key="detail.id" >
                    <td class="border text-right px-1" style="font-size:0.7em">{{ detail.no }}</td>
                    <td class="border px-1" style="font-size:0.7em">{{ detail.name }}</td>
                    <td class="border px-1" style="font-size:0.7em">{{ detail.breakdown }}</td>
                    <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0">{{ detail.volume.toLocaleString()  }}</td><td class="border" v-else></td>
                    <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0">{{ detail.unit_name }}</td><td class="border" v-else></td>
                    <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0">{{ detail.cost.toLocaleString() }}</td><td class="border" v-else></td>
                    <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0">{{ detail.amount.toLocaleString() }}</td><td class="border" v-else></td>
                    <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0">{{ detail.yield_ratio }}</td><td class="border" v-else></td>
                    <td class="border text-right px-1" style="font-size:0.7em" v-if="detail.isLabel==0">{{ detail.yield_amount.toLocaleString() }}</td><td class="border" v-else></td>
                    <td class="border px-1" style="font-size:0.7em" v-if="detail.isLabel==0 && detail.remain_ratio>0">{{ "残" + detail.remain_ratio + "% "  + detail.memo }}</td>
                    <td class="border" v-else>{{  detail.memo }}</td>
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
                    <td class="border text-right p-1" style="font-size:0.7em">{{ (master.amount-master.discount+master.adjust_amount).toLocaleString() }}</td>
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
                    <td class="border text-right p-1" style="font-size:0.7em">{{ (master.bill_amount).toLocaleString() }}</td>
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
import { mapState, mapGetters } from 'vuex'
import PDF from "../PDF";
import dbCompanyFunctions from '../../functions/dbCompanyFunctions'
const { getCompany } = dbCompanyFunctions()
export default {
    components: {
        PDF,
    },
    props: ["selectItems"],
    data: function () {
      return {
        PaperOrientation: 2,
        unitNames: [],
        masters: [],
        company: [],
      }
    },
    methods: {
        getRecords(company) {
            this.company = company;
            const ids = this.selectItems.map(item => item.id);
            axios.post('/invoice_master/print', { ids : ids} ).then((res) => {
                this.masters = res.data;
            });
        },
    },
    computed: {
        wareki: function() {
            return function (key) {
            var date = new Date(key);
            var wa = new Intl.DateTimeFormat('ja-JP-u-ca-japanese',
                { era:'long', year: 'numeric', month: 'long' }).format(date)
            return wa;
            };
        },
        ...mapGetters([ 'getUnitNames' ]),
    },
    created() {
        this.unitNames = this.$store.getters.getUnitNames;
    },
    mounted() {
        getCompany(2,this.getRecords);
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

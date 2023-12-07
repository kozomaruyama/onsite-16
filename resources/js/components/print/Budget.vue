<template>
  <PDF ref="pdf" :PaperOrientation="PaperOrientation">
    <v-simple-table class="sheet" >
      <template v-slot:default >
        <div v-for="subject in subjects" :key="subject.id" class="pagebreak">
        <!-- <hr width="100%" style="border-top: 1px dashed ;" class="no-printing my-10"> -->
        <!-- 明細 -->
            <h6 class="text-center">【実行予算書】</h6>
            <div style="font-size:0.7em">案件名：{{ subject.name }}</div>
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
                        <th width="200px" class="border text-center px-1" style="font-size:0.7em">備考</th>
                    </tr>
                </thead>
                <tbody>
                    <tr height="25px" v-for="(item) in subject.v_tasks" :key="item.id" >
                        <td class="border text-right px-1" style="font-size:0.7em">{{ item.no }}</td>
                        <td class="border px-1" style="font-size:0.7em">{{ item.name }}</td>
                        <td class="border px-1" style="font-size:0.7em">{{ item.breakdown }}</td>
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="item.isLabel == 0">{{ item.volume }}</td><td v-else class="border text-right px-1" ></td>
                        <td class="border text-center px-1" style="font-size:0.7em" v-if="item.isLabel == 0">{{ unitNames[item.unit-1].name }}</td><td v-else class="border text-right px-1" ></td>
                        <td class="border text-right  px-1" style="font-size:0.7em" v-if="item.cost !== null && item.isLabel == 0">{{ item.cost.toLocaleString() }}</td><td v-else class="border text-right px-1" ></td>
                        <td class="border text-right px-1" style="font-size:0.7em" v-if="item.isLabel == 0">{{ item.amount.toLocaleString() }}</td><td v-else class="border text-right px-1" ></td>
                        <td class="border px-1" style="font-size:0.7em">{{ item.memo }}</td>
                    </tr>
                    <tr v-if="subject.v_tasks.length==0">
                        <td class="border p-1" style="font-size:0.7em">1</td>
                        <td class="border p-1" style="font-size:0.7em">{{ subject.name }}</td>
                        <td class="border p-1" style="font-size:0.7em">{{ subject.breakdown }}</td>
                        <td class="border text-center p-1" style="font-size:0.7em">1</td>
                        <td class="border text-center p-1" style="font-size:0.7em">式</td>
                        <td class="border text-center p-1" style="font-size:0.7em"></td>
                        <td class="border text-right p-1" style="font-size:0.7em">{{ subject.expenses.toLocaleString() }}</td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="border p-1" style="font-size:0.7em"></td>
                        <td class="border text-center p-1" style="font-size:0.7em">小　　計</td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                        <td class="border text-center p-1" style="font-size:0.7em"></td>
                        <td class="border text-center p-1" style="font-size:0.7em"></td>
                        <td class="border text-center p-1" style="font-size:0.7em"></td>
                        <td class="border text-right p-1" style="font-size:0.7em">{{ subject.expenses.toLocaleString() }}</td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                    </tr>
                    <tr>
                        <td class="border p-1" style="font-size:0.7em"></td>
                        <td class="border text-center p-1" style="font-size:0.7em">法定福利費</td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                        <td class="border text-right p-1" style="font-size:0.7em">1.0</td>
                        <td class="border text-center p-1" style="font-size:0.7em">式</td>
                        <td class="border text-right p-1" style="font-size:0.7em"></td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                    </tr>
                    <tr>
                        <td class="border p-1" style="font-size:0.7em"></td>
                        <td class="border text-center p-1" style="font-size:0.7em">合　　計</td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                        <td class="border text-center p-1" style="font-size:0.7em"></td>
                        <td class="border text-center p-1" style="font-size:0.7em"></td>
                        <td class="border text-right p-1" style="font-size:0.7em"></td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                    </tr>
                    <tr class=""><td></td></tr>
                    <tr>
                        <td class="border p-1" style="font-size:0.7em"></td>
                        <td class="border text-center p-1" style="font-size:0.7em">ＮＥＴ金額</td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                        <td class="border text-right p-1" style="font-size:0.7em">1.0</td>
                        <td class="border text-center p-1" style="font-size:0.7em">式</td>
                        <td class="border text-right p-1" style="font-size:0.7em"></td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                        <td class="border p-1" style="font-size:0.7em"></td>
                    </tr>
                </tfoot>
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
import commFunctions from '../../functions/commFunctions'
const { isDate, getDate } = commFunctions();
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
            subjects: [],
            tasks: [],
            company: [],
            printDate: new Date(),
        }
    },
    methods: {
			getRecords() {
                const ids = this.selectItems.map(item => item.id);
				axios.post('/subject/print', { ids : ids} )
					.then((res) => {
						this.subjects = res.data;
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
        this.getRecords();
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

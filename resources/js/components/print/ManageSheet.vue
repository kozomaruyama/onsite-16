<template>
    <PDF ref="pdf" :PaperOrientation="PaperOrientation" fileName="受注管理表">
      <v-simple-table class="sheet" >
        <template v-slot:default >
          <!-- <hr width="100%" style="border-top: 1px dashed ;" class="no-printing my-10"> -->
          <!-- 明細 -->
          <!-- <div v-for="subject in subjects" :key="subject.id" class="pagebreak_before"> -->
              <!-- <div style="font-size:0.7em">現場名：{{ master.site_name }}</div> -->
              <table id="print_master">
                <thead >
                    <tr height="10px " >
                        <th class="text-center px-1" style="font-size:2.0ex" colspan="16">
                          受注管理表　（{{ title }}）
                        </th>
                    </tr>
                    <tr height="10px " >
                        <th v-if="selectItems.key=='date'" width="25px" class="border text-center px-1" style="font-size:0.7em">施工日</th>
                        <th v-else width="80px" class="border text-center px-1" style="font-size:0.7em">施工日</th>
                        <th width="25px" class="border text-center px-1" style="font-size:0.7em">列２</th>
                        <th width="25px" class="border text-center px-1" style="font-size:0.7em">★</th>
                        <th width="300px" class="border text-center px-1" style="font-size:0.7em">現場名・工事名</th>
                        <th width="100px" class="border text-center px-1" style="font-size:0.7em">元請</th>
                        <th width="80px" class="border text-center px-1" style="font-size:0.7em">規模</th>
                        <th width="100px" class="border text-center px-1" style="font-size:0.7em">担当者</th>
                        <th width="50px" class="border text-center px-1" style="font-size:0.7em">架払</th>
                        <th width="50px" class="border text-center px-1" style="font-size:0.7em">請求日</th>
                        <th width="100px" class="border text-center px-1" style="font-size:0.7em">請負金額</th>
                        <th width="50px" class="border text-center px-1" style="font-size:0.7em">出来高</th>
                        <th width="100px" class="border text-center px-1" style="font-size:0.7em">出来高金額</th>
                        <th width="25px" class="border text-center px-1" style="font-size:0.7em">締日</th>
                        <th width="100px" class="border text-center px-1" style="font-size:0.7em">下請</th>
                        <th width="100px" class="border text-center px-1" style="font-size:0.7em">発注金額(税抜)</th>
                        <th width="100px" class="border text-center px-1" style="font-size:0.7em">発注金額(税込)</th>
                        <th width="100px" class="border text-center px-1" style="font-size:0.7em">出来高金額(税抜)</th>
                        <th width="100px" class="border text-center px-1" style="font-size:0.7em">利益率</th>
                    </tr>
                </thead>
                <tbody>
                    <tr height="25px" v-for="schedule in schedules" :key="schedule.id" >
                        <td class="border text-right px-1" style="font-size:0.7em">{{ schedule.day }}</td>
                        <td class="border px-1" style="font-size:0.7em"></td>
                        <td class="border px-1" style="font-size:0.7em"></td>
                        <td class="border text-left px-1" style="font-size:0.7em" >{{ schedule.subject }}</td>
                        <td class="border text-left px-1" style="font-size:0.7em" >{{ schedule.client_nick }}</td>
                        <td class="border text-left px-1" style="font-size:0.7em" ></td>
                        <td class="border text-left px-1" style="font-size:0.7em" ></td>
                        <td class="border text-center px-1" style="font-size:0.7em" >{{ schedule.schedule_class }}</td>
                        <td class="border text-center px-1" style="font-size:0.7em" >{{ schedule.bill_issue }}</td>
                        <td class="border text-right px-1" style="font-size:0.7em" >{{ schedule.amount }}</td>
                        <td class="border text-right px-1" style="font-size:0.7em" >{{ ratio(schedule.pay_ratio) }}</td>
                        <td class="border text-right px-1" style="font-size:0.7em" >{{ schedule.bill_amount }}</td>
                        <td class="border text-right px-1" style="font-size:0.7em" >{{ schedule.pay_closing }}</td>
                        <td class="border text-left px-1" style="font-size:0.7em" >{{ schedule.sub_nick }}</td>
                        <td class="border text-right px-1" style="font-size:0.7em" >{{ schedule.amount_subcontract }}</td>
                        <td class="border text-right px-1" style="font-size:0.7em" >{{ amount_total(schedule.amount_subcontract) }}</td>
                        <td class="border text-right px-1" style="font-size:0.7em" >{{ schedule.pay_amount }}</td>
                        <td v-if="schedule.pay_amount>0 && schedule.bill_amount>0" class="border text-right px-1" style="font-size:0.7em" >{{ 100 - Math.round((schedule.pay_amount / schedule.bill_amount) * 100) }}%</td>
                        <td v-else class="border text-right px-1" style="font-size:0.7em" ></td>
                    </tr>
                </tbody>
              </table>
              <hr width="100%" style="border-top: 1px dashed ;" class="no-printing my-10">
          <!-- </div> -->
        </template>
      </v-simple-table>
    </PDF>
  </template>
  <script>

  import { mapState, mapGetters } from 'vuex'
  import PDF from "../PDF";
  import commFunctions from '../../functions/commFunctions';
  const { getLastDay,getFormatYMD,calcTax } = commFunctions();
  export default {
      components: {
          PDF,
      },
      props: ["selectItems"],
      data: function () {
        return {
          PaperOrientation: 2,
          unitNames: [],
        //   subjects: [],
          schedules: [],
          select_date:null,
          title:null,
        }
      },
      methods: {
          getRecords() {
            this.title=this.selectItems.item.value
            if (this.selectItems.key=="date") {
              this.select_date = {
                para1:1, 
                year:this.selectItems.item.id.substr(0,4), 
                month:this.selectItems.item.id.substr(5,2),
                client:0 
              }
            } else {
              const end_date = getLastDay(new Date());
              const start_date = new Date(new Date().getFullYear() - 1 ,new Date().getMonth() + 1,1);
              this.title =  this.title + "  " + getFormatYMD(start_date) + "～" + getFormatYMD(end_date) 
              this.select_date = { para1:2,year:1,month:1,client:this.selectItems.item.id }
            }

// alert(JSON.stringify(this.select_date))
            axios.post('/schedule/mansheet', this.select_date).then((res) => {
// alert(JSON.stringify(res.data))
              this.schedules = res.data;
            })
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
          ratio: function() {
            return function(val) {
                if (val == 0 ) {
                    return ""
                } else {
                    return String(parseInt(val,10)) + "%"
                }
            }
          },
          amount_total: function() {
            return function(amount) {
              const value = calcTax(amount,2) ;
              return value.total;
            } 
          }
        //   ...mapGetters([ 'getUnitNames' ]),
      },
      created() {
        //   this.unitNames = this.$store.getters.getUnitNames;
      },
      mounted() {
        //   getCompany(2,this.getRecords);
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
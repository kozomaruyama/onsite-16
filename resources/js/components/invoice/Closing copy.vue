<template>
<v-app>
  <v-stepper v-model="e1" outlined >
    <v-stepper-items outlined>
      <v-stepper-content step="1">
        <v-container>
          <v-card-title>
            締め対象とする顧客をチェックし選択してください。
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
              class="m-0 p-0"
            ></v-text-field>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text class="m-0 p-0">
            <v-data-table
              v-model="client_selected"
              :headers="client_headers"
              :items="clients"
              :single-select="false"
              :search="search"
              :server-items-length="1"
              :loading="loading"
              hide-default-footer
              item-key="id"
              show-select
              class="elevation-1"
            >
              <template v-slot:top>
              </template>
            </v-data-table>
          </v-card-text>
          <v-row class="m-0 p-0">
            <v-col class="col-auto">
              <v-btn color="primary" @click="close_dialog()" ><v-icon left>mdi-close</v-icon>中止</v-btn>
            </v-col>
            <v-col />
            <v-col class="col-auto">
              <!-- <v-btn color="primary" @click="e1 = 2">次へ<v-icon right>mdi-arrow-right</v-icon></v-btn> -->
              <v-btn color="primary" @click="getSubjects()">次へ<v-icon right>mdi-arrow-right</v-icon></v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-stepper-content>
      <!--  -->
      <v-stepper-content step="2">
        <v-container>
          <v-card-title>
            「締切日」と対象となる「案件」をチェックし、[生成]ボタンをクリックすると請求データが作成されます。
            <v-spacer></v-spacer>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text  class="m-0 p-0">
            <v-menu
              v-model="menu_closing_date"
              transition="scale-transition"
              offset-y
              max-width="290px"
              min-width="auto"
            >
              <template v-slot:activator="{ on }">
                <v-text-field
                  v-model="closing_date.ym"
                  label="締切日"
                  prepend-icon="mdi-calendar"
                  v-on="on"
                  class = "col-2"
                  @change="closungData_changed"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="closing_date.ym"
                type="month"
                no-title
                scrollable
                locale="ja"
                @change="closungData_changed"
                @input="menu_closing_date = false"
              >
              </v-date-picker>
            </v-menu>
          </v-card-text>
          <v-card-text  class="m-0 p-0">
            <v-data-table
              v-model="subject_selected"
              :headers="subject_headers"
              :items="subjects"
              :single-select="false"
              :server-items-length="1"
              :loading="loading"
              hide-default-footer
              item-key="id"
              show-select
              class="elevation-1"
              @click:row = "doClick_subject"
            >
              <!-- <template v-slot:top>
              </template> -->
            </v-data-table>
          </v-card-text>
          <v-row class="m-0 p-0">
            <v-col class="col-auto">
              <v-btn color="primary" @click="e1 = 1"><v-icon left>mdi-arrow-left</v-icon>戻る</v-btn>
            </v-col>
            <v-col />
            <v-col class="col-auto">
              <v-btn color="primary" @click="close_dialog()" ><v-icon left>mdi-close</v-icon>中止</v-btn>
            </v-col>
            <v-col />
            <v-col class="col-auto">
              <v-btn color="success" @click="doClosing()" ><v-icon left>mdi-pencil-plus</v-icon>生成</v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-stepper-content>
      <!--  -->

      <!--  -->

    </v-stepper-items>

    <!-- <v-stepper-header outlined style="background-color:#E0E0E0"> -->
    <v-stepper-header outlined>
      <v-stepper-step :complete="e1 > 1" step="1" >
        顧客
        <!-- 「着工日」「現場名称」「現場住所」をセットしてください。 -->
      </v-stepper-step>

      <v-divider></v-divider>

      <v-stepper-step :complete="e1 > 2" step="2">
        案件
        <!-- 「着工日」「現場名称」「現場住所」をセットしてください。 -->
      </v-stepper-step>


    </v-stepper-header>


  </v-stepper>
  <!-- <DialogStep2 v-bind:subject="subject"></DialogStep2> -->
  </v-app>
</template>

<script>
  // import BtnGrp from "./ButtonGroup";
	import { mapState, mapGetters } from 'vuex'
//   import DialogStep2 from "./DialogStep2";
  export default {
    components: {
    //   DialogStep2
    },
    props: [
        'clients',
    ],
    data: function () {
      return {
        e1: 1,
        menu_closing_date: false,
        closing_date: null,

        // products: [],
        subjectClassItems: [],
        invoiceClassItems: [],
        search: '',


        singleSelect: false,
        client_selected: [],
        client_headers: [
          {
            text: '顧客名',
            align: 'start',
            sortable: false,
            value: 'name',
          },
          { text: '分類', value: 'client_class' },
          { text: '締日', value: 'bill_closing' },
        ],

        subject: [],
        subjects: [],
        subject_selected: [],
        subject_headers: [
          {
            text: '顧客名',
            align: 'start',
            sortable: false,
            value: 'client',
          },
          {
            text: '案件名',
            align: 'start',
            sortable: false,
            value: 'name',
          },
          { text: '総額', value: 'bill_amount' },
          { text: '残(％)', value: 'zan_ratio' },
          { text: '請求(％)', value: 'bill_ratio' },
          { text: '状態', value: 'process_name' },
        ],
      }
    },
    methods: {
        init() {
            this.e1 = 1;
            this.client_selected = [];
            this.subject_selected = [];
        },
        close_dialog() {
            this.e1 = 1;
            this.$emit('close_dialog');
        },
        // getClients() {

        // },
        getSubjects() {
// alert(JSON.stringify(this.closing_date));
            // 連想配列の特定キーを配列に変換し(map)、さらに取得した配列を文字列に変換(join)
            let keys = this.client_selected.map(item => item.id).join(',');
            let day = this.closing_date.year + '-' + this.closing_date.month;
            axios.post('/subjects/clients', { clients: keys, process: 26, closing_date: this.closing_date.ym })
                .then((res) => {
                    this.subjects = res.data;
                    this.e1 = 2;
            });
        },
        closing_date_changed(e) {
            this.getSubjects();
        },

        doClosing() {
            axios.post('/invoices/close', { clients: this.client_selected, subjects: this.subject_selected, date: this.closing_date })
                .then((res) => {
                    this.$emit('enter',res.data);
                }
            );
        },
        list() {
            this.$emit('list');
        },
        closungData_changed(e) {
            this.subject_selected = [];
            this.closing_date.year = this.closing_date.ym.substr(0,4);
            this.closing_date.month = Number(this.closing_date.ym.substr(5));
            this.getSubjects();
        },
        doClick_subject(subject) {
    // alert(JSON.stringify(his.closing_date));
        }
    },
    mounted() {
        // this.getClients();
        this.init();
        // this.getProducts();
    },
    watch: {
    },
    computed: {
        // ...mapState([ 'subjectClasses' ]),
        ...mapGetters([ 'getSubjectClasses' ]),
        ...mapGetters([ 'getInvoiceClasses' ])
    },
    created() {
        this.subjectClassItems = this.$store.getters.getSubjectClasses;
        this.invoiceClassItems = this.$store.getters.getInvoiceClasses;
        var today  =  new Date();
        this.closing_date = { ym:today.getFullYear()+'-'+('00'+Number(today.getMonth()+1)).slice(-2) ,year: today.getFullYear() , month: today.getMonth()+1} ;
    },
  }
</script>

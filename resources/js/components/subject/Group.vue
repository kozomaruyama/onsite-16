<template>

  <!-- <v-card>
    <v-card-text>
      <v-row align="center">
          <v-col class="col-auto m-0 p-0">
              <v-checkbox v-model="includeFiles" hide-details class="shrink mr-2 mt-0"></v-checkbox>
          </v-col>
          <v-col class="col-auto m-0 p-0">
              <v-text-field label="Include files"></v-text-field>
          </v-col>
      </v-row>
    </v-card-text>
  </v-card> -->


    <v-container fluid class="bg-peimary px-5">
        <v-row class="m-0 p-0">
            <v-col></v-col>
        </v-row>
        <v-row no-gutters>
            <v-col v-if="flag(1)" cols="auto" class="ml-2 pt-1">
                <v-checkbox v-model="enabled[0]" hide-details class="shrink mt-0" @change="itemChecked(enabled[0],'code')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(1)" cols="3">
                <v-text-field :disabled="!enabled[0]" label="コード" id="code" v-model="subject.code" dense></v-text-field>
            </v-col>
            <v-col v-if="flag(2)" cols="auto" class="ml-6 pt-1">
                <v-checkbox v-model="enabled[1]" hide-details class="shrink mt-0" @change="itemChecked(enabled[1],'class')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(2)" cols="3">
                <v-select :disabled="!enabled[1]" :items="subjectClasses" item-text="name" item-value="no" label="区分" id="class" v-model="subject.class" dense></v-select>
            </v-col>
            <v-col v-if="flag(3)" cols="auto" class="ml-6 pt-1">
                <v-checkbox v-model="enabled[2]" hide-details class="shrink mt-0" @change="itemChecked(enabled[2],'order_code')"></v-checkbox>
            </v-col>
            <v-col  v-if="flag(3)" cols="3">
                <v-text-field :disabled="!enabled[2]" label="発注番号" v-model="subject.order_code" dense></v-text-field>
            </v-col>
        </v-row>
        <v-row v-if="flag(4)" no-gutters class="ml-2">
            <v-col cols="auto" class="pt-1">
                <v-checkbox v-model="enabled[3]" hide-details class="shrink mt-0" @change="itemChecked(enabled[3],'name')"></v-checkbox>
            </v-col>
            <v-col cols="11">
                <v-text-field :disabled="!enabled[3]" label="名称" v-model="subject.name" dense></v-text-field>
            </v-col>
        </v-row>
        <v-row v-if="flag(5)" no-gutters class="ml-2">
            <v-col cols="auto" class="pt-1">
                <v-checkbox v-model="enabled[4]" hide-details class="shrink mt-0" @change="itemChecked(enabled[4],'breakdown')"></v-checkbox>
            </v-col>
            <v-col cols="11">
                <v-text-field :disabled="!enabled[4]" label="仕様" v-model="subject.breakdown" dense></v-text-field>
            </v-col>
        </v-row>
        <v-row v-if="flag(6)" no-gutters class="ml-2">
            <v-col cols="auto"  class="pt-1">
                <v-checkbox v-model="enabled[5]" hide-details class="shrink mt-0" @change="itemChecked(enabled[5],'breakdown')"></v-checkbox>
            </v-col>
            <v-col cols="11">
                <v-text-field :disabled="!enabled[5]" label="現場名" v-model="subject.site_name" dense></v-text-field>
            </v-col>
        </v-row>
        <v-row v-if="flag(7)" no-gutters class="ml-2 pt-1">
            <v-col cols="auto">
                <v-checkbox v-model="enabled[6]" hide-details class="shrink mt-0" @change="itemChecked(enabled[6],'site_address')"></v-checkbox>
            </v-col>
            <v-col cols="11">
                <v-text-field :disabled="!enabled[6]" label="現場住所" v-model="subject.site_address" dense></v-text-field>
            </v-col>
        </v-row>
        <!-- <v-row no-gutters>
            <v-col v-if="flag(8)" cols="auto" class="ml-2 pt-1">
                <v-checkbox v-model="enabled[7]" hide-details class="shrink mt-0" @change="itemChecked(enabled[7],'start_date')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(8)" cols="3">
                <v-menu
                    v-model="menu1"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            v-model="subject.start_date"
                            label= "着工日"
                            prepend-icon="mdi-calendar"
                            :disabled="!enabled[7]"
                            v-bind="attrs"
                            v-on="on"
                            dense
                        ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="subject.start_date"
                        no-title
                        locale="ja"
                        @input="menu1 = false"
                    ></v-date-picker>
                </v-menu>
            </v-col>
            <v-col v-if="flag(9)" cols="auto" class="ml-6 pt-1">
                <v-checkbox v-model="enabled[8]" hide-details class="shrink mt-0" @change="itemChecked(enabled[8],'end_date')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(9)" cols="3">
                <v-menu
                    v-model="menu2"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            v-model="subject.end_date"
                            label= "完了日"
                            prepend-icon="mdi-calendar"
                            :disabled="!enabled[8]"
                            v-bind="attrs"
                            v-on="on"
                            dense
                        ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="subject.end_date"
                        no-title
                        locale="ja"
                        @input="menu2 = false"
                    ></v-date-picker>
                </v-menu>
            </v-col>
            <v-col v-if="flag(10)" cols="auto" class="ml-6 pt-1">
                <v-checkbox v-model="enabled[9]" hide-details class="shrink mt-0" @change="itemChecked(enabled[9],'isDay')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(10)" cols="3">
                <label class="mt-2">全日</label><input :disabled="!enabled[9]" type="checkbox" id="checkbox" v-model="subject.isDay" class="mx-2">
            </v-col>
        </v-row>
        <v-row no-gutters>
            <v-col v-if="flag(11)" cols="auto" class="ml-2 pt-1">
                <v-checkbox v-model="enabled[10]" hide-details class="shrink mt-0" @change="itemChecked(enabled[10],'bill_arriving')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(11)" cols="3">
                <v-menu
                    v-model="menu3"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            v-model="subject.bill_arriving"
                            label= "請求日"
                            prepend-icon="mdi-calendar"
                            :disabled="!enabled[10]"
                            v-bind="attrs"
                            v-on="on"
                            dense
                        ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="subject.bill_arriving"
                        no-title
                        locale="ja"
                        @input="menu3 = false"
                    ></v-date-picker>
                </v-menu>
            </v-col>
            <v-col v-if="flag(12)" cols="auto" class="ml-6 pt-1">
                <v-checkbox v-model="enabled[11]" hide-details class="shrink mt-0" @change="itemChecked(enabled[11],'process')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(12)" cols="3">
                <v-select :disabled="!enabled[11]" :items="processNames" item-text="name" item-value="no" label="状態" v-model="subject.process" dense></v-select>
            </v-col>
            <v-col v-if="flag(26)" cols="auto" class="ml-6 pt-1">
                <v-checkbox v-model="enabled[23]" hide-details class="shrink mt-0" @change="itemChecked(enabled[23],'percentage')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(26)" cols="3">
                <v-text-field :disabled="!enabled[23]" label="進捗"  suffix="％" v-model="subject.percentage" type="number" dense></v-text-field>
            </v-col>
        </v-row> -->
        <v-row no-gutters>
            <v-col v-if="flag(26)" cols="auto" class="ml-2 pt-1">
                <v-checkbox v-model="enabled[25]" hide-details class="shrinkmt-0 mt-0" @change="itemChecked(enabled[25],'message1_title')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(26)" cols="3">
                <v-text-field :disabled="!enabled[25]" label="見積書コメントタイトル１"  v-model="subject.message1_title" dense></v-text-field>
            </v-col>
            <v-col v-if="flag(29)" cols="auto" class="ml-6 pt-1">
                <v-checkbox v-model="enabled[28]" hide-details class="shrink mr-2 mt-0" @change="itemChecked(enabled[28],'message1')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(29)" cols="7">
                <v-text-field :disabled="!enabled[28]" label="見積書コメント１"  v-model="subject.message1" dense></v-text-field>
            </v-col>
        </v-row>
        <v-row no-gutters>
            <v-col v-if="flag(27)" cols="auto" class="ml-2 pt-1">
                <v-checkbox v-model="enabled[26]" hide-details class="shrinkmt-0 mt-0" @change="itemChecked(enabled[26],'message2_title')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(27)" cols="3">
                <v-text-field :disabled="!enabled[26]" label="見積書コメントタイトル２"  v-model="subject.message2_title" dense></v-text-field>
            </v-col>
            <v-col v-if="flag(30)" cols="auto" class="ml-6 pt-1">
                <v-checkbox v-model="enabled[29]" hide-details class="shrink mr-2 mt-0" @change="itemChecked(enabled[29],'message2')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(30)" cols="7">
                <v-text-field :disabled="!enabled[29]" label="見積書コメント２"  v-model="subject.message2" dense></v-text-field>
            </v-col>
        </v-row>
        <v-row no-gutters>
            <v-col v-if="flag(28)" cols="auto" class="ml-2 pt-1">
                <v-checkbox v-model="enabled[27]" hide-details class="shrinkmt-0 mt-0" @change="itemChecked(enabled[28],'message3_title')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(28)" cols="3">
                <v-text-field :disabled="!enabled[27]" label="見積書コメントタイトル３" v-model="subject.message3_title" dense></v-text-field>
            </v-col>
            <v-col v-if="flag(31)" cols="auto" class="ml-6 pt-1">
                <v-checkbox v-model="enabled[30]" hide-details class="shrink mr-2 mt-0" @change="itemChecked(enabled[30],'message3')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(31)" cols="7">
                <v-text-field :disabled="!enabled[30]" label="見積書コメント３"  v-model="subject.message3" dense></v-text-field>
            </v-col>
        </v-row>
        <v-row class="m-0 p-0">
            <v-col>
                一括編集する項目をチェックし、値を入力してください。
            </v-col>
            <v-col class="col-auto">
                <v-btn depressed class="text-white bg-primary" color="success" @click="enter()">
                    <v-icon left>mdi-content-save-outline</v-icon>登　録
                </v-btn>
            </v-col>
        </v-row>
	</v-container>
</template>
<script>
    import { mapState, mapGetters } from 'vuex'
	import CyberDate from "../CyberDate";
    import commFunctions from '../../functions/commFunctions';
    const { arrayToCsv } = commFunctions();
    export default {
		components: {
			CyberDate,
		},
        props: ["selectItems"],
        data: function () {
            return {
                // values:[],
                enabled: [
                    // 0,0,0,0,0,0,0,0,0,0,
                    // 0,0,0,0,0,0,0,0,0,0,
                    // 0,0,0,0,0,0,0,0,0,0,
                    // 0,0,0,0,0,0,0,0,0,0,
                    // 0,0,0,0,0,0,0,0,0,0,
                ],
                subject:[],
                accessClass:0,
                subjectClasses:[],
                taxClassItems:[],
                processNames:[],
                editItems:[],
                menu1:false,
                menu2:false,
                menu3:false,
            }
        },
        created() {
            //
            this.subjectClasses = this.$store.getters.getSubjectClasses;
            this.taxClassItems = this.$store.getters.getTaxClasses;
            this.processNames = this.$store.getters.getProcessNames;
            this.editItems = this.$store.getters.getEditItems;
// alert(JSON.stringify(this.editItems));
        },
        mounted() {
            // this.subject = Vue.util.extend({}, this.values[0]);
            // Object.keys(this.subject).forEach(key => { this.subject[key] = null });
        },
        updated() {
// alert(JSON.stringify('updated'));
        },
        watch: {
        },
        computed: {
            ...mapGetters([ 'getSubjectClasses' ]),
            ...mapGetters([ 'getTaxClasses' ]),
            ...mapGetters([ 'getProcessNames' ]),
            ...mapGetters([ 'getEditItems' ]),
            flag: function() {
                return function(no) {
                    return this.editItems[0].state.substr(no,1)>'0'
                };
            }
        },
        methods: {
            reset() {
                this.subject=[];
                this.enabled =  [
                    0,0,0,0,0,0,0,0,0,0,
                    0,0,0,0,0,0,0,0,0,0,
                    0,0,0,0,0,0,0,0,0,0,
                    0,0,0,0,0,0,0,0,0,0,
                    0,0,0,0,0,0,0,0,0,0,
                ];
            },
            itemChecked(isChecked,name) {
                if (!isChecked) {
                    this.subject[name] = null
                }
            },
            enter() {
                const selectItems = arrayToCsv(this.selectItems.map(value => value['id']));
                const values = Object.assign({},this.subject)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
                axios.post('/subject/group', { selectItems: selectItems, values: values  } ).then((res) => {
                    if (res.status==200) {
                        axios.post('/subject/read_in', {items:selectItems} ).then((res) => {
                            if (res.status==200) {
                                this.reset();
                                this.$emit('enter', res.data);
                            }
                        });
                    }
                });
            },
        },

    }
</script>

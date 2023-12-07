<template>

    <v-container fluid class="bg-peimary px-5">
        <v-row class="m-0 p-0">
            <v-col></v-col>
        </v-row>
        <v-row v-if="flag(1)" no-gutters>
            <v-col cols="auto" >
                <v-checkbox v-model="enabled[0]" hide-details class="shrink mt-0" @change="itemChecked(enabled[0],'name')"></v-checkbox>
            </v-col>
            <v-col cols="11" class="mr-6">
                <v-text-field :disabled="!enabled[0]" label="名称" id="name" v-model="values.name" value="null" dense></v-text-field>
            </v-col>
        </v-row>

        <v-row v-if="flag(7)" no-gutters>
            <v-col cols="auto">
                <v-checkbox v-model="enabled[1]" hide-details class="shrink mt-0" @change="itemChecked(enabled[1],'breakdown')"></v-checkbox>
            </v-col>
            <v-col cols="11" class="mr-6">
               <v-text-field :disabled="!enabled[1]" label="仕様" id="breakdown" v-model="values.breakdown" value="null" dense></v-text-field>
            </v-col>
        </v-row>
        <v-row no-gutters>
            <v-col v-if="flag(14)" cols="auto">
                <v-checkbox v-model="enabled[2]" hide-details class="shrink mt-0" @change="itemChecked(enabled[2],'start_date')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(14)" cols="3" class="mr-6">
                <v-text-field :disabled="!enabled[2]" label="着工日" id="start_date" v-model="values.start_date" value="null" dense></v-text-field>
            </v-col>
            <v-col v-if="flag(15)" cols="auto">
                <v-checkbox v-model="enabled[3]" hide-details class="shrink mt-0" @change="itemChecked(enabled[3],'end_date')"></v-checkbox>
            </v-col>
            <v-col  v-if="flag(15)" cols="3" class="mr-6">
                <v-text-field :disabled="!enabled[3]" label="完了日" id="end_date" v-model="values.end_date" value="null" dense></v-text-field>
            </v-col>
            <v-col v-if="flag(16)" cols="auto">
                <v-checkbox v-model="enabled[4]" hide-details class="shrink mt-0" @change="itemChecked(enabled[4],'isDay')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(16)" cols="3">
                <label class="mt-2">全日</label><input :disabled="!enabled[4]" type="checkbox" v-model="values.isDay" class="mx-2">
            </v-col>
        </v-row>
        <v-row no-gutters>
            <v-col v-if="flag(2)" cols="auto">
                <v-checkbox v-model="enabled[5]" hide-details class="shrink mt-0" @change="itemChecked(enabled[5],'product_class')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(2)" cols="3" class="mr-6">
				<v-select :disabled="!enabled[5]" label="区分" :items="productClassItems" item-text="name" item-value="no" v-model="values.product_class" dense></v-select>
           </v-col>
            <v-col v-if="flag(9)" cols="auto">
                <v-checkbox v-model="enabled[6]" hide-details class="shrink mt-0" @change="itemChecked(enabled[6],'percentage')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(9)" cols="3" class="mr-6">
				<v-text-field :disabled="!enabled[6]" label="進捗" v-model="values.percentage" dense suffix="%"></v-text-field>
           </v-col>
        </v-row>
        <v-row no-gutters>
            <v-col v-if="flag(12)" cols="auto">
                <v-checkbox v-model="enabled[7]" hide-details class="shrink mt-0" @change="itemChecked(enabled[7],'cost')"></v-checkbox>
            </v-col>
			<v-col v-if="flag(12)" cols="3" class="mr-6">
				<v-text-field :disabled="!enabled[7]" label="単価" v-model="values.cost" @change="calcTotal" dense suffix="円"></v-text-field>
			</v-col>
            <v-col v-if="flag(10)" cols="auto">
                <v-checkbox v-model="enabled[8]" hide-details class="shrink mt-0" @change="itemChecked(enabled[8],'volume')"></v-checkbox>
            </v-col>
			<v-col v-if="flag(10)" cols="2" class="mr-6">
				<v-text-field :disabled="!enabled[8]" label="数量" v-model="values.volume" dense></v-text-field>
			</v-col>
            <v-col v-if="flag(11)" cols="auto">
                <v-checkbox v-model="enabled[9]" hide-details class="shrink mt-0" @change="itemChecked(enabled[9],'unit')"></v-checkbox>
            </v-col>
			<v-col v-if="flag(11)" cols="1" class="mr-6">
				<v-select :disabled="!enabled[9]" label="単位" :items="unitNames" item-text="name" item-value="no" v-model="values.unit" dense></v-select>
			</v-col>
            <!-- <v-col v-if="flag(13)" cols="auto">
                <v-checkbox v-model="enabled[10]" hide-details class="shrink mt-0" @change="itemChecked(enabled[10],'amount')"></v-checkbox>
            </v-col> -->
			<!-- <v-col v-if="flag(13)" cols="3" class="mr-6">
				<v-text-field :disabled="!enabled[10]" label="金額" id="amount" v-model="values.amount" dense suffix="円"></v-text-field>
			</v-col> -->
        </v-row>
        <v-row v-if="flag(22)" no-gutters>
            <v-col cols="auto">
                <v-checkbox v-model="enabled[13]" hide-details class="shrink mt-0" @change="itemChecked(enabled[13],'memo')"></v-checkbox>
            </v-col>
            <v-col cols="11" class="mr-6">
               <v-text-field :disabled="!enabled[13]" label="備考" id="breakdown" v-model="values.memo" value="null" dense></v-text-field>
            </v-col>
        </v-row>

        <v-row class="m-0 p-0">
            <v-col>
                一括編集する項目をチェックし、値を入力してください。
            </v-col>
            <v-col class="col-auto">
                <v-btn depressed class="text-white bg-primary" @click="enter()">
                    <v-icon left>mdi-check-underline</v-icon>確　定
                </v-btn>
            </v-col>
        </v-row>
	</v-container>
</template>
<script>
    import { mapState, mapGetters } from 'vuex'
	import CyberDate from "../CyberDate";
    import commFunctions from '../../functions/commFunctions';
    export default {
		components: {
			CyberDate,
		},
        data: function () {
            return {
                values:[],
                enabled: [],
                productClassItems:[],
                unitNames:[],
                editItems:[],
            }
        },
        created() {
            this.unitNames = this.$store.getters.getUnitNames;
            this.productClassItems = this.$store.getters.getProductClasses;
            this.editItems = this.$store.getters.getEditItems;
        },
        computed: {
            ...mapGetters([ 'getUnitNames' ]),
            ...mapGetters([ 'getProductClasses' ]),
            ...mapGetters([ 'getEditItems' ]),
            flag: function() {
                return function(no) {
                    return this.editItems[1].state.substr(no,1)>'0'
                };
            }
        },
        methods: {
            reset() {
                this.values=[];
                this.enabled =  [
                    0,0,0,0,0,0,0,0,0,0,
                    0,0,0,0,0,0,0,0,0,0,
                    0,0,0,0,0,0,0,0,0,0,
                    0,0,0,0,0,0,0,0,0,0,
                    0,0,0,0,0,0,0,0,0,0,
                ];
            },
            itemChecked(isChecked,name) {
                this.values[name] = null
            },
            enter() {
                const values = Object.assign({},this.values)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
                this.$emit('enter', values);
                this.reset();
            },
        },
    }
</script>

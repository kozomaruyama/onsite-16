<template>

    <v-container fluid class="bg-peimary px-5">
        <v-row class="m-0 p-0">
            <v-col></v-col>
        </v-row>

        <v-row v-if="status" no-gutters class="">
            <v-col v-if="flag(1)" cols="auto" class="ml-2">
                <v-checkbox v-model="enabled[0]" hide-details class="shrink mt-0" @change="itemChecked(enabled[0],'yield_ratio')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(1)" cols="12" md="3">
                <v-text-field :disabled="!enabled[0]" label="支払率" v-model.lazy="detail.yield_ratio" @focus="active='ratio'" type="number" dense></v-text-field>
            </v-col>
            <!-- <v-col v-if="flag(2)" cols="auto" class="ml-6">
                <v-checkbox v-model="enabled[1]" hide-details class="shrink mt-0" @change="itemChecked(enabled[1],'yield_amount')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(2)" cols="12" md="3">
                <v-text-field :disabled="!enabled[1]" label="支払額" v-model.lazy="detail.yield_amount" @focus="active='amount'" type="number" dense></v-text-field>
            </v-col> -->
        </v-row>
        <v-row no-gutters class="">
            <v-col v-if="flag(3)" cols="auto" class="ml-2">
                <v-checkbox v-model="enabled[2]" hide-details class="shrink mt-0" @change="itemChecked(enabled[2],'memo')"></v-checkbox>
            </v-col>
            <v-col v-if="flag(3)" cols="11">
                <v-text-field :disabled="!enabled[2]" label="備考" v-model.lazy="detail.memo" dense></v-text-field>
            </v-col>
        </v-row>
        <v-row class="m-0 p-0">
            <v-col>
                一括編集する項目をチェックし、値を入力してください。
            </v-col>
            <v-col class="col-auto">
                <v-btn depressed class="text-white bg-primary" color="primary" @click="enter()">
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
    const { arrayToCsv } = commFunctions();
    export default {
		components: {
			CyberDate,
		},
        props: ['status'],
        data: function () {
            return {
                enabled: [],
                detail:[],
                editItems:[],
            }
        },
        created() {
            this.editItems = this.$store.getters.getEditItems;
// alert(JSON.stringfiy(this.editItems))
        },
        computed: {
            ...mapGetters([ 'getEditItems' ]),
            flag: function() {
                return function(no) {
                    return this.editItems[0].state.substr(no,1)>'0'
                };
            }
        },
        methods: {
            // reset() {
            //     this.detail=[];
            //     this.enabled =  [
            //         0,0,0,0,0,0,0,0,0,0,
            //         0,0,0,0,0,0,0,0,0,0,
            //         0,0,0,0,0,0,0,0,0,0,
            //         0,0,0,0,0,0,0,0,0,0,
            //         0,0,0,0,0,0,0,0,0,0,
            //     ];
            // },
            itemChecked(isChecked,name) {
                if (isChecked) {
                    this.detail[name] = null
                } else {
                    delete this.detail[name];
                }
            },
            enter() {
// alert(JSON.stringfiy(this.detail))
                const values = Object.assign({},this.detail)
                this.$emit('enter',values);
            },
        },
        mounted() {
            this.detail=[];
        }
    }
</script>

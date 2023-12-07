<template>
    <div class="pt-6">
        <v-row class="m-0 p-0">
            <!-- <v-col></v-col> -->
            <v-col class="">
                <v-btn depressed class="text-gray bg-white" @click="lastMonth()">
                    <v-icon left>mdi-chevron-double-left</v-icon>前月
                </v-btn>
            </v-col>
            <v-col></v-col>
            <v-col class="">
                <v-btn depressed class="text-gray bg-white" @click="currentMonth()">
                    当月
                </v-btn>
            </v-col>
            <v-col></v-col>
            <v-col class="">
                <v-btn depressed class="text-gray bg-white" @click="nextMonth()">
                    翌月<v-icon right>mdi-chevron-double-right</v-icon>
                </v-btn>
            </v-col>
            <!-- <v-col></v-col> -->
        </v-row>         
		<v-row no-gutters class="">
            <v-col></v-col>
			<v-col cols="5" class="px-2">
                <v-menu
                    ref="dateMenu1"
                    v-model="dateMenu1"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                    <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="start_date"
                        label= "期間"
                        prepend-icon="mdi-calendar"
                        v-bind="attrs"
                        v-on="on"
                        dense
                    ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="start_date"
                        no-title
                        locale="ja"
                        @input="dateMenu1 = false"
                    ></v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="auto" class="mt-2">　～　</v-col>
			<v-col cols="5"  class="px-2">
                <v-menu
                    ref="dateMenu2"
                    v-model="dateMenu2"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                    <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="end_date"
                        label= ""
                        prepend-icon="mdi-calendar"
                        v-bind="attrs"
                        v-on="on"
                        dense
                    ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="end_date"
                        no-title
                        locale="ja"
                        @input="dateMenu2 = false"
                    ></v-date-picker>
                </v-menu>
			</v-col>
            <v-col></v-col>
		</v-row>    
        <v-row class="m-0 p-0">
            <v-col></v-col>
            <v-col class="col-auto">
                <v-btn depressed class="text-white bg-primary" @click="print()">
                    <v-icon left>mdi-printer</v-icon>印刷
                </v-btn>
            </v-col>
            <v-col></v-col>
        </v-row>        
    </div>
</template>
<script>
    import moment from 'moment';    
	import ManageSheet from "../print/ManageSheet";
    export default {
		components: {
			ManageSheet,
		},    
        props: ["today"],    
		data: function () {
			return {
                listName:"",
                start:new Date(),
                start_date:null,
                end_date:null,
                dateMenu1:false,
                dateMenu2:false,
			}
		}, 
        methods: {
            print() {            
                // const items = {'year':moment(this.value).year(),'month':moment(this.value).month()};                
                const items = {'start':this.start_date,'end':this.end_date};                
                localStorage.setItem('selectItems', JSON.stringify(items));                
				window.open("/print", '_blank');
            },
            setDate() {
                this.end_date = moment(this.start).endOf('months').format('YYYY-MM-DD');
                this.start_date = moment(this.start).format('YYYY-MM-DD');

            },
            currentMonth() {
                this.start = moment(this.today).endOf('months').startOf('months');
                this.setDate();  
            },
            nextMonth() {
                this.start = moment(this.start).add(1, 'M');
                this.setDate();            
            } ,
            lastMonth() {
                this.start = moment(this.start).add(-1, 'M');
                this.setDate();   
            }        
        },
        mounted() {
            this.currentMonth();
        },
    }
</script>
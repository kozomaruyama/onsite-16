<template>
    <div>
        <v-container class="container-fluid mt-2">
            <v-row v-if="role<10" no-gutters class="">
                <v-col v-if="value.class<30" cols="12" md="4" class="px-2">
                    <v-select :items="subContracts" item-text="name" item-value="id" label="下請" v-model="value.subcontract_id" dense  @change="getPeople()"></v-select>
                </v-col>        
                <!-- <v-col cols="12" md="2" class="px-2">
                    <v-select :items="people" item-text="name" item-value="id" label="担当" v-model="values.person_id" dense :disabled="disabled_sw"></v-select>
                </v-col> -->
                <v-col v-else cols="12"  class="px-2 mt-2">
                    <v-text-field label="タイトル" v-model="value.name" dense ></v-text-field>
                </v-col>
            </v-row>
            <v-row no-gutters class="">
                <v-col cols="auto" class="px-2" >
                    <v-menu ref="dateMenu1" v-model="dateMenu1" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field v-model="value.start_date" prepend-icon="mdi-calendar" v-bind="attrs" v-on="on" style="width:140px"/>
                        </template>
                        <v-date-picker v-model="value.start_date" no-title locale="ja" @input="dateMenu1 = false" />
                    </v-menu>
                </v-col>

                <v-col v-if="value.class==30" cols="2">
                    <v-menu ref="menuStartTime" v-model="modelStartTime" :close-on-content-click="false" :nudge-right="40" :return-value.sync="start_time" transition="scale-transition" offset-y max-width="290px" min-width="290px">
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field v-model="start_time" prepend-icon="mdi-clock-time-four-outline" readonly v-bind="attrs" v-on="on" />
                        </template>
                        <v-time-picker v-if="modelStartTime" v-model="start_time" full-width @click:minute="$refs.menuStartTime.save(start_time)" />
                    </v-menu>
                </v-col>

                <v-col v-if="value.class<30" cols="auto" class="mt-7 text-center">～</v-col>
                <v-col v-if="value.class<30" cols="auto" class="px-2">
                    <v-menu ref="dateMenu2" v-model="dateMenu2" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field v-model="value.end_date" label= "" prepend-icon="mdi-calendar" v-bind="attrs" v-on="on" style="width:140px" />
                        </template>
                        <v-date-picker v-model="value.end_date" no-title locale="ja" @input="dateMenu2 = false" />
                    </v-menu>
                </v-col>
                <v-col v-if="value.class<30" cols="auto" class="mt-7 text-center">
                    ({{value.schedule_class}})
                </v-col>

                <v-col v-else cols="auto" class="mt-4 text-center">
                    <v-btn depressed small fab @click="alarmSwitch">
                        <v-icon v-if="Math.abs(value.status)==5" color="grey darken-2">mdi-bell-off-outline</v-icon>
                        <v-icon v-else color="grey darken-2">mdi-bell-outline</v-icon>
                    </v-btn>
                </v-col>
                <v-col v-if="value.class>=30" cols="auto" class="pl-2 mx-2">
                    <v-text-field label="分前" type="number" min="0" max="1440" v-model="value.alarm_interval" ></v-text-field>
                </v-col>
                <v-col v-if="value.class>=30" cols="auto" class="pl-2 mt-4 mx-2">
					<v-sheet
						class="mx-auto rounded-circle"
						height="30"
						width="30"
						:color="value.color"
						@click="$refs.ColorSelecter.open()"
					/>
                </v-col>
                
                <!-- <v-col cols="12" class="px-2">
                    <v-text-field class="" v-model="value.schedule_class" readonly hint="変更不可"/>            
                </v-col>             -->
            </v-row> 
            <v-row v-if="value.class<30" no-gutters class="">            
                <v-col cols="3" class="px-4 mt-4">
                    <v-text-field v-if="value.v_schedule_details.length>0 " label="進捗" type="number"  v-model="percentage" suffix="％" dense readonly></v-text-field>
                    <v-text-field v-else label="進捗" type="number" :max=100 min=0 :rules="[rules.percentage]" v-model="value.percentage" suffix="％" dense @change="inputCheck1(value.percentage)"></v-text-field>
                </v-col>
                <v-col cols="auto" class="px-2 mt-6">
                    <div>(出来高:{{ pay_ratio }}％) </div>
                </v-col> 
                <v-col v-if="role==1" cols="auto" class="mt-4 bg-peimary">
                    <v-btn v-if="value.percentage==100" class="" text icon color="blue lighten-2">
                        <v-icon>mdi-thumb-up</v-icon>
                    </v-btn>
                    <v-btn v-else class="" text icon color="red lighten-2" @click="completed()">
                        <v-icon>mdi-thumb-down</v-icon>
                    </v-btn>
                </v-col>


                <!-- <v-col cols="4" class="px-2">
                    <v-text-field class="d-none" v-model="value.id"></v-text-field>     
                    <v-checkbox v-if="(value.status<2)" v-model="value.status" label="完了" @change="status_checked"/>    
                    <v-checkbox v-else v-model="value.status" label="完了" disabled @change="status_checked"/>    
                </v-col> 
                            -->
            </v-row>
            <v-row no-gutters class="">  
                <v-col cols="12" >
                    <!-- <v-text-field label="備考" v-model="value.memo" dense ></v-text-field> -->
                    <v-textarea outlined v-model="value.memo" />
                </v-col>
            </v-row>
            <v-row v-if="value.v_schedule_details!=null" no-gutters class="">
                <v-simple-table dense>
                    <template v-slot:default>
                        <!-- <thead>
                            <tr>
                                <th class="text-left w-75">
                                    案件名
                                </th>
                                <th class="text-left w-25">
                                    出来高
                                </th>
                            </tr>
                        </thead> -->
                        <tbody>
                            <tr v-for="item in value.v_schedule_details" :key="item.id">
                                <td  class="text-left w-40">{{ item.name }}</td>
                                <td  class="text-left w-35">{{ item.breakdown }}</td>
                                <td  v-if="item.isLabel==0" class="w-25">
                                    <v-text-field v-model="item.percentage" suffix="％" type="number" min="0" :max=100 :rules="[rules.percentage]" dense @change="inputCheck1(item.percentage)"></v-text-field>
                                </td>
                                    <!-- <td><v-slider hint="Im a hint" v-model="item.percentage" min="0" max="100" ></v-slider></td> -->
                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </v-row>

            <v-row class="m-0 p-0">          
                <v-btn v-if="(role<10 && value.status<9)" depressed small fab @click="del()">
                    <v-icon color="grey">mdi-delete-outline</v-icon>
                </v-btn>              
                <v-col></v-col>
                <v-col class="col-auto">
                    <!-- <v-btn v-if="(value.status<2)" depressed class="text-white bg-primary" color="success" @click="enter()"> -->
                    <v-btn  depressed class="text-white bg-primary" color="success" :disabled="enter_disabled" @click="enter()">
                        <v-icon left>mdi-content-save-outline</v-icon>登　録
                    </v-btn>
                </v-col>
            </v-row>               
        </v-container>
		<MsgBox ref="MsgBox" @enter="msgBox_enter">
			<template v-slot:caption></template>
			<template v-slot:main>
				<v-container>
					<v-row align-content="center" >
						<v-col align="center">
							<div class="text-h6">削除してよろしいですか？</div>
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>
		<MsgBox ref="MsgBox2" @enter="enter_delete">
			<template v-slot:caption></template>
			<template v-slot:main>
				<v-container>
					<v-row align-content="center" >
						<v-col align="center">
							<div class="text-h6">案件情報も削除されます。処理を続行しますか？</div>
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>
        <MsgBox ref="ColorSelecter">
			<template v-slot:caption>カラーパレット</template>
			<template v-slot:main>
				<v-container >
					<v-row align-content="center" class="p-2">
						<v-col v-for="(color, index) in colors" :key="index"  class="col-auto px-2 mt-2 text-center">
							<!-- <span v-for="(color, index) in colors" :key="index" class="p-2"> -->
								<v-avatar :color="color.name" size="30" @click="color_selected(color.name)" class=""/>
							<!-- </span> -->
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>
    </div>
</template>
<script>

    import MsgBox from "../msgbox";
    import { mapState, mapGetters } from 'vuex'
    import { json } from "body-parser";
    export default {
        components: {
			MsgBox,
		},
        props: ["value","details","role","status"],
        data: function () {
            return {
                dateMenu1:false,
                dateMenu2:false,
                enter_disabled:false,
                subContracts: [],
                people: [],
                rules: {
                    // percentage: value => {
                    //     if (value<0 || value>this.value.pay_ratio) {
                    //         return false || `０～` + this.value.pay_ratio + `の間で設定してください`;
                    //     } else {
                    //         return true
                    //     }
                    // },
                    percentage: value => {
                        if (value<0 || value>100) {
                            return false || `０～１００の間で設定してください`;
                        } else {
                            return true
                        }
                    },
                },
                start_time:"",
                modelStartTime: false,
                colors:[],  
            }
        },
        methods:{
            alarmSwitch() {
// alert(JSON.stringify(this.value))
                if (Math.abs(this.value.status)==1) {
                    this.value.status = this.value.status * 5
                } else {
                    this.value.status = this.value.status / Math.abs(this.value.status)
                }
// alert(JSON.stringify(this.value))
            },
            inputCheck1(val) {
                if (val<0 || val>100) {
                    this.enter_disabled = true
                } else {
                    this.enter_disabled = false
                }
                // if (val<0 || val>this.value.pay_ratio) {
                //     this.enter_disabled = true
                // } else {
                //     this.enter_disabled = false
                // }
            },
            // inputCheck() {
            //     this.enter_disabled = false
            //     this.value.v_schedule_details.forEach(detail => {
            //         if (detail.percentage<0 || detail.percentage>this.value.pay_ratio) {
            //             this.enter_disabled = true
            //         }
            //     })
            // },
            getSubject() {                  
                axios.get('/subject/list').then((res) => {                    
                    this.subjects = res.data;
                });
            },
            enter() {
                let schedule = Object.assign({}, this.value)   
                schedule.pay_ratio=this.pay_ratio;
                schedule.start_time=schedule.start_date + ' ' + this.start_time;
                axios.put('/schedule/' + schedule.id, schedule).then((res) => {
                    if (res.data.status) {
                        this.$emit('enter',{ value:res.data.value });
                    } else {
                        this.$emit('error', res.data.value );
                    }
                });                
            },
            del() {
                this.$refs.MsgBox.open('del')
            },
            status_checked(e){
                if (this.value.v_schedule_details.length==0) {
                    if (e==true) {
                        this.value.percentage = 100;
                    }
                }  else {
                    this.value.v_schedule_details.forEach(detail => {
                        detail.percentage = 100
                    })  
                }
                // if (this.value.v_schedule_details.length==0) {
                //     if (e==true) {
                //         this.value.percentage = this.value.pay_ratio;
                //     }
                // }  else {
                //     this.value.v_schedule_details.forEach(detail => {
                //         detail.percentage = detail.pay_ratio
                //     })  
                // }
            },
			msgBox_enter(para) {
// alert(JSON.stringify(this.value))
				if (para=='del') {
                    axios.get('/schedule/subjectId/' + this.value.subject_id).then((res) => {
                        if (res.data.length==1 && this.value.class<30) {
                            this.$refs.MsgBox2.open('yn')
                        } else {
                            this.enter_delete()
                        }
                    }) 
                }
			},
            enter_delete() {
// alert(JSON.stringify(this.value.id))
                axios.delete('/schedule/' + this.value.id).then((res) => {
                    if (res.data.status) {
                        this.$emit('remove',this.value.id);
                    } else {
// alert(JSON.stringify(res.data))
                        if (res.data.value.errorInfo[0]=="23000") {
                            this.$emit('error', "他の情報で参照されているため、削除できません。" );
                        } else {
                            this.$emit('error', "削除できませんでした。(" + res.data.value.errorInfo[0] + ")" );
                        }
                    }
                })
            },
            getSubContracts() {
                axios.get('/clients/subcontract').then((res) => {
                    this.subContracts = res.data;
                });
            },
            getPeople() {
                axios.get('/people/byclient/' + this.value.subcontract_id).then((res) => {
                    this.people = res.data;
                });                
            },
            completed() {
                this.value.percentage=100
            },
            color_selected(e) {
				this.value.color = e;
				this.$refs.ColorSelecter.close()
// alert(JSON.stringify(this.value))
			},
        },
        watch: {
            'value.start_date': function(newVal, oldVal) {
                if (newVal==="") {
                    this.value.start_date = oldVal
                }else if (newVal > this.value.end_date) {
                    this.value.end_date = newVal
                }
            },
            'value.end_date': function(newVal, oldVal) {
                if (newVal==="") {
                    this.value.end_date = oldVal
                }else if (newVal < this.value.start_date) {
                    this.value.start_date = newVal
                }
            }, 
        },       
        computed: {
			...mapGetters([ 'getSubjectClasses' ]),            
            ...mapGetters([ 'getScheduleClasses' ]),
            percentage: function() {
                if (this.value.v_schedule_details.length>0 && this.value.expenses>0) {
                    let total = 0
                    this.value.v_schedule_details.forEach(detail => {
                        if (detail.isLabel==0) {
                            total += (Number(detail.amount) * Number(detail.percentage)) / 100
                        }
                    })
                    this.value.percentage = Math.round((total / Number(this.value.expenses))*100000)/1000
                }
                return this.value.percentage
            },
            pay_ratio: function() {
                // let percentagr  = 0
                // if (this.value.v_schedule_details.length==0) {
                //     percentagr = this.value.percentage
                // } else {
                //     percentagr = this.percentage
                // }
                // if (this.value.class==this.value.pay_status) {
                //     return (this.value.pay_ratio2 * percentagr) / 100;
                // } else {
                //     return ((100 - this.value.pay_ratio2) * percentagr) / 100;
                // }
                return Math.round(this.value.master_pay_ratio * this.value.percentage * 10)/1000;
            },
        },  
        created () {
            this.colors = this.$store.getters.getColors;   
        },   
        mounted() { 
// alert(JSON.stringify(this.value))
            this.getSubContracts();
            this.getPeople();
            this.start_time = this.value.start_time.toLocaleString().substring(11,16)
        }           
    }
</script>
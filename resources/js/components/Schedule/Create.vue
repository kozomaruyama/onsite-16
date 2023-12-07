<template>
    <div>
        <v-container class="container-fluid mt-2">
            <v-text-field class="d-none" id="index" v-model="value.id" />           
            <v-row v-if="value.class<30" no-gutters class="">
                <v-col cols="11"  class="px-2 mt-2">
                    <v-select class="m-0 p-0"  :items="subjects" item-text="name" item-value="id" label="案件" return-object @change="select_subject" />
                </v-col>
                <v-col v-if="role<10" cols="auto"  class="px-2 mt-4">
                    <v-icon size="20" class="mr-2" @click.stop="createSubject(value)">
                        mdi-pencil-plus
                    </v-icon>                
                </v-col>
            </v-row>
            <v-row v-else no-gutters class="">
                <v-col cols="12"  class="px-2 mt-2">
                    <v-text-field label="タイトル" v-model="value.name" dense ></v-text-field>
                </v-col>
            </v-row>
            <v-row no-gutters class="">
                <v-col cols="auto" class="px-2">
                    <v-menu ref="dateMenu1" v-model="dateMenu1" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field v-model="value.start_date" prepend-icon="mdi-calendar" v-bind="attrs" v-on="on"  style="width:140px"/>
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
                            <v-text-field v-model="value.end_date" label= "" prepend-icon="mdi-calendar" v-bind="attrs" v-on="on" style="width:140px"/>
                        </template>
                        <v-date-picker v-model="value.end_date" no-title locale="ja" @input="dateMenu2 = false" />
                    </v-menu>
                </v-col>
                <v-col v-if="value.class<30" cols="auto" class="px-2 mt-4">
                    <v-select class="m-0 p-0" :items="classItems" item-text="name" item-value="no" label="区分" v-model="value.class" style="width:50px" readonly />
                </v-col>     
                <v-col v-else cols="auto" class="text-center mt-3 mx-2">
                    <v-btn depressed small fab @click="alarmSwitch">
                        <v-icon v-if="status==5" color="grey darken-2">mdi-bell-off-outline</v-icon>
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
            </v-row> 
            <v-row no-gutters class="">  
                <v-col cols="12"  class="px-2 mt-2">
                    <!-- <v-text-field label="備考" id="name" v-model="value.memo" dense ></v-text-field> -->
                    <v-textarea outlined v-model="value.memo" />
                </v-col>
            </v-row>
            <v-row class="m-0 p-0">
                <v-col class="col-auto">
                    <v-checkbox v-model="value.isSchedule" label="プライベートスケジュール" @change="isSchedule_checked" />
                </v-col>
                <v-col />
                <v-col class="col-auto">
                    <v-btn depressed class="text-white bg-primary" color="success" @click="enter()">
                        <v-icon left>mdi-content-save-outline</v-icon>登　録
                    </v-btn>
                </v-col>
            </v-row>               
        </v-container>
        <!-- 案件編集ダイアログ -->
        <CyberDialog ref="SubjectDialog">
            <template v-slot:caption>案件追加</template>
            <template v-slot:main>
                <EditSubject
                    :key="create_key"
                    :values="subject"
                    :smode="mode"
                    :role="role"
                    :status="1"
                    @click_task="click_task"
                    @enter="enter_SubjectDialog"
                    @remove="remove_subject"
                    @add_task="add_task"
                    @error="error" 
                />
            </template>
        </CyberDialog>
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
    import CyberDialog from "../CyberDialog";
    import EditSubject from "../subject/EditSubject";
    import MsgBox from "../msgbox";
    import { mapState, mapGetters } from 'vuex'
    export default {
        props: ["value","role"],
        components: {
            CyberDialog,
            EditSubject,
            MsgBox,
        },        
        data: function () {
            return {
                subjects:[],
                subject:[],
                classItems:[],
                dateMenu1:false,
                dateMenu2:false,  
                create_key: true,  
                mode:"add", 
                start_time:"",
                modelStartTime: false,   
                status:1,      
                colors:[],  
            }
        },
        methods:{
            init() {  
                this.getSubject();
                const now = new Date()
                const time = ( '00' + Math.floor(now.getMinutes() / 5) * 5 ).slice( -2 )
                this.start_time =  now.getHours() + ":" + time

// alert(JSON.stringify(this.start_time))
            },
            alarmSwitch() {
                if (Math.abs(this.status)==1) {
                    this.status = this.status * 5
                } else {
                    this.status = this.status / Math.abs(this.status)
                }
            },
            getSubject() {
                axios.get('/subject/list').then((res) => {
// alert(JSON.stringify(res.data))
                    let array = []
                    res.data.forEach(subject => {
                        if (subject.schedules.length < 2 && subject.class < 10) {
                            array.push(subject)
                        } else if (subject.schedules.length == 0 && subject.class > 10) {
                            array.push(subject)
                        }
                    });
                    this.subjects = array;
                });
            },
            select_subject(val) {
// alert(JSON.stringify(val))
                if (val.schedules.length==0) {
                    this.value.class = 10
                } else {
                    if (val.schedules[0].class==10) {
                        this.value.class = 20
                    } else {
                        this.value.class = 10
                    }
                }
                this.value.subject_id = val.id 
                this.subject = val
            },
            
            enter() {
                const schedule = Object.assign({}, this.value) 
                schedule.start_time = schedule.start_date + ' ' + this.start_time;
                schedule.status=this.status;
                axios.post('/schedule/store', schedule).then((res) => {
                    if (res.data.status) {
                        this.$emit('enter',{ value:res.data.value,class:30 });
                    } else {
                        this.$emit('error', res.data.value );
                    }
                });                
            },

            createSubject(e) {
// alert(JSON.stringify(e)) 
                this.$emit('close');
                this.subject = {
                    'id':-1,
                    'class':1,
                    // 'client_id':e.client_id,
                    'start_date':e.start_date,
                    'end_date':e.start_date,
                    'expenses':0,
                    'tax_state':2,
                    'discount':0,
                    'amount_subcontract':0,
                    'process':10,
                    'v_tasks':[],
                }
                // this.mode ="add";
                // this.editSubject_caption="案件追加"
                // this.create_key != this.create_key
                this.$refs.SubjectDialog.open({
                    isFullscreen:true
                });
            },      
            click_task(task) {
// alert(JSON.stringify(task));
                // this.task = task;
                // this.$refs.TaskDialog.open({
                //     isFullscreen:false
                // });
            },
            isSchedule_checked(e) {
// alert(JSON.stringify(e));
                if (e) {
                    this.value.subject_id = 0
                    this.value.class = 30
                } else {
                    this.value.class = 10
                }
                
            },
            color_selected(e) {
				this.value.color = e;
				this.$refs.ColorSelecter.close()
// alert(JSON.stringify(this.value))
			},
            enter_SubjectDialog(e) {
// alert(JSON.stringify(e));
                this.$refs.SubjectDialog.close();
                this.$emit('enter',{ class:10 });
                // if (e.mode=="add") {
                //     if (e.value.process<50)  {
                //         this.tableItems.unshift(e.value);
                //     }
                // } else {
                //     if (e.value.process<50)  {
                //         this.tableItems.splice(this.selectTableRowNo  , 1, e.value);
                //     } else {
                //         this.tableItems.splice(this.selectTableRowNo  , 1);
                //     }
                // }
                // this.$refs.SubjectDialog.close();
            },
            remove_subject() {
                // const key = this.tableItems[this.selectTableRowNo]['start_date'].substr( 0, 7 );
                // this.tableItems.splice(this.selectTableRowNo  , 1);
                // if (this.tableItems,length == 0) {
                //     const sideMenu_item = this.sideMenu_items.find((item) => item.key === this.category_key);
                //     let chileItems = sideMenu_item.items
                //     const id = chileItems.findIndex((item) => item.key === key)
                //     chileItems.splice(id,1)
                // }
                // this.$refs.SubjectDialog.close();
            },

            add_task() {},
            error(e) {
                this.err_message = e
                this.$refs.MsgBox.open('ok')
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
            }
        },
        computed: {
            ...mapGetters([ 'getSubjectClasses' ]),            
            ...mapGetters([ 'getScheduleClasses' ]),
        },
        created () {
            this.classItems = this.$store.getters.getScheduleClasses;         
            this.colors = this.$store.getters.getColors;   
        },               
        mounted() {
// alert(JSON.stringify(this.value))   
            this.init();
        },
    }
</script>
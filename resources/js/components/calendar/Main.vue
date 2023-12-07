<template>
  <div>
    <!-- メイン -->
    <CyberMain
      :isNaviVisible="true"
    >
      <!-- サイドメニュー -->
      <template v-slot:navi>
        <div class="mt-7 border-top">
          <v-simple-table
            fixed-header
            :height="display_height"
          >
            <template v-slot:default>
              <!-- <thead>
                <tr>
                  <th class="text-left" width="50px">日</th>
                  <th class="text-left">案件</th>
                </tr>
              </thead> -->
              <tbody>
                <tr v-for="schedule in schedules" :key="schedule.id" @click="click_sideListItem(schedule)">
                  <td>{{ schedule.day }}日</td>
                  <td>{{ schedule.name }}</td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </div>
      </template>
      <!-- メイン -->
      <template v-slot:main>
        <v-row class="fill-height">
          <v-col>
            <v-sheet height="64">
              <v-toolbar flat>
                <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">Today</v-btn>
                <v-btn fab text small color="grey darken-2" @click="prev">
                  <v-icon small>mdi-chevron-left</v-icon>
                </v-btn>
                <v-btn fab text small color="grey darken-2" @click="next">
                  <v-icon small>mdi-chevron-right</v-icon>
                </v-btn>
                <v-toolbar-title v-if="$refs.calendar" class="" >
                  {{ $refs.calendar.title }}
                </v-toolbar-title>
                <v-col v-if="role<10" cols="12" md="3" class="">
                  <v-select class="pt-6 pl-4" @change="client_selected" :items="clients" item-text="nickname" item-value="id" label="元請" v-model="client_id" clearable></v-select>
                </v-col>
                <v-col v-if="role<10" cols="12" md="3" class="">
                  <v-select class="pt-6 pl-4" @change="subcontract_selected" :items="subcontracts" item-text="nickname" item-value="id" label="下請" v-model="subcontract_id" clearable></v-select>
                </v-col>
                <!-- <v-col cols="12" md="2" class="">
                  <v-select class="pt-6 pl-4" @change="process_selected" :items="processes" item-text="name" item-value="no" label="状態" v-model="process" clearable multiple></v-select>
                </v-col> -->
                <v-spacer />
                <v-menu bottom right>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn outlined color="grey darken-2" v-bind="attrs" v-on="on">
                      <span>{{ typeToLabel[type] }}</span>
                      <v-icon right>mdi-menu-down</v-icon>
                    </v-btn>
                  </template>
                  <v-list>
                    <v-list-item @click="type = 'day'">
                      <v-list-item-title>日</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="type = 'week'">
                      <v-list-item-title>週</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="type = 'month'">
                      <v-list-item-title>月</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </v-toolbar>
            </v-sheet>
            <v-sheet :height="display_height">
              <!-- <CyberSplitpanes>
                <template v-slot:main> -->
                  <div class="w-100 h-100">
                    <v-calendar
                      ref="calendar"
                      v-model="focus"
                      color="primary"
                      locale="ja-jp"
                      :type="type"
                      :events="schedules"
                      :event-color="getEventColor"
                      :eventType="1"
                      :event-ripple="false"          
                      @click:event="showSchedule"
                      @click:more="viewDay"
                      @click:date="createSchedule"
                      @change="calendar_change"
                    >
                    </v-calendar>
                  </div>
                <!-- </template> -->
                <!-- <template v-slot:sub>
                  <div class="w-100 h-100">
                    <CyberDataTable
                      ref="CyberDataTable"
                      :filterItems="filterItems"
                      :commandItems="commandItems"
                      :headers="headers"
                      :items="tableItems"
                      :searchText="searchText"
                      :show_select="false"
                      :visible_add="false"
                      :visible_copy="false"
                      :visible_group="false"
                      :visible_print="false"
                      :visible_print2="false"
                    >
                      <template v-slot:title>案件一覧</template>
                    </CyberDataTable>
                  </div>
                </template> -->
              <!-- </CyberSplitpanes> -->
              <v-menu v-model="selectedOpen" :close-on-content-click="false" :activator="selectedElement" offset-x>
                <v-card color="grey lighten-4" max-width="600px" min-width="350px" flat>
                  <v-toolbar color="grey lighten-4" flat dense>
                    <v-col v-if="isAddSchedule">追加</v-col><v-col v-else>{{schedule.name}}</v-col>
                    <v-col v-if="isAddSchedule==false" class="m-0 p-0 col-auto">
                      <v-btn icon @click="showSubject(value)">
                        <v-icon>mdi-note-text-outline</v-icon>
                      </v-btn>
                    </v-col>
                    <v-col col-auto class="m-0 p-0 col-auto">
                      <!-- <v-btn icon @click="selectedOpen = false"> -->
                      <v-btn icon @click="close">
                        <v-icon>mdi-close</v-icon>
                      </v-btn>
                    </v-col>
                  </v-toolbar>
                  <div v-if="isAddSchedule">
                    <CreateSchedule 
                      :key="create_key" 
                      :value="schedule" 
                      @enter="enter_createSchedule" 
                      @close="close_createSchedule"
                    />
                  </div>
                  <div v-else>
                    <EditSchedule 
                      :key="edit_key" 
                      :value="schedule"
                      :role="role" 
                      @enter="enter_schedule" 
                      @remove="remove_schedule" 
                      @error="error" />
                  </div>
                </v-card>
              </v-menu>
            </v-sheet>
          </v-col>
        </v-row>
      </template>
    </CyberMain> 
    <CyberDialog ref="SubjectDialog">
      <template v-slot:caption>{{  caption_text   }}</template>
      <template v-slot:main>
        <EditSubject 
          :key="subject.id" 
          :values="subject" 
          :schedule="schedule" 
          :status="2"
          @enter="enter_SubjectDialog" 
        />
      </template>
    </CyberDialog>
		<EditBox ref="EditBox" >
			<!-- <template v-slot:caption>{{ editSubject_caption }}</template> -->
			<template v-slot:main>
        <v-card color="grey lighten-4" max-width="800px" min-width="350px" flat>
          <v-toolbar color="grey lighten-4" flat dense>
            <v-col v-if="isAddSchedule">追加</v-col>
            <v-col v-else>
              <span v-if="schedule.class<30">{{schedule.name}}</span>
              <span v-else>プライベートスケジュール</span>
            </v-col>
            <v-col v-if="isAddSchedule==false && role<10 && schedule.class<30" class="m-0 p-0 col-auto">
              <v-btn icon @click="showSubject">
                <v-icon>mdi-note-text-outline</v-icon>
              </v-btn>
            </v-col>
            <v-col col-auto class="m-0 p-0 col-auto">
              <!-- <v-btn icon @click="selectedOpen = false"> -->
              <v-btn icon @click="close">
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </v-col>
          </v-toolbar>
          <div v-if="isAddSchedule">
            <CreateSchedule 
              :key="create_key" 
              :value="schedule" 
              :role="role" 
              @enter="enter_createSchedule" 
              @close="close_createSchedule"
              @enter_createSubject="enter_createSubject"
              @error="error" 
            />
          </div>
          <div v-else>
            <EditSchedule 
              :key="edit_key" 
              :value="schedule"
              :details="schedule_details"
              :role="role" 
              @enter="enter_schedule" 
              @remove="remove_schedule" 
              @error="error" 
            />
          </div>
        </v-card>
			</template>			
		</EditBox>
    <MsgBox ref="MsgBox">
			<template v-slot:caption></template>
			<template v-slot:main>
				<v-container>
					<v-row align-content="center" >
						<v-col align-content="center">
							<div class="text-h6">{{ err_message }}</div>
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>   
    <AlartBox ref="AlartBox">
			<template v-slot:main>
        <div class="text-h6">{{ alarmText }}</div>
			</template>			
		</AlartBox>
  </div>    
</template>
<script>
  // let loginTime = 0;

  import { mapState, mapGetters } from 'vuex'
  import CyberMain from "../CyberMain";
  import CyberSplitpanes from "../CyberSplitpanes";
  import CyberSideMenu from "../CyberSideMenu";
  import CyberDialog from "../CyberDialog";
  import CreateSchedule from "../Schedule/Create";
  import EditSchedule from "../Schedule/Edit";
  import EditSubject from "../subject/EditSubject";
  import EditBox from "../EditBox";
  import CyberDataTable from "../CyberDataTable";
  import MsgBox from "../msgbox";
  import AlartBox from "../AlartBox";
  import { assertLiteral, tSMethodSignature } from "@babel/types";
  import { relativeTimeThreshold } from 'moment';
  import commFunctions from '../../functions/commFunctions';
  const { getFormatYMD } = commFunctions();
    export default {
      props: ["role","user"],
      components: {
        CyberMain,
        CyberSplitpanes,
        CyberSideMenu,
        CyberDialog,
        CreateSchedule,
        EditSchedule,
        EditSubject,
        EditBox,
        CyberDataTable,
        MsgBox,
        AlartBox,
      },    
      data: () => ({
        focus: '',
        type: 'month',
        typeToLabel: { month: '月', week: '週', day: '日' },
        selectedEvent: {},
        selectedElement: null,
        selectedOpen: false,
        schedule_sources: [],
        schedules: [],
        schedule: [],
        schedule_details: [],
        inner_width: '',
        inner_height: '',
        isAddSchedule:false,
        subject:[],
        edit_key:false,
        create_key: true,
        isDrag:false,
        calendar_date:[],
        sideMenu_items: [],
        clients:[],
        client_id:0,
        subcontracts:[],
        subcontract_id:null,
        person:[],
        processes:[],
        process:null,
        processToCsv:null,
        editSubject_caption:"",
        headers: [
          { text: '元請', value: 'main_nick' },
          { text: '下請', value: 'sub_nick' },
          {
              text: '案件名',
              align: 'start',
              sortable: true,
              value: 'name',
              width:'280',
              groupable:false
          },
          { text: '受注額', value: 'expenses' ,  groupable:false, align:'end'  },
          { text: '区分', value: 'subject_class'},
          { text: '期間(予定)', value: 'period' , groupable:false, align:'start' },
          { text: '状態', value: 'process_name'},
          { text: '', value: 'actions', sortable: false, align: 'end'  },
          // { text: '', value: 'data-table-expand' },
        ],
        tableItems:[],
        filterItems:[],
        // コマンドアイテム
        commandItems: [
          { title: '新規' ,name:'create' , icon:'mdi-folder-plus-outline'},
          { title: '一括' ,name:'group', icon:'mdi-pencil-box-multiple-outline' },
        ],

        // 印刷アイテム
        printItems: [
          { title: '見積書',name:'quotation', icon:'mdi-receipt-outline' },
          // { title: '発注書',name:'order', icon:'mdi-receipt-outline' },
        ],
        // 印刷２アイテム
        print2Items: [
          { title: '見積書',name:'quotation', icon:'mdi-receipt-outline' },
          // { title: '発注書',name:'order', icon:'mdi-receipt-outline' },
          // { title: '請求管理表',name:'ManageSheet', icon:'mdi-receipt-outline' },
        ],
        err_message:"",
        alarms:[],
        alarmText:"",
        timerIds:[]
      }),


      created () {
// alert("created");
        this.processes=this.$store.getters.getProcessNames;       
        // this.alarms=this.$store.getters.getAlarms;       
      },
      mounted () {
        this.$refs.calendar.checkChange()
        window.addEventListener('resize', this.getWindowSize);
        this.getWindowSize(); 
        this.getPerson();
        if (this.role<10) {
          this.getClients();
        }
        // this.setAleam()   
      },

      computed: {
        ...mapGetters([ 'getProcessNames' ]),
        ...mapGetters([ 'getAlarms' ]),
        // ...mapGetters([ 'getLoginTime' ]),
        display_height: function() {
          return this.inner_height-140;
        },
        caption_text: function() {
          return getFormatYMD(this.schedule.start_date) + '～('+ this.schedule.schedule_class +')';
        }
      },    
      methods: {
        setAleam() {
// alert("setAleam");
// alert(JSON.stringify(this.timerIds));
          this.timerIds.forEach(function(id) {
            clearTimeout(id);
          })
          this.alarms=this.$store.getters.getAlarms;  
// alert(JSON.stringify(this.alarms));
          this.timerIds = []
          for (let i=0;i< this.alarms.length;i++) {
// alert(JSON.stringify(this.alarms[i].start_time)); 
            const timestamp1 = new Date( this.alarms[i].start_time).getTime() - this.alarms[i].alarm_interval*60*1000;
            const timestamp2 = new Date().getTime();
            const seconds = timestamp1 - timestamp2
            if (seconds>0 && this.alarms[i].status==1) {
// alert(this.alarms[i])
              let id = setTimeout((value) => {
                this.alarmText=value.name
                this.$refs.AlartBox.open()
              }, seconds, this.alarms[i])
// alert(JSON.stringify(id));  
              this.timerIds.push(id);
            }
          }
// alert(JSON.stringify(this.alarms));
        },
        close() {
          this.selectedOpen = false
          this.$refs.EditBox.close()
          // location.reload()
        },
        viewDay ({ date }) {
          this.focus = date
          this.type = 'day'
        },
        getEventColor (event) {
          return event.color
        },
        setToday () {
          this.focus = ''
        },
        prev () {
          // this.$refs.AlartBox.open("aaa",true)
          this.$refs.calendar.prev()
        },
        next () {
          this.$refs.calendar.next()
        },
        click_sideListItem(e) {
          this.isAddSchedule = false;
          let schedule = this.schedule_sources.find((s) => s.id === e.id);
          this.schedule = JSON.parse(JSON.stringify(schedule.value))
// alert(JSON.stringify(this.schedule));
          if (schedule.value.v_schedule_detail===undefined) {
            this.schedule.v_schedule_details=[]
          } else {
            this.schedule_details = JSON.parse(JSON.stringify(schedule.value.v_schedule_details))
          }
          this.edit_key = !this.edit_key;
          this.$refs.EditBox.open()
        },
        getPerson() {
          axios.get('/people/' + this.user.person_id).then((res) => {
// alert(JSON.stringify(res.data));
            this.person = res.data;
            // this.getSchedules();
            // this.subcontract_selected(this.person.client_id)
          });
        },
        getClients() {
          axios.get('/clients/prime').then((res) => {
            this.clients = res.data;
            axios.get('/clients/subcontract').then((res) => {
              this.subcontracts = res.data;
// alert(JSON.stringify(this.user.person_id));
//               axios.get('/schedule/personId/' + this.user.person_id).then((res) => {
//                 if (res.status==200) {
// alert(JSON.stringify(res.data));
//                   // this.schedule = res.data
//                   // this.schedule_details = res.data.v_schedule_details
//                   // this.$refs.EditBox.open()
//                 }
//               });
            });
          });
        },
        showSchedule ({ nativeEvent, event }) {
// alert(JSON.stringify(event))
          this.isAddSchedule = false;
          let schedule = this.schedule_sources.find((s) => s.id === event.id);
          this.schedule = JSON.parse(JSON.stringify(schedule.value))
          if (this.schedule.class<30) {
            this.schedule_details = JSON.parse(JSON.stringify(schedule.value.v_schedule_details))
          } else {
            this.schedule_details=[]
          }
// alert(JSON.stringify(this.schedule))
          axios.get('/schedule/scheduleId/' + event.id).then((res) => {
            if (res.status==200) {
              this.schedule = res.data
              this.schedule_details = res.data.v_schedule_details
              this.edit_key = !this.edit_key;
              this.$refs.EditBox.open()
            }
          });
        },
        createSchedule(e) {
// alert(JSON.stringify(this.user))
// alert(JSON.stringify(this.client_id))
          this.isAddSchedule = true;  
          this.create_key = !this.create_key;
          this.schedule = {
            subject_id:0,
            person_id:this.user.person_id,
            client_id:this.client_id,
            start_date:e.date,
            end_date:e.date,
            class:10,
            memo:"",
            color:this.user.color,
            alarm_interval:0,
          }
          this.edit_key = !this.edit_key;
          this.$refs.EditBox.open()
        },            
        getWindowSize() {
          this.inner_width = window.innerWidth;
          this.inner_height = window.innerHeight;
        },
        calendar_change({ start, end }) {
// alert("calendar_change")
          this.calendar_date = { start:start.date, end:end.date }
          this.getSchedules()
          this.getSubjects()
        },
        getSchedules() {
// alert("getSchedules")
          let schedules = []
          let schedule_sources = []
          if (this.role>10) {
            this.subcontract_id = this.person.client_id
          } else {
            this.setAleam()   
          }
// alert(JSON.stringify(this.calendar_date))
          const para = 
            { 
              date:this.calendar_date, 
              client:this.client_id, 
              subcontract:this.subcontract_id, 
              // process:this.process.join(','),
              process:this.process,
            }
// alert(JSON.stringify(para))
          axios.post('/schedule/calendar',para).then((res) => {
            const values = res.data;
// alert(JSON.stringify(values.result2))
// // alert(JSON.stringify(values[2].v_schedule_details))
            values.result1.forEach(function(value) {
              if (value.start_date != null && value.end_date != null) {
                schedules.push({
                  name: value.name  + "　" +  value.schedule_class + "　" + value.main_nick ,
                  // name: value.schedule_class + "　" + value.main_nick + "　" + value.id,
                  color: value.color,
                  start:value.start_date,
                  end:value.end_date,
                  timed: true,
                  id:value.id,
                  day:value.start_date.substr(8,2),
                  value: value,
                });
              }
            });
            values.result2.forEach(function(value) {
              if (value.start_date != null && value.end_date != null) {
                schedules.push({
                  name:  "●"+value.start_time.substr(11,5) + ' ' + value.name ,
                  color: value.color,
                  start:value.start_date,
                  end:value.end_date,
                  timed: true,
                  id:value.id,
                  day:value.start_date.substr(8,2),
                  value: value,
                });
              }
              // this.setAleam()
            });
            this.schedules = JSON.parse(JSON.stringify(schedules))
// alert(JSON.stringify(this.schedules))
            this.schedule_sources = JSON.parse(JSON.stringify(schedules))
// alert(JSON.stringify(this.schedules))
          })
          // this.setAleamTime()
        },
        getSubjects() {
          const ym = this.calendar_date.start.substring(0,7)
          axios.get('/subject/date/' + ym).then((res) => {
            if (res.status==200) {
// alert(JSON.stringify(res.data))
              this.tableItems = res.data;
            }
          });
        },
        showSubject(e) {
          axios.get('/subject/' + this.schedule.subject_id).then((res) => {
            this.subject = res.data;
            this.mode ="upd";
            if (this.subject=="") {
              alert("他の利用者により削除されています。")
              this.$refs.subjectTable.refrash();
            } else {
              this.$refs.EditBox.close()
              this.editSubject_caption="案件編集"
              this.$refs.SubjectDialog.open({
                  isFullscreen:true
              });
            }
          });
        },
        client_selected(e) {
          this.client_id = (e==null) ? 0 : e;
          this.getSchedules();
        },
        subcontract_selected(e) {
          this.subcontract_id = (e==null) ? 0 : e;
// alert(JSON.stringify(this.subcontract_id))
          this.getSchedules();
        },
        enter_SubjectDialog(e) {
// alert(JSON.stringify(e))
// alert(JSON.stringify(this.schedule))
          this.$refs.SubjectDialog.close();
          this.getSchedules();
        },      
        enter_createSchedule(e) {
// alert("enter_createSchedule")
// alert(JSON.stringify(e.value.alarm_interval))  
          if (e.class==30) {
            let value = {
              'id':e.value.id,
              'start_time':e.value.start_time,
              'alarm_interval':e.value.alarm_interval,
              'status':e.value.status,
              'name':e.value.name,
              'memo':e.value.memo,
            }        
            this.$store.commit('addAlarm',  { value:  value } );
          }
// alert(JSON.stringify(this.alarms))
          this.$refs.EditBox.close() 
          this.getSchedules();
        },
        close_createSchedule() {
            this.$refs.EditBox.close() 
        },
        enter_createSubject() {
          this.getSchedules();
        },
//         alartBox_opened() {
//           const music = new Audio('/sound/alarm01.mp3');
//           // music.autoplay = true;
//           // music.muted  = true;
//           music.play();
// alert("alartBox_opened")
//         },
        enter_schedule(e) {
// alert(JSON.stringify(this.schedules[0]))
// alert(JSON.stringify(e))
          // const id = this.schedules.findIndex(e => e.value.id === e.id);  
          // this.schedules.splice(id,1,e.value);           
          this.selectedOpen = false
          let value = {
            'id':e.value.id,
            'start_time':e.value.start_time,
            'status':e.value.status,
            'name':e.value.name,
            'memo':e.value.memo,
          }       
          this.$store.commit('updateAlarm',  { value:  value } );
          this.getSchedules();
          this.$refs.EditBox.close()
        },
        remove_schedule(val) {
// alert(JSON.stringify(val))
          const id = this.schedules.findIndex(e => e.value.id === val);  
          this.schedules.splice(id,1);   
          this.$refs.EditBox.close()    
        },
        error(e) {
          this.err_message = e
          this.$refs.MsgBox.open('ok')
        }, 
      },
    }
</script>
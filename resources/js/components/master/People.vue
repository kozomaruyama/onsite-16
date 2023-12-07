<template>
	<div>
    <v-container fluid class="bg-peimary px-5">
		<v-row no-gutters class="mt-4">
			<v-text-field class="d-none" id="index" v-model="values.id"></v-text-field>
			<v-col cols="12" md="2" class="px-2 mt-2">
				<v-text-field label="コード" id="code" v-model="values.code" dense maxlength="10"></v-text-field>
			</v-col>
			<v-col cols="12" md="2" class="px-2 mt-2">
				<v-select :items="clientClasses" item-text="name" item-value="no" label="区分" v-model="values.class" dense></v-select>
			</v-col>
			<v-col cols="12" md="4" class="px-2 mt-2">
				<v-select :items="clients" item-text="name" item-value="id" label="所属" v-model="values.client_id" :disabled=select_clients_disabled dense ></v-select>
			</v-col>
		</v-row>
		<v-row no-gutters class="">
			<v-col cols="12" md="3" class="px-2 mt-2">
				<v-text-field label="氏名" id="name" v-model="values.name"  ></v-text-field>
			</v-col>
			<v-col cols="12" md="3" class="px-2 mt-2">
				<v-text-field label="カナ" id="kana" v-model="values.kana"  ></v-text-field>
			</v-col>
			<v-col cols="12" md="2" class="px-2 mt-2">
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
							v-model="values.birthday"
							label= "生年月日"
							prepend-icon="mdi-calendar"
							v-bind="attrs"
							v-on="on"
						></v-text-field>
					</template>
					<v-date-picker
						v-model="values.birthday"
						no-title
						locale="ja"
						@input="dateMenu1 = false"
					></v-date-picker>
				</v-menu>
			</v-col>
			<v-col cols="12" md="3" class="px-2 mt-2">
				<v-radio-group v-model="values.sex" dense row> 
					<v-radio :key="0" label="男" :value="0"></v-radio>
					<v-radio :key="1" label="女" :value="1"></v-radio>
				</v-radio-group>		
			</v-col>

		</v-row>
		<v-row no-gutters class="">
			<v-col cols="12" md="2" class="px-2 mt-2">
				<v-text-field label="連絡先１" id="cost" v-model="values.tel1" dense></v-text-field>
			</v-col>
			<v-col cols="12" md="2" class="px-2 mt-2">
				<v-text-field label="連絡先２" id="cost" v-model="values.tel2" dense></v-text-field>
			</v-col>
			<v-col cols="12" md="2" class="px-2 mt-2">
				<v-text-field label="eMail" v-model="values.mail" dense ></v-text-field>
			</v-col>
			<v-col cols="auto" class="pl-2 mt-2 mx-2">
				<v-sheet
					class="mx-auto rounded-circle"
					height="30"
					width="30"
					:color="values.color"
					@click="$refs.ColorSelecter.open()"
				/>
			</v-col>
		</v-row>

		<v-row no-gutters class="">
			<v-col cols="12" md="2" class="px-2 mt-2">
				<v-text-field label="〒" v-model="values.zip" dense ></v-text-field>
			</v-col>
			<v-col cols="12" md="8" class="px-2 mt-2">
				<v-text-field label="住所" v-model="values.address" dense ></v-text-field>
			</v-col>
		</v-row>
		<!-- <v-row v-if="values.id>1 || mode=='create'" class="m-0 p-0"> -->
		<v-row class="m-0 p-0">
			<v-col class="col-auto">
				<v-btn v-if="mode=='edit'" depressed small fab @click="del()">
						<v-icon>mdi-delete-outline</v-icon>
				</v-btn>
			</v-col>
			<v-col></v-col>
			<v-col class="col-auto">
				<v-btn depressed class="text-white bg-primary" color="success" @click="enter()">
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
		<MsgBox ref="ColorSelecter">
			<template v-slot:caption>カラーパレット</template>
			<template v-slot:main>
				<v-container >
					<v-row align-content="center" class="p-2">
						<v-col v-for="(color, index) in colors" :key="index"  class="col-auto px-2 mt-2 text-center">
							<!-- <span v-for="(color, index) in colors" :key="index" class="p-2"> -->
								<v-avatar	:color="color.name" size="30" @click="color_selected(color.name)" class=""/>
							<!-- </span> -->
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>	
	</div>
</template>
<script>
	import { mapState, mapGetters } from 'vuex'
	import MsgBox from "../msgbox";
	export default {
		components: {
			MsgBox,
		},
		props: ["values","mode"],
		data: function () {
			return {
				clientClasses: [],
				peopleClasses: [],
				clients:[],
				dateMenu1:false,	
				// select_clients_disabled:false,	
				colors:[],
			}
		},
		watch: {
			'values.class': function (newVal, oldVal) {
				this.values.client_id = 0;
				this.getClients(newVal)
			}
		},
		methods: {
			getClients($class) {
				// if ($class <=20) {
					axios.get('/clients/class/'+$class).then((res) => {
						this.clients = res.data;
					});
				// } else if ($class <=30) {
				// 	this.clients = [
				// 		{"name":"事務所","id":10},{"name":"現場","id":20}
				// 	];
				// }
			},	
			// client_selected(e) {	
			// 	this.client = this.clients.find((client) => client.id === e);
			// 	this.values.class = this.client.class;
			// },					
			close() {
				this.$emit('close');
			},
			enter() {
				var person = Object.assign({}, this.values)    // clientObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応		
				switch (this.mode) {
					case "edit":
						axios.put('/people/' + person.id, person).then((res) => {
							if (res.data.status) {
								this.$emit('enter',{ value:res.data.value, mode:"upd" });
							} else {
								this.$emit('error', res.data.value );
							}
						});
						break;
					case "create":
// alert(JSON.stringify(person))
						// axios.post('/people', person).then((res) => {
						// 	this.$emit('enter',{ value:res.data, mode:"create" });
						// });
						axios.post('/people', person).then((res) => {
// alert(JSON.stringify(res.data))
							if (res.data.status) {
								this.$emit('enter',{ value:res.data.value, mode:"create" });
							} else {
								this.$emit('error', res.data.value );
							}
							// this.$emit('enter',{ value:res.data, mode:"create" });
						});

						break;
					case "group":
						axios.post('/api/people/group', { targetItems: this.selectItems, updateValues: person } )
							.then((res) => {
								this.$emit('close');
						});
						break;
				}
			},
			del() {
				this.$refs.MsgBox.open('del')
			},
			color_selected(e) {
				this.values.color = e;
				this.$refs.ColorSelecter.close()
// alert(JSON.stringify(e))
			},
			msgBox_enter(para) {
				if (para='del') {
					axios.delete('/people/' + this.values.id).then((res) => {
						if (res.data.status) {
								this.$emit('remove');
						} else {
							if (res.data.value.errorInfo[0]=="00000") {
								this.$emit('error', [res.data.value.errorInfo[1]] );
							} else if (res.data.value.errorInfo[0]=="23000") {
								this.$emit('error', ["他の情報で参照されているため、削除できません。"] );
							} else {
								this.$emit('error', ["削除できませんでした。(" + res.data.value.errorInfo[0] + ")"] );
							}
						}
					});
				}
			},
		},
		computed: {
			...mapGetters([ 'getClientClasses' ]),
			...mapGetters([ 'getPeopleClasses' ]),
			select_clients_disabled:function() {
				return this.values.class > 40
			}
		},
		created() {
			this.clientClasses = this.$store.getters.getClientClasses;
			this.peopleClasses = this.$store.getters.getPeopleClasses;
			this.colors = this.$store.getters.getColors;
// alert(JSON.stringify(this.values))
		},
		mounted() {
			this.getClients(this.values.class);
// alert(JSON.stringify(this.values))
		}
	}
</script>


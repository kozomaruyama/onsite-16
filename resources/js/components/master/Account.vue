<template>
  <div>
    <div>
      <!-- <v-text-field label="表示名" v-model="account.name" ></v-text-field> -->
      <v-text-field label="ログインID" v-model="account.name" ></v-text-field>
      <!-- <span v-if="mode=='create'"> -->
        <v-text-field label="パスワード" v-model="account.password" type="password"></v-text-field>
        <v-text-field label="パスワード再入力" v-model="password_confirmation" type="password"></v-text-field>
      <!-- </span> -->
    </div>
    <v-row class="m-0 p-0">
      <v-col v-if="mode=='edit' && user.role <= 2" class="col-auto">
        <v-btn depressed small fab @click="remove()">
          <v-icon color="grey">mdi-delete-outline</v-icon>
        </v-btn>
      </v-col>      
			<v-col></v-col>
			<v-col class="col-auto">
				<v-btn depressed class="text-white bg-primary" color="success" @click="enter()">
					<v-icon left>mdi-content-save-outline</v-icon>登　録
				</v-btn>
			</v-col>
		</v-row>
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
  </div>
</template>
<script>
  import MsgBox from "../msgbox";
  export default {
    components: {
			MsgBox,
		},
    props: ["account","mode","user"],
    data: function () {
			return {
        // name:"",
        // id:"",
        // password:"",
        password_confirmation:"",
        // mode:"create",
      }
    },
		mounted() {
      this.password_confirmation = ""
		},    
		methods: {
			close() {
        this.crear_screen_data
				this.$emit('close');
			},
			enter() {
        var account = Object.assign({}, this.account)    // clientObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
        if (account.person_id==1) {
          account.role=1
        }
        if (this.mode == 'create') {
          if (this.account.password != this.password_confirmation ) {
            alert('パスワードが一致しません')
          } else {
            axios.post('/account/store', account ).then((res) => {
// alert(JSON.stringify(res))        
              this.crear_screen_data
              this.$emit('enter',{ value:res.data, mode:"add" });
            });        
          }
        } else {
          axios.post('/account' , account).then((res) => {
            if (res.data.status==0) {
              this.$emit('enter',{ value:res.data, mode:"upd" });
            } else {
              alert(res.data.value)
            }            
          });          
        }
      },
      remove() {
        this.$refs.MsgBox.open('del')
        // if (window.confirm('アカウントを削除してよろしいですか？')) {
        //   axios.post('/account/delete', this.account).then((res) => {
				// 		if (res.data.status) {
				// 				this.$emit('remove');
				// 		} else {
				// 			if (res.data.value.errorInfo[0]=="00000") {
        //         this.$emit('error', [res.data.value.errorInfo[1]] );
        //       } else if (res.data.value.errorInfo[0]=="23000") {
        //         this.$emit('error', ["他の情報で参照されているため、削除できません。"] );
        //       } else {
        //         this.$emit('error', ["削除できませんでした。(" + res.data.value.errorInfo[0] + ")"] );
        //       }
				// 		}
        //   });
        // }
      },

			msgBox_enter(para) {
				if (para='del') {
					axios.post('/account/delete', this.account).then((res) => {
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
					})
				}
			},


    },    
  }
</script>
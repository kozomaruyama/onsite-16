<template>
  <v-dialog v-model="dialog" :width="width" hide-overlay scrollable>
    <v-card>
      <v-card-title dense flat  outlined class="m-0 p-0">
        <v-app-bar dense flat outlined color="white">
          <v-toolbar-title>
            <slot name="caption" />
          </v-toolbar-title>
          <v-spacer />
          <div>
            <v-btn depressed small fab @click="close">
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </div>
        </v-app-bar>
      </v-card-title>
      <v-divider />
      <v-card-text class="m-0 p-0">
        <v-container>
					<v-row align-content="center" >
						<v-col align="center">
							<slot name="main" />
						</v-col>
					</v-row>
				</v-container>
      </v-card-text>
      <v-container>
        <v-row class="m-0 p-0">
          <v-col />
          <v-col class="col-auto">
            <v-btn depressed class="text-white bg-primary" color="success" @click="close()">
              <v-icon left>mdi-clise</v-icon>閉じる
            </v-btn>
          </v-col>
          <v-col />
        </v-row>                      
      </v-container>
    </v-card>
  </v-dialog>  
</template>
<script>
  export default {
    data: function () {
      return {
        dialog: false,
        isFullscreen:false,
        width:"500px",
        para:null,
        style:""
      }
    }, 
    methods: {
      open() {
        const music = new Audio('/sound/alarm01.mp3');
        music.play();
        this.$emit('opened');
        this.dialog = true
      },
      close() {
        this.$emit('closed');
        this.dialog = false
      },
    }
  }
</script>
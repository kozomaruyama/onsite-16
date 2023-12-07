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
        <slot name="main" />
      </v-card-text>
      <v-container>
        <v-row v-if="style=='ok'" class="m-0 p-0">
          <v-col />
          <v-col class="col-auto">
            <v-btn depressed class="text-white bg-primary" color="success" @click="cancel()">
              <v-icon left>mdi-clise</v-icon>閉じる
            </v-btn>
          </v-col>
          <v-col />
        </v-row>            
        <v-row v-if="style=='yn'" class="m-0 p-0">
          <v-col />
          <v-col class="col-auto">
            <v-btn depressed class="text-white bg-primary" color="success" @click="enter()">
              <v-icon left>mdi-content-save-outline</v-icon>はい
            </v-btn>
          </v-col>
          <v-col />
          <v-col class="col-auto">
            <v-btn depressed class="text-white bg-primary" color="primary" @click="cancel()">
              <v-icon left>mdi-cancel</v-icon>いいえ
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
      open(para, alart = false) {
        if (para=="add" || para=="del" || para=="marge" || para=="yn") {
          this.style= "yn"
        } else {
          this.style= "ok"
        }
        this.para = para;
        if (alart) {
          const music = new Audio('/sound/alarm01.mp3');
          music.play();
        }
        this.$emit('opened');
        this.dialog = true
      },
      close() {
        this.$emit('closed');
        this.dialog = false
      },
      enter() {
        this.$emit('enter',this.para);
        this.dialog = false
      },
      cancel() {
        this.$emit('cancel');
        this.dialog = false
      },

    }
  }
</script>
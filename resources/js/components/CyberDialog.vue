<template>
    <v-dialog v-model="dialog" :fullscreen="isFullscreen" :width="width" :name="name" transition="dialog-top-transition" scrollable>
        <v-card>
            <v-card-title dense flat  outlined class="m-0 p-0">
                <v-app-bar dense flat outlined color="amber">
                    <v-toolbar-title><slot name="caption"></slot></v-toolbar-title>
                    <v-spacer />
                    <div>
                        <v-btn depressed small fab @click="close">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </div>
                </v-app-bar>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <slot name="main" />
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
 export default {
    data: function () {
        return {
            name: "",
            dialog: false,
            isFullscreen:false,
            width:"100%",
        }
    },
    methods: {
        open(para) {
            this.$emit('before_opened');
            this.isFullscreen = para.isFullscreen;
            if ('width' in para) {
                this.width = para.width;
            }
            this.dialog = true
        },
        close() {
            this.$emit('before_closed');
            this.dialog = false
        }
    }
 }
</script>

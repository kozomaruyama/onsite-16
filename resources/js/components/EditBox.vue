<template>
  <v-dialog v-model="dialog" :width="width" hide-overlay scrollable>
    <v-card>
      <slot name="main" />
    </v-card>
  </v-dialog>  
</template>
<script>
  export default {
    data: function () {
      return {
        dialog: false,
        width:"800px",
      }
    }, 
    methods: {
      open() {
        this.$emit('opened');
        this.dialog = true
      },
      close() {
        this.$emit('closed');
        this.dialog = false
      },
      enter() {
        this.$emit('enter');
        this.dialog = false
      },
      cancel() {
        this.$emit('cancel');
        this.dialog = false
      }
    }
  }
</script>
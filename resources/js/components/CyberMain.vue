<template>
  <div class="h-100 ">
    <v-row class="h-100">
      <v-col v-if="isNaviVisible" class="col-auto m-0 ml-2 mt-2 p-0 no-printing" >
        <v-navigation-drawer
            permanentprimary
            :expand-on-hover="expandOnHover"
            disable-resize-watcher
            width="200"
            permanent
            mini-variant-width="55"
            :height="display_height"
            @transitionend="transitionend"
            @update:mini-variant="visible = true"
        >
            <v-btn class="mx-1 mt-1 p-0" text icon @click="expand_navi">
                <v-icon v-if="expandOnHover" small>mdi-chevron-double-right</v-icon>
                <v-icon v-else small>mdi-chevron-double-left</v-icon>
            </v-btn>
            <slot name="navi" />
        </v-navigation-drawer>
      </v-col>
      <!-- <v-col v-else class="col-auto"/> -->
      <v-col class="m-0 mt-3 p-0">
        <slot name="main" />
      </v-col>
    </v-row>
  </div>
</template>

<script>
  export default {
      props: ["filterItems","commandItems","printItems","isNaviVisible"],
      data: function () {
        return {
          inner_width:0,
          inner_height:0,
          expandOnHover:true,
          visible:false,
        }
      },
      computed: {
          display_height: function() {
              return this.inner_height-60;
          }
      },
      methods: {
          expand_navi(e) {
            this.expandOnHover = !this.expandOnHover
          },
          getWindowSize() {
            this.inner_width = window.innerWidth;
            this.inner_height = window.innerHeight;
          },
          transitionend() {
            this.visible = !this.visible
            this.$emit('transitionend',this.visible);              
          },
          bbb() {
            this.visible = true;
          }
      },
      created () {
      },
      mounted() {
          window.addEventListener('resize', this.getWindowSize);
          this.getWindowSize();
      }
  }
</script>
<style lang="scss" scoped>



</style>

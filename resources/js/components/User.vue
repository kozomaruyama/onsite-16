<template>
  <video style="width: calc(100vw);" class="inner_content m-0 p-0" autoplay loop muted playsinline poster="http://www.tc888.co.jp/wp-content/uploads/2017/01/smart_top1920_1080.jpg">
      <source src="http://www.tc888.co.jp/wp-content/uploads/2017/01/Building3-1.mp4" type='video/mp4'>
  </video>
</template>

<script>
  import { mapState, mapGetters } from 'vuex'
  export default {
      mounted() {
          this.getSysItems();
      },
      methods: {
          setSysItems() {
              this.$store.commit('setSysItems',  { sysItems:  this.sys_items } );
          },
          getSysItems() {
              axios.get('/api/sys_items').then((res) => {
                  this.sys_items = res.data;
                  this.setSysItems();
              });
          },

      } ,
      computed: {
          appPage: function() {
              return this.$store.getters.getProcess;
          },
          ...mapState([ 'process' ]),
          ...mapGetters([ 'getProcess' ])
      },
  }
</script>
<style lang="scss" scoped>


</style>

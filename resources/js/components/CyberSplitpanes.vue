<template>
  <splitpanes horizontal class="default-theme">
    <pane size="100">
      <slot name="main" />
    </pane>
    <pane size="0">
      <slot name="sub" />
    </pane>
  </splitpanes>
  <!-- <splitpanes class="default-theme" style="">
    <pane min-size="20">
      <span>1</span>
    </pane>
    <pane>
      <splitpanes horizontal>
        <pane>
          <slot name="main" />
        </pane>
        <pane>
          <slot name="sub" />
        </pane>
        <pane>
          <span>4</span>
        </pane>
      </splitpanes>
    </pane>
    <pane>
      <span>5</span>
    </pane>
  </splitpanes> -->
</template>

<script>
  import { Splitpanes, Pane } from 'splitpanes'
  import 'splitpanes/dist/splitpanes.css'
  export default {
    components: { 
      Splitpanes, 
      Pane
    },
  }
</script>
<style lang="scss" scoped>
  .splitpanes__pane {
  justify-content: center;
  align-items: center;
  display: flex;
  }
  .splitpanes__pane span {
  font-family: Helvetica, Arial, sans-serif;
  color: #fff;
  font-size: 5em;
  opacity: 0.6;
  }
  .splitpanes.default-theme .splitpanes__pane {
  background-color: #f2f2f2;
  }
</style>
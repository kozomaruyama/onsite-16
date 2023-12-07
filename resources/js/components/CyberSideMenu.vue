<template>
    <div>
      <v-expansion-panels v-model="panel" accordion flat>
          <v-expansion-panel v-for="(item,item_no) in items" :key="item_no">
              <v-expansion-panel-header class="p-0 pl-2" expand-icon="mdi-menu-down" hide-actions @click="click_category(item)">
                  <v-icon dense class="m-0 pl-1 pr-6" v-text="item.icon"></v-icon>
                  <v-col class="m-0 p-0">{{item.text}}</v-col>
              </v-expansion-panel-header>
              <v-expansion-panel-content >
                  <v-list class="p-0">
                      <v-list-item-group v-model="selectedItem" color="primary">
                          <v-list-item v-for="(item, child_no) in item.items" :key="child_no" class="p-0"  @click="click_item({category:item.category,key:item.key,label:item.text, value:item.value})">
                              <v-list-item-icon>
                                  <v-icon dense v-text="item.icon"></v-icon>
                              </v-list-item-icon>
                              <v-list-item-content>
                                  <v-list-item-title v-text="item.text"></v-list-item-title>
                              </v-list-item-content>
                          </v-list-item>
                      </v-list-item-group>
                  </v-list>
              </v-expansion-panel-content>
          </v-expansion-panel>
      </v-expansion-panels>
    </div>
</template>
<script>
    export default {
        props: ["items"],
        data: function () {
          return {
            panel:[],
            selectedItem:0,
          }
        },
        methods: {
            expand(val){
// alert(JSON.stringify(val))
                this.panel = val
                this.click_category(this.items[val])
                this.selectedItem = val
            },
            click_category(e) {
                this.selectedItem = [];
                this.$emit('click_category',e);
            },
            click_item(e) {
                this.$emit('click_item',e);
            },
        }
    }
</script>
<style lang="scss" scoped>
</style>

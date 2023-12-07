<template>
    <v-simple-table>
    <template v-slot:default>
      <thead class="border">
        <tr>
          <th v-for="(item, index) in headers" :key="index" class="text-left">{{ item.label }}</th> 
        </tr>
      </thead>
      <tbody class="border">
        <tr v-for="(detail, index) in details" :key="index">
          <td v-if="detail['isLabel']==0" v-for="(item, index) in headers" :key="index" class="py-0 px-2">
            <span v-if="item.isEdit==1"><v-text-field input dense v-model="detail[item.name]"></v-text-field></span>
            <span v-else>{{ detail[item.name] }}</span>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <v-btn v-show="visible_add" color="grey lighten-2" small>
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </tfoot>
    </template>
  </v-simple-table>
</template>
<script>
  export default {
    props: [
      "headers",
      "details",
      "visible_add",
    ],
		data: function () {
			return {
        
      }
    }
  }
</script>
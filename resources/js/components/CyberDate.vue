<template>
    <v-col
      cols="12"
      sm="6"
      class="m-0 p-0"
    >
      <v-menu
        ref="menu"
        v-model="menu"
        :close-on-content-click="false"
        :return-value.sync="value"
        transition="scale-transition"
        offset-y
        min-width="auto"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            v-model="aaa"
            :label= "labelText"
            :disabled="disabled"
            readonly
            v-bind="attrs"
            v-on="on"
            dense
          ></v-text-field>
        </template>
        <v-date-picker
            v-model="aaa"
            locale="ja"
            @input="menu = false"
        ></v-date-picker>
      </v-menu>
    </v-col>
</template>
<script>

export default {
    props: ["value","labelText","disabled"],
    data: function () {
        return {
            // date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
            menu: false,
            aaa:[],
        }
    },
    computed: {
        date: {
            get() {
                return this.value;
            },
            set(newValue) {
                this.$emit("input", newValue);
            },
        },
    },
}
</script>

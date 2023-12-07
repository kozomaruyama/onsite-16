<template>
    <v-toolbar flat dense class="m-0 p-0">
        <!-- 検索 -->
        <v-text-field
            v-if="show_field"
            class="p-0 m-0"
            v-model = "searchText"
            append-icon="mdi-magnify"
            label="検索"
            single-line
            hide-details
            clearable
        ></v-text-field>

        <!-- 抽出 -->
        <span v-for="(item,index) in filterItems" :key="index">
            <v-select
                class="p-0 m-0 ml-2"
                v-model = "filterValues[index]"
                :items = "item.value"
                item-text = "name"
                item-value = "no"
                :label = "item.lavel"
                multiple
                clearable
                dense
                @change = "filter(filterValues)"
            ></v-select>
        </span>
        <!-- </div> -->
        <!-- コマンド -->
        <v-menu  >
            <template v-slot:activator="{ on, attrs }">
                <v-btn class="ml-2" color="primary" v-bind="attrs" v-on="on">
                    <v-icon left>mdi-dots-grid</v-icon>操作
                </v-btn>
            </template>
            <v-list>
                <v-list-item v-for="(item, index) in commandItems" :key="index" link>
                    <v-list-item-title @click="clickCommand(item.name,selected)">{{ item.title }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
        <!-- 印刷 -->
        <v-menu  >
            <template v-slot:activator="{ on, attrs }">
                <v-btn class="ml-2" color="primary" v-bind="attrs" v-on="on">
                    <v-icon left>mdi-printer</v-icon>印刷
                </v-btn>
            </template>
            <v-list>
                <v-list-item v-for="(item, index) in printItems" :key="index" link>
                    <v-list-item-title @click="clickPrint(item.name,selected)">{{ item.title }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
    </v-toolbar>
</template>
<script>
	export default {
        props: ["filterItems","commandItems","printItems"],
        data () {
            return {
                //  データテーブル
                searchText: "",
                filterValues:[],
                selected:[],
                show_field:false,
            }
        },
        watch: {
            searchText: function(newVal, oldVal ) {
                 this.$emit('search',newVal);
            }
        },
        computed: {
        },
        methods: {
            filter(val) {
                let filterItems = [];
                for (let i = 0; i < this.filterItems.length; i++) {
                    if (val[i]==null) {val[i]=[]}
                    filterItems.push({name:this.filterItems[i].name,value:val[i]})
                }
                this.$emit('filter',filterItems);
            },
            clickCommand(val) {
                this.$emit('command',val);
            },
            clickPrint(val) {
                this.$emit('print',val);
            }
        },
        mounted() {
        },
    }
</script>

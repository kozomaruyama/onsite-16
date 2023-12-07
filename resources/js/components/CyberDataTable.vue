<template>
    <div>
        <!-- ヘッダ -->
        <v-toolbar flat class="mr-2">
            <v-toolbar-title class="mr-2">
                <slot name="title"></slot>
            </v-toolbar-title>
            <!-- 検索 -->
            <v-text-field
                class="mx-4"
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
                    class="ml-2 mt-6"
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
            <slot name="command"></slot>
        </v-toolbar>
        <v-divider light></v-divider>
        <!-- <v-toolbar v-if="visible_subCommand" flat height="30" class=" m-0 p-0 mr-7"> -->
        <v-toolbar v-if="visible_subCommand" flat height="30" class=" m-0 p-0 mr-7">
            <v-icon v-if="visible_group" right @click.stop="item_group()">
                mdi-pencil-box-multiple-outline
            </v-icon>
            <v-icon v-if="visible_calc" right @click.stop="item_calc()">
                mdi-calculator
            </v-icon>
            <v-icon v-if="visible_marge" right @click.stop="item_marge()">
                mdi-paperclip
            </v-icon>
            <v-menu v-if="visible_print" offset-y  >
                <template v-slot:activator="{ on, attrs }">
                    <v-icon right v-bind="attrs" v-on="on">mdi-printer</v-icon>
                </template>
                <v-list>
                    <span v-for="(item, index) in printHeaderItems" :key="index">
                        <v-divider v-if="item.name=='divider'"></v-divider>
                        <v-list-item v-else min-height dense link @click="Print(item.name,selected)">
                            <v-icon left>{{ item.icon }}</v-icon>{{ item.title }}
                        </v-list-item>
                    </span>
                </v-list>
            </v-menu>
        </v-toolbar>
        <!-- ボディー -->
        <v-data-table
            v-model="selected"
            :items-per-page = "items.length"
            :headers = "headers"
            :items = "items"
            :height = "display_height"
            :search = "searchText"
            :show-expand = "show_expand"
            :show-select = "show_select"
            single-expand
            :expanded.sync="expanded"
            @click:row = "click"
            @item-expanded = "item_expanded"
            @item-selected="item_selected"
            @toggle-select-all="item_select_all"
        >
            <template v-slot:expanded-item="{ headers, item }">
                <!-- <td :colspan="headers.length" class="bg-white p-0 pr-2 m-10" > -->
                <td :colspan="headers.length" class="bg-white p-0 m-0 ">
                    <slot :detail="item"></slot>
                </td>
            </template>
            <template v-slot:header.actions="{ header }">
                <!-- <v-icon size="20" class="mr-2" @click.stop="item_group()">
                    mdi-pencil-box-multiple-outline
                </v-icon> -->
                <v-icon v-if="visible_add" size="20" class="mr-1" @click.stop="item_add()">
                    mdi-pencil-plus
                </v-icon>
                <v-menu v-if="visible_printHeader" offset-y  >
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon right size="20" v-bind="attrs" v-on="on">mdi-printer</v-icon>
                    </template>
                    <v-list>
                        <span v-for="(printItem, index) in printHeaderItems" :key="index">
                            <v-divider v-if="printItem.name=='divider'"></v-divider>
                            <v-list-item v-else min-height dense link @click="header_print(printItem.name)">
                                <v-icon size="20" left>{{ printItem.icon }}</v-icon>{{ printItem.title }}
                            </v-list-item>
                        </span>
                    </v-list>
                </v-menu>
            </template>
            <template v-slot:item.status="{ item }">
                <!-- <v-row justify="space-around"> -->
                <v-row>
                    <v-col v-if="[item.status]==0 " class="col-auto p-0 m-0 " >
                        <v-icon size="20" center color="grey lighten-1">mdi-file</v-icon>
                    </v-col>
                    <v-col v-if="[item.status]==1 || [item.status]==2" class="col-auto p-0 m-0 " >
                        <!-- <v-icon small center color="red">mdi-file-lock-outline</v-icon> -->
                        <v-icon size="20" center color="red">mdi-file</v-icon>
                    </v-col>
                    <!-- <v-col v-if="[item.status]==2" class="col-auto p-0 m-0 " >
                        <v-icon small center color="red">mdi-file-multiple</v-icon>
                    </v-col> -->
                    <v-col v-if="[item.status]==9" class="col-auto p-0 m-0 " >
                        <v-icon size="20" center color="grey lighten-1">mdi-script-outline</v-icon>
                        <!-- <v-icon small center color="red">mdi-file-multiple</v-icon> -->
                    </v-col>
                    <!-- <v-col v-if="[item.status]==1" class="col-auto p-0 m-0 " >
                        <v-icon small center color="red darken-2">mdi-file-alert-outline</v-icon>
                    </v-col> -->
                    <!-- <v-col v-if="[item.status]==2" class="col-auto p-0 m-0 " >
                        <v-icon small center color="blue darken-2">mdi-file-document</v-icon>
                    </v-col> -->
                    <!-- <v-col v-if="status_icon2==2" class="col-auto p-0 m-0 ">
                        <v-icon small >mdi-pencil-plus</v-icon>
                    </v-col> -->
                </v-row>
            </template>
            <template v-slot:item.actions="{ item }">
                <!-- <v-icon v-if="visible_print2" size="20" class="mr-2" @click.stop="item_print(item)">
                    mdi-printer
                </v-icon> -->
                <v-icon v-if="visible_edit" size="20" right @click.stop="item_edit(item)">
                    mdi-pencil
                </v-icon>
                <!-- <v-icon v-if="visible_add" size="20" class="mr-2" @click.stop="item_add(item)">
                    mdi-pencil-plus
                </v-icon> -->

                <v-menu v-if="visible_printDetail" offset-y  >
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon right size="20" v-bind="attrs" v-on="on">mdi-printer</v-icon>
                    </template>
                    <v-list>
                        <span v-for="(printItem, index) in printDetailItems" :key="index">
                            <v-divider v-if="printItem.name=='divider'"></v-divider>
                            <v-list-item v-else min-height dense link @click="Print(printItem.name,[item])">
                                <v-icon size="20" left>{{ printItem.icon }}</v-icon>{{ printItem.title }}
                            </v-list-item>
                        </span>
                    </v-list>
                </v-menu>
                    <v-icon v-if="visible_xls" size="20" right @click.stop="item_xls(item)">
                    mdi-microsoft-excel
                </v-icon>                
                    <v-icon v-if="visible_copy" size="20" right @click.stop="item_copy(item)">
                    mdi-content-copy
                </v-icon>
                <span v-if="item.role==-1">
                        <v-icon v-if="visible_account" size="20" right @click.stop="create_account(item)">
                        mdi-account-alert-outline
                    </v-icon>
                </span>
                <span v-else>
                    <v-icon v-if="visible_account" size="20" right @click.stop="edit_account(item)">
                        mdi-shield-account
                    </v-icon>                
                </span>
            </template>
        </v-data-table>
        <MsgBox ref="MsgBox" :style="MsgBox_style" @enter="msgBox_enter">
			<template v-slot:caption></template>
			<template v-slot:main>
				<v-container>
					<v-row align-content="center" >
						<v-col align="center">
							<div class="text-h6">{{ MsgBox_meaagge }}</div>
						</v-col>
					</v-row>
				</v-container>
			</template>			
		</MsgBox>
    </div>
</template>
<script>
  import MsgBox from "./msgbox";
  export default {
    components: {
      MsgBox,
    },
    props: [
        "headers",
        "items",
        "show_expand",
        "show_select",
        "filterItems",
        "commandItems",
        "printHeaderItems",
        "printItems",
        "printDetailItems",
        'visible_group',
        'visible_calc',
        'visible_marge',
        'visible_printHeader',
        'visible_printDetail',
        'visible_add',
        'visible_edit',
        'visible_copy',
        'visible_xls',
        'visible_account',
        // 'status_icon1',
        // 'status_icon2',
    ],
    data: function () {
        return {
            expanded:[],
            searchText: "",
            filterValues:[],
            selected:[],
            //
            inner_width:0,
            inner_height:0,
            selectIndex:0,
            visible_subCommand:false,
            MsgBox_style:'yn',
            MsgBox_meaagge:''
        }
    },
    watch: {
        'selected': function(val) {
            this.visible_subCommand = this.selected.length>0;
        },
    },
    computed: {
        display_height: function() {
            return this.inner_height-187;
        }
    },
    methods: {
        init() {
            this.expanded=[];
            this.selected=[];
        },
        //
        filter(val) {
            let filterItems = [];
            for (let i = 0; i < this.filterItems.length; i++) {
                if (val[i]==null) {val[i]=[]}
                filterItems.push({name:this.filterItems[i].name,value:val[i]})
            }
            this.$emit('filter',filterItems);
        },
        // clickCommand(val) {
        //     this.$emit('command',val);
        // },
        header_print(name) {
// alert(JSON.stringify(name));
            this.$emit('print',{ name:name, items:[] });
        },
        Print(name,selected) {
// alert(JSON.stringify(name));
            this.$emit('print',{ name:name, items:selected });
        },
        item_xls(e) {
             this.$emit('xls',e);
        },        
        //
        getWindowSize() {
            this.inner_width = window.innerWidth;
            this.inner_height = window.innerHeight;
        },
        click(e,slot) {
            this.selectIndex = this.items.indexOf(e);
            this.$emit('click_TableRow',{rowNo:this.selectIndex,value:e,slot:slot});
        },
        getSelectTableRows() {
            return this.selected;
        },
        clearSelectTableRows() {
            this.selected = [];
        },
        item_expanded(e) {          
// alert(JSON.stringify(e));
            const isSelected = this.selected.some((item) => item.id === e.item.id);
            this.selectIndex = this.items.indexOf(e.item);
            this.$emit('item_expanded',{rowNo:this.selectIndex,value:e.item, status:e.value, isSelected:isSelected});
        },
        item_selected(e) {
            const rowNo = this.items.indexOf(e.item);
            this.$emit('select_TableRow',{rowNo:rowNo,value:e.item,isSelected:e.value});
        },
        item_select_all(e) {
            this.$emit('select_TableRow_all',{rowCount:e.items.length,values:e.items,isSelected:e.value});
        },
        // item_add() {
        //     this.$emit('add');
        // },
        item_group() {
             this.$emit('group');
        },
        item_calc() {
             this.$emit('calc',this.selected);
        },

        item_marge() {
            this.MsgBox_style='yn'
            this.MsgBox_meaagge='集約してよろしいですか？'
            this.$refs.MsgBox.open('marge')
        },
        msgBox_enter(para) {
            if (para=='marge') {
                this.$emit('marge',this.selected);
                this.selected=[];
            }
        },



        item_edit(e) {
            this.selectIndex = this.items.indexOf(e);
// alert(JSON.stringify(this.selectIndex));
            this.$emit('edit',{rowNo:this.selectIndex,value:e});                   
        },
        item_add() {
            // this.selectIndex = this.items.indexOf(e);
// alert(JSON.stringify(e));
            this.$emit('add');                   
        },
        item_copy(e) {
            var newItem = { ...e };
            const rowNo = this.items.indexOf(e);
            // this.items.splice(rowNo,0,newItem);
            this.$emit('copy', {val:e,rowNo:rowNo+1});
            // const rowNo = this.items.indexOf(e.item);
            // this.$emit('select_TableRow',{rowNo:rowNo,value:e.item,isSelected:e.value});
        },
        create_account(e) {
            const rowNo = this.items.indexOf(e);
            this.$emit('create_account', {'rowNo':rowNo,'value':e});
        },
        edit_account(e) {
            const rowNo = this.items.indexOf(e);
            this.$emit('edit_account', {'rowNo':rowNo,'value':e});
        }
    },
    mounted() {
        window.addEventListener('resize', this.getWindowSize);
        this.getWindowSize();
    },
  }
</script>

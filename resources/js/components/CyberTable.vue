<template>
    <v-container fluid>
        <div v-if="visible_subCommand" class="m-0 pl-3">
            <!-- <v-icon v-if="visible_group" class="m-0 p-0 mr-3" dense @click.stop="item_group()">
                mdi-pencil-box-multiple-outline
            </v-icon>
            <v-icon v-if="visible_calc" class="m-0 p-0 mr-3" dense @click.stop="item_group()">
                mdi-pcalculator
            </v-icon> -->
            <!-- <v-btn v-if="visible_group" class="m-0 p-0" depressed small fab icon @click="item_group()"> -->
            <v-btn v-if="visible_group" icon @click="group()" >
                <v-icon class="mr-2" dense >
                    mdi-pencil-box-multiple-outline
                </v-icon>
            </v-btn>
            <v-btn v-if="visible_calc" icon @click="calc()">
                <v-icon class="mr-2" dense >
                    mdi-calculator
                </v-icon>
            </v-btn>
            <v-menu v-if="visible_print" offset-y  >
                <template v-slot:activator="{ on, attrs }">
                    <v-icon class="mr-2" dense v-bind="attrs" v-on="on">mdi-printer</v-icon>
                </template>
                <v-list>
                    <span v-for="(item, index) in printItems" :key="index">
                        <v-divider v-if="item.name=='divider'"></v-divider>
                        <v-list-item v-else min-height dense link @click="print(item.name,selected)">
                            <v-icon left>{{ item.icon }}</v-icon>{{ item.title }}
                        </v-list-item>
                    </span>
                </v-list>
            </v-menu>
        </div>
        <table class="table table-striped table-hover ml-2">
            <thead>
                <tr class="p-0 m-0">
                    <th v-if="show_select">
                        <v-checkbox
                            class="m-0 p-0"
                            v-model="isSelectAll"
                            :indeterminate="isIndeterminate"
                            :disabled="disabled"
                            hide-details
                            dense
                            color="glay"
                            @click.stop="checkedAll(isSelectAll)"
                        />
                    </th>
                    <th v-else></th>
                    <th>No</th>
                    <th v-for="(header, index) in headers" draggable :key="index" @click="reOrder(index)">
                        {{ header.label }}
                    </th>
                    <th style="width: 30px">
                        <div class="d-flex flex-row">
                            <span v-if="show_del" class="text-right" style="width: 30px">
                                <v-icon class="mr-2" @click="undoList()" :disabled="disabled" dense>mdi-undo </v-icon>
                            </span>
                            <span v-if="show_add" class="text-right" style="width: 30px">
                                <v-icon class="mr-2" @click="add()" :disabled="disabled" dense>mdi-pencil-plus</v-icon>
                            </span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <!-- 行番号を算出するためのロジック  -->
                <span v-show="false">{{ rowNo[0]=0 }}</span>
                <span v-show="false">{{ rowNo[1]=0 }}</span>
                <!--  -->
                <tr v-for="(detail, index) in details" :key="detail.id"
                    draggable
                    @click="clickRow({rowNo:index, value:detail})"
                    @dragstart="dragList($event, index)"
                    @drop="dropList($event, index)"
                    @dragover.prevent
                    @dragenter.prevent
                >
                    <!-- 行番号を算出するためのロジック  -->
                    <span v-show="false">
                        <span>{{ ++rowNo[0] }}</span>
                        <span v-if="detail.isLabel==1" />
                        <span v-else>{{ ++rowNo[1] }}</span>
                        <!-- <span v-if="index==0">{{ rowNo[2] = rowNo[0] }}</span> -->
                    </span>
                    <!--  -->
                    <td v-if="show_select" style="width: 30px">
                        <v-checkbox
                            class="m-0 p-0"
                            v-model="selected[index]"
                            hide-details
                            dense
                            color="glay"
                            :disabled="disabled"
                            @click.stop="checked({selected:selected[index],index:index,item:detail})"
                        >
                        </v-checkbox>
                    </td>
                    <td v-else style="width: 10px"></td>
                    <td v-if="detail.isLabel==1" />
                    <td v-else>{{ rowNo[1] }}</td>
                    <td v-for="(header, id)  in headers" :key="id">
                        <span v-if="detail.isLabel==1">
                            <span v-if="header.isLabelItem==1">{{ detail[header.name] }}</span>
                            <span v-else />
                        </span>
                        <span v-else>
                            <span v-if="Array.isArray(header.name)">
                                <span v-for="(s_name,id2) in header.name" :key="id2">
                                    {{ detail[s_name] }}
                                </span>
                            </span>
                            <span v-else>
                                {{ detail[header.name] }}
                            </span>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex flex-row">
                            <span v-if="show_del" class="text-right" >
                                <v-icon @click.stop="del(index)" :disabled="disabled">mdi-delete-outline</v-icon>
                            </span>
                            <span v-if="show_copy" class="text-right" >
                                <!-- <v-icon right v-bind:detail="detail" @click.stop="copy(detail)">mdi-pencil-box-multiple</v-icon> -->
                                <v-icon dense right v-bind:detail="detail" @click.stop="copy(detail)" :disabled="disabled">mdi-content-copy</v-icon>
                            </span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </v-container>
</template>
<script>
  export default {
    props: ["headers","details", "show_select","show_add","show_del","show_copy",'visible_group','visible_calc','visible_print','printItems','disabled'],
    data: function () {
        return {
            // show_select: true,
            // show_add: true,
            rowNo: [0,0,0],
            itams:[],
            undo:[],
            selected:[],
            // selectItems:[],
            isSelectAll: false,
            visible_subCommand:false,
            selectIndex:-1,
            isIndeterminate:false,
        }
    },
    computed: {
        selectAll: function(e) {
            // return this.inner_height-187;
        }
    },
    watch: {
        'selected': function(vals) {
// alert(JSON.stringify(vals))
            if (vals.some(val => val==true)) {
                this.visible_subCommand = true
                if (vals.some(val => val==false)) {
                    this.isIndeterminate = true
                } else {
                    this.isSelectAll = true;
                    this.isIndeterminate = false
                }
            } else {
                this.isSelectAll = false;
                this.isIndeterminate = false
                this.visible_subCommand = false
            }
            // vals.some(val => this.visible_subCommand = val==true);
            // vals.some(val => this.isIndeterminate = val==true);
        },
    },
    mounted() {

// alert(JSON.stringify(this.details));
      // let items = [];
      // items.push({name:"name",label:"名称"});
      // items.push({name:"val1",label:"値１"});
      // items.push({name:"val2",label:"値２"});
      // this.headers = items;
      // let aa = [];
      // for (let i = 0; i < 3; i++ ) {
      //   aa.push({id:i,name:"aaa",val1:"123",val2:"456"});
      // }
      // this.details = aa;
    },
    beforeMount() {
    },
    created () {
        this.undo=[];
        this.selected=[];
    },
    methods: {
        reOrder(e) {
// alert(JSON.stringify(this.headers[e].name))
//             let sort_target = this.headers[e].name
//             let aa = this.details
//             // this.details.sort((a,b) => a[sort_target]-b[sort_target]);
//             this.details.sort((a,b) => b[sort_target]-a[sort_target]);
// alert(JSON.stringify(this.details))
        },
        clickRow(e) {
// alert(JSON.stringify(this.disabled))
            if (!this.disabled) {
                this.selectIndex = e.rowNo;
                this.$emit('click_TableRow',e)
            }
        },
        getSelectTableRows() {
            let selectRows = [];
// alert(JSON.stringify(this.details))
            for (let i = 0; i < this.details.length; i++) {
                if (this.selected[i]) {
                    selectRows.push(this.details[i]);
                    // selectRows.push(this.details[i]);
                }
            }
            return selectRows;
        },
        checked(e) {
            // this.selected[1]
//             if (e.selected) {
//                 this.selected.push(e.item.id);
//             } else {
//                 this.selected.splice(e.index , 1);
//             }
// alert(JSON.stringify(this.selected))
        },
        setSelectAll(e) {
// alert(JSON.stringify(e))
            this.isSelectAll = e.isSelected;
            this.selected=[];
            for (let i = 0; i<e.count; i++) {
                this.selected.push(e.isSelected)
            }
// alert(JSON.stringify(e))
        },
        checkedAll(isChecked) {
// alert(JSON.stringify(this.details[0]))
            // this.isSelectAll = e.isSelected;
            this.selected=[];
            for (let i = 0; i < this.details.length; i++) {
// alert(JSON.stringify(this.details[i].status))
                // if (this.details[i].status == 1) {
                    this.selected[i] = isChecked;
                // }
            }
        },
        add() {
            this.$emit('add');
        },
        del(index) {
            this.undo.unshift({row:index,item:this.details[index]});
            this.details.splice(index,1);
        },
        undoList() {
            if (this.undo.length>0) {
                this.details.splice(this.undo[0].row,0,this.undo[0].item);
                this.undo.shift();
            }
        },
        copy(e) {
            // var newDetail = { ...e };
            // this.details.push(newDetail);
            // this.$emit('copy',e)
            const rowNo = this.details.indexOf(e);
            this.$emit('copy', {val:e,rowNo:rowNo+1});
        },
        change(e) {
// alert(JSON.stringify(e));
        },
        remove() {
            this.del(this.selectIndex)
        },
        group() {
// alert(JSON.stringify(this.selected));
            this.$emit('group',this.selected)
            // this.isSelectAll = false
            // this.selected=[]
            // this.visible_subCommand = false
        },
        calc() {
            this.$emit('calc',this.getSelectTableRows())
            // this.isSelectAll = false
            // this.selected=[]
            // this.visible_subCommand = falseß
        },
        print(name,selected) {
            let items = [];
            for (let i=0; i < this.selected.length; i++) {
                if (this.selected[i]) {
                    items.push(this.details[i])
                }
            }
            this.$emit('print',{ name:name, items:items });
        },
        // テーブル行のドラッグ＆ドロップ   -----------------------------
        dragList(event, dragIndex){
            event.dataTransfer.effectAllowed = 'move'
            event.dataTransfer.dropEffect = 'move'
            event.dataTransfer.setData('drag-index',dragIndex)
        },
        dropList(event, dropIndex){
            var dragIndex = event.dataTransfer.getData('drag-index')
            var deleteList = this.details.splice(dragIndex , 1);
            this.details.splice(dropIndex, 0, deleteList[0])
            let orderNo = 0;
            this.details.forEach(detail => {
                detail.orderNo = ++orderNo;
            });
            this.$emit('reOrder',this.details);
        },
    },

}

</script>

<template>
	<div>
		<CyberDataTable
            ref="CyberDataTable"
            :filterItems="filterItems"
            :commandItems="commandItems"
            :printItems="printItems"
            :headers="headerItems"
            :items="tableItems"
            :searchText="searchText"
            :show_select="false"
            :visible_group="true"
            :visible_print="false"
            :visible_add="true"
            :visible_copy="true"
            @search="search"
            @filter="filter"
            @command="command"
            @print="print"
            @click_TableRow="click_TableRow"
            @add="add"
            @copy="copy"
            @del="del"
		>
            <template v-slot:title>商品一覧</template>
        </CyberDataTable>
		<!-- 編集ダイアログ -->
		<CyberDialog ref="EditDialog" >
				<template v-slot:caption>{{caption}}</template>
				<template v-slot:main>
                    <!-- タスク情報 -->
                    <Product :values="product" :mode="mode" @enter="enter" @del="del" />
				</template>
		</CyberDialog>
	</div>
</template>
<script>
	import CyberDataTable from "../CyberDataTable";
	import CyberDialog from "../CyberDialog";
	import Product from "./Product";
	export default {
		components: {
			CyberDataTable,
			CyberDialog,
			Product,
		},
		props: ["dessertItems"],
		data: function () {
			return {
				printItems:[],
				searchText: "",
				selectItems: [],
				headerItems: [
					{ text: '品番', value: 'code', groupable:false },
					{
						text: '名称',
						align: 'start',
						sortable: true,
						value: 'name',
					},
					{ text: '規格・詳細', value: 'breakdown' },
					{ text: '価格', value: 'cost', groupable:false},
					{ text: 'サイズ', value: 'size', groupable:false},
					{ text: '重量', value: 'weight', groupable:false},
					{ text: '', value: 'actions', sortable: false, align: 'right'  },
					// { value: 'data-table-expand', filterable : false , sortable: false, groupable:false}
				],
				tableItems: [],
				// フィルターアイテム
				filterItems: [],
				//
				subjectDialog:false,
				// コマンドアイテム
				commandItems: [
					{ title: '新規' ,name:'create'},
					{ title: '一括' ,name:'groupUpdate' },
				],
				// 印刷アイテム
				PrintItems: [
					{ title: '商品一覧',name:'productsList' },
				],
                product:[],
                selectTableRowNo:-1,
                caption:"",
				mode:"edit",
            }
		},
		created() {
			this.tableItems = this.dessertItems;
		},
		mounted() {
		},
		methods: {
			read() {
				axios.get('/products')
					.then((res) => {
                        this.dessertItems = res.data;
				});
			},
			click_TableRow(e) {
                this.selectTableRowNo = e.rowNo;
                axios.get('/products/' + e.value.id).then((res) => {
                    if (res.data=="") {
                        alert("他の利用者により削除されています。")
						this.$refs.dataTable.refrash();
					} else {
                        this.product=res.data;
                        this.mode = "edit"
                        this.caption ="商品情報-編集"
                        this.$refs.EditDialog.open({
                            isFullscreen:false
                        });
					}
				});
			},
			create(val) {
                this.product=val;
                this.mode = "create"
                this.caption ="商品情報-新規"
				this.$refs.EditDialog.open({
                    isFullscreen:false
				});
			},
            add() {
                this.create({})
            },
            copy(e) {
                this.selectTableRowNo = e.rowNo;
                var newTask = { ...e.val };
                this.create(newTask);
            },
			del() {
                this.tableItems.splice(this.selectTableRowNo  , 1);
                this.$refs.EditDialog.close();
            },
			enter(e) {
				switch (e.mode) {
					case "add":
						// this.subject.push(e.value);
                        this.tableItems.splice(this.selectTableRowNo  , 0, e.value);
                        this.$refs.EditDialog.close();
						return;
					case "del":
						return;
					case "upd":
                        this.tableItems.splice(this.selectTableRowNo  , 1, e.value);
                        this.$refs.EditDialog.close();
						return;
				}
			},
            close() {
                this.$refs.EditDialog.close();
            },
            command(val){
                switch (val) {
                    case 'create':
                        this.selectTableRowNo = this.tableItems.length
                        this.create([]);
                        break;
                    case 'group':
                        // this.$refs.GroupDialog.open({
                        //     isFullscreen:true
                        // });
                        break;
                }
            },
			filter(){},
            search(){},			
			print(e) {
// alert(JSON.stringify(e));
            },
			edit() {},
			itemSelected() {},
			remove() {},
			group() {}  ,
		},
	}
</script>

<template>
	<div>
		<v-card-title class="m-0 p-0">
				<!-- 明細内容選択
			<v-spacer></v-spacer> -->
			<v-text-field
				v-model="search"
				append-icon="mdi-magnify"
				label="検索"
				single-line
				hide-details
				clearable
				class="m-0 p-0 py-2"
			/>
		</v-card-title>
		<v-divider></v-divider>
		<v-card-text  class="m-0 p-0">
			<v-data-table
				v-model="selected"
				:headers="headers"
				:items="products"
				:single-select="false"
				:search="search"
				item-key="id"
				show-select
				class="elevation-1"
			>
			<template v-slot:top></template>
			</v-data-table>
		</v-card-text>
		<v-row class="m-0 p-0">
			<v-col class="col-auto">
				<v-btn color="primary" @click="close()" ><v-icon left>mdi-close-thick</v-icon>中止</v-btn>
			</v-col>
			<v-col />
			<v-col class="col-auto">
				<v-btn color="primary" @click="enter()"><v-icon right>mdi-check-underline</v-icon>確定</v-btn>
			</v-col>
		</v-row>
	</div>
</template>
<script>
    export default {
			data: function () {
				return {
					search: '',
					selected: [],
					headers: [
						{
							text: '品名',
							align: 'start',
							sortable: false,
							value: 'name',
						},
						{ text: '型番', value: 'code' },
						{ text: '寸法', value: 'size' },
						{ text: '重量 (kg)', value: 'weight' },
						{ text: '単価 ', value: 'cost' },
						{ text: '数量 ', value: 'volume' },
					],
					products:[],
				}
			},
			mounted() {
				this.selected = [];
				this.getProducts();
			},
			methods: {
				close() {
					this.$emit('close');
				},
				enter() {
					this.$emit('enter',this.selected);
				},
				getProducts() {
					axios.get('/products').then((res) => {
						this.products = res.data;
					});
				},
			},
		}
</script>
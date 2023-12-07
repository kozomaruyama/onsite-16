<template>
	<div>
		<div id="scrolling-row">
			<component
				v-bind:is="previewPage"
				:selectItems="selectItems"
				:person="person"
			/>
		</div>
	</div>
</template>

<script>
    // 見積書
	import Quotation from "./Quotation";
    // 注文書
	import Order from "./Order";
    // 実行予算書
	import Budget from "./Budget";
    // 請求書
	import Invoice from "./Invoice2";
    // 明細書
	import Detail from "./Detail";
	// 
	import ManageSheet from "./ManageSheet";
	// 
	import Pay from "./Pay";
	export default {
		components: {
			Quotation,
			Order,
			Budget,
			Invoice,
			Detail,
			ManageSheet,
			Pay,
		},
		data: function () {
			return {
				previewPage: "",
				selectItems: [],
				person:[],
			}
		},
		methods: {
		},
		mounted() {
			this.previewPage = JSON.parse(localStorage.getItem('printName'));
			this.selectItems = JSON.parse(localStorage.getItem('selectItems'));
			this.person = JSON.parse(localStorage.getItem('person'));
// alert(JSON.stringify(this.previewPage))
		}
	}
</script>

<template>
	<div class="sheets p-0 m-3">
		<div class="row no-printing">
			<div class="col-auto">
                <v-menu
                    ref="menu"
                    v-model="menu"
                    :close-on-content-click="false"
                    :return-value.sync="printDate"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            v-model="printDate"
                            label="発行日付"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                        ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="printDate"
                        no-title
                        scrollable
                        locale="ja"
                        @input="changeDate"
                    >
                    </v-date-picker>
                </v-menu>
            </div>
			<div class="col-2">
                <v-text-field v-model="clerk" label="担当" @change="changeClerk"></v-text-field>
            </div>
			<div class="col"></div>
			<div class="col-auto">
                <v-btn fab text small color="grey darken-2" @click="dounload">
                    <v-icon>mdi-microsoft-excel</v-icon>
                </v-btn>            
                <v-btn class="mx-2" depressed small fab @click="handlePrint">
                    <v-icon>mdi-printer</v-icon>
                </v-btn>
			</div>
		</div>
		<v-divider class="no-printing"/>
		<div class="printPages">
			<slot></slot>
		</div>
	</div>
</template>

<!-- <script lang="javascript" src="xlsx.bundle.js"></script> -->
<script>
import xlsx from "xlsx-js-style";
export default {
	props: ["PaperOrientation","fileName","clerk"],
    data () {
        return {
            printDate: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
            menu: false,
        }
    },
	methods: {
		handlePrint() {
			window.print()
		},
        changeDate() {
            this.menu =false;
            this.$refs.menu.save(this.printDate)
            this.$emit('changeDate',this.printDate);
        },
        changeClerk() {
            this.$emit('changeClerk',this.clerk);
        },
        dounload() {           
            const table = document.getElementById("print_master");
            let workbook = xlsx.utils.table_to_book(table);
            const details = document.querySelectorAll('.print_details')
            details.forEach(function (print_detail, index) {
                let sheet = xlsx.utils.table_to_sheet(print_detail);
                let sheetName = 'sheet' & (index + 1)
                xlsx.utils.book_append_sheet(workbook, sheet, sheetName);
            });
            xlsx.writeFile(workbook, this.fileName + `.xlsx`);
        },
	},
}
</script>

<style lang="scss" scoped>
	@media print {
		.no-printing {
            display: none;
		}
	}

</style>

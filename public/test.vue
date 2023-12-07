<template>

</template>
<script>
    // import * as xlsx from 'xlsx';
    var xlsx = require('xlsx');
    // const book = xlsx.readFile('test.xlsx');
    const book = xlsx.readFile('./test.xlsx');
    // const book = XLSX.read('test',{ cellDates: true });
    alert(JSON.stringify(book))
    const sheetName = book.SheetNames[0];
    const worksheet = book.Sheets[sheetName];

    worksheet["A3"] = {
        v: "AAA",
        t: "s",
        w: "AAA"
    };
    alert(JSON.stringify(worksheet['A1'].v));

    XLSX.writeFile(book, 'out.xlsx');


	export default {
		data: function () {
			return {

            }
        },
		methods: {
            update(){
                // XLSX.writeFile(workbook, 'out.xlsx');
                // const book = XLSX.readFile('test.xlsx');

            },
		},
	}
</script>

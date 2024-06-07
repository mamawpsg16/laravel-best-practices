import {  utils, writeFile } from 'xlsx';
import { jsPDF } from "jspdf";
import autoTable from 'jspdf-autotable'

const exporter = {
    toXlsx(data, headers, sheetName = '', exportName = 'export'){
        /* generate worksheet and workbook */
        const worksheet = utils.json_to_sheet(data);
        const workbook = utils.book_new();
        utils.book_append_sheet(workbook, worksheet, sheetName);

        /* fix headers */
        utils.sheet_add_aoa(worksheet, [[...headers]], { origin: "A1" });

        console.log(worksheet,'worksheet');

        /* create an XLSX file and try to save to exportName.xlsx */
        writeFile(workbook, `${exportName}.xlsx`, { compression: true, FS:'\t' });
    },

    toCsv(data, headers, sheetName = '', exportName = 'export'){
        /* generate worksheet and workbook */
        const worksheet = utils.json_to_sheet(data);
        const workbook = utils.book_new();
        utils.book_append_sheet(workbook, worksheet, sheetName);

        /* fix headers */
        utils.sheet_add_aoa(worksheet, [[...headers]], { origin: "A1" });

        /* create an XLSX file and try to save to exportName.xlsx */
        writeFile(workbook, `${exportName}.csv`, { compression: true, bookType:'csv' });
    },

    toPdf(data, headers,){
        const pdfData =  data.map(obj => Object.values(obj));
        const doc = new jsPDF()

        autoTable(doc, {
            head: [[...headers]],
            body: [...pdfData],
        })

        doc.save('table.pdf')
    
    }
}

export default exporter;
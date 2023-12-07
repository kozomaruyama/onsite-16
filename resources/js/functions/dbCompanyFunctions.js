// import { vue } from "laravel-mix";
// import Vue from "vue";
// import {reactive} from 'vue'

export default function (){
    const getCompany = (id, callback) => {
        axios.get('/company/' + id).then((res) => {
// alert(JSON.stringify(res.data))        
            callback(res.data);
        });
    };
    const updateCompany = (company) => {
        var company = Object.assign({},company)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応         
        axios.put('/company/' + company.id, company).then((res) => {        
        });
    };
    return {
        getCompany,
        updateCompany,
    };
}

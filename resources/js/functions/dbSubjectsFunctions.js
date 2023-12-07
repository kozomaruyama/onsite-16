export default function (){
    const addSubject = (subject) => {
alert(JSON.stringify(subject));
    };
    const updateSubject = (subject) => {
        var subject = Object.assign({},subject)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
        // axios.post('/subject/update', subject).then((res) => {
        axios.put('/subject/' + subject.id, subject).then((res) => {
alert(JSON.stringify(res));
return true;
        });
    };
    return {
        addSubject,
        updateSubject,
    };
}

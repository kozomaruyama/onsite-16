export default function (){
    const addTask = (task) => {
        delete task.id
        var taskObject = Object.assign({},task)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応
alert(JSON.stringify(taskObject));  
        axios.post('/api/tasks', taskObject).then((res) => {         
        });
    };
    const updateTask = (task) => {
        var taskObject = Object.assign({},task)    // subjectObjectへの変換はaxiosのPOSTでVueのobjectが空になってしまう問題への対応   
        axios.put('/api/tasks/' + taskObject.id, taskObject).then((res) => {    
        });
    };
    // alert(JSON.stringify(res));
    const delTask = (task) => {
        if (window.confirm('削除して宜しいですか？')) {
            axios.delete('/api/tasks/' + task.id).then((res) => {          
            });
            return true;
        } else {
            return false;
        }
    };
    return {
        addTask,
        updateTask,
        delTask,
    };
}
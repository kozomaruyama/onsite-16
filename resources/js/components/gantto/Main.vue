<template>
    <Gantto
        :categories="categories"
        :tasks="tasks"
        @clickCategory="clickCategory"
        @toggleCategory="toggleCategory"
        @unToggleCategory="unToggleCategory"
        @editTask="editTask"
        @editCategory="editCategory"
    />
</template>

<script>
import moment from 'moment';
import { mapState, mapGetters } from 'vuex'
import CyberMain from "../CyberMain";
import CyberSideMenu from "../CyberSideMenu";
import Gantto from "../CyberGantto";
import File from "../FileManeger";
export default {
    components: {
        CyberMain,
        CyberSideMenu,
        Gantto,
        File,
    },
    data: function () {
        return {
            inner_width:0,
            inner_height:0,
            expandOnHover:true,
        }
    },
    computed: {
        ...mapGetters([ 'getSubjectClasses' ]),
        ...mapGetters([ 'getProcessNames' ]),
        display_height: function() {
            return this.inner_height-60;
        }
    },
    created() {
        this.subjectClasses = this.$store.getters.getSubjectClasses;
        this.processNames = this.$store.getters.getProcessNames;
    },
    data(){
        return {
            isNaviVisible:true,
            filterItems:[],
            commandItems: [
                { title: '締処理' ,name:'closing', icon:'mdi-calculator-variant-outline'},
                { title: '一　括' ,name:'group', icon:'mdi-pencil-box-multiple-outline' },
            ],
            // 印刷アイテム
            printItems: [
                { title: '請求書',name:'invoice', icon:'mdi-receipt-outline'},
            ],

            eventFilters: [],
            subjectClasses: [],
            subjectClasFilters: [],
            processNameFilters:[],
            processNames: [],
            searchText: "",
            CommandListLabel:
            {
                icon: "mdi-printer",
                text: "印刷"
            },
            CommandListItems:["見積書","発注書"],
            subjects:[],
            subject:[],
            categories: [],
            tasks: [],
            task:[],
        }
    },
    methods: {
        create() {
        },
        filter() {
        },
        doPrint() {
        },
        setcategories() {
            var param = "";
            var categories = []
            axios.post('/subject/gantto', { filter: this.eventFilters }).then((res) => {
                this.subjects = res.data;
                this.subjects.forEach(function(subject){
                    categories.push({
                        id: subject.id,
                        name: subject.name,
                        start_date: moment(subject.start_date).format('YY-MM-DD'),
                        end_date: moment(subject.end_date).format('YY-MM-DD'),
                        percentage: subject.percentage,
                        value: subject,
                        collapsed: true,
                    });
                });
                this.categories = categories;
            })
        },
        clickCategory(category) {
        },
        toggleCategory(id) {
            var items = this.tasks;
            axios.get('/tasks/subject/' + id).then((res) => {
                this.tasks = res.data;
                this.tasks.forEach(function(task){
                    if (task.isLabel==false) {
                        items.push({
                            id: task.id,
                            category_id: id,
                            name: task.name,
                            start_date: moment(task.start_date).format('YY-MM-DD'),
                            end_date: moment(task.end_date).format('YY-MM-DD'),
                            incharge_user: task.manager_name,
                            percentage: task.percentage,
                        });
                    }
                });
                this.tasks = items;
            });
        },
        unToggleCategory(id) {
            const items = this.tasks.filter(item => item.category_id !== id);
            this.tasks = items;
        },
        editTask(task) {
            this.task = task;
            this.$refs.DialogTask.open();
        },
        editCategory(val) {
            this.subject = val;
            this.$refs.EditSubject.open();
        },
        expand_navi() {
            this.expandOnHover = !this.expandOnHover
        },
        getWindowSize() {
            this.inner_width = window.innerWidth;
            this.inner_height = window.innerHeight;
        },
    },
    mounted() {
        window.addEventListener('resize', this.getWindowSize);
        this.getWindowSize();
        this.setcategories();
    }
}
</script>

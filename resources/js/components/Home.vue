<template>
    <div>
            <video style="width: calc(100vw);" class="inner_content m-0 p-0" autoplay loop muted playsinline poster="http://www.tc888.co.jp/wp-content/uploads/2017/01/smart_top1920_1080.jpg">
                <source src="http://www.tc888.co.jp/wp-content/uploads/2017/01/Building3-1.mp4" type='video/mp4'>
            </video>
            <!-- <div class="overlay">
                <a href="https://lin.ee/0TToEun"><img src="https://scdn.line-apps.com/n/line_add_friends/btn/ja.png" alt="友だち追加" style="width: 10vw;height: auto;" border="0"></a>
            </div> -->
        <MsgBox ref="AlartBox" @enter="" @opened="">
            <template v-slot:caption></template>
            <template v-slot:main>
                <v-container>
                    <v-row align-content="center" >
                        <v-col align="center">
                            <div class="text-h6">削除してよろしいですか？</div>
                        </v-col>
                    </v-row>
                </v-container>
            </template>			
        </MsgBox>
        <AlartBox ref="AlartBox" @opened="alartBox_opened">
			<template v-slot:main>
                <div class="text-h6">{{ alarmText }}</div>
			</template>			
		</AlartBox>
    </div>
</template>

<script>
    import { mapState, mapGetters } from 'vuex'
    import AlartBox from "./AlartBox";
    export default {
    components: {
        AlartBox,
      },         
    mounted() {
        this.getSysItems();
    },
    methods: {
        setSysItems() {
            this.$store.commit('setSysItems',  { sysItems:  this.sys_items } );
        },
        getSysItems() {
            axios.get('/sys_items').then((res) => {
                this.sys_items = res.data;
                this.setSysItems();
                let alarms=this.$store.getters.getAlarms; 
                for (let i=0;i<alarms.length;i++) {
                    const timestamp1 = new Date( this.alarms[i].start_time).getTime() - this.alarms[i].alarm_interval*60*1000;
                    const timestamp2 = new Date().getTime();
                    const seconds = timestamp1 - timestamp2
                    if (seconds>0) {
                        setTimeout((value) => {
                            this.alarmText=value.name
                            this.$refs.AlartBox.open()
                        }, seconds,alarms[i])
                    }
                }
            });
        },
    } ,
    computed: {
        appPage: function() {
            return this.$store.getters.getProcess;
        },
        ...mapState([ 'process' ]),
        ...mapGetters([ 'getProcess' ])
    },
}
</script>
<style lang="scss" scoped>
    // #home_container {
    //     width: calc(100vw);
    //     // width: calc(100vw);
    //     // height: calc(100vh - 50px);
    // }
    // .inner_content {
    //     width: calc(100vw);

    //     // height: calc(100vh);
    // }
    video#bgvid {
        position: fixed; right: 0; bottom: 0;
        min-width: 100%; min-height: 100%;
        width: auto; height: auto; z-index: -100;
        background-size: cover;
    }

</style>

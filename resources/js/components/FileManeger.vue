<template>
    <div>
        <v-container fluid class="">
            <!-- <p><v-file-input label="画像選択" v-model="files" multiple @change="getFileName" dense /></p> -->
            <p><v-file-input label="画像選択" v-model="images" multiple @change="getFileName" dense /></p>
            <v-row class="justify-start">
                <v-col class=" m-1 p-0 bg-primary col-auto" v-for="(file,index) in files" :key="index" >
                    <!-- <v-img max-width="50" :alt="alt" :src="file.path" :key="file" @click="click({file:file,index:index})" /> -->
                    <v-img max-width="50" :alt="alt" :src="file.path" :key="index" @click="click({file:file,index:index})" />
                </v-col>
            </v-row>
        </v-container>
        <!-- 画像ダイアログ -->
        <v-dialog v-model="imageDialog"	max-width="800">
            <v-card>
                <v-carousel hide-delimiters v-model="model">
                    <v-carousel-item v-for="(item,i) in items" :key="i">
                        <v-img :alt="alt" :src="item.src" />
                    </v-carousel-item>
                </v-carousel>

                <v-container>
                    <v-row>
                        <v-btn depressed small fab @click="del()">
                            <v-icon color="grey">mdi-delete-outline</v-icon>
                        </v-btn>
                        <v-col></v-col>
                        <v-btn depressed small fab @click="imageDialog=false">
                            <v-icon color="grey">mdi-close</v-icon>
                        </v-btn>
                    </v-row>

                </v-container>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    export default {
        props : ["image_class","link_id"],
        data: function(){
            return {
                images:[],
                images2:[],
                files: [],
                delIds: [],
                items: [],
                imageDialog:false,
                model:0,
                alt:"non",
                srcset:"/storage/IMG_5883.jpeg"
            }
        },
        computed: {
        },
        mounted() {
            this.fileDownload()
// alert(this.link_id)
        },
        methods: {
            click(e) {
                this.model=e.index;
                this.imageDialog=true;
            },
            fileDownload() {
                axios.get('/api/doc_files/filter/{ "class":'
                    + this.image_class + ', "link_id":' + this.link_id + '}').then((res) => {
                        this.files = res.data;
// alert(JSON.stringify(this.files))
                        Object.keys(this.files).forEach(key => this.items.push({src:this.files[key].path}));
                });
            },
            getFileName(e){
                let no = this.files.length + 1;
                for (let i = 0; i < this.images.length; i++) {
                    this.files.push({
                        id:-1,
                        class:this.image_class,
                        no:no++,
                        link_id:this.link_id,
                        path:URL.createObjectURL(this.images[i])
                    });
                    // alert(JSON.stringify(this.files[i].path))
                    this.items.push({src:URL.createObjectURL(this.images[i])})
                };
                this.images2 = this.images2.concat(this.images)
                this.images = []
            },
            fileUpload() {
                let formData = new FormData();
// alert(JSON.stringify(this.images2))
                this.images2.forEach((file, index) => {
                    formData.append('files[' + index + ']', file) // formDataに追加していく
                })
                formData.append('execClass',this.image_class);
                formData.append('link_id',this.link_id);
                formData.append('delIds',this.delIds);
// alert(JSON.stringify(formData))
                axios.post('/api/doc_files', formData).then((res) =>{
                });
            },
            del(){
                // if (window.confirm('削除して宜しいですか？')) {
                    let file = this.files[this.model]
                    if (file.id!=-1) {
                        this.delIds.push(file.id);
                    }
                    this.files.splice(this.model  , 1);
                    this.items.splice(this.model  , 1);
                    this.images2.splice(this.model  , 1);
                    this.imageDialog=false;
                // }
            },
        },
    }
</script>

<style>
.content{
    margin:5em;
}
</style>

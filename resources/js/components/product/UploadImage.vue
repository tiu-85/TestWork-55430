<template>
    <div>
        <label>Фото товара</label>
        <input type="file" v-on:change="onFileChange" class="form-control-file">
        <br>
        <div class="col-md-12">
            <div class="col-md-2">
                <img :src="image" class="img-responsive">
            </div>
        </div>
    </div>
</template>

<style scoped>
    img{
        max-height: 60px;
    }
</style>

<script>
    export default {
        name: 'UploadImage',
        data(){
            return {
                image: null
            }
        },
        methods: {
            onFileChange(e) {
                let files = e.target.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
                this.$emit( 'changeFile', files[0]);
            },
            createImage(file) {
                let reader = new FileReader(),
                    upFile = this;
                reader.onload = (e) => {
                    upFile.image = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }
</script>

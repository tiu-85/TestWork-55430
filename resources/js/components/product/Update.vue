<template>
    <div>
        <h3 class="text-center">Изменить товар</h3>

        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="onSubmit">
                    <div v-if="errors" class="bg-red-500 text-dark py-2 px-4 pr-0 font-bold mb-4">
                        <ul v-for="(v, k) in errors" :key="k">
                            <li><p v-for="error in v" :key="error" class="text-sm">
                                {{ error }}
                            </p></li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label>Артикул</label>
                        <input :disabled=btnDisabled type="text" class="form-control" v-model="product.sku">
                    </div>
                    <div class="form-group">
                        <label>Название</label>
                        <input :disabled=btnDisabled type="text" class="form-control" v-model="product.name">
                    </div>
                    <div class="form-group">
                        <label>Цена</label>
                        <input :disabled=btnDisabled type="text" class="form-control" v-model="product.price">
                    </div>
                    <div class="form-group">
                        <label>Описание</label>
                        <textarea :disabled=btnDisabled class="form-control" v-model="product.description"/>
                    </div>
                    <img :src="product.image" class="img-responsive" style="max-width: 525px">
                    <div class="form-group">
                        <image-upload @changeFile="onChangeFile"></image-upload>
                    </div>
                    <button :disabled=btnDisabled class="btn btn-primary" v-bind:class="{'is-loading': loading}">Изменить</button>
                    <router-link to="/"><el-button>Назад</el-button></router-link>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                product: {},
                image: null,
                errors: null,
                loading: false,
                btnDisabled: true
            }
        },
        created() {
            this.axios
                .get(`/api/products/${this.$route.params.id}`)
                .then((response) => {
                    this.product = response.data.data
                    this.btnDisabled = false
                })
                .catch(error => {
                    this.$message(error.message)
                    console.log(error)
                });
        },
        methods: {
            onSubmit() {
                this.loading = true
                this.updateProduct()
            },
            onChangeFile(image) {
                this.image = image
            },
            async updateProduct() {
                let formData = new FormData()
                formData.append('file', this.image)
                _.each(this.product, (value, key) => {
                    formData.append(key, value)
                })

                this.btnDisabled = true
                this.axios
                    .post(`/api/products/update/${this.$route.params.id}`, formData)
                    .then(response => {
                        this.$message('Товар успешно изменен')
                        this.$router.push({name: 'home'})
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors
                        console.log(error)
                    })
                    .finally(() => {
                        this.loading = false
                        this.btnDisabled = false
                    })
            }
        }
    }
</script>

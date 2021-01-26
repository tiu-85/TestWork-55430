<template>
    <div>
        <h3 class="text-center">Добавить товар</h3>

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
                        <input type="text" class="form-control" v-model="product.sku">
                    </div>
                    <div class="form-group">
                        <label>Название</label>
                        <input type="text" class="form-control" v-model="product.name">
                    </div>
                    <div class="form-group">
                        <label>Цена</label>
                        <input type="text" class="form-control" v-model="product.price">
                    </div>
                    <div class="form-group">
                        <label>Описание</label>
                        <textarea class="form-control" v-model="product.description"/>
                    </div>
                    <div class="form-group">
                        <image-upload @changeFile="onChangeFile"></image-upload>
                    </div>
                    <button class="btn btn-primary" v-bind:class="{'is-loading': loading}">Добавить</button>
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
                loading: false
            }
        },
        methods: {
            onSubmit() {
                this.loading = true
                this.addProduct()
            },
            onChangeFile(image) {
                this.image = image
            },
            async addProduct() {
                let formData = new FormData()
                formData.append('file', this.image)
                _.each(this.product, (value, key) => {
                    formData.append(key, value)
                })

                this.axios
                    .post('/api/products/create', formData)
                    .then(response => {
                        this.$message('Товар успешно добавлен')
                        this.$router.push({name: 'home'})
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors
                        console.log(error)
                    })
                    .finally(() => this.loading = false)
            }
        }
    }
</script>

<template>
    <div>
        <h3 class="text-center">Список товаров</h3><br/>

        <div style="margin-bottom: 10px">
            <el-row>
                <el-col :span="18">
                    <router-link to="/create"><el-button>Добавить</el-button></router-link>
                </el-col>
                <el-col :span="6">
                    <el-input placeholder="Поиск по артикулу" v-model="filters[0].value"></el-input>
                </el-col>
            </el-row>
        </div>

        <data-tables-server :data="products" :action-col="actionCol" :filters="filters" :total="total" :loading="loading" @query-change="loadData" :page-size="5" :pagination-props="{ pageSizes: [5, 10, 20, 50, 100]}">
            <el-table-column v-for="title in titles" :prop="title.prop" :label="title.label" :key="title.prop" sortable="custom"></el-table-column>
        </data-tables-server>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                products: [],
                total: 0,
                loading: false,
                qInfo: null,
                titles: [
                    {
                        prop: "sku",
                        label: "Артикул"
                    },
                    {
                        prop: "name",
                        label: "Название"
                    },
                    {
                        prop: "price",
                        label: "Цена"
                    },
                    {
                        prop: "description",
                        label: "Описание"
                    },
                    {
                        prop: "created",
                        label: "Добавлен"
                    },
                    {
                        prop: "updated",
                        label: "Обновлен"
                    }
                ],
                filters: [{
                    prop: 'sku',
                    value: ''
                }],
                actionCol: {
                    props: {
                        label: 'Действия',
                    },
                    buttons: [
                        {
                            props: {
                                icon: 'el-icon-edit'
                            },
                            handler: row => {
                                this.$router.push({name: 'update', params: {id: row.id}})
                            },
                        },
                        {
                            props: {
                                icon: 'el-icon-delete'
                            },
                            handler: row => {
                                if (confirm('Вы действительно хотите удалить выбранный товар?')) {
                                    let id = row.id
                                    this.axios
                                        .delete(`/api/products/${id}`)
                                        .then(response => {
                                            this.$message('Товар успешно удален')
                                            this.loadData(this.qInfo)
                                        });
                                }
                            },
                        }
                    ]
                },
            }
        },
        methods: {
            async loadData(queryInfo) {
                this.qInfo = queryInfo

                let queryString = new URLSearchParams();
                queryString.append('limit', queryInfo.pageSize);
                queryString.append('offset', (queryInfo.page-1)*queryInfo.pageSize);
                queryString.append('sort', queryInfo.sort.prop || '');
                queryString.append('order', queryInfo.sort.order || '');
                queryString.append('filter', queryInfo.filters[0].value)

                this.loading = true
                this.axios
                    .get('/api/products?' + queryString.toString())
                    .then(response => {
                        this.products = response.data.data;
                        this.total = response.data.total
                    }).catch(error => this.$message(error.message))
                    .finally(() => this.loading = false);
            }
        }
    }
</script>

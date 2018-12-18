<template>
    <div class="row">
        <div class="col-md-12">
            <div class="form-inline">
                <div class="links">
                    <button class="btn btn-outline-primary" @click="$router.push({ path: '/product/add' })">
                        Добавить
                    </button>
                </div>
                <div class="search-group col">
                    <div class="search-group__filter">
                        <input v-if="search.filter" class="form-control" v-model.lazy="search.keyword" v-debounce="500">

                        <select class="form-control" v-model="search.filter">
                            <option value="name">По наименованию</option>
                            <option value="cost">По стоимости</option>
                        </select>
                        <button class="btn btn-outline-danger" type="button" @click="resetFilter()">Очистить</button>
                    </div>
                </div>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Наименование</th>
                    <th>Стоимость</th>
                    <th>Дата выпуска</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(product, index) in products">
                    <td>{{ product.id }}</td>
                    <td>{{ product.name }}</td>
                    <td>{{ product.cost }} &#8381;</td>
                    <td>{{ product.manufacture_date }}</td>
                    <td>
                        <a @click="$router.push({ path: '/product/edit/' + product.id })">
                            <i class="text-success fa fa-gear"></i>
                        </a>
                        <a @click="remove(product.id)">
                            <i class="text-danger fa fa-ban"></i>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
            <paginate
                    v-model="pagination.page"
                    :page-count="pagination.last_page"
                    :container-class="'pagination'"
                    :page-class="'page-item'"
                    :page-link-class="'page-link'"
                    :prev-text="'След.'"
                    :prev-class="'page-item'"
                    :prev-link-class="'page-link'"
                    :next-text="'Пред.'"
                    :next-class="'page-item'"
                    :next-link-class="'page-link'"
                    :click-handler="switchPage">
            </paginate>
        </div>
    </div>
</template>

<script>
    import debounce from 'v-debounce';
    export default {
        directives: { debounce },
        data() {
            return {
                products: [],
                pagination: {
                    last_page: 1,
                    page: 1
                },

                search: {
                    filter: '',
                    keyword: '',
                    is_search: false,
                },
            }
        },
        methods: {
            switchPage(page) {
                this.pagination.page = page;
                if (! this.search.is_search) {
                    this.loadData();
                } else {
                    this.searchData();
                }

            },

            resetFilter() {
                this.search = {
                    filter: '',
                        keyword: '',
                    is_search: false,
                };
                this.switchPage(1);
            },

            async remove(id) {
                let response = await axios.post('/products/delete/' + id);
                if (response.data.status === 'success') {
                    this.$swal('Успешно!', 'Запись о продукте успешно удалена!', 'success');
                    this.loadData();
                } else {
                    this.$swal('Ошибка!', 'Не удалось удалить запись о продукте!', 'danger');
                }
            },

            async loadData() {
                let response = await axios.get('/products', { params: { 'page': this.pagination.page } });
                if (response.data.status == 'success') {
                    this.products = response.data.products.data;
                    this.pagination.last_page = response.data.products.last_page;
                } else {
                    this.$swal('Ошибка!', 'Произошла ошибка. Повторите позднее.', 'danger');
                }
            },

            async searchData() {
                let response = await axios.get('/products/search/', { params: { keyword: this.search.keyword, filter: this.search.filter, page: this.pagination.page } });
                if (! response.status) throw response;
                if (response.data.status === 'success') {
                    this.products = response.data.products.data;
                    this.pagination.last_page = response.data.products.last_page;
                } else if (response.data.status === 'error') {
                    this.$swal('Ошибка!', response.data.msg, 'error');
                    return false;
                }

            },
        },

        watch: {
            'search.keyword': function (after, before) {
                if (after === '') {
                    return false;
                }
                this.search.is_search = true;
                this.switchPage(1);
            }
        },

        mounted() {
            this.loadData();
        }
    }
</script>

<style scoped>

</style>
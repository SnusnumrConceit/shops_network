<template>
    <div class="row">
        <div class="col-md-12">
            <div class="form-inline">
                <div class="links">
                    <button class="btn btn-outline-primary" @click="$router.push({ path: '/provider/add' })">
                        Добавить
                    </button>
                </div>
                <div class="search-group col">
                    <div class="search-group__filter">
                        <input v-if="search.filter" class="form-control" v-model.lazy="search.keyword" v-debounce="500">

                        <select class="form-control" v-model="search.filter">
                            <option value="name">По наименованию</option>
                            <option value="address">По адресу</option>
                            <option value="bank">По счёту</option>
                            <option value="phone">По номеру</option>
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
                    <th>Адрес</th>
                    <th>Банковский счёт</th>
                    <th>Конкатный номер</th>
                    <th>Продукты</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(provider, index) in providers">
                    <td>{{ provider.id }}</td>
                    <td>{{ provider.name }}</td>
                    <td>{{ provider.legal_address }}</td>
                    <td>{{ provider.bank_account }}</td>
                    <td>{{ provider.contact_phone }}</td>
                    <td>
                        <button class="btn btn-outline-success" v-if="provider.products.length" @click="showModal({ products: provider.products})">
                            Продукты
                        </button>
                    </td>
                    <td>
                        <a @click="$router.push({ path: '/provider/edit/' + provider.id })">
                            <i class="text-success fa fa-gear"></i>
                        </a>
                        <a @click="remove(provider.id)">
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
        <modal name="productsModal">
            <div class="modal-title">
                <h3>Продукты</h3>
            </div>
            <div class="modal-body">
                <ul class="products">
                    <li v-for="(product, key, index) in modal_products">
                        <router-link :to="'/product/edit/' + product[index].id">{{ product[index].name}}</router-link>{{ product[index].cost }} &#8381;</li>
                </ul>
            </div>
        </modal>
    </div>
</template>

<script>
    import debounce from 'v-debounce';
    export default {
        directives: { debounce },
        data() {
            return {
                providers: [],
                pagination: {
                    last_page: 1,
                    page: 1
                },

                modal_products: [],

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

            showModal(products) {
                this.modal_products = {...products};
              this.$modal.show('productsModal');
            },

            hideModal() {
                this.$modal.hide('productsModal');
            },

            async remove(id) {
                let response = await axios.post('/providers/delete/' + id);
                if (response.data.status === 'success') {
                    this.$swal('Успешно!', 'Запись о поставщике успешно удалена!', 'success');
                    this.loadData();
                } else {
                    this.$swal('Ошибка!', 'Не удалось удалить запись о поставщике!', 'danger');
                }
            },

            async loadData() {
                let response = await axios.get('/providers', { params: { 'page': this.pagination.page } });
                if (response.data.status == 'success') {
                    this.providers = response.data.providers.data;
                    this.pagination.last_page = response.data.providers.last_page;
                } else {
                    this.$swal('Ошибка!', 'Произошла ошибка. Повторите позднее.', 'danger');
                }
            },

            async searchData() {
                let response = await axios.get('/providers/search/', { params: { keyword: this.search.keyword, filter: this.search.filter, page: this.pagination.page } });
                if (! response.status) throw response;
                if (response.data.status === 'success') {
                    this.providers = response.data.providers.data;
                    this.pagination.last_page = response.data.providers.last_page;
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
    .modal-title {
        text-align: center;
    }
</style>

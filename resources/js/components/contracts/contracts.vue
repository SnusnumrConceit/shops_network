<template>
    <div class="row">
        <div class="col-md-12">
            <div class="form-inline">
                <div class="links">
                    <button class="btn btn-outline-primary" @click="$router.push({ path: '/contract/add' })">
                            Добавить
                    </button>
                </div>
                <div class="search-group col">
                    <div class="search-group__filter">
                        <select v-if="search.filter === 'provider'" class="form-control" v-model="search.keyword">
                            <option v-for="provider in providers" :value="provider.id">{{ provider.name }}</option>
                        </select>
                        <select v-else-if="search.filter === 'shop'" class="form-control" v-model="search.keyword">
                            <option v-for="shop in shops" :value="shop.id">{{ shop.name }}</option>
                        </select>
                        <select v-else-if="search.filter === 'author'" class="form-control" v-model="search.keyword">
                            <option v-for="author in authors" :value="author.id">{{ author.last_name }} {{ author.first_name }}</option>
                        </select>
                        <select class="form-control" v-model="search.filter">
                            <option value="provider">По заказчику</option>
                            <option value="shop">По магазину</option>
                            <option value="author">По заключителю</option>
                        </select>
                        <button class="btn btn-outline-danger" type="button" @click="resetFilter()">Очистить</button>
                    </div>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Поставщик</th>
                        <th>Заключитель</th>
                        <th>Магазин</th>
                        <th>Дата заключения</th>
                        <th>Срок выполнения</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(contract, index) in contracts">
                        <td>{{ contract.id }}</td>
                        <td>{{ isDelete(contract.provider) }}</td>
                        <td v-if="contract.author !== null">{{ contract.author.last_name + ' ' + contract.author.first_name }}</td>
                        <td v-else>не указан</td>
                        <td>{{ isDelete(contract.shop) }}</td>
                        <td>{{ contract.created_at }}</td>
                        <td>{{ contract.deadline }}</td>
                        <td>
                            <a @click="$router.push({ path: '/contract/edit/' + contract.id})">
                                <i class="text-success fa fa-gear">
                                </i>
                            </a>
                            <a @click="remove(contract.id)">
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
    export default {
        data() {
            return {
                contracts: [],
                pagination: {
                    last_page: 1,
                    page: 1
                },

                search: {
                    filter: '',
                    keyword: '',
                    is_search: false,
                },

                providers: {},
                authors: {},
                shops: {}
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
              }
              this.switchPage(1);
            },

            async remove(id) {
                let response = await axios.post('/contracts/delete/' + id);
                if (response.data.status === 'success') {
                    this.$swal('Успешно!', 'Запись о договоре успешно удалена!', 'success');
                    this.loadData();
                } else {
                    this.$swal('Ошибка!', 'Не удалось удалить запись о договоре!', 'error');
                }
            },

            async loadData() {
                let response = await axios.get('/contracts', { params: { 'page': this.pagination.page } });
                if (response.data.status == 'success') {
                    this.contracts = response.data.contracts.data;
                    this.pagination.last_page = response.data.contracts.last_page;
                } else {
                    this.$swal('Ошибка!', 'Произошла ошибка. Повторите позднее.', 'error');
                }
            },

            async searchData() {
              let response = await axios.get('/contracts/search/', { params: { keyword: this.search.keyword, filter: this.search.filter, page: this.pagination.page } });
              if (! response.status) throw response;
              if (response.data.status === 'success') {
                  this.contracts = response.data.contracts.data;
                  this.pagination.last_page = response.data.contracts.last_page;
              } else if (response.data.status === 'error') {
                  this.$swal('Ошибка!', response.data.msg, 'error');
                  return false;
              }

            },

            isDelete(obj) {
                return (obj === null) ? 'не указан' : obj.name;
            },

            async getExtendedData() {
                const response = await axios.get('/contracts/form_info');
                if (! response.status) throw response;
                this.providers = response.data.providers;
                this.authors = response.data.users;
                this.shops = response.data.shops;
            }
        },

        watch: {
          'search.keyword': function (after, before) {
              if (after === '') {
                  console.log(this.search.keyword);
                  return false;
              }
              this.search.is_search = true;
              this.switchPage(1);
          }
        },

        mounted() {
            this.loadData();
            this.getExtendedData();
        }
    }
</script>

<style scoped>

</style>
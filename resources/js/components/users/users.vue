<template>
    <div class="row">
        <div class="col-md-12">
            <div class="links">
                <button class="btn btn-outline-primary" @click="$router.push({ path: '/user/add' })">
                    Добавить
                </button>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Права</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(user, index) in users">
                    <td>{{ user.id }}</td>
                    <td>{{ user.first_name }}</td>
                    <td>{{ user.last_name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.phone }}</td>
                    <td>{{ user.role.name }}</td>
                    <td>
                        <a @click="$router.push({ path: '/user/edit/' + user.id })">
                            <i class="text-success fa fa-gear"></i>
                        </a>
                        <a @click="remove(user.id)">
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
    import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
                users: [],
                pagination: {
                    last_page: 1,
                    page: 1
                }
            }
        },
        computed: {
            ...mapGetters('auth', {
                user: 'getUser'
            })
        },

        methods: {
            switchPage(page) {
                this.pagination.page = page;
                this.loadData();
            },

            async remove(id) {
                const response = await axios.post('/users/delete/' + id);
                if (response.data.status === 'success') {
                    this.$swal('Успешно!', 'Запись о магазине успешно удалена!', 'success');
                    this.loadData();
                } else {
                    this.$swal('Ошибка!', 'Не удалось удалить запись о магазине!', 'danger');
                }
            },

            async loadData() {
                let response = await axios.get('/users', { params: { 'page': this.pagination.page } });
                if (response.data.status == 'success') {
                    this.users = response.data.users.data;
                    this.pagination.last_page = response.data.users.last_page;
                } else {
                    this.$swal('Ошибка!', 'Произошла ошибка. Повторите позднее.', 'danger');
                }
            }
        },

        mounted() {
            if (this.user.role_id !== 1) {
                this.$router.push('/contracts');
            }
            this.loadData();
        }
    }
</script>

<style scoped>

</style>
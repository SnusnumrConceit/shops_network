<template>
    <div class="row">
        <div class="col-md-6">
            <form action="">
                <div class="form-group">
                    <label for="">Поставщик</label>
                    <select v-model="contract.provider_id" class="form-control">
                        <option :value="provider.id" v-for="(provider, index) in providers">{{ provider.name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Заключитель</label>
                    <select v-model="contract.author_id" class="form-control">
                        <option :value="user.id" v-for="(user, index) in users">{{ user.last_name + ' ' + user.first_name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Магазин</label>
                    <select v-model="contract.shop_id" class="form-control">
                        <option :value="shop.id" v-for="(shop, index) in shops">{{ shop.name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Дата заключения</label>
                    <date-picker v-model="contract.created_at"
                                 :first-day-of-week="1"
                                 :format="'DD-MM-YYYY'"
                                 :lang="'ru'"></date-picker>
                </div>
                <div class="form-group">
                    <label for="">Срок выполнения</label>
                    <date-picker v-model="contract.deadline"
                                 :first-day-of-week="1"
                                 :format="'DD-MM-YYYY'"
                                 :lang="'ru'"></date-picker>
                </div>
                <div class="form-group">
                    <button class="btn btn-outline-success"
                            type="button"
                            v-if="$route.params.id"
                            @click="save()">
                        Сохранить
                    </button>
                    <button class="btn btn-outline-success"
                            type="button"
                            v-else
                            @click="save()">
                        Добавить
                    </button>
                    <button class="btn btn-outline-default"
                            type="button"
                            @click="$router.push({ path: '/contracts' })">
                        Отмена
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import DatePicker from 'vue2-datepicker';

    export default {
        components: { DatePicker },
        data() {
            return {
                contract: {},
                providers: {},
                users: {},
                shops: {}
            }
        },

        computed: {

        },

        methods: {
            validate() {
                if (this.contract.provider_id === undefined || this.contract.provider_id === null || ! this.contract.provider_id) {
                    this.$swal('Ошибка!', 'Вы не указали наименование поставщика', 'error');
                    return false;
                }
                if (this.contract.author_id === undefined || this.contract.author_id === null || ! this.contract.author_id) {
                    this.$swal('Ошибка!', 'Вы не указали заключителя', 'error');
                    return false;
                }
                if (this.contract.shop_id === undefined || this.contract.shop_id === null || ! this.contract.shop_id) {
                    this.$swal('Ошибка!', 'Вы не указали магазин', 'error');
                    return false;
                }
                if (this.contract.created_at === null || this.contract.created_at === undefined || ! this.contract.created_at) {
                    this.$swal('Ошибка!', 'Вы не указали дату заключения договора', 'error');
                    return false;
                }
                if (this.contract.deadline === null || this.contract.deadline === undefined || ! this.contract.deadline) {
                    this.$swal('Ошибка!', 'Вы не указали дату срока выполнения', 'error');
                    return false;
                }
                return true;

            },
            async save() {
                if (! this.validate()) return false;
                if (! this.contract.id) {
                   const response  = await axios.post('/contracts/create', { ...this.contract });
                    if (response.data.status === 'success') {
                        this.$swal('Успешно!', 'Договор успешно заключен.', 'success');
                        return this.$router.push('/contracts');
                        return true;
                    } else if (response.data.status === 'error'){
                        this.$swal('Ошибка!', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                } else {
                    const response = await axios.post('/contracts/update/' + this.contract.id, { ...this.contract});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно!', 'Договор успешно обновлён.', 'success');
                        return this.$router.push('/contracts');
                        return true;
                    } else if (response.data.status === 'error'){
                        this.$swal('Ошибка!', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                }
            },
            async loadData() {
                const response = await axios.get('/contracts/edit/' + this.$route.params.id);
                if (! response.status) throw response;
                this.contract = response.data.contract;
            },
            async getExtendedData() {
                const response = await axios.get('/contracts/form_info');
                if (! response.status) throw response;
                this.providers = response.data.providers;
                this.users = response.data.users;
                this.shops = response.data.shops;
            }
        },

        created() {
            if (this.$route.params.id) {
              this.loadData();
            }
            this.getExtendedData();
        }
    }
</script>

<style scoped>
    .row {
        justify-content: center;
    }
</style>
<template>
    <div class="row">
        <div class="col-md-6 col-offset-md-6">
            <form action="">
                <div class="form-group">
                    <label for="">Наименование</label>
                    <input type="text" class="form-control" v-model="shop.name">
                </div>
                <div class="form-group">
                    <label for="">Адрес</label>
                    <input type="text" class="form-control" v-model="shop.address">
                </div>
                <div class="form-group">
                    <label for="">Банковский счёт</label>
                    <input type="text" class="form-control" v-model="shop.bank_account">
                </div>
                <div class="form-group">
                    <label for="">Контактный телефон</label>
                    <input type="text" class="form-control" v-model="shop.contact_phone">
                </div>
                <div class="form-group">
                    <button class="btn btn-outline-success"
                            v-if="$route.params.shop_id"
                            @click="save()">
                        Сохранить
                    </button>
                    <button class="btn btn-outline-success"
                            v-else
                            @click="save()">
                        Добавить
                    </button>
                    <button class="btn btn-outline-default"
                            @click="$router.push({ path: '/shops' })">
                        Отмена
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                shop: {}
            }
        },

        methods: {
            validate() {
                if (this.shop.name === undefined || this.shop.name === null || ! this.shop.name) {
                    this.$swal('Ошибка!', 'Вы не указали наименование магазина', 'error');
                    return false;
                } 
                if (this.shop.address === undefined || this.shop.address === null || ! this.shop.address) {
                    this.$swal('Ошибка!', 'Вы не указали адрес магазина', 'error');
                    return false;
                }
                if (this.shop.bank_account === undefined || this.shop.bank_account === null || ! this.shop.bank_account) {
                    this.$swal('Ошибка!', 'Вы не указали бановские реквизиты магазина', 'error');
                    return false;
                }
                if (this.shop.contact_phone === undefined || this.shop.contact_phone === null || ! this.shop.contact_phone) {
                    this.$swal('Ошибка!', 'Вы не указали контактный телефон магазина', 'error');
                    return false;
                }
                return true;

            },
            async save() {
                if (! this.validate()) return false;
                if  (this.shop.id) {
                    const response = await axios.post('/shops/update/' + this.shop.id, { ...this.shop });
                    if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее', 'error');
                        return false;
                    }
                    else if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Сведения о магазине успешно обновлены', 'success');
                        this.$router.push('/shops');
                        return true;
                    }
                } else {
                    const response = await axios.post('/shops/create', { ...this.shop });
                    if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее', 'error');
                        return false;
                    }
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Сведения о магазине успешно добавлены', 'success');
                        this.$router.push('/shops');
                        return true;
                    }
                }
            },
            async loadData() {
                const response = await axios.get('/shops/edit/' + this.$route.params.id);
                if (! response.status) throw response;
                this.shop = response.data.shop;
            }
        },

        mounted() {
            if (this.$route.params.id) {
                this.loadData();
            }
        }
    }
</script>

<style scoped>
    .row {
        justify-content: center;
    }
</style>
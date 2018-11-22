<template>
    <div class="row">
        <div class="col-md-6">
            <form action="">
                <div class="form-group">
                    <label for="">Наименование</label>
                    <input type="text" class="form-control" v-model="provider.name">
                </div>
                <div class="form-group">
                    <label for="">Адрес</label>
                    <input type="text" class="form-control" v-model="provider.legal_address">
                </div>
                <div class="form-group">
                    <label for="">Банковский счёт</label>
                    <input type="text" class="form-control" v-model="provider.bank_account">
                </div>
                <div class="form-group">
                    <label for="">Контактный телефон</label>
                    <input type="text" class="form-control" v-model="provider.contact_phone">
                </div>
                <div class="form-group">
                    <label for="">Продукты</label>
                    <div class="form-group" v-if="provider.products.length"  v-for="(element, index) in provider.products" style="display: flex;">
                        <select class="form-control col-md-10" v-model="provider.products[index]">
                            <option :value="product.id" v-for="product in products">{{ product.name }}</option>
                        </select>
                        <label @click="removeProduct(index)" v-if="provider.products[index]" class="col-md-2" style="padding-top: 10px;">
                            <i class="text-danger fa fa-ban"></i>
                        </label>
                    </div>
                    <div class="form-group">
                    <span v-if="provider.products.length < products.length" @click="provider.products.push('')" class="text-success" style="cursor: pointer">+</span>
                    </div>
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
                    <button class="btn btn-outline-default" @click="$router.push({ path: '/providers' })">
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
                provider: {
                    products: []
                },
                products: []
            }
        },

        methods: {
            validate() {
                if (this.provider.name === undefined || this.provider.name === null || ! this.provider.name) {
                    this.$swal('Ошибка!', 'Вы не указали наименование поставщика', 'error');
                    return false;
                }
                if (this.provider.legal_address === undefined || this.provider.legal_address === null || ! this.provider.legal_address) {
                    this.$swal('Ошибка!', 'Вы не указали юридический адрес поставщика', 'error');
                    return false;
                }
                if (this.provider.bank_account === undefined || this.provider.bank_account === null || ! this.provider.bank_account) {
                    this.$swal('Ошибка!', 'Вы не указали бановские реквизиты поставщика', 'error');
                    return false;
                }
                if (this.provider.contact_phone === undefined || this.provider.contact_phone === null || ! this.provider.contact_phone) {
                    this.$swal('Ошибка!', 'Вы не указали контактный телефон поставщика', 'error');
                    return false;
                }
                return true;

            },

            async save() {
                if (! this.validate()) return false;
                if  (this.provider.id) {
                    const response = await axios.post('/providers/update/' + this.provider.id, {...this.provider} );
                    if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее', 'error');
                        return false;
                    }
                    else if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Сведения о поставщике успешно обновлены', 'success');
                        this.$router.push('/providers');
                        return true;
                    }
                } else {
                    const response = await axios.post('/providers/create', {...this.provider} );
                    if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее', 'error');
                        return false;
                    }
                    else if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Сведения о поставщике успешно добавлены', 'success');
                        this.$router.push('/providers');
                        return true;
                    }
                }
            },

            removeProduct(id) {
                this.provider.products.splice(id, 1);
            },

            async loadAdditionalData() {
                const response = await axios.get('/providers/form_info');
                if (! response.status ) throw response;
                this.products = response.data.products;
            },

            async loadData() {
                const response = await axios.get('/providers/edit/' + this.$route.params.id);
                if (! response.status) throw response;
                this.provider = response.data.provider;
                this.provider.products.forEach((product, index) => {
                    this.provider.products[index] = this.provider.products[index].id;
                });
            }
            // validate() {
            //     if (this.provider.name !== undefined && this.provider.name !== null && this.provider.name.length) {
            //
            //     } else {
            //
            //     }
            // }
        },

        mounted() {
            if (this.$route.params.id) {
                this.loadData();
            }
            this.loadAdditionalData();
        }
    }
</script>

<style scoped>
    .row {
        justify-content: center;
    }
</style>
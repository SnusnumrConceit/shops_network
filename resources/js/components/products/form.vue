<template>
    <div class="row">
        <div class="col-md-6">
            <form action="">
                <div class="form-group">
                    <label for="">Наименование</label>
                    <input type="text" class="form-control" v-model="product.name">
                </div>
                <div class="form-group">
                    <label for="">Стоимость</label>
                    <input type="text" class="form-control" v-model="product.cost">
                </div>
                <div class="form-group">
                    <label for="">Дата выпуска</label>
                    <date-picker v-model="product.manufacture_date"
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
                    <button class="btn btn-outline-default" @click="$router.push({ path: '/products'})">Отмена</button>
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
                product: {}
            }
        },

        methods: {
            validate() {
              if (this.product.name === null || this.product.name === undefined || ! this.product.name) {
                  this.$swal('Ошибка!', 'Вы не указали наименование товара', 'error');
                  return false;
              }
              if (this.product.cost === null || this.product.cost === undefined || ! this.product.cost || isNaN(parseFloat(this.product.cost))) {
                this.$swal('Ошибка!', 'Вы не указали стоимость товара', 'error');
                return false;
              }
              if (this.product.manufacture_date === null || this.product.manufacture_date === undefined || ! this.product.manufacture_date) {
                this.$swal('Ошибка!', 'Вы не указали дату производства товара', 'error');
                return false;
             }
             return true;
            },
            async save() {
                if (! this.validate()) return false;
                if (this.product.id) {
                    const response = await axios.post('/products/update/' + this.product.id, {...this.product});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Запись о продукте успешно обновлена', 'success');
                        this.$router.push('/products');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                } else {
                    const response = await axios.post('/products/create', {...this.product});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Запись о продукте успешно обновлена', 'success');
                        this.$router.push('/products');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                }
            },
            async loadData() {
              const response = await axios.get('/products/edit/' + this.$route.params.id);
              if (! response.status) throw response;
              this.product = response.data.product;
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
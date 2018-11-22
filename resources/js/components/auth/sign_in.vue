<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <!--<div class="card-header">{{ __('Login') }}</div>-->
                    <div class="card-header">Авторизация</div>

                    <div class="card-body">
                        <form>

                            <div class="form-group row">
                                <!--<label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>-->
                                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                                <div class="col-md-6">
                                    <input id="email"
                                           type="email"
                                           class="form-control"
                                           name="email"
                                           v-model="email"
                                           required
                                           placeholder="Введите email">

                                    <!--<span class="invalid-feedback" role="alert">-->
                                        <!--<strong>{{ $errors->first('email') }}</strong>-->
                                    <!--</span>-->

                                </div>
                            </div>

                            <div class="form-group row">
                                <!--<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>-->
                                <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>

                                <div class="col-md-6">
                                    <input id="password"
                                           type="password"
                                           class="form-control"
                                           v-model="pass"
                                           required
                                           placeholder="Введите пароль">

                                    <!--<span class="invalid-feedback" role="alert">-->
                                        <!--<strong>{{ $errors->first('password') }}</strong>-->
                                    <!--</span>-->

                                </div>
                            </div>

                            <!--<div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>-->

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-2">
                                    <button @click.prevent="signIn()" class="btn btn-primary">
                                        <!--{{ __('Login') }}-->
                                        Войти
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex';
    export default {
        data() {
            return {
                email: '',
                pass: ''
            }
        },

        methods: {
            ...mapActions('auth', {
                setUser: 'setUser',
                setToken: 'setToken'
            }),

            async signIn() {
                const response = await axios.post('/login', { email: this.email, password: this.pass});
                if  (response.data.msg !== undefined) {
                 this.$swal('Ошибка', response.data.msg, 'error');
                 return false;
                }
                const token = response.data.token;
                const user = response.data.user;
                localStorage.setItem('token', token);
                this.setToken();
                this.setUser(user);
                this.$router.push({ path: '/contracts'});
            }
        }
    }
</script>

<style scoped>

</style>
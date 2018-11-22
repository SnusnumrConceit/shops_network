<template>
    <div class="content full-height">
        <div class="row">
            <div class="collapse navbar-collapse col-md-12" id="navbarSupportedContent">
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav mr-auto pull-right" v-if="token && user">

                    <li class="nav-item">
                        <a>
                            {{ user.last_name + ' ' + user.first_name }}
                        </a>


                    </li>
                    <li class="nav-item">
                        <router-link to="/logout">
                                Выйти
                        </router-link>
                    </li>

                            <!--<form id="logout-form" action="/logout" method="POST" style="display: none;">-->
                                <!--@csrf-->
                            <!--</form>-->

                </ul>
            </div>
            <div class="authenticable col-md-12" v-if="token && user">
                <div class="links" style="float: left">
                    <div class="list-group">
                        <router-link to="/contracts" class="list-group-item list-group-item-action">Заказы</router-link>
                        <router-link to="/providers" class="list-group-item list-group-item-action">Поставщики</router-link>
                        <router-link to="/shops" class="list-group-item list-group-item-action">Магазины</router-link>
                        <router-link to="/products" class="list-group-item list-group-item-action">Продукция</router-link>
                        <router-link to="/users" class="list-group-item list-group-item-action" v-if="user.role === 1 || user.role_id === 1">Пользователи</router-link>
                    </div>
                </div>
                    <router-view></router-view>
            </div>
            <div class="main col-md-12 col-md-offset-4" v-else-if="! token || !user">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    export default {
        name: "app",

        computed: {
            ...mapGetters('auth', {
                token: 'isAuthenticated',
                user: 'getUser'
            }),
        },
        methods: {
            ...mapActions('auth', {
                setUser: 'setUser',
                setToken: 'setToken'
            }),
            async getUser() {
                const response = await axios.get('/auth');
                if (! response.status) throw response;
                if (response.data.user === null) {
                    this.$router.push({ path: '/login'});
                    console.log(response.data.user);
                    return false;
                }
                this.setUser(response.data.user);
            }
        },
        created() {
            if (! this.user.length) {
                this.getUser();
            }
        },
        mounted() {

        }
    }
</script>

<style scoped lang="scss">
    .collapse {
        display: block;
    }
    .navbar-nav {
        flex-direction: unset;
        .nav-item {
            padding: 0px 50px;
        }
    }
</style>
<template>
    <div></div>
</template>

<script>
    import { mapActions } from 'vuex';
    export default {
        methods: {
            ...mapActions('auth', {
                setToken: 'setToken',
               setUser: 'setUser'
            }),

            async logout() {
                const response = await axios.post('/logout');
                if (! response.status) throw response;
                localStorage.removeItem('token');
                this.setToken();
                this.setUser({});
                this.$router.push({ path: '/login' });
            }
        },

        created() {
            this.logout();
        }
    }
</script>

<style scoped>

</style>
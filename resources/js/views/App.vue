<template>
    <div id="app">
        <el-container>
            <el-header v-if="authenticated">
                <el-menu router :default-active="$route.path" :unique-opened="uniqueOpened" :menu-trigger="triggerType" mode="horizontal" >
                    <el-menu-item index="/config">Config</el-menu-item>
                    <el-menu-item index="/users">Users</el-menu-item>
                    <el-menu-item index="/posts">Posts</el-menu-item>
                    <el-menu-item index="/terms">Terms</el-menu-item>
                    <el-submenu v-if="user" index="user-right" class="submenu-profile">
                        <template slot="title">Hello, {{ user.username }}</template>
                        <el-menu-item index="">
                            <a @click="logout" href="javascript:;" class="block">Logout</a>
                        </el-menu-item>
                    </el-submenu>
                </el-menu>
            </el-header>
            <el-main>
                <router-view></router-view>
            </el-main>
        </el-container>
    </div>
</template>

<script>
export default {
    data() {
        return {
            uniqueOpened: true,
            triggerType: 'click',
            authenticated: auth.check(),
            user: auth.user
        }
    },

    mounted() {
        Event.$on('userLoggedIn', () => {
            this.authenticated = true;
            this.user = auth.user;
        });
        Event.$on('userLoggedOut', () => {
            this.authenticated = false;
            this.user = null;
        });
    },

    methods: {
        logout() {
            this.$alert('Sure?', '', {
                type: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                closeOnClickModal: true
            }).then(() => {
                auth.logout();
                this.$router.push('/login');
            }).catch(e => {});
        }
    }
}
</script>
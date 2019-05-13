<template>
    <div class="user-index">
        <el-row style="margin-bottom: 1.5rem;">
            <el-col :span="24">
                <el-button type="primary" @click="goToCreateUser">New User</el-button>
            </el-col>
        </el-row>
        <el-table
                border
                v-loading="loading"
                :data="users"
                style="width: 100%">
            <el-table-column prop="id" label="ID" width="60"></el-table-column>
            <el-table-column prop="username" label="Username"></el-table-column>
            <el-table-column prop="login_ip" label="Login IP"></el-table-column>
            <el-table-column prop="login_time" label="Login Time"></el-table-column>
            <el-table-column prop="created_at" label="Created At"></el-table-column>
            <el-table-column label="Actions" width="250">
                <template slot-scope="scope">
                    <el-button
                            size="mini"
                            @click="userEdit(scope.row.id)">Edit</el-button>
                    <el-button
                            size="mini"
                            @click="user2FAQRCode(scope.row.qr_code_2fa)">2FA</el-button>
                    <el-button
                            size="mini"
                            type="danger"
                            @click="userDelete(scope.row.id)">Delete</el-button>
                </template>
            </el-table-column>
        </el-table>

        <el-pagination
                background
                layout="prev, pager, next"
                :total="pagination.total"
                :current-page="pagination.current"
                @current-change="handleCurrentChange">
        </el-pagination>
    </div>
</template>

<script>
export default {
    mounted() {
        this.getUsers('users');
    },

    data() {
        return {
            users: [],
            loading: true,
            pagination: {
                total: 0,
                current: 1,
            }
        }
    },

    methods: {
        getUsers(url) {
            this.loading = true;

            api.call('get', url)
                .then(({data}) => {
                    this.users = [];

                    for (let user of data.data.users.data) {
                        this.users.push(user);
                    }

                    // Pagination
                    this.pagination.total = data.data.users.total;
                    this.pagination.current = data.data.users.current_page;

                    this.loading = false;
                })
                .catch(response => {
                    console.log(response);
                });
        },

        userEdit(id) {
            this.$router.push(`/users/edit/${id}`);
        },

        userDelete(id) {
            this.$alert('Sure?', '', {
                type: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                closeOnClickModal: true
            }).then(() => {
                api.call('delete', 'users/' + id)
                    .then(({data}) => {
                        this.$notify.success({
                            title: data.message,
                            duration: 2000
                        });
                        this.getUsers('users?page=' + this.pagination.current);
                    });
            }).catch(e => {});
        },

        user2FAQRCode(url) {
            if (!url.startsWith('http')) {
                this.$alert('2FA QRCode is empty.', '', {
                    closeOnClickModal: true
                }).catch(e => {});
            } else {
                this.$alert(`<img src="${url}">`, '2FA QRCode', {
                    center: true,
                    customClass: '2fa-qr-code-wrap',
                    closeOnClickModal: true,
                    showConfirmButton: false,
                    dangerouslyUseHTMLString: true
                }).catch(e => {});
            }
        },

        handleCurrentChange(current) {
            this.getUsers('users?page=' + current);
        },

        goToCreateUser() {
            this.$router.push('/users/create');
        }
    }
}
</script>

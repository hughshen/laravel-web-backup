<template>
    <div class="user-edit">
        <el-row style="margin-bottom: 1.5rem;">
            <el-col :span="24">
                <el-button type="primary" @click="backToList">Back To List</el-button>
            </el-col>
        </el-row>
        <el-form ref="user" :model="user" label-width="120px">
            <el-form-item
                    label="Username"
                    prop="username"
                    :rules="[{ required: true, message: 'The username field is required.' }]">
                <el-input
                        type="text"
                        v-model="user.username">
                </el-input>
            </el-form-item>
            <el-form-item label="Password">
                <el-input
                        type="text"
                        v-model="user.password">
                </el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="userUpdate">Update</el-button>
                <el-button @click="userReset">Reset</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getUser(this.$route.params.id);
        },

        data() {
            return {
                user: {
                    username: '',
                    password: '',
                }
            }
        },

        methods: {
            getUser(id) {
                api.call('get', 'users/' + id)
                    .then(({data}) => {
                        this.user = data.data.user;
                    })
                    .catch(response => {
                        console.log(response);
                        if (response.status == 404) {
                            this.backToList();
                        }
                    });
            },

            userUpdate() {
                this.$refs['user'].validate((valid) => {
                    if (!valid) {
                        return false;
                    }

                    let data = this.user;
                    data._method = 'put';

                    api.call('post', 'users/' + this.$route.params.id, data)
                        .then(({data}) => {
                            this.$alert(data.message, '', {
                                closeOnClickModal: true
                            }).catch(e => {});
                        })
                        .catch(response => {
                            console.log(response);
                        });
                });
            },

            userReset() {
                this.$refs['user'].resetFields();
            },

            backToList() {
                this.$router.push('/users');
            }
        }
    }
</script>

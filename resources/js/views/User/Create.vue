<template>
    <div class="user-create">
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
            <el-form-item
                    label="Password"
                    prop="password"
                    :rules="[{ required: true, message: 'The password field is required.' }]">
                <el-input
                        type="text"
                        v-model="user.password">
                </el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="userCreate">Create</el-button>
                <el-button @click="userReset">Reset</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: {
                    username: '',
                    password: '',
                }
            }
        },

        methods: {
            userCreate() {
                this.$refs['user'].validate((valid) => {
                    if (!valid) {
                        return false;
                    }

                    api.call('post', 'users', this.user)
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

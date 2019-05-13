<template>
    <div class="login-page">
        <h1 class="login-page-title">Login to your account</h1>

        <el-form ref="form" :model="form" class="login-page-form">
            <el-form-item
                    prop="username"
                    :rules="[{ required: true, message: 'The username field is required.' }]">
                <el-col :span="24">
                    <el-input v-model="form.username" placeholder="Username"></el-input>
                </el-col>
            </el-form-item>
            <el-form-item
                    prop="password"
                    :rules="[{ required: true, message: 'The password field is required.' }]">
                <el-col :span="24">
                    <el-input v-model="form.password" type="password" placeholder="Password"></el-input>
                </el-col>
            </el-form-item>
            <el-form-item>
                <el-col :span="24">
                    <el-input v-model="form.code_2fa" type="code_2fa" placeholder="2FA Code"></el-input>
                </el-col>
            </el-form-item>
            <el-form-item>
                <el-col :span="24">
                    <el-button type="primary" @click="login" class="login-submit-btn">Login</el-button>
                </el-col>
            </el-form-item>
        </el-form>

    </div>
</template>

<script>
export default {
    mounted() {
        if (auth.check()) {
            this.goToPosts();
        }
    },

    data() {
        return {
            form: {
                username: '',
                password: '',
                code_2fa: '',
            }
        }
    },

    methods: {
        login() {
            this.$refs['form'].validate((valid) => {
                if (!valid) {
                    return false;
                }

                api.call('post', 'login', this.form)
                    .then(({data}) => {
                        if (typeof data.data !== 'undefined') {
                            auth.login(data.data.token, data.data.user);
                            this.goToPosts();
                        } else {
                            this.$alert(data.message, '', {
                                closeOnClickModal: true
                            }).catch(e => {});
                        }
                    })
                    .catch(response => {
                        console.log(response);
                    });
            });
        },

        goToPosts() {
            this.$router.push('/posts');
        }
    }
}
</script>

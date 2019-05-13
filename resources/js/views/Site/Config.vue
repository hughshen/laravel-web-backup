<template>
    <div class="configs-index">
        <el-form ref="configs" :model="configs" label-width="160px">
            <el-form-item label="Site Name">
                <el-input
                        type="text"
                        v-model="configs.site_name">
                </el-input>
            </el-form-item>
            <el-form-item label="Site Keywords">
                <el-input
                        type="text"
                        v-model="configs.site_keywords">
                </el-input>
            </el-form-item>
            <el-form-item label="Site Description">
                <el-input
                        type="text"
                        v-model="configs.site_description">
                </el-input>
            </el-form-item>
            <el-form-item label="Site Copyright">
                <el-input
                        type="text"
                        v-model="configs.site_copyright">
                </el-input>
            </el-form-item>
            <el-form-item label="Backend 2FA">
                <el-select v-model="configs.site_backend_2fa">
                    <el-option
                            v-for="item in yesOrNoList"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Bing Auth">
                <el-input
                        type="text"
                        v-model="configs.site_bing_auth">
                </el-input>
            </el-form-item>
            <el-form-item label="Google Verification">
                <el-input
                        type="text"
                        v-model="configs.site_google_verification">
                </el-input>
            </el-form-item>
            <el-form-item label="Google Analytics Code">
                <el-input
                        type="textarea"
                        v-model="configs.site_google_analytics_code"
                        rows="6"
                        resize="none">
                </el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="configsUpdate">Update</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getConfigs();
        },

        data() {
            return {
                configs: {
                    site_name: '',
                    site_keywords: '',
                    site_description: '',
                    site_copyright: '',
                    site_backend_2fa: '0',
                    site_bing_auth: '',
                    site_google_verification: '',
                    site_google_analytics_code: '',
                },
                yesOrNoList: [
                    {
                        value: '1',
                        label: 'Yes'
                    },
                    {
                        value: '0',
                        label: 'No'
                    }
                ],
            }
        },

        methods: {
            getConfigs() {
                api.call('get', 'configs/')
                    .then(({data}) => {
                        this.configs = data.data.configs;
                    })
                    .catch(response => {
                        console.log(response);
                    });
            },

            configsUpdate() {
                api.call('post', 'configs/update', this.configs)
                    .then(({data}) => {
                        this.$alert(data.message, '', {
                            closeOnClickModal: true
                        }).catch(e => {});
                    })
                    .catch(response => {
                        console.log(response);
                    });
            }
        }
    }
</script>

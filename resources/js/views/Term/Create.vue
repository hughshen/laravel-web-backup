<template>
    <div class="term-create">
        <el-row style="margin-bottom: 1.5rem;">
            <el-col :span="24">
                <el-button type="primary" @click="backToList">Back To List</el-button>
            </el-col>
        </el-row>
        <el-form ref="term" :model="term" label-width="120px">
            <el-form-item
                    label="Name"
                    prop="name"
                    :rules="[{ required: true, message: 'The name field is required.' }]">
                <el-input
                        type="text"
                        v-model="term.name">
                </el-input>
            </el-form-item>
            <el-form-item
                    label="Slug"
                    prop="slug"
                    :rules="[{ required: true, message: 'The slug field is required.' }]">
                <el-input
                        type="text"
                        v-model="term.slug">
                </el-input>
            </el-form-item>
            <el-form-item
                    label="Taxonomy"
                    prop="taxonomy"
                    :rules="[{ required: true, message: 'The taxonomy field is required.' }]">
                <el-select v-model="term.taxonomy">
                    <el-option
                            v-for="item in taxList"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Description">
                <el-input
                        type="textarea"
                        v-model="term.description"
                        rows="3"
                        resize="none">
                </el-input>
            </el-form-item>
            <el-form-item
                    label="Status"
                    prop="status"
                    :rules="[{ required: true, message: 'The status field is required.' }]">
                <el-select v-model="term.status">
                    <el-option
                            v-for="item in statusList"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="termCreate">Create</el-button>
                <el-button @click="termReset">Reset</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                term: {
                    title: '',
                    slug: '',
                    taxonomy: 'tag',
                    description: '',
                    status: 1
                },
                taxList: [
                    {
                        value: 'tag',
                        label: 'Tag'
                    },
                    {
                        value: 'category',
                        label: 'Category'
                    },
                ],
                statusList: [
                    {
                        value: 1,
                        label: 'Show'
                    },
                    {
                        value: 0,
                        label: 'Hide'
                    }
                ],
            }
        },

        methods: {
            termCreate() {
                this.$refs['term'].validate((valid) => {
                    if (!valid) {
                        return false;
                    }

                    api.call('post', 'terms', this.term)
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

            termReset() {
                this.$refs['term'].resetFields();
            },

            backToList() {
                this.$router.push('/terms');
            }
        }
    }
</script>

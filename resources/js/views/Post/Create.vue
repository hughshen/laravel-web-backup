<template>
    <div class="post-create">
        <el-row style="margin-bottom: 1.5rem;">
            <el-col :span="24">
                <el-button type="primary" @click="backToList">Back To List</el-button>
            </el-col>
        </el-row>
        <el-form ref="post" :model="post" label-width="120px">
            <el-form-item
                    label="Title"
                    prop="title"
                    :rules="[{ required: true, message: 'The title field is required.' }]">
                <el-input
                        type="text"
                        v-model="post.title">
                </el-input>
            </el-form-item>
            <el-form-item
                    label="Slug"
                    prop="slug"
                    :rules="[{ required: true, message: 'The slug field is required.' }]">
                <el-input
                        type="text"
                        v-model="post.slug">
                </el-input>
            </el-form-item>
            <el-form-item
                    label="Content"
                    prop="content"
                    :rules="[{ required: true, message: 'The content field is required.' }]">
                <el-input
                        type="textarea"
                        v-model="post.content"
                        rows="12"
                        resize="none">
                </el-input>
                <el-row style="margin-top: 1rem;">
                    <el-col :span="24">
                        <el-button type="primary" @click="markdownToHtml">Markdown to Html</el-button>
                    </el-col>
                </el-row>
            </el-form-item>
            <el-form-item label="Excerpt">
                <el-input
                        type="textarea"
                        v-model="post.excerpt"
                        rows="3"
                        resize="none">
                </el-input>
            </el-form-item>
            <el-form-item label="Tags">
                <el-select
                        v-model="post.tags"
                        multiple
                        filterable
                        placeholder="Select Tags"
                        style="width: 100%">
                    <el-option
                            v-for="item in tags"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Categories">
                <el-select
                        v-model="post.cats"
                        multiple
                        filterable
                        placeholder="Select Categories"
                        style="width: 100%">
                    <el-option
                            v-for="item in cats"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item
                    label="Status"
                    prop="status"
                    :rules="[{ required: true, message: 'The status field is required.' }]">
                <el-select v-model="post.status">
                    <el-option
                            v-for="item in statusList"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="postCreate">Create</el-button>
                <el-button @click="postReset">Reset</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getTerms();
        },

        data() {
            return {
                post: {
                    title: '',
                    content: '',
                    excerpt: '',
                    slug: '',
                    tags: [],
                    cats: [],
                    status: 'publish'
                },
                statusList: [
                    {
                        value: 'publish',
                        label: 'Publish'
                    },
                    {
                        value: 'draft',
                        label: 'Draft'
                    }
                ],
                tags: [],
                cats: [],
            }
        },

        methods: {
            getTerms() {
                api.call('get', 'terms?all=1')
                    .then(({data}) => {
                        for (let term of data.data.terms) {
                            let item = {
                                value: term.id,
                                label: term.name
                            };

                            if (term.taxonomy == 'tag') {
                                this.tags.push(item);
                            } else if (term.taxonomy == 'category') {
                                this.cats.push(item);
                            }
                        }
                    })
                    .catch(response => {
                        console.log(response);
                    });
            },

            postCreate() {
                this.$refs['post'].validate((valid) => {
                    if (!valid) {
                        return false;
                    }

                    api.call('post', 'posts', this.post)
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

            markdownToHtml() {
                api.call('post', 'posts/markdown', {content: this.post.content})
                    .then(({data}) => {
                        this.$alert(data.data.html, 'Markdown Parsed', {
                            customClass: 'md-to-html-wrap',
                            closeOnClickModal: true,
                            showConfirmButton: false,
                            dangerouslyUseHTMLString: true
                        }).catch(e => {});
                    })
                    .catch(response => {
                        console.log(response);
                    });
            },

            postReset() {
                this.$refs['post'].resetFields();
            },

            backToList() {
                this.$router.push('/posts');
            }
        }
    }
</script>

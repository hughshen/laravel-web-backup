<template>
    <div class="post-index">
        <el-row style="margin-bottom: 1.5rem;">
            <el-col :span="24">
                <el-button type="primary" @click="goToCreatePost">New Post</el-button>
            </el-col>
        </el-row>
        <el-table
                border
                v-loading="loading"
                :data="posts"
                style="width: 100%">
            <el-table-column prop="id" label="ID" width="60"></el-table-column>
            <el-table-column prop="title" label="Title"></el-table-column>
            <el-table-column prop="slug" label="Slug"></el-table-column>
            <el-table-column label="Tags">
                <template slot-scope="scope">
                    <el-tag
                            size="medium"
                            v-for="tag in scope.row.tags"
                            :key="`post-${scope.row.id}-tag-${tag.id}`">{{ tag.name }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column label="Categories">
                <template slot-scope="scope">
                    <el-tag
                            size="medium"
                            v-for="cat in scope.row.cats"
                            :key="`post-${scope.row.id}-cat-${cat.id}`">{{ cat.name }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column prop="status" label="Status" width="80"></el-table-column>
            <el-table-column prop="created_at" label="Created At"></el-table-column>
            <el-table-column prop="updated_at" label="Updated At"></el-table-column>
            <el-table-column label="Actions">
                <template slot-scope="scope">
                    <el-button
                            size="mini"
                            @click="postEdit(scope.row.id)">Edit</el-button>
                    <el-button
                            size="mini"
                            type="danger"
                            @click="postDelete(scope.row.id)">Delete</el-button>
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
        this.getPosts('posts');
    },

    data() {
        return {
            posts: [],
            loading: true,
            pagination: {
                total: 0,
                current: 1,
            }
        }
    },

    methods: {
        getPosts(url) {
            this.loading = true;

            api.call('get', url)
                .then(({data}) => {
                    this.posts = [];

                    for (let post of data.data.posts.data) {
                        this.posts.push(post);
                    }

                    // Pagination
                    this.pagination.total = data.data.posts.total;
                    this.pagination.current = data.data.posts.current_page;

                    this.loading = false;
                })
                .catch(response => {
                    console.log(response);
                });
        },

        postEdit(id) {
            this.$router.push(`/posts/edit/${id}`);
        },

        postDelete(id) {
            this.$alert('Sure?', '', {
                type: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                closeOnClickModal: true
            }).then(() => {
                api.call('delete', 'posts/' + id)
                    .then(({data}) => {
                        this.$notify.success({
                            title: data.message,
                            duration: 2000
                        });
                        this.getPosts('posts?page=' + this.pagination.current);
                    });
            }).catch(e => {});
        },

        handleCurrentChange(current) {
            this.getPosts('posts?page=' + current);
        },

        goToCreatePost() {
            this.$router.push('/posts/create');
        }
    }
}
</script>

<template>
    <div class="term-index">
        <el-row style="margin-bottom: 1.5rem;">
            <el-col :span="24">
                <el-button type="primary" @click="goToCreateTerm">New Term</el-button>
            </el-col>
        </el-row>
        <el-table
                border
                v-loading="loading"
                :data="terms"
                style="width: 100%">
            <el-table-column prop="id" label="ID" width="60"></el-table-column>
            <el-table-column prop="name" label="Name"></el-table-column>
            <el-table-column prop="slug" label="Slug"></el-table-column>
            <el-table-column prop="taxonomy" label="Taxonomy"></el-table-column>
            <el-table-column prop="status" label="Status" width="80"></el-table-column>
            <el-table-column prop="created_at" label="Created At"></el-table-column>
            <el-table-column prop="updated_at" label="Updated At"></el-table-column>
            <el-table-column label="Actions">
                <template slot-scope="scope">
                    <el-button
                            size="mini"
                            @click="termEdit(scope.row.id)">Edit</el-button>
                    <el-button
                            size="mini"
                            type="danger"
                            @click="termDelete(scope.row.id)">Delete</el-button>
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
        this.getTerms('terms');
    },

    data() {
        return {
            terms: [],
            loading: true,
            pagination: {
                total: 0,
                current: 1,
            }
        }
    },

    methods: {
        getTerms(url) {
            this.loading = true;

            api.call('get', url)
                .then(({data}) => {
                    this.terms = [];

                    for (let term of data.data.terms.data) {
                        this.terms.push(term);
                    }

                    // Pagination
                    this.pagination.total = data.data.terms.total;
                    this.pagination.current = data.data.terms.current_page;

                    this.loading = false;
                })
                .catch(response => {
                    console.log(response);
                });
        },

        termEdit(id) {
            this.$router.push(`/terms/edit/${id}`);
        },

        termDelete(id) {
            this.$alert('Sure?', '', {
                type: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                closeOnClickModal: true
            }).then(() => {
                api.call('delete', 'terms/' + id)
                    .then(({data}) => {
                        this.$notify.success({
                            title: data.message,
                            duration: 2000
                        });
                        this.getTerms('terms?page=' + this.pagination.current);
                    });
            }).catch(e => {});
        },

        handleCurrentChange(current) {
            this.getTerms('terms?page=' + current);
        },

        goToCreateTerm() {
            this.$router.push('/terms/create');
        }
    }
}
</script>

<template>
    <el-table ref="dragTable" v-loading="isLoading" row-key="id" border fit highlight-current-row style="width: 100%"
        v-bind:data="list">
        <!-- tiêu đề -->
        <el-table-column min-width="120px" label="Tiêu đề">
            <template v-slot="item">
                <span>{{ item.row.title }}</span>
            </template>
        </el-table-column>
    </el-table>
</template>
<script>
export default {
    name: 'TableVulanTemplates',
    props: {
        list: {
            type: Array,
            default: ''
        },
        isLoading: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {
        }
    },

    filters: {},

    created() {
    },

    methods: {
    }
}

</script>

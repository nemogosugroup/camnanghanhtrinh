<template>
    <el-dropdown trigger="click" @command="handleSetSize">
        <div>
            <span class="svg-icon">
                <i class="ri-font-size-2"></i>
            </span>
        </div>
        <el-dropdown-menu :v-slot="dropdown">
            <el-dropdown-item
                v-for="item of sizeOptions"
                :key="item.value"
                :disabled="size === item.value"
                :command="item.value"
            >
                {{ item.label }}
            </el-dropdown-item>
        </el-dropdown-menu>
    </el-dropdown>
</template>

<script>
export default {
    data() {
        return {
            sizeOptions: [
                { label: "Default", value: "default" },
                { label: "Small", value: "small" },
                { label: "Large", value: "large" },
            ],
        };
    },
    computed: {
        size() {
            return this.$store.getters.size;
        },
    },
    methods: {
        handleSetSize(size) {
            this.$ELEMENT.size = size;
            this.$store.dispatch("app/setSize", size);
            this.refreshView();
            this.$message({
                message: "Switch Size Success",
                type: "success",
            });
        },
        refreshView() {
            // In order to make the cached page re-rendered
            this.$store.dispatch("tagsView/delAllCachedViews", this.$route);

            const { fullPath } = this.$route;

            this.$nextTick(() => {
                this.$router.replace({
                    path: "/redirect" + fullPath,
                });
            });
        },
    },
};
</script>

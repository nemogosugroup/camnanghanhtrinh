<template>
    <div v-if="isEdit" ref="draggable" id="draggableLogo" class="draggable index logo"
        :style="{ top: drapLogo.top + 'px', left: drapLogo.left + 'px' }">
        <div class="flex-center">
            <a href="https://gosucorp.vn/" target="_blank">
                <el-image :src="Logo" />
            </a>
            <span class="move" @click="hanldeEnableMove($event)">Điều chỉnh <i class="ri-drag-move-2-fill"></i></span>
        </div>
    </div>
    <div v-else class="index logo" :style="{ top: drapLogo.top + 'px', left: drapLogo.left + 'px' }">
        <div class="flex-center">
            <a href="https://gosucorp.vn/" target="_blank">
                <el-image :src="Logo" />
            </a>
        </div>
    </div>
</template>
<script>
import Logo from '@/assets/images/vulan/logo.png';
import $ from 'jquery';
import 'jquery-ui/ui/widgets/draggable';
export default {
    name: 'LogoGosu',
    components: {},
    props: {
        style: {
            type: Object,
            required: true,
            default: {
                top: 0,
                left: 0
            }
        },
        isEdit: {
            type: Boolean,
            required: true,
            default: false
        },
    },
    data() {
        return {
            drapLogo: {
                left: this.style.left,
                top: this.style.top,
                isDragging: false,
                offsetX: 0,
                offsetY: 0,
            },
            Logo: Logo
        }
    },
    setup() {
    },
    filters: {

    },
    created() {
    },
    watch: {
        isEdit(newVal) {
            if (newVal) {
                this.reloadDraggable();
            }
        }
    },
    mounted() {
        this.initDraggable();
    },
    methods: {
        initDraggable() {
            const draggableElement = $(this.$refs.draggable);
            let $this = this;
            draggableElement.draggable({
                drag: (event, ui) => {
                    const position = ui.position;
                },
                stop: (event, ui) => {
                    const position = ui.position;
                    $this.hanldeEmitData(position.left, position.top);
                }
            });
        },
        hanldeEmitData(left, top) {
            this.drapLogo.left = left;
            this.drapLogo.top = top;
            this.$emit('getStyleLogo', this.drapLogo, 'slider_1');
        },
        reloadDraggable() {
            $(this.$refs.draggable).draggable("destroy"); // Hủy draggable hiện tại
            this.$nextTick(() => {
                this.initDraggable(); // Khởi tạo lại draggable
            });
        }
    }
}
</script>
<style scoped>
.draggable {
    cursor: move;
}

.logo {
    width: auto;
    position: absolute;

    :deep(.el-image) {
        max-width: 200px;
    }
}
</style>

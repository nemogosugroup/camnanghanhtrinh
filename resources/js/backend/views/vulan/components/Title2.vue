<template>
    <div v-if="isEdit" ref="draggable" id="draggableTitle" class="draggable index title"
        :style="{ top: drapTitle.top + 'px', left: drapTitle.left + 'px' }">
        <div class="flex-end">
            <span class="move" @click="hanldeEnableMove($event)">Điều chỉnh <i class="ri-drag-move-2-fill"></i></span>
            <span class="change-color"><el-color-picker v-model="colorTitle" @change="changeColor" /></span>
        </div>
        <div class="title-vulan" :style="color">
            <div class="text-center">
                <h1 class="bellico">{{ 'VuLan' }}</h1>
                <span class="aleo-bold-italic">2024</span>
            </div>
            <span class="aleo-italic">Chuyến xe hồi ức</span>
        </div>
    </div>
    <div v-else class="index title" :style="{ top: drapTitle.top + 'px', left: drapTitle.left + 'px' }">
        <div class="title-vulan" :style="color">
            <div class="text-center">
                <h1 class="bellico">{{ 'VuLan' }}</h1>
                <span class="aleo-bold-italic">2024</span>
            </div>
            <span class="aleo-italic">Chuyến xe hồi ức</span>
        </div>
    </div>
</template>
<script>
import $ from 'jquery';
import 'jquery-ui/ui/widgets/draggable';
export default {
    name: 'TitleVulan',
    components: {},
    props: {
        style: {
            type: Object,
            required: true,
            default: {}
        },
        isEdit: {
            type: Boolean,
            required: true,
            default: false
        },
    },
    data() {
        return {
            drapTitle: {
                left: this.style.left,
                top: this.style.top,
                isDragging: false,  // Trạng thái kéo thả
                offsetX: 0,  // Khoảng cách ngang từ con trỏ tới thẻ <div>
                offsetY: 0,   // Khoảng cách dọc từ con trỏ tới thẻ <div>
            },
            colorTitle: this.style.color,
            color: null
        }
    },
    setup() {
    },
    filters: {

    },

    created() {
    },
    mounted() {
        this.color = { color: this.colorTitle };
        this.initDraggable();
    },
    watch: {
        isEdit(newVal) {
            if (newVal) {
                this.reloadDraggable();
            }
        }
    },
    methods: {
        changeColor() {
            this.color = { color: this.colorTitle };
        },
        hanldeEmitData(left, top) {
            this.drapTitle.left = left;
            this.drapTitle.top = top;
            this.$emit('getStyleTitle', this.drapTitle, 'slider_1');
        },
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
        reloadDraggable() {
            $(this.$refs.draggable).draggable("destroy"); // Hủy draggable hiện tại
            this.$nextTick(() => {
                this.initDraggable(); // Khởi tạo lại draggable
            });
        }
        // startDrag(target, event) {
        //     this[`drap${target}`].isDragging = true;
        //     this[`drap${target}`].offsetX = event.clientX - this[`drap${target}`].left;
        //     this[`drap${target}`].offsetY = event.clientY - this[`drap${target}`].top;
        // },
        // drag(target, event) {
        //     if (this[`drap${target}`].isDragging) {
        //         const newX = Math.max(
        //             0,
        //             Math.min(
        //                 event.clientX - this[`drap${target}`].offsetX,
        //                 this.sliderDimensions.width - this.$el.clientWidth
        //             )
        //         );
        //         const newY = Math.max(
        //             0,
        //             Math.min(
        //                 event.clientY - this[`drap${target}`].offsetY,
        //                 this.sliderDimensions.height - this.$el.clientHeight
        //             )
        //         );

        //         this[`drap${target}`].left = newX;
        //         this[`drap${target}`].top = newY;
        //         this.$emit('getStyleTitle', this.drapTitle, 'slider_1');
        //     }
        // },
        // endDrag(target) {
        //     this[`drap${target}`].isDragging = false;
        // }
    }
}
</script>
<style scoped>
.draggable {
    cursor: move;
}

.title {
    width: auto;
    max-width: 100%;
    position: absolute;
}

.change-color :deep(.el-color-picker__trigger) {
    border: 0;
    padding: 3px;
}
.vulan-container .title-vulan h1 {
    font-size: 180px;
    position: relative;
    top: 40px;
    left: 30px;
}
</style>

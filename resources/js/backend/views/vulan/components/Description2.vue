<template>
    <div v-if="isEdit" ref="draggable" id="draggableDesc" class="draggable index description"
        :style="{ top: drapDesc.top + 'px', left: drapDesc.left + 'px' }">
        <div class="content-desc" :style="`background-image: url(${bg_textarea})`">
            <div @click="hanldeDestroy">
                <ckeditor :editor="editor" v-model="drapDesc.content" :config="editorConfig" @input="onEditorInput"
                    @blur="onEditorBlur" />
            </div>
            <span class="edit"><i class="ri-edit-2-fill"></i></span>
        </div>
    </div>
    <div v-else class="index description"
        :style="{ top: drapDesc.top + 'px', left: drapDesc.left + 'px', color: color }">
        <div class="content-desc" :style="`background-image: url(${bg_textarea})`">
            <div class="content" v-html="drapDesc.content"></div>
        </div>
    </div>
</template>
<script>
import { InlineEditor, Autosave, Essentials, FontColor, Paragraph, Undo } from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';
import $ from 'jquery';
import 'jquery-ui/ui/widgets/draggable';
import 'jquery-ui/ui/widgets/draggable';
export default {
    name: 'Descriptions2',
    components: {},
    props: {
        style: {
            type: Object,
            required: true,
            default: {}
        },
        content: {
            type: String,
            required: true,
            default: ""
        },
        isEdit: {
            type: Boolean,
            required: true,
            default: false
        },
    },
    data() {
        return {
            bg_textarea: "/static/vulan/generals/bg_textarea.jpg",
            drapDesc: {
                left: this.style.left,
                top: this.style.top,
                isDragging: false,  // Trạng thái kéo thả
                offsetX: 0,  // Khoảng cách ngang từ con trỏ tới thẻ <div>
                offsetY: 0,   // Khoảng cách dọc từ con trỏ tới thẻ <div>
                content: this.content
            },
            color: this.style.color,
            editorConfig: {
                toolbar: {
                    items: ['fontColor'],
                    shouldNotGroupWhenFull: false
                },
                plugins: [Autosave, Essentials, FontColor, Paragraph, Undo],
            },
            editor: InlineEditor
        }
    },
    setup() {
    },
    filters: {

    },

    created() {
    },
    mounted() {
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
            this.drapDesc.left = left;
            this.drapDesc.top = top;
            this.$emit('getContentDesc', this.drapDesc, 'slider_1');
        },
        onEditorInput() {
            this.$emit('getContentDesc', this.drapDesc, 'slider_1');
        },
        reloadDraggable() {
            $(this.$refs.draggable).draggable("destroy"); // Hủy draggable hiện tại
            this.$nextTick(() => {
                this.initDraggable(); // Khởi tạo lại draggable
            });
        },
        onEditorBlur() {
            this.$nextTick(() => {
                this.initDraggable(); // Khởi tạo lại draggable
            });
        },
        hanldeDestroy() {
            $(this.$refs.draggable).draggable("destroy");
        }
    }
}
</script>

<style lang="scss" scoped>
.draggable {
    color: #fff;

    .ck-content {
        width: auto;
        max-width: 400px;

        &:hover {
            cursor: text;
        }
    }
}

.draggable:hover {
    cursor: move;
}

.description {
    width: auto;
    padding: 10px;
    position: absolute;
    color: #000;
}
.vulan-container .content-desc {
    align-items: start;
    width: 700px;
    height: 350px;
    background-size: contain;
    background-repeat: no-repeat;
    padding-top: 70px;
    padding-left: 90px;
}
.vulan-container .content-desc > div {
    transform: rotate(1deg);
}
</style>

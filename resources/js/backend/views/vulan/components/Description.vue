<template>
    <div v-if="isEdit" ref="draggable" id="draggableDesc" class="draggable index description"
        :style="{ top: drapDesc.top + 'px', left: drapDesc.left + 'px' }">
        <div class="content-desc">
            <div @click="hanldeDestroy">
                <div>
                    <Editor ref="editor" class="desc" style="max-width: 400px;" v-model="drapDesc.content"
                        :defaultConfig="editorConfig" @onChange="onEditorBlur" @onFocus="handleFocus"
                        @onBlur="onEditorBlur" @click="handleSelectAll" />
                </div>
            </div>
            <span class="edit"><i class="ri-edit-2-fill"></i></span>
        </div>
    </div>
    <div v-else class="index description"
        :style="{ top: drapDesc.top + 'px', left: drapDesc.left + 'px', color: color }">
        <div class="content-desc">
            <div class="content" v-html="drapDesc.content"></div>
        </div>
    </div>
</template>
<script>
import $ from 'jquery';
import 'jquery-ui/ui/widgets/draggable';
import '@wangeditor/editor/dist/css/style.css'
import { Editor, Toolbar } from '@wangeditor/editor-for-vue';
import { i18nChangeLanguage } from '@wangeditor/editor'

// Switch language - 'en' or 'zh-CN'
i18nChangeLanguage('en')

export default {
    name: 'Descriptions',
    components: { Editor, Toolbar },
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
            drapDesc: {
                left: this.style.left,
                top: this.style.top,
                isDragging: false,  // Trạng thái kéo thả
                offsetX: 0,  // Khoảng cách ngang từ con trỏ tới thẻ <div>
                offsetY: 0,   // Khoảng cách dọc từ con trỏ tới thẻ <div>
                content: this.content
            },
            color: this.style.color,
            editorDesc: null,
            check: false,
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
        window.addEventListener('click', this.handleClick);
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
            if ($(this.$refs.draggable).length > 0) {
                $(this.$refs.draggable).draggable("destroy"); // Hủy draggable hiện tại
            }
            this.$nextTick(() => {
                this.initDraggable(); // Khởi tạo lại draggable
            });
        },
        onEditorBlur(editor) {
            //editor.deselect();
            this.$nextTick(() => {
                this.initDraggable(); // Khởi tạo lại draggable
                this.$emit('getContentDesc', this.drapDesc, 'slider_1');
            });
        },
        handleFocus(editor) {
            this.editorDesc = editor;
        },
        hanldeDestroy() {
            if ($(this.$refs.draggable).length > 0) {
                $(this.$refs.draggable).draggable("destroy");
            }
        },
        handleSelectAll(editor) {
            // if ($(this.$refs.draggable).length > 0) {
            //     if (!this.check) {
            //         this.editorDesc.selectAll();
            //         this.check = true;
            //     }
            // }
        },
        handleClick(event) {
            // const targetElement = event.target;
            // const classList = targetElement.classList;
            // if ($(this.$refs.draggable).length > 0) {
            //     if (classList.value != '') {
            //         if (this.check) {
            //             this.editorDesc.deselect();
            //             this.check = false;
            //         }
            //     }
            // }
        },
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
    color: #fff;
}

:deep(.w-e-text-container) {
    background-color: transparent;
    border: 0;
    color: #fff;

    .w-e-bar-show {
        top: -100% !important
    }
}

#draggableDesc {
    z-index: 991;
}
</style>

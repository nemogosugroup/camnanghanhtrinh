<template>
    <b>Giữ Ctrl + scroll để Zoom</b>
    <el-divider/>
    <div id="departmentsTree"></div>
</template>
<script>
import OrgChart from "@balkangraph/orgchart.js"

export default {
    name: 'DepartmentsBlock',
    props: {
        departments: {
            type: Array,
            default: []
        }
    },
    data() {
        return {}
    },
    computed: {},
    mounted() {
        this.initChart(document.getElementById('departmentsTree'), this.departments);
    },
    created() {
    },
    methods: {
        initChart(domEl, x) {
            const membersIcon = "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"white\"><path d=\"M10 19.748V16.4C10 15.1174 10.9948 14.1076 12.4667 13.5321C11.5431 13.188 10.5435 13 9.5 13C7.61013 13 5.86432 13.6168 4.45286 14.66C5.33199 17.1544 7.41273 19.082 10 19.748ZM18.8794 16.0859C18.4862 15.5526 17.1708 15 15.5 15C13.4939 15 12 15.7967 12 16.4V20C14.9255 20 17.4843 18.4296 18.8794 16.0859ZM9.55 11.5C10.7926 11.5 11.8 10.4926 11.8 9.25C11.8 8.00736 10.7926 7 9.55 7C8.30736 7 7.3 8.00736 7.3 9.25C7.3 10.4926 8.30736 11.5 9.55 11.5ZM15.5 12.5C16.6046 12.5 17.5 11.6046 17.5 10.5C17.5 9.39543 16.6046 8.5 15.5 8.5C14.3954 8.5 13.5 9.39543 13.5 10.5C13.5 11.6046 14.3954 12.5 15.5 12.5ZM12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22Z\"></path></svg>";

            OrgChart.templates.department = Object.assign({}, OrgChart.templates.ana);
            OrgChart.templates.department.size = [370, 85];
            OrgChart.templates.department.node =
                '<rect x="0" y="0" width="370" height="85" fill="#ffffff" stroke-width="1" stroke="#aeaeae" rx="7" ry="7"></rect>';
            OrgChart.templates.department.field_0 = '<text data-width="350" data-text-overflow="multiline" style="font-size: 24px;" fill="#757575" x="185" y="40" text-anchor="middle">{val}</text>';
            OrgChart.templates.department.ripple = {
                radius: 0,
                color: "#f08e35",
                rect: null
            };

            OrgChart.templates.department.editFormHeaderColor = '#f08e35';

            this.chart = new OrgChart(domEl, {
                nodes: x,
                layout: OrgChart.treeLeftOffset,
                align: OrgChart.ORIENTATION,
                scaleInitial: OrgChart.match.boundary,
                mouseScrool: OrgChart.action.ctrlZoom,
                editForm: {
                    buttons: {
                        members: {
                            icon: membersIcon,
                            text: 'Thành Viên'
                        },
                        edit: null,
                        share: null,
                        pdf: null,
                        remove: null,
                    }
                },
                toolbar: {
                    fit: true
                },
                enableSearch: false,
                nodeBinding: {
                    field_0: "name"
                },
                tags: {
                    "Department": {
                        template: "department"
                    }
                }
            });

            const me = this;
            this.chart.editUI.on('button-click', function (sender, args) {
                if (args.name === 'members') {
                    // call dialog show department's members
                    const department = me.departments.find(obj => obj.id === args.nodeId);
                    me.emitter.emit('is-open-members-dialog', department.dept);
                }
            });
        }
    }
}

</script>
<style lang="scss">
#departmentsTree {
    min-height: 600px;
    .boc-toolbar-container svg {
        box-shadow: none !important;
        border: none !important;
        transform: scale(1);
        transition: all .4s ease;
        &:hover {
            transform: scale(1.2);
        }
    }
    [data-n-id="1"] {
        rect {
            fill: #7867cb !important;
            stroke: #7867cb !important;
            &:hover {
                cursor: pointer;
            }
        }
        text {
            fill: #ffffff !important;
            &:hover {
                cursor: pointer;
            }
        }
    }
    [data-sl="1"] {
        rect {
            fill: #f08e35 !important;
            stroke: #f08e35 !important;
            &:hover {
                cursor: pointer;
            }
        }
        text {
            fill: #ffffff !important;
            &:hover {
                cursor: pointer;
            }
        }
    }
    [data-l="2"] {
        rect {
            stroke: #f08e35 !important;
            &:hover {
                cursor: pointer;
            }
        }
        text {
            &:hover {
                cursor: pointer;
            }
        }
    }
    .boc-img-button svg {
        top: 0 !important;
    }
    .boc-edit-form-avatar {
        display: none !important;
    }
    .boc-edit-form-title {
        position: relative !important;
        top: 30%;
    }
    .boc-edit-form-instruments {
        margin-top: 12px !important;
    }
}
</style>

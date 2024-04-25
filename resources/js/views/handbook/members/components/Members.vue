<template>
    <b>Giữ Ctrl + scroll để Zoom</b>
    <el-divider/>
    <el-tag type="warning" size="large">Quản Lý</el-tag>
    <div id="managersTree"></div>
    <el-divider/>
    <el-tag type="primary" size="large">Nhân Sự</el-tag>
    <div id="employeesTree"></div>
</template>
<script>
import OrgChart from "@balkangraph/orgchart.js";
import {ref} from 'vue';

export default {
    name: 'MembersBlock',
    data() {
        return {
            activeName: ref('managers'),
        }
    },
    computed: {},
    mounted() {
        this.emitter.on('isset-members-data', data => {
            this.initChart(document.getElementById('managersTree'), data.managers);
            this.initChart(document.getElementById('employeesTree'), data.employees);
        });
    },
    created() {
    },
    methods: {
        initChart(domEl, x) {
            OrgChart.templates.staff = Object.assign({}, OrgChart.templates.mila);
            OrgChart.templates.staff.ripple = {
                radius: 0,
                color: "#f08e35",
                rect: null
            };

            OrgChart.templates.staff.editFormHeaderColor = '#f08e35';

            this.chart = new OrgChart(domEl, {
                nodes: x,
                layout: OrgChart.tree,
                columns: 4,
                align: OrgChart.CENTER,
                scaleInitial: OrgChart.match.boundary,
                mouseScrool: OrgChart.action.ctrlZoom,
                editForm: {
                    buttons: {
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
                    field_0: "name",
                    field_1: "role",
                    field_2: "level",
                    img_0: "img"
                },
                tags: {
                    "Staff": {
                        template: "staff"
                    }
                }
            });
        }
    }
}

</script>
<style lang="scss">
#membersChartWrapper {
    max-height: 800px;
    overflow-y: auto;
    &::-webkit-scrollbar {
        width: 5px;
    }

    &::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    &::-webkit-scrollbar-thumb {
        background: #c5c2c2;
    }

    &::-webkit-scrollbar-thumb:hover {
        background: #eee;
    }
}
#managersTree {
    min-height: 500px;

    .boc-toolbar-container svg {
        box-shadow: none !important;
        border: none !important;
        transform: scale(1);
        transition: all .4s ease;

        &:hover {
            transform: scale(1.2);
        }
    }

    [data-n-id="1"],
    [data-n-id="2"],
    [data-n-id="3"],
    [data-n-id="4"],
    [data-n-id="5"] {
        rect {
            &:hover {
                cursor: pointer;
            }
        }
    }

    [data-l="0"],
    [data-l="1"],
    [data-l="2"],
    [data-l="3"] {
        rect {
            fill: #ffffff !important;
            stroke: #f08e35 !important;
        }

        line {
            stroke: #f08e35 !important;
        }

        text {
            fill: #757575 !important;
        }
    }
}

#employeesTree {
    min-height: 500px;

    .boc-toolbar-container svg {
        box-shadow: none !important;
        border: none !important;
        transform: scale(1);
        transition: all .4s ease;

        &:hover {
            transform: scale(1.2);
        }
    }

    [data-n-id="1"],
    [data-n-id="2"],
    [data-n-id="3"],
    [data-n-id="4"],
    [data-n-id="5"] {
        rect {
            &:hover {
                cursor: pointer;
            }
        }
    }

    [data-l="0"],
    [data-l="1"],
    [data-l="2"],
    [data-l="3"] {
        rect {
            fill: #ffffff !important;
            stroke: #409eff !important;
        }

        line {
            stroke: #409eff !important;
        }

        text {
            fill: #757575 !important;
        }
    }
}
</style>

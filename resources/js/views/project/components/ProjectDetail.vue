<template>
    <div class="app-container">
        <el-form
            ref="postForm"
            :model="postForm"
            :rules="rules"
            class="form-container"
        >
            <sticky :z-index="10" :class-name="'sub-navbar ' + postForm.status">
                <!-- <CommentDropdown v-model="postForm.comment_disabled" />
                <PlatformDropdown v-model="postForm.platforms" />
                <SourceUrlDropdown v-model="postForm.source_uri" /> -->                
                <el-button
                    v-loading="loading"
                    type="warning"
                    @click="draftForm"
                >
                    Huỷ
                </el-button>
				<el-button
                    v-loading="loading"
                    style="margin-left: 10px"
                    type="success"
                    @click="submitForm"
                >
                    Lưu
                </el-button>
            </sticky>
			<el-row>
				<el-col :span="18" style="padding-right: 10px;">
					<el-card shadow="always">
						<h1>{{ isEdit ? 'Sửa dự án' : 'Tạo dự án mới' }}</h1>
						<el-form-item prop="" label="Chọn dự án">
							<el-select filterable placeholder="Chọn dự án"  style="width:100%;" v-model="postForm.project" @change="handleChooseProject">
								<el-option v-for="item in listProjectApi" :key="item.id" :label="`[${item.code_product}] [${item.department}] ${item.name_product}`" :value="item.id" />
							</el-select>
						</el-form-item>
						<el-row :gutter="20">
							<el-col :span="8">
								<el-form-item label="Mã dự án" prop="code_project">
									<el-input v-model="postForm.code" :disabled="true"></el-input>
								</el-form-item>	
							</el-col>		
							<el-col :span="8">		
								<el-form-item prop="company_center">
									<el-form-item label="Trung tâm" prop="company_center">
										<el-input v-model="postForm.company_center" :disabled="true"></el-input>
									</el-form-item>
								</el-form-item>
							</el-col>	
							<el-col :span="8">
								<el-form-item label="Tên dự án" prop="title">
									<el-input v-model="postForm.title" :disabled="true"></el-input>
								</el-form-item>
							</el-col>
						</el-row>
						<el-row :gutter="20">
							<el-col :span="12">
								<el-form-item
									label="Ngày bắt đầu"
									class="postInfo-container-item"
                                    >
                                        <el-date-picker
                                            v-model="postForm.start_time"
                                            type="datetime"
                                            format="yyyy-MM-dd HH:mm:ss"
                                            placeholder="Ngày bắt đầu"
											style="width: 100%;"
                                        />
                                    </el-form-item>
							</el-col>
							<el-col :span="12">
								<el-form-item
									label="Ngày kết thúc"
									class="postInfo-container-item"
                                    >
                                        <el-date-picker
                                            v-model="postForm.end_time"
                                            type="datetime"
                                            format="yyyy-MM-dd HH:mm:ss"
                                            placeholder="Ngày kết thúc"
											style="width: 100%;"
                                        />
                                    </el-form-item>
							</el-col>
						</el-row>
						<el-form-item prop="description" style="margin-bottom: 30px">
							<strong>Nội dung</strong>
							<Tinymce
								ref="editor"
								v-model="postForm.description"
								:height="400"
							/>
						</el-form-item>
					</el-card>
				</el-col>
				<el-col :span="6" style="padding-left: 10px;">
					<el-card shadow="always">
						<template #header>
							<span style="float:left;">Hình ảnh</span>
						</template>
						<el-form-item prop="images" style="margin-bottom: 30px">
							<Upload v-model="postForm.images" />
						</el-form-item>
					</el-card>
				</el-col>
			</el-row>
        </el-form>
    </div>
</template>

<script>
import Tinymce from "@/components/Tinymce";
import MDinput from "@/components/MDinput";
import Sticky from "@/components/Sticky";
import Ckfinder from '@/components/Ckfinder'
import { validURL } from "@/utils/validate";
import { fetchArticle } from "@/api/article";
import { searchUser } from "@/api/remote-search";
import Warning from "./Warning";
import Upload from "@/components/Upload/SingleImage4";
import {
    CommentDropdown,
    PlatformDropdown,
    SourceUrlDropdown,
} from "./Dropdown";

const defaultForm = {
    title: "",
    description: "",
    source_uri: "",
    images: "",
    display_time: undefined,
    id: undefined,
	start_time:"",
	end_time:"",
	company_center:"",
	code:"",
};

import RepositoryFactory from "@/utils/RepositoryFactory";
import moment from "moment";
const ProjectRepository = RepositoryFactory.get("project");

export default {
    name: "ProjectDetail",
    components: {
        Tinymce,
        MDinput,
        Upload,
        Sticky,
        Warning,
        CommentDropdown,
        PlatformDropdown,
        SourceUrlDropdown,
		Ckfinder
    },
    props: {
        isEdit: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        const validateRequire = (rule, value, callback) => {
            if (value === "") {
                this.$message({
                    message: rule.field + "为必传项",
                    type: "error",
                });
                callback(new Error(rule.field + "为必传项"));
            } else {
                callback();
            }
        };
        const validateSourceUri = (rule, value, callback) => {
            if (value) {
                if (validURL(value)) {
                    callback();
                } else {
                    this.$message({
                        message: "外链url填写不正确",
                        type: "error",
                    });
                    callback(new Error("外链url填写不正确"));
                }
            } else {
                callback();
            }
        };
        return {
            postForm: Object.assign({}, defaultForm),
            loading: false,
            userListOptions: [],
            rules: {
                images: [{ validator: validateRequire }],
                title: [{ validator: validateRequire }],
                description: [{ validator: validateRequire }],
                source_uri: [{ validator: validateSourceUri, trigger: "blur" }],
            },
            tempRoute: {},
			listProjectApi: []
        };
    },
    computed: {
        // contentShortLength() {
        //     return this.postForm.content_short.length;
        // },
        displayTime: {
            // set and get is useful when the data
            // returned by the back end api is different from the front end
            // back end return => "2013-06-25 06:59:25"
            // front end need timestamp => 1372114765000
            get() {
                return +new Date(this.postForm.display_time);
            },
            set(val) {
                this.postForm.display_time = new Date(val);
            },
        },
    },
    created() {
        if (this.isEdit) {
            const id = this.$route.params && this.$route.params.id;
            this.fetchData(id);
        }

        // Why need to make a copy of this.$route here?
        // Because if you enter this page and quickly switch tag, may be in the execution of the setTagsViewTitle function, this.$route is no longer pointing to the current page
        // https://github.com/PanJiaChen/vue-element-admin/issues/1221
        this.tempRoute = Object.assign({}, this.$route);

		this.fetchProjectApi();
    },
    methods: {
		/** 
		 * Lấy tất cả dự án 
		 * */
		async fetchProjectApi(){
			const { data } = await ProjectRepository.apiProject();
			this.listProjectApi = data.data;
		},

		// chọn dự án
		handleChooseProject(){
			console.log('postForm', this.postForm);
		},
        fetchData(id) {
            fetchArticle(id)
                .then((response) => {
                    this.postForm = response.data;

                    // just for test
                    this.postForm.title += `   Article Id:${this.postForm.id}`;
                    this.postForm.content_short += `   Article Id:${this.postForm.id}`;

                    // set tagsview title
                    this.setTagsViewTitle();

                    // set page title
                    this.setPageTitle();
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        setTagsViewTitle() {
            const title = "Edit Article";
            const route = Object.assign({}, this.tempRoute, {
                title: `${title}-${this.postForm.id}`,
            });
            this.$store.dispatch("tagsView/updateVisitedView", route);
        },
        setPageTitle() {
            const title = "Edit Article";
            document.title = `${title} - ${this.postForm.id}`;
        },
        submitForm() {
            console.log(this.postForm);
            this.$refs.postForm.validate((valid) => {
                if (valid) {
                    this.loading = true;
                    this.$notify({
                        title: "成功",
                        message: "发布文章成功",
                        type: "success",
                        duration: 2000,
                    });
                    this.postForm.status = "published";
                    this.loading = false;
                } else {
                    console.log("error submit!!");
                    return false;
                }
            });
        },
        draftForm() {
            if (
                this.postForm.description.length === 0 ||
                this.postForm.title.length === 0
            ) {
                this.$message({
                    message: "请填写必要的标题和内容",
                    type: "warning",
                });
                return;
            }
            this.$message({
                message: "保存成功",
                type: "success",
                showClose: true,
                duration: 1000,
            });
            this.postForm.status = "draft";
        },
        getRemoteUserList(query) {
            searchUser(query).then((response) => {
                if (!response.data.items) return;
                this.userListOptions = response.data.items.map((v) => v.name);
            });
        },
    },
};
</script>

<style lang="scss" scoped>
@import "~@style/mixin.scss";

.createPost-container {
    position: relative;

    .createPost-main-container {
        padding: 40px 45px 20px 50px;

        .postInfo-container {
            position: relative;
            @include clearfix;
            margin-bottom: 10px;

            .postInfo-container-item {
                float: left;
            }
        }
    }

    .word-counter {
        width: 40px;
        position: absolute;
        right: 10px;
        top: 0px;
    }
}

.article-textarea :deep(textarea){
    padding-right: 40px;
    resize: none;
    border: none;
    border-radius: 0px;
    border-bottom: 1px solid #bfcbd9;
}
</style>

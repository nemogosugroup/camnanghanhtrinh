<template>
  <div class="app-container">
    <search-level
      :list-query="listQuery.level"
      @showdialog="handleShowDialog"
      @search="handleSearch"
    ></search-level>
    <table-data @showdialog="handleShowDialog" :list="list"></table-data>
    <pagination
      v-show="total > 10"
      v-bind:total="total"
      v-model:page="listQuery.page"
      v-model:limit="listQuery.limit"
      :layout="layout"
      @pagination="fetch"
    />
    <dialog-level
      :dialog-form-visible="dialogFormVisible"
      :dialog-status="dialogStatus"
      :formData="formData"
      :title-dialog="titleDialog"
      @hidedialog="handleHideDialog"
      @update-data="handleUpdateData"
    ></dialog-level>
    <dialog-remove
      :title-dialog="titleDialog"
      :title="title"
      :dialog-status="dialogStatus"
      :dialog-visible-remove="dialogVisibleRemove"
      @hiddendialog="handleHideDialog"
      @delete="handleRemove"
    ></dialog-remove>
  </div>
</template>

<script>
import Pagination from "@/components/Pagination";
import DialogRemove from "@/backend/components/RemovePost";
import TableData from "./data";
import DialogLevel from "./dialog";
import SearchLevel from "./search";
import AdminRepositoryFactory from "@/backend/respository";
const levelAdminRepository = AdminRepositoryFactory.get("level");
const defaultForm = {
  level: 1,
  experience: 1,
  gold: 1,
  equipment_id: null,
};
export default {
  name: "Level",
  components: { Pagination, SearchLevel, TableData, DialogLevel, DialogRemove },
  data() {
    return {
      list: [],
      listQuery: {
        page: 1,
        limit: 10,
        sort: "desc",
        level: "",
      },
      isLoading: true,
      dialogStatus: "",
      dialogFormVisible: false,
      dialogVisibleRemove: false,
      titleDialog: "",
      total: 0,
      formData: Object.assign({}, defaultForm),
      id: false,
      title: "",
      isShowCategory: false,
      layout:"prev, pager, next",
    };
  },

  filters: {},

  created() {
    this.fetch();
  },

  methods: {
    async fetch() {
      this.isLoading = true;
      const { data } = await levelAdminRepository.list(this.listQuery);
      this.isLoading = false;
      if (data.success) {
        const results = data.data.data;
        this.list = results.map((item) => {
          item.edit = false;
          item.isLoadingSave = false;
          item.isLoadingDelete = false;
          return item;
        });
        if (data.equipments) {
          this.emitter.emit("list-equipment-data", data.equipments);
        }
        this.total = data.data.total;
      }
    },
    // show dialog
    handleShowDialog(data) {
      this.dialogStatus = data.status ? data.status : "create";
      this.titleDialog =
        this.dialogStatus == "create" ? "Tạo level mới" : "Chỉnh sửa level";
      if (data.status == "delete") {
        this.dialogVisibleRemove = true;
        this.id = data.id;
        this.title = data.title;
        this.titleDialog = "Bạn có muốn xoá level";
      } else {
        this.dialogFormVisible = true;
      }

      if (data.status == "edit") {
        let item = data.item;
        this.formData = {
          level: item.level,
          id: item.id,
          experience: item.experience,
          gold: item.gold,
          equipment_id: item.equipment_id,
        };
      }
    },
    // hide dialog
    handleHideDialog() {
      this.dialogStatus = "";
      this.dialogFormVisible = false;
      this.dialogVisibleRemove = false;
      this.titleDialog = "";
      this.formData = Object.assign({}, defaultForm);
    },
    // update data
    handleUpdateData(data) {
      switch (data.status) {
        case "edit":
          let indexList = false;
          this.list.filter((item, index) => {
            if (item.id == data.item.id) {
              indexList = index;
            }
          });
          this.list[indexList] = data.item;
          break;
        default:
          this.list.unshift(data.item);
          break;
      }
      this.handleHideDialog();
    },
    // remove
    async handleRemove() {
      const { data } = await levelAdminRepository.delete(this.id);
      if (data.success) {
        this.$message({
          message: data.message,
          type: "success",
        });
        this.handleHideDialog();
        this.fetch();
      } else {
        this.$message({
          message: data.message,
          type: "error",
        });
      }
    },
    // search
    handleSearch(data) {
      (this.listQuery = {
        page: 1,
        limit: 10,
        sort: "desc",
        title: data,
      }),
        this.fetch();
    }
  },
};
</script>

<style lang="scss" scoped></style>
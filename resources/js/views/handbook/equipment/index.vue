<template>
  <div class="app-container">
      <el-row>
          <el-col :span="24">
              <el-row>
                  <InfoEquipmentPerson
                      v-bind:defaultAvatar="defaultAvatar"
                      v-bind:col-span="12"
                  ></InfoEquipmentPerson>
                  <InfoEquipment
                      v-bind:col-span="12"
                  ></InfoEquipment>
              </el-row>
          </el-col>
      </el-row>
  </div>
</template>

<script>
    import defaultAvatar from '@/assets/images/logo/avatar.gif';
    import { mapGetters } from 'vuex';
    import Pagination from '@/components/Pagination';
    import HandBookMenu from '@/views/handbook/menu';
    import InfoEquipmentPerson from "@/views/handbook/equipment/person/index.vue";
    import InfoEquipment from "@/views/handbook/equipment/info/index.vue";
    export default {
        name: 'HandBookEquipment',
        components: {InfoEquipmentPerson, InfoEquipment, Pagination, HandBookMenu},
        data () {            
            return {
                defaultAvatar: defaultAvatar
            }            
        },

        filters:{
            
        },

        created() {
        },

        methods: {
            ...mapGetters(['sidebar','user']),
        }
    }

</script>

<style lang="scss" scoped>
@import '@style/custom.scss';
</style>
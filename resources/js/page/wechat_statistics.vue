<template>
  <el-main>
    <el-form :inline="true" :model="searchForm" class="demo-form-inline" ref="refname">
      <el-form-item label="名称" prop="accountId">
        <el-select v-model="searchForm.accountId" filterable placeholder="请选择">
        <el-option
          v-for="item in accountList"
          :key="item.id"
          :label="item.name"
          :value="item.id">
        </el-option>
      </el-select>
      </el-form-item>
      <el-form-item label="日期选择" prop="dateRange">
        <el-date-picker
          v-model="searchForm.dateRange"
          type="daterange"
          align="right"
          unlink-panels
          range-separator="至"
          start-placeholder="开始日期"
          end-placeholder="结束日期"
          value-format="yyyy-MM-dd"
          :picker-options="pickerOptions1">
        </el-date-picker>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">查询</el-button>
      </el-form-item>
      <el-form-item>
        <el-button type="warning" @click="onClear('refname')">清空<i class="el-icon-delete el-icon--right"></i></el-button>
      </el-form-item>
      <el-form-item>
        <download-excel
            class = "export-excel-wrapper"
            :data = "list"
            :fields = "excelFields"
            name = "wechat.xls">
            <el-button type="primary">导出<i class="el-icon-download el-icon--right"></i></el-button>
        </download-excel>
      </el-form-item>
    </el-form>
    <el-table
      :data="list"
      style="width: 100%">
      <el-table-column
        prop="id"
        label="id"
        sortable>
      </el-table-column>
      <el-table-column
        prop="name"
        label="名称"
        >
      </el-table-column>
      <el-table-column
        prop="count"
        label="文章数"
        sortable>
      </el-table-column>
      <el-table-column
        prop="read_sum"
        label="总阅读数"
        sortable>
      </el-table-column>
      <el-table-column
        prop="like_sum"
        label="总点赞数"
        sortable>
      </el-table-column>
      <el-table-column
        prop="read_avg"
        label="平均阅读数"
        sortable>
      </el-table-column>
      <el-table-column
        prop="like_avg"
        label="平均点赞数"
        sortable>
      </el-table-column>
      <el-table-column
        prop="read_max"
        label="最大阅读数"
        sortable>
      </el-table-column>
      <el-table-column
        prop="like_max"
        label="最大点赞数"
        sortable>
      </el-table-column>
      <el-table-column
        prop="read_min"
        label="最小阅读数"
        sortable>
      </el-table-column>
      <el-table-column
        prop="like_min"
        label="最小点赞数"
        sortable>
      </el-table-column>
      <el-table-column
        prop="crawl_time"
        label="抓取时间"
        sortable>
      </el-table-column>
    </el-table>
  </el-main>
</template>

  <script>
    import { mapState, mapActions } from 'vuex';
    export default({
      data() {
        return {
            searchForm: {
                dateRange: '',
                accountId: '',
            },
            pickerOptions1: {
              disabledDate(time) {
                return time.getTime() > Date.now();
              },
              shortcuts: [{
                text: '最近一周',
                onClick(picker) {
                  const end = new Date();
                  const start = new Date();
                  start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                  picker.$emit('pick', [start, end]);
                }
              }, {
                text: '最近一个月',
                onClick(picker) {
                  const end = new Date();
                  const start = new Date();
                  start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                  picker.$emit('pick', [start, end]);
                }
              }, {
                text: '最近三个月',
                onClick(picker) {
                  const end = new Date();
                  const start = new Date();
                  start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                  picker.$emit('pick', [start, end]);
                }
              }, {
                text: '最近半年',
                onClick(picker) {
                  const end = new Date();
                  const start = new Date();
                  start.setTime(start.getTime() - 3600 * 1000 * 24 * 180);
                  picker.$emit('pick', [start, end]);
                }
              }, {
                text: '最近一年',
                onClick(picker) {
                  const end = new Date();
                  const start = new Date();
                  start.setTime(start.getTime() - 3600 * 1000 * 24 * 365);
                  picker.$emit('pick', [start, end]);
                }
              }]
            },
            excelFields:{
                '名称':'name',
                '文章数':'count',
                '总阅读数':'read_sum',
                '总点赞数':'like_sum',
                '平均阅读数':'read_avg',
                '平均点赞数':'like_avg',
                '最大阅读数':'read_max',
                '最大点赞数':'like_max',
                '最小阅读数':'read_min',
                '最小点赞数':'like_min',
                '抓取时间':'crawl_time',
            },
        }
      },
      computed: mapState({
        list: state => state.WechatStatistics.list,
        accountList: state => state.WechatAccount.list,
      }),
      created() {
        //this.WechatStatistics.getWechatStatisticsList();  
        //this.getWechatAccountList();  
        this.$store.dispatch('WechatStatistics/getWechatStatisticsList');
        this.$store.dispatch('WechatAccount/getWechatAccountList');
      },
      methods: {
        ...mapActions([
            'WechatStatistics/getWechatStatisticsList',
            'WechatAccount/getWechatAccountList',
        ]),
        onSubmit() {
           var params = {};
           if (this.searchForm.accountId) {
               params.account_id = this.searchForm.accountId;
           }
           if (this.searchForm.dateRange instanceof Array) {
               if (this.searchForm.dateRange[0]) {
                   params.start_date = this.searchForm.dateRange[0]
               }
               if (this.searchForm.dateRange[1]) {
                   params.end_date = this.searchForm.dateRange[1]
               }
           }
           this.$store.dispatch('WechatStatistics/getWechatStatisticsList', params);
        },
        onClear(refname) {
                this.$refs[refname].resetFields();
            this.$store.dispatch('WechatStatistics/getWechatStatisticsList', {});
        },
      },
    });

  </script>

<template>
  <el-main>
    <el-form :inline="true" :model="searchForm" class="demo-form-inline" ref="refname">
      <el-form-item label="账号" prop="accountId">
        <el-select v-model="searchForm.accountId" filterable placeholder="请选择">
        <el-option
          v-for="item in accountList"
          :key="item.id"
          :label="item.name"
          :value="item.id">
        </el-option>
      </el-select>
      </el-form-item>
      <el-form-item label="发布日期" prop="dateRange">
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
    </el-form>
    <el-table
      :data="list.msg_list"
      fixed="left"
      style="width: 100%"
      @sort-change="onSortChange"
      ref="table">
      <el-table-column
        prop="id"
        label="id"
        sortable="custom">
      </el-table-column>
      <el-table-column
        prop="account_name"
        label="账号"
        >
      </el-table-column>
      <el-table-column
        prop="url"
        label="url"
        min-width="240"
        >
        <template slot-scope="scope">
            <a :href="scope.row.url"
                target="_blank" class="buttonText">{{scope.row.url}}
            </a>
        </template>
      </el-table-column>
      <el-table-column
        prop="forward"
        label="转发"
        sortable="custom">
      </el-table-column>
      <el-table-column
        prop="comment"
        label="评论"
        sortable="custom">
      </el-table-column>
      <el-table-column
        prop="like"
        label="点赞"
        sortable="custom">
      </el-table-column>
      <el-table-column
        prop="pubtime"
        label="发布时间"
        sortable="custom">
      </el-table-column>
      <el-table-column
        prop="crawl_time"
        label="抓取时间"
        min-width="170">
      </el-table-column>
    </el-table>
    <el-pagination
      background
      layout="total, prev, pager, next, jumper"
      @current-change="onCurrentPageChange"
      :current-page.sync="list.current_page"
      :page-size="20"
      :total="list.total">
    </el-pagination>
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
                page: '',
                sortProp: '',
                sortOrder: '',
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
        }
      },
      computed: mapState({
        list: state => state.Weibo.msgList,
        accountList: state => state.Weibo.accountList,
      }),
      created() {
        this.$store.dispatch('Weibo/getMsgList');
        this.$store.dispatch('Weibo/getAccountList');
      },
      methods: {
        ...mapActions([
            'Weibo/getMsgList',
            'Weibo/getAccountList',
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
           this.$store.dispatch('Weibo/getMsgList', params);
        },
        onClear(refname) {
            this.$refs[refname].resetFields();
            this.$store.dispatch('Weibo/getMsgList', {});
            this.$refs['table'].clearSort();
        },
        onCurrentPageChange(val) {
            this.searchForm.page = val;
            this.$store.dispatch('Weibo/getMsgList', this.genFormParams());
        },
        onSortChange(column) {
            this.searchForm.sortProp = column.prop;
            if (column.order == 'descending') {
                this.searchForm.sortOrder = 'desc';
            } else if (column.order == 'ascending') {
                this.searchForm.sortOrder = 'asc';
            }
            this.$store.dispatch('Weibo/getMsgList', this.genFormParams());
        },
        genFormParams() {
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
           if (this.searchForm.page) {
                params.page = this.searchForm.page;
           }
           if (this.searchForm.sortProp && this.searchForm.sortOrder) {
                params.sort_field = this.searchForm.sortProp;
                params.sort_order = this.searchForm.sortOrder;
           }
           return params;
        },
      },
    });

  </script>

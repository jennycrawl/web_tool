<template>
  <el-main>
    <i class="el-icon-circle-plus"></i>
    <el-table
      :data="list"
      style="width: 100%">
      <el-table-column
        prop="name"
        label="名称"
        >
      </el-table-column>
      <el-table-column
        prop="biz"
        label="biz"
        >
      </el-table-column>
      <el-table-column
        prop="crawl_time"
        label="最近抓取时间"
        sortable
        >
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
            <el-button
              size="mini"
              @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
            <el-button
              size="mini"
              type="danger"
              @click="handleDelete(scope.$index, scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog title="公众号信息" :visible.sync="dialogFormVisible">
      <el-form :model="form">
        <el-form-item label="名称" :label-width="formLabelWidth">
          <el-input v-model="form.name" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="biz" :label-width="formLabelWidth">
          <el-input v-model="form.biz" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="dialogFormVisible = false">确 定</el-button>
      </div>
    </el-dialog>

  </el-main>
</template>

  <script>
    import { mapState, mapActions } from 'vuex';
    export default({
      computed: mapState({
        list: state => state.WechatAccount.list
      }),
      created() {
        this.getWechatAccountList();  
      },
      methods: {
        ...mapActions([
            'getWechatAccountList'
        ]),
        handleDelete() {
            this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
              confirmButtonText: '确定',
              cancelButtonText: '取消',
              type: 'warning'
            }).then(() => {
              this.$message({
                type: 'success',
                message: '删除成功!'
              });
            }).catch(() => {
              this.$message({
                type: 'info',
                message: '已取消删除'
              });          
            });
        },
        handleEdit(index,row) {
            console.log(index, row);
        },
      },
      data() {
        return {
            dialogFormVisible: false,
            form: {
              name: '',
              region: '',
              date1: '',
              date2: '',
              delivery: false,
              type: [],
              resource: '',
              desc: ''
            },
            formLabelWidth: '120px' 
        }
      }
    });

  </script>

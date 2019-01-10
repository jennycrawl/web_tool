<template>
  <el-main>
    <el-button
      type="primary"
      size="mini" @click="handleInsert">添加公众号<i class="el-icon-plus el-icon--right"></i></el-button>
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
      <el-form :model="form" :rules="rules" ref="dialogForm">
        <el-form-item label="名称" prop="name" :label-width="formLabelWidth">
          <el-input v-model="form.name" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="biz" prop="biz" :label-width="formLabelWidth">
          <el-input v-model="form.biz" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="handelPushForm">确 定</el-button>
      </div>
    </el-dialog>

  </el-main>
</template>

  <script>
    import { mapState, mapActions } from 'vuex';
    export default({
      computed: mapState({
        list: state => state.Wechat.accountList
      }),
      created() {
        this.$store.dispatch('Wechat/getAccountList');
      },
      methods: {
        ...mapActions([
            'Wechat/getAccountList',
        ]),
        handleDelete(index, row) {
            this.$confirm('此操作将永久删除该配置, 是否继续?', '提示', {
              confirmButtonText: '确定',
              cancelButtonText: '取消',
              type: 'warning'
            }).then(() => {
                this.$store.dispatch('Wechat/deleteAccount', {'id':row['id'], 'params':{}}).then(res => {
                    if (res.data && res.data['success']) {
                        this.dialogFormVisible = false;
                        this.$store.dispatch('Wechat/getAccountList');
                        this.$message({
                            type: 'success',
                            message: '删除成功!'
                        });
                    } else {
                        this.$message({
                            type: 'error',
                            message: '删除失败，' + res.data['msg'],
                        });
                    }
                });
            }).catch(() => {
              this.$message({
                type: 'info',
                message: '已取消删除'
              });          
            });
        },
        handleInsert() {
            this.form = {}
            this.dialogFormVisible = true;
        },
        handleEdit(index,row) {
            this.form = this.genPostParams(row);
            this.dialogFormVisible = true;
        },
        handelPushForm() {
            this.$refs['dialogForm'].validate((valid) => {
                if (!valid) {
                    return false;
                } else {
                    if (this.form['id']) {
                        this.$store.dispatch('Wechat/updateAccount', {'id':this.form['id'], 'params':this.form}).then(res => {
                            if (res.data && res.data['success']) {
                                this.dialogFormVisible = false;
                                this.$store.dispatch('Wechat/getAccountList');
                                this.$message({
                                    type: 'success',
                                    message: '提交成功!'
                                });
                            } else {
                                this.$message({
                                    type: 'error',
                                    message: '提交失败，' + res.data['msg'],
                                });
                            }
                        });
                    } else {
                        this.$store.dispatch('Wechat/insertAccount', this.form).then(res => {
                            if (res.data && res.data['success']) {
                                this.dialogFormVisible = false;
                                this.$store.dispatch('Wechat/getAccountList');
                                this.$message({
                                    type: 'success',
                                    message: '提交成功!'
                                });
                            } else {
                                this.$message({
                                    type: 'error',
                                    message: '提交失败，' + res.data['msg'],
                                });
                            }
                        });
                    }
                }
            });
        },
        genPostParams(row) {
            var params = {
                'id':row['id'],
                'name':row['name'],
                'biz':row['biz'],
            };
            return params;
        }
      },
      data() {
        return {
            dialogFormVisible: false,
            form: {
              id: 0,
              name: '',
              biz: '',
            },
            formLabelWidth: '120px',
            rules: {
              name: [
                { required: true, message: '请输入名称', trigger: 'blur' },
              ],
              biz: [
                { required: true, message: '请输入biz', trigger: 'blur' },
              ],
            },
        }
      }
    });

  </script>

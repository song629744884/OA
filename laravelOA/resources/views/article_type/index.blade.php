@extends('layouts.app')
@section('content')
    <el-container style="background-color: #fff;">
        <el-header style="margin:10px;">

            <el-row style="margin-bottom: 10px;">
                <el-button type="primary" icon="el-icon-plus" @click="handleEdit()">新增</el-button>
            </el-row>
        </el-header>
        <el-container>
            <el-main v-show="list">
                <el-table
                        ref="multipleTable"
                        :data="list"
                        tooltip-effect="dark"
                        style="width: 100%"
                        highlight-current-row
                        height="340"
                        default-expand-all
                        row-key="id"
                        @selection-change="handleSelectionChange">
                    <el-table-column
                            type="selection"
                            width="55">
                    </el-table-column>
                    <el-table-column
                    type="index">
                    </el-table-column>
                    <el-table-column
                            prop="name"
                            label="类型名称"
                            width="200" show-overflow-tooltip>
                    </el-table-column>
                    <el-table-column label="操作" width="320" fixed="right">
                        <template slot-scope="scope">
                            <el-button size="mini" type="success"
                                       @click="handleEdit(scope.$index,scope.row)" plain
                                       class="el-icon-folder-opened">编辑
                            </el-button>
                           
                            <el-button size="mini" type="danger" plain class="el-icon-delete"
                                       @click="handleDelete(scope.$index, scope.row)">删除
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-main>
            <el-footer>
                <el-pagination
                        :page-size="10"
                        layout="prev, pager, next"
                        :total="4">
                </el-pagination>
            </el-footer>
        </el-container>
    </el-container>
@endsection


@section('from')
    <!--添加修改表单 -->
    <el-dialog  :title="addMenu.addMenuTitle" :visible.sync="addMenu.addMenuVisible">
        <el-form ref="editForm" :model="editForm" :rules="editRules" :label-width="addMenu.formLabelWidth">

            <el-form-item label="类型名称" required prop="name">
                <el-input v-model="editForm.name"></el-input>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="setSubmit('editForm')" >保存</el-button>
                <el-button @click="addMenu.addMenuVisible = false">取消</el-button>
            </el-form-item>
        </el-form>
    </el-dialog>


@endsection

@section('script')
    <script>
        new Vue({
            el: '#app',
            data: function() {
                return {
                    activeIndex:'1',
                    menus:[],
                    list:[],
                    addMenu: {
                        addMenuTitle: "新增",
                        addMenuVisible: false,
                        formLabelWidth: "110px",
                    },
                   
                    editForm: {
                        name: '',
                    },
                    editRules: {
                        name: [
                            {required: true, message: '请输入类型名称', trigger: 'blur'},
                            {min: 2, max: 6, message: '长度在 2 到 6 个字符', trigger: 'blur'}
                        ],
                    },
                    roleMenu: [],
                    roleForm:{},
                    defaultProps: {
                        children: 'children',
                        label: 'label'
                    }
                }
            },
            created(){
                this.getMenu();
                this.getTypeList();
            },
            methods: {
                getMenu(){
                    let that = this;
                    this.$http.get("{{ url('menu/menuList') }}").then(function(res){
                        if (res.status == '200') {
                            that.menus = res.body;
                        }
                    },function(){
                        console.log('请求失败处理');
                    });
                },
                loginOut: function () {
                    let that = this;
                    // var DjangoCookie = getCookie('csrftoken');
                    that.$http.post("{{ url('index/logout') }}",'',{headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(function(res){
                        if (res.body.code == '1') {
                            location.href="{{ url('index/login') }}";
                        }else{
                            that.$message({
                                type: 'error',
                                message: res.body.msg
                            });
                        }
                    },function(res){
                        console.log(res);
                    });
                },
                routeTo(route){
                    //console.log(route);
                    location.href='/index.php/'+route;
                },
                handleSelect(key, keyPath) {
                    console.log(key, keyPath);
                },
                handleOpen(key, keyPath) {
                    console.log(key, keyPath);
                },
                handleClose(key, keyPath) {
                    console.log(key, keyPath);
                },
                handleSelectionChange(val) {
                    this.multipleSelection = val;
                },
                getTypeList(){
                    let that = this;
                    this.$http.get('data').then(function(res){
                        if (res.status == '200') {
                            that.list = res.body;
                        }
                    },function(){
                        console.log('请求失败处理');
                    });
                },
                
                //添加、编辑菜单
                handleEdit(index, row) {
                    this.addMenu.addMenuVisible = true;
                    if (row) {
                        this.addMenu.addMenuTitle = '编辑';
                        this.editForm = Object.assign({}, row);
                    } else {
                        this.addMenu.addMenuTitle = '新增';
                        this.editForm = {
                            name: '',
                        };
                    }
                    let that = this;
                    setTimeout(_ => that.$refs.editForm.clearValidate(), 10)
                },
                setSubmit: function (formName) {
                    let that = this;
                    //var DjangoCookie = getCookie('csrftoken');
                    that.$refs[formName].validate((valid) => {
                        if (valid) {
                            that.$http.post('save',that.editForm,{headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(function(res){
                                //console.log(res);
                                if (res.body.code == '1') {
                                    that.getTypeList();
                                    that.addMenu.addMenuVisible = false;
                                    setTimeout(_ => that.$refs.editForm.clearValidate(), 10);
                                }else{
                                    that.$message({
                                        type: 'error',
                                        message: res.body.msg
                                    });
                                }
                            },function(res){
                                console.log(res);
                            });
                        } else {
                            console.log('error submit!!');
                            return false;
                        }
                    });
                },
                handleDelete(index,row){
                    let that = this;
                    that.$confirm('确定删除, 是否继续?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning',
                        center: true
                    }).then(() => {
                        //var DjangoCookie = getCookie('csrftoken');
                        that.$http.post('delete/'+row.id,{},{headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(function(res){
                            if (res.body.code == '1') {
                                that.getTypeList();
                                that.$message({
                                    type: 'success',
                                    message: '删除成功!'
                                });
                            }else{
                                that.$message({
                                    type: 'error',
                                    message: '删除失败'
                                });
                            }
                        },function(res){
                            console.log(res);
                        });

                    }).catch(() => {
                        that.$message({
                            type: 'info',
                            message: '已取消删除'
                        });
                    });
                },
            }
        })
    </script>
@endsection


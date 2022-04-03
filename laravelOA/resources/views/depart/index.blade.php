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
                                    :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
                                    @selection-change="handleSelectionChange">
                                <el-table-column
                                        type="selection"
                                        width="55">
                                </el-table-column>
                                
                                <el-table-column
                                        prop="name"
                                        label="部门名称"
                                        width="200" show-overflow-tooltip>
                                </el-table-column>
                                <el-table-column
                                        prop="intro"
                                        label="介绍"
                                        width="250" show-overflow-tooltip>
                                </el-table-column>
                                <el-table-column
                                        prop="count"
                                        label="部门人数"
                                        width="200" show-overflow-tooltip>
                                </el-table-column>
                                
                                <el-table-column label="操作" width="270" fixed="right">
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
                        <!-- <el-footer>
                            <el-pagination
                                    :page-size="10"
                                    layout="prev, pager, next"
                                    :total="4">
                            </el-pagination>
                        </el-footer> -->
                    </el-container>
                </el-container>
@endsection


@section('from')
<!--添加修改表单 -->
<el-dialog  :title="addMenu.addMenuTitle" :visible.sync="addMenu.addMenuVisible">
    
        <el-form ref="editForm" :model="editForm" :rules="editRules" :label-width="addMenu.formLabelWidth">
            <el-form-item label="父级部门" required prop="pid">
                <el-select v-model="editForm.pid" placeholder="请选择父级菜单">
                    <el-option label="顶级部门" value="0"></el-option>
                    <el-option
                            v-for="item in menuSelectList"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="名称" required prop="name">
                <el-input v-model="editForm.name"></el-input>
            </el-form-item>
            <el-form-item required label="部门介绍" >
                <el-input type="textarea" v-model="editForm.intro"></el-input>
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
            
                menus:[],
                activeIndex:'1',
                list:[],
                addMenu: {
                    addMenuTitle: "新增部门",
                    addMenuVisible: false,
                    formLabelWidth: "110px",
                },
                editForm: {
                    pid: '',
                    name: '',
                    intro: '',
                },
                editRules: {
                    name: [
                        {required: true, message: '请输入部门名称', trigger: 'blur'},
                       
                    ],
                    intro: [
                        {required: true, message: '请输入部门说明', trigger: 'blur'}
                    ],
                    pid: [
                        {required: true, message: '请选择父级菜单', trigger: 'change'}
                    ],

                },
                menuSelectList:[],
               
            }
        },
        created(){
            this.getMenu();
            this.getdepartList();
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
            routeTo(route){'/index.php/'+route;
                //console.log(route);
                location.href=
            },
            
            handleSelectionChange(val) {
                this.multipleSelection = val;
                console.log("multipleSelection", val);
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
            getdepartList(){
                let that = this;
                this.$http.get('data').then(function(res){
                    console.log(res)
                     if (res.status == '200') {
                         that.list = res.body.data;
                         that.menuSelectList = res.body.menuSelectList;
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
                        pid: '',
                        name: '',
                        intro: '',
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
                            
                            if (res.body.code == '1') {
                                 that.addMenu.addMenuVisible = false;
                                 setTimeout(_ => that.$refs.editForm.clearValidate(), 10);
                                 that.getdepartList();
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
                //var DjangoCookie = getCookie('csrftoken');
                let that = this;
                that.$confirm('确定删除, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning',
                    center: true
                }).then(() => {
                    that.$http.post('delete/'+row.id,{},{headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(function(res){
                        console.log(res);
                        if (res.body.code == '1') {
                            location.reload();
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


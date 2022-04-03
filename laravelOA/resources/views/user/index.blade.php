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
                            label="姓名"
                            width="200" >
                    </el-table-column>
                    <el-table-column
                            prop="code"
                            label="工号"
                            width="200" >
                    </el-table-column>
                    <el-table-column
                            prop="phone"
                            label="手机号"
                            width="200" show-overflow-tooltip>
                    </el-table-column>
                    <el-table-column
                            prop="depart_str.name"
                            label="部门"
                            width="200" show-overflow-tooltip>
                    </el-table-column>
                    
                    <el-table-column
                            prop="sex_str"
                            label="性别"
                            width="200" >
                    </el-table-column>
                    <!-- <el-table-column
                            prop="birth"
                            label="生日"
                            width="200" show-overflow-tooltip>
                    </el-table-column> -->
                   
                    <el-table-column
                        prop="status_str"
                        label="状态"
                        width="200" >
                    </el-table-column>
                    <el-table-column label="操作" width="200" fixed="right">
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
                        :page-size="page_num"
                        layout="prev, pager, next"
                        :total="total"
                        @current-change="handleCurrentChange"
                        >
                </el-pagination>
            </el-footer>
        </el-container>
    </el-container>
@endsection


@section('from')
    <!--添加修改表单 -->
    <el-dialog  :title="addMenu.addMenuTitle" :visible.sync="addMenu.addMenuVisible">
        <el-form ref="editForm" :model="editForm" :rules="editRules" :label-width="addMenu.formLabelWidth">

            <el-form-item label="姓名" required prop="name">
                <el-input v-model="editForm.name"></el-input>
            </el-form-item>

            <el-form-item label="手机号码" required prop="phone">
                <el-input v-model="editForm.phone"></el-input>
            </el-form-item>

            <!-- <el-form-item label="工号"  prop="code">
                <el-input v-model="editForm.code" :readonly="true"></el-input>
            </el-form-item> -->

            <!-- <el-form-item label="年龄" required prop="age">
                <el-input v-model="editForm.age" type="number"></el-input>
            </el-form-item> -->

            <el-form-item label="性别" required prop="sex">
                <el-radio-group v-model="editForm.sex">
                    <el-radio :label="1">男</el-radio>
                    <el-radio :label="2">女</el-radio>
                </el-radio-group>
                
            </el-form-item>

            <el-form-item label="出生日期" required prop="birth">
                <el-date-picker
                    v-model="editForm.birth"
                    type="date"
                    placeholder="选择日期">
                </el-date-picker>
            </el-form-item>

            <el-form-item label="部门" required prop="depart">
                <!-- <el-select v-model="editForm.depart" placeholder="请选择">
                    <el-option
                    v-for="item in departarr"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                    </el-option>
                </el-select> -->
                <el-cascader
                v-model="editForm.depart"
                :options="depart_options"
                :props="{ checkStrictly: true,emitPath:false }"
                clearable></el-cascader>
            </el-form-item>

            <el-form-item label="角色" required prop="role_id">
                <el-select v-model="editForm.role_id" placeholder="请选择">
                    <el-option
                    v-for="item in rolelist"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="状态" required prop="status">
                <el-radio-group v-model="editForm.status" size="small">
                    <el-radio-button label="1">开启</el-radio-button>
                    <el-radio-button label="2">禁用</el-radio-button>
                </el-radio-group>
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
                    departarr:[],
                    addMenu: {
                        addMenuTitle: "新增",
                        addMenuVisible: false,
                        formLabelWidth: "110px",
                    },
                    editForm: {
                        name: '',
                        code:'',
                        // age:'',
                        sex:'',
                        birth:'',
                        depart:'',
                        status:1,
                        role_id:'',
                        phone:''
                    },
                    editRules: {
                        name: [
                            {required: true, message: '请输入姓名', trigger: 'blur'},
                            {min: 2, max: 5, message: '长度在 2 到 5 个字符', trigger: 'blur'}
                        ],
                        phone: [
                            {required: true, message: '请输入手机号码', trigger: 'blur'},
                        ],
                        sex: [
                            { required: true, message: '请选择性别', trigger: 'change' }
                        ],
                        status: [
                            { required: true, message: '请选择状态', trigger: 'change' }
                        ],
                        role_id: [
                            { required: true, message: '请选择角色', trigger: 'change' }
                        ],
                        birth: [
                            { required: true, message: '请选择日期', trigger: 'change' }
                        ],
                        
                    },
                    rolelist: [],
                    defaultProps: {
                        children: 'children',
                        label: 'label'
                    },
                    page_num:10,
                    currentPage:0,
                    total:0,
                    depart_options:[],
                }
            },
            created(){
                this.getMenu();
                this.getUserList();
                this.getRoleList();
                this.getdepartOption();
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
                handleCurrentChange(val) {
                    // console.log(`当前页: ${val}`);
                    this.currentPage = val
                    this.getUserList();
                },
                getUserList(){
                    let that = this;
                    this.$http.get('data',{params:{page_num:that.page_num,currentPage:that.currentPage}}).then(function(res){
                        // if (res.status == '200') {
                            that.list = res.body.data;
                            that.total = res.body.total;
                        // }
                    },function(){
                        console.log('请求失败处理');
                    });
                },
                getRoleList(){
                    let that = this;
                    this.$http.get("/index.php/role/data").then(function(res){
                        if (res.status == '200') {
                            that.rolelist = res.body;
                        }
                    },function(){
                        console.log('请求失败处理');
                    });
                },
                getdepartOption(){
                    let that = this;
                    this.$http.get("../depart/option").then(function(res){
                        if (res.status == '200') {
                            that.depart_options = res.body;
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
                            code:'',
                            // age:'',
                            sex:'',
                            birth:'',
                            depart:'',
                            status:1,
                            role_id:'',
                            phone:''
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
                                console.log(res);
                                if (res.body.code == '1') {
                                    that.getUserList();
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
                                that.getUserList();
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


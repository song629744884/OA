@extends('layouts.app')
@section('style')
<style>
   
  </style>
@endsection
@section('content')

<el-container style="background-color: #fff;min-height: 500px;">
    <el-main >
        <el-col :span="12">
        <el-form ref="form" :model="form" :rules="editRules" required label-width="80px">
            <el-form-item label="旧密码" required prop="password">
                <el-input v-model="form.password" type="password"></el-input>
            </el-form-item>
            <el-form-item label="新密码" required prop="npassword">
                <el-input v-model="form.npassword" type="password"></el-input>
            </el-form-item>
            <el-form-item label="确认密码" required prop="rpassword">
                <el-input v-model="form.rpassword" type="password"></el-input>
            </el-form-item>
            <el-button type="primary" @click="onSubmit('form')">保存</el-button>
            </el-form-item>
        </el-form>
    </el-col>
    </el-main>
</el-container>
@endsection

@section('script')
<script>
    new Vue({
        el: '#app',
        data: function() {
            return {
            
                menus:[],
                activeIndex:'1',
                myUserInfo:{},
                form: {
                    password: '',
                    npassword:'',
                    rpassword:'',
                    },
                    editRules: {
                        password: [
                            {required: true, message: '请输入姓名', trigger: 'blur'},
                            {min: 6, max: 20, message: '长度在 6 到 20 个字符', trigger: 'blur'}
                        ],
                        npassword: [
                            {required: true, message: '请输入姓名', trigger: 'blur'},
                            {min: 6, max: 20, message: '长度在 6 到 20 个字符', trigger: 'blur'}
                        ],
                        rpassword: [
                            {required: true, message: '请输入姓名', trigger: 'blur'},
                            {min: 6, max: 20, message: '长度在 6 到 20 个字符', trigger: 'blur'}
                        ],
                        
                    },
                    header:{'X-CSRFToken':''},
            }
        },
        created(){
            this.getMenu();
            this.header['X-CSRFToken'] = getCookie('csrftoken');
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
            onSubmit: function (formName) {
                    let that = this;
                    //var DjangoCookie = getCookie('csrftoken');
                    that.$refs[formName].validate((valid) => {
                        if (valid) {
                            that.$http.post('passwordSave',that.form,{headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(function(res){
                                console.log(res);
                                if (res.body.code == '1') {
                                    that.$message({
                                        type: 'success',
                                        message: res.body.msg
                                    });
                                    location.reload();
                                    setTimeout(_ => that.$refs.form.clearValidate(), 10);
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
           
        }
    })
</script>
@endsection
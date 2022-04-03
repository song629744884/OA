@extends('layouts.app')
@section('style')
<style>
    .avatar-uploader .el-upload {
      border: 1px dashed #d9d9d9;
      border-radius: 6px;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
      border-color: #409EFF;
    }
    .avatar-uploader-icon {
      font-size: 28px;
      color: #8c939d;
      width: 178px;
      height: 178px;
      line-height: 178px;
      text-align: center;
    }
    .avatar {
      width: 178px;
      height: 178px;
      display: block;
    }
  </style>
@endsection
@section('content')

<el-container style="background-color: #fff;">
    <el-main >
        <el-col :span="12">
        <el-form ref="form" :model="form" :rules="editRules" required label-width="80px">
            <el-form-item label="姓名" required prop="phone">
                <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="手机号码" >
                <el-input v-model="form.phone" :readonly="true"></el-input>
            </el-form-item>
            <el-form-item label="性别" required prop="sex">
                <el-radio-group v-model="form.sex">
                    <el-radio :label="1">男</el-radio>
                    <el-radio :label="2">女</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="出生日期" required prop="birth">
                <el-date-picker
                    v-model="form.birth"
                    type="date"
                    placeholder="选择日期">
                </el-date-picker>
            </el-form-item>
            <el-form-item label="头像">
                <el-upload
                    class="avatar-uploader"
                    action="{{ url('index/imgUpload') }}"
                    :headers="header"
                    :show-file-list="false"
                    :on-success="handleAvatarSuccess"
                    :before-upload="beforeAvatarUpload">
                    <img v-if="form.pic" :src="form.pic" class="avatar">
                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
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
                        name: '',
                        sex:'',
                        birth:'',
                        phone:'',
                        pic:''
                    },
                    editRules: {
                        name: [
                            {required: true, message: '请输入姓名', trigger: 'blur'},
                            {min: 2, max: 5, message: '长度在 2 到 5 个字符', trigger: 'blur'}
                        ],
                        sex: [
                            { required: true, message: '请选择性别', trigger: 'change' }
                        ],
                        birth: [
                            { required: true, message: '请选择日期', trigger: 'change' }
                        ],
                        
                    },
                    header:{'X-CSRFToken':''},
            }
        },
        created(){
            this.getMenu();
            this.getMyUserInfo();
            this.header['X-CSRF-TOKEN'] = '{{ csrf_token() }}'
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
            handleAvatarSuccess(res, file) {
                console.log(res)
                console.log(file)
                this.form.pic = res;
                // this.form.pic = URL.createObjectURL(file.raw);
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG) {
                this.$message.error('上传头像图片只能是 JPG 格式!');
                }
                if (!isLt2M) {
                this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return isJPG && isLt2M;
            },
            getMyUserInfo(){
                    let that = this;
                    this.$http.get('myUserInfo',{}).then(function(res){
                        if (res.body.code == '1') {
                            that.form = res.body.data;
                        }
                    },function(){
                        console.log('请求失败处理');
                    });
                },
            onSubmit: function (formName) {
                    let that = this;
                    //var DjangoCookie = getCookie('csrftoken');
                    that.$refs[formName].validate((valid) => {
                        if (valid) {
                            that.$http.post('saveMyUserInfo',that.form,{headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(function(res){
                                console.log(res);
                                if (res.body.code == '1') {
                                    that.$message({
                                        type: 'success',
                                        message: res.body.msg
                                    });
                                    that.getMyUserInfo();
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
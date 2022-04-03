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
        <el-col :span="16">
        <el-form ref="form" :model="form"  required label-width="80px">
            <el-form-item label="标题" >
                <el-input v-model="form.title" readonly="true"></el-input>
            </el-form-item>
            <el-form-item label="内容" >
                <el-input v-model="form.content" type="textarea" :rows="8" readonly="true"></el-input>
            </el-form-item>
            <el-form-item label="类型" >
                <el-select v-model="form.type_id" placeholder="请选择" disabled="true">
                    <el-option
                      v-for="item in typeArr"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id">
                    </el-option>
                  </el-select>
            </el-form-item>
           
            <el-form-item label="封面">
                
                    <el-image :src="form.pic"></el-image>
            </el-form-item>
            <el-button type="primary" @click="onBack()">返回</el-button>
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
                    title: '',
                    content:'',
                    type_id:'',
                    pic:'',
                },
                
                header:{'X-CSRFToken':''},
                typeArr:[],
                id:''
            }
        },
        created(){
            this.getMenu();
            this.getTypeList();
            this.id = '{{ $id }}'
            if(this.id){
                this.getArticleInfo();
            }
            
            //this.header['X-CSRFToken'] = getCookie('csrftoken');
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
           
            getTypeList(){
                    let that = this;
                    this.$http.get("{{ url('article_type/data') }}").then(function(res){
                        if (res.status == '200') {
                            that.typeArr = res.body;
                        }
                    },function(){
                        console.log('请求失败处理');
                    });
                },
            getArticleInfo(){
                    let that = this;
                    this.$http.get("{{ url('article/read') }}",{params:{id:that.id}}).then(function(res){
                        if (res.body.code == '1') {
                            that.form = res.body.data;
                        }
                    },function(){
                        console.log('请求失败处理');
                    });
                },
            
            onBack(){
                history.back()
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
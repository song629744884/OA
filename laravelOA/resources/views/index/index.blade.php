@extends('layouts.app')
@section('content')
<el-container style="background-color: #fff;">
    <el-image style="width:100%;height:500px;" :src="pic"></el-image>
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
                pic:'/static/pic/2037038.jpg',
                index:0,
                piclist:[
                    '/static/pic/2037038.jpg',
                    '/static/pic/2037724.jpg',
                    '/static/pic/2038119.jpg',
                    '/static/pic/2045429.jpg',
                    '/static/pic/2051134.jpg',
                    '/static/pic/2052074.jpg',
                    '/static/pic/2052098.jpg',
                    '/static/pic/2053578.jpg'
                ]
               
            }
        },
        created(){
            this.getMenu();
            setInterval(() => {
                this.run()
            }, 3000);
        },
        methods: {
            getMenu(){
                let that = this;
                this.$http.get('{{ url('menu/menuList') }}').then(function(res){
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
            run(){
                let that = this;
                that.pic = that.piclist[that.index]
                if(that.index==(that.piclist.length-1)){
                    that.index=0;
                }else{
                    that.index++;
                }
                
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
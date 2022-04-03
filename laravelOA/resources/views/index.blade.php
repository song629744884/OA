@extends('layouts.app')
@section('content')
    <el-container style="background-color: #fff;">

    </el-container>
@endsection




@section('script')
    <script>
        new Vue({
            el: '#app',
            data: function() {
                return {
                    menus:[],

                }
            },
            created(){
                this.getMenu();
            },
            methods: {
                getMenu(){
                    let that = this;
                    this.$http.get('{{ url('menu/menu') }}').then(function(res){
                        if (res.body.code == '1') {
                            that.menus = res.data.data;
                        }
                    },function(){
                        console.log('请求失败处理');
                    });
                },
                routeTo(route){
                    //console.log(route);
                    location.href='http://www.songshop.com/index.php/'+route;
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



            }
        })
    </script>
@endsection


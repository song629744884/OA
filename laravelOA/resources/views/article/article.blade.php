@extends('layouts.app')
@section('content')
    <el-container style="background-color: #fff;">
        <el-header style="margin:10px;">

            
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
                            prop="title"
                            label="标题"
                            width="200" show-overflow-tooltip>
                    </el-table-column>
                    <el-table-column
                            prop="type.name"
                            label="类型"
                            width="200" show-overflow-tooltip>
                    </el-table-column>
                    <el-table-column
                            prop="user.name"
                            label="发布人"
                            width="140" show-overflow-tooltip>
                    </el-table-column>
                    <el-table-column
                            prop="created_at"
                            label="发布时间"
                            width="200" show-overflow-tooltip>
                    </el-table-column>
                    <el-table-column label="操作" width="180" fixed="right">
                        <template slot-scope="scope">
                            <el-button size="mini" type="success"
                                       @click="handleEdit(scope.$index,scope.row)" plain
                                       class="el-icon-folder-opened">查看
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
                        @current-change="handleCurrentChange">
                </el-pagination>
            </el-footer>
        </el-container>
    </el-container>
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
                    
                    
                    defaultProps: {
                        children: 'children',
                        label: 'label'
                    },
                    page_num:6,
                    currentPage:0,
                    total:0,
                }
            },
            created(){
                this.getMenu();
                this.getArticleList();
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
                handleCurrentChange(val) {
                    // console.log(`当前页: ${val}`);
                    this.currentPage = val
                    this.getArticleList();
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
                getArticleList(){
                    let that = this;
                    this.$http.get('allData',{params:{page_num:that.page_num,currentPage:that.currentPage}}).then(function(res){
                        if (res.status == '200') {
                            that.list = res.body.data;
                            that.total = res.body.total;
                            // for(let n =0 ;n<that.list.length;n++){
                            //     let created_at = new Date(that.list[n]['created_at'])
                            //     that.list[n]['created_at'] = created_at.toLocaleString()
                            // }
                        }
                    },function(){
                        console.log('请求失败处理');
                    });
                },
                
                //添加、编辑菜单
                handleEdit(index, row) {
                    if (row) {
                        location.href="/index.php/article/view?id="+row.id
                    } 
                    
                },
               
                
            }
        })
    </script>
@endsection


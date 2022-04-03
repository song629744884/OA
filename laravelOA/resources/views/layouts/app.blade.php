<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--<meta name="_token" content="{{ csrf_token() }}"/>--}}
    <title>pyoffice</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('css/element.css') }}">
    <link rel="stylesheet" href="{{ asset('css/view.css') }}">

    <!-- Styles -->

</head>
<style>



</style>
<body>
<div id="app">
    <el-container>
        <el-header id="header">
            <div class="head-div">
                <div class="webname">pyoffice</div>
                <div class="title"><span>|</span><label><a style="color: aliceblue;text-decoration:none;" href="{{ url('index/index') }}">{{ $title }}</a></label></div>
                <div class="middiv"></div>
                <div class="photo">
                    <img src="{{ $me->pic }}">
                    <el-menu
                            :default-active="activeIndex"
                            class="el-menu-demo"
                            mode="horizontal"
                            @select="handleSelect"
                            background-color="#464c5c"
                            text-color="#fff"
                            active-text-color="#ffd04b">
                        <el-submenu index="1">
                            <template slot="title">{{ $me->name }}</template>
                            <el-menu-item index="1-1"><a style="color: aliceblue;text-decoration:none;" href="">修改密码</a></el-menu-item>
                            <el-menu-item index="1-2" @click="loginOut">退出</el-menu-item>
                        </el-submenu>
                    </el-menu>
                </div>
            </div>
        </el-header>
        <el-container>
            <el-aside width="200px" id="aside">
                <el-menu
                        default-active="1"
                        class="el-menu-vertical-demo"
                        @open="handleOpen"
                        @close="handleClose">
                    <el-submenu  v-for="menu in menus" :key="menu.index" :index="menu.index">
                        <template slot="title">
                            <i :class="menu.icon"></i>
                            <span>@{{ menu.name }}</span>
                        </template>
                        <template v-if="menu.child.length>0">
                            <el-menu-item  v-for="item  in menu.child" :index="item.index" @click="routeTo(item.url)" :key="item.index">@{{ item.name }}</el-menu-item>
                        </template>
                    </el-submenu>
                </el-menu>
            </el-aside>
            <el-main id="main">
                @yield('content')
            </el-main>
        </el-container>
    </el-container>
    @yield('from')
</div>
</body>
<!-- import Vue before Element -->
<script type="text/javascript" src="{{ asset('js/vue.js') }}" charset="utf-8"></script>
<!-- import JavaScript -->
<script type="text/javascript" src="{{ asset('js/element.js') }}" charset="utf-8"></script>
<script type="text/javascript" src="{{ asset('js/vue-resource.min.js') }}" charset="utf-8"></script>
@yield('script')

</html>

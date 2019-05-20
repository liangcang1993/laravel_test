<!-- #section:basics/sidebar -->
<div id="sidebar" class="sidebar responsive">


    <ul class="nav nav-list">
        <li class="">
            <a href="/">
                <span class="menu-text"> 首页 </span>
            </a>

            <b class="arrow"></b>
        </li>

        {!! $models !!}
        {{--<li class="">--}}
            {{--<a class="dropdown-toggle">--}}
                {{--<span class="menu-text"> 用户管理 </span>--}}
                {{--<b class="arrow fa fa-angle-down"></b>--}}
            {{--</a><b class="arrow"></b>--}}

            {{--<b class="arrow"></b>--}}

            {{--<ul class="submenu">--}}

                {{--<li class="">--}}
                    {{--<a href="/admin/delUser">--}}
                        {{--<span class="menu-text"> 用户删除 </span>--}}
                    {{--</a>--}}

                    {{--<b class="arrow"></b>--}}
                {{--</li>--}}
            {{--</ul>--}}
        {{--</li>--}}
        {{--<li class="">--}}
            {{--<a class="dropdown-toggle">--}}
                {{--<span class="menu-text"> BANNER管理 </span>--}}
                {{--<b class="arrow fa fa-angle-down"></b>--}}
            {{--</a><b class="arrow"></b>--}}

            {{--<b class="arrow"></b>--}}

            {{--<ul class="submenu">--}}

                {{--<li class="">--}}
                    {{--<a href="/admin/banner">--}}
                        {{--<span class="menu-text"> IOS </span>--}}
                    {{--</a>--}}

                    {{--<b class="arrow"></b>--}}
                {{--</li>--}}
                {{--<li class="">--}}
                    {{--<a href="/admin/banner?dev=1">--}}
                        {{--<span class="menu-text"> Android </span>--}}
                    {{--</a>--}}

                    {{--<b class="arrow"></b>--}}
                {{--</li>--}}
            {{--</ul>--}}
        {{--</li>--}}
        {{--<li class="">--}}
            {{--<a href="/admin/notifi">--}}
                {{--<span class="menu-text"> 推送统计 </span>--}}
            {{--</a>--}}

            {{--<b class="arrow"></b>--}}
        {{--</li>--}}
        {{--<li class="">--}}
            {{--<a class="dropdown-toggle">--}}
                {{--<span class="menu-text"> 素材管理 </span>--}}
                {{--<b class="arrow fa fa-angle-down"></b>--}}
            {{--</a><b class="arrow"></b>--}}

            {{--<b class="arrow"></b>--}}
            {{--<ul class="submenu">--}}
                {{--<li class="">--}}
                    {{--<a class="dropdown-toggle">--}}
                        {{--<span class="menu-text"> 素材列表 </span>--}}
                        {{--<b class="arrow fa fa-angle-down"></b>--}}
                    {{--</a><b class="arrow"></b>--}}

                    {{--<b class="arrow"></b>--}}

                    {{--<ul class="submenu">--}}

                        {{--<li class="">--}}
                            {{--<a href="/admin/material">--}}
                                {{--<span class="menu-text"> IOS </span>--}}
                            {{--</a>--}}

                            {{--<b class="arrow"></b>--}}
                        {{--</li>--}}
                        {{--<li class="">--}}
                            {{--<a href="/admin/material?dev=1">--}}
                                {{--<span class="menu-text"> Android </span>--}}
                            {{--</a>--}}

                            {{--<b class="arrow"></b>--}}
                        {{--</li>--}}

                    {{--</ul>--}}
                {{--</li>--}}
                {{--<li class="">--}}
                    {{--<a class="dropdown-toggle">--}}
                        {{--<span class="menu-text"> 分类管理 </span>--}}
                        {{--<b class="arrow fa fa-angle-down"></b>--}}
                    {{--</a><b class="arrow"></b>--}}

                    {{--<b class="arrow"></b>--}}

                    {{--<ul class="submenu">--}}

                        {{--<li class="">--}}
                            {{--<a href="/admin/type">--}}
                                {{--<span class="menu-text"> IOS </span>--}}
                            {{--</a>--}}

                            {{--<b class="arrow"></b>--}}
                        {{--</li>--}}
                        {{--<li class="">--}}
                            {{--<a href="/admin/type?dev=1">--}}
                                {{--<span class="menu-text"> Android </span>--}}
                            {{--</a>--}}

                            {{--<b class="arrow"></b>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                {{--<li class="">--}}
                    {{--<a href="/admin/material/create">--}}
                        {{--<span class="menu-text"> 素材添加 </span>--}}
                    {{--</a>--}}

                    {{--<b class="arrow"></b>--}}
                {{--</li>--}}
            {{--</ul>--}}
        {{--</li>--}}
        {{--@if (Auth::user()->is_super == 1)--}}
            {{--<li class="">--}}
                {{--<a  class="dropdown-toggle">--}}
                    {{--<span class="menu-text"> 用户管理 </span>--}}
                    {{--<b class="arrow fa fa-angle-down"></b>--}}
                {{--</a><b class="arrow"></b>--}}

                {{--<b class="arrow"></b>--}}

                {{--<ul class="submenu">--}}

                    {{--<li class="">--}}
                        {{--<a href="/admin/delUser">--}}
                            {{--<span class="menu-text"> 删除用户 </span>--}}
                        {{--</a>--}}


                        {{--<b class="arrow"></b>--}}
                    {{--</li>--}}

                {{--</ul>--}}
            {{--</li>--}}
        {{--@endif--}}

            {{--@if (Auth::user()->is_super == 1)--}}
            {{--<li class="">--}}
                {{--<a  class="dropdown-toggle">--}}
                    {{--<span class="menu-text"> 后台用户管理 </span>--}}
                    {{--<b class="arrow fa fa-angle-down"></b>--}}
                {{--</a><b class="arrow"></b>--}}

                {{--<b class="arrow"></b>--}}

                {{--<ul class="submenu">--}}

                    {{--<li class="">--}}
                        {{--<a href="/admin/admin_user">--}}
                            {{--<span class="menu-text"> 用户管理 </span>--}}
                        {{--</a>--}}

                        {{--<b class="arrow"></b>--}}
                    {{--</li>--}}
                    {{--<li class="">--}}
                        {{--<a href="/admin/admin_role">--}}
                            {{--<span class="menu-text"> 角色管理 </span>--}}
                        {{--</a>--}}

                        {{--<b class="arrow"></b>--}}
                    {{--</li>--}}
                    {{--<li class="">--}}
                        {{--<a href="/admin/admin_auth">--}}
                            {{--<span class="menu-text"> 权限管理 </span>--}}
                        {{--</a>--}}

                        {{--<b class="arrow"></b>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}
        {{--@endif--}}
        {{--<li class="">--}}
            {{--<a class="dropdown-toggle">--}}
                {{--<span class="menu-text"> 配置管理 </span>--}}
                {{--<b class="arrow fa fa-angle-down"></b>--}}
            {{--</a><b class="arrow"></b>--}}

            {{--<b class="arrow"></b>--}}

            {{--<ul class="submenu">--}}
                {{--<li class="">--}}
                    {{--<a class="dropdown-toggle">--}}
                        {{--<span class="menu-text"> 配置列表 </span>--}}
                        {{--<b class="arrow fa fa-angle-down"></b>--}}
                    {{--</a><b class="arrow"></b>--}}

                    {{--<b class="arrow"></b>--}}

                    {{--<ul class="submenu">--}}

                        {{--<li class="">--}}
                            {{--<a href="/admin/config">--}}
                                {{--<span class="menu-text"> IOS </span>--}}
                            {{--</a>--}}

                            {{--<b class="arrow"></b>--}}
                        {{--</li>--}}
                        {{--<li class="">--}}
                            {{--<a href="/admin/config?dev=1">--}}
                                {{--<span class="menu-text"> Android </span>--}}
                            {{--</a>--}}

                            {{--<b class="arrow"></b>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                {{--<li class="">--}}
                        {{--<a href="/admin/config?dev=3">--}}
                            {{--<span class="menu-text"> 素材vip系数 </span>--}}
                        {{--</a>--}}
                        {{--<b class="arrow"></b>--}}
                {{--</li>--}}
            {{--</ul>--}}
        {{--</li>--}}
        {{--<li class="">--}}
            {{--<a class="dropdown-toggle">--}}
                {{--<span class="menu-text"> 广告统计 </span>--}}
                {{--<b class="arrow fa fa-angle-down"></b>--}}
            {{--</a><b class="arrow"></b>--}}

            {{--<b class="arrow"></b>--}}

            {{--<ul class="submenu">--}}
                {{--<li class="">--}}
                    {{--<a href="/admin/adList">--}}
                        {{--<span class="menu-text"> 统计列表 </span>--}}
                    {{--</a>--}}
                    {{--<b class="arrow"></b>--}}
                {{--</li>--}}
            {{--</ul>--}}
        {{--</li>--}}

    </ul>


    <!-- #section:basics/sidebar.layout.minimize -->
    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left"
           data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>

    <!-- /section:basics/sidebar.layout.minimize -->
    <script type="text/javascript">
        $(function () {
            var urlhref = window.location.href;
            var urlpathname = window.location.pathname;
            var index = window.location.protocol + "//" + window.location.host + "/";

            if (urlpathname == '/admin') {
                urlpathname = "/";
            }
            $("#sidebar")
                .find("ul.nav a")
                .filter(function () {

                    if (urlpathname == "/") {
                        return this.href == index;
                    } else {
                        if (index != this.href) {
                            if (urlhref == this.href) {
                                return this.href == this.href;
                            }
                        }
                    }

                })
                .addClass("active")
                .parents("li")
                .addClass("active")
                .parents("ul.nav")
                .not("#sidebar");
        });
    </script>
</div>
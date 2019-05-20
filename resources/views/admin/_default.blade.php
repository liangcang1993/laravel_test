<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title>阿法科技后台管理</title>

        <meta name="description" content="overview &amp; stats" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="/css/bootstrap.css" />
        <link rel="stylesheet" href="/css/font-awesome.css" />
        <link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css"/>
        <link rel="stylesheet" href="/css/jquery-ui.css"/>

        <link rel="stylesheet" href="/css/sweet-alert.css"/>

        <!-- ace styles -->
        <link rel="stylesheet" href="/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

        <link rel="stylesheet" href="/css/default.css" class="ace-main-stylesheet" id="main-ace-style" />

        <link rel='stylesheet' type='text/css'href='/css/timepicki.css'/>

        <!-- basic scripts -->

        <!--[if !IE]> -->
        <script type="text/javascript">
            window.jQuery || document.write("<script src='/js/jquery.js'>"+"<"+"/script>");
        </script>

        <!-- <![endif]-->

        <script src="/js/bootstrap.js"></script>
        <script src="/js/bootstrap-datetimepicker.min.js"></script>
        <script type='text/javascript'src='/js/timepicki.js'></script>


        <script src="/js/jquery.form.js"></script>
        <script src="/js/jquery-ui.js"></script>

        <!-- ace scripts -->

        <script src="/js/ace/ace.js"></script>
        <script src="/js/ace/ace.sidebar.js"></script>
        <!-- <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script> -->

        <script src="/js/sweet-alert.min.js"></script>

        <script src="/js/admin.js"></script>

    </head>

    <body class="no-skin">
        <!-- #section:basics/navbar.layout -->
        <div id="navbar" class="navbar navbar-default">

            <div class="navbar-container" id="navbar-container">


                <!-- /section:basics/sidebar.mobile.toggle -->
                <div class="navbar-header pull-left">
                    <!-- #section:basics/navbar.layout.brand -->
                    <a href="/admin" class="navbar-brand">
                        <small>
                            阿法科技Jigsaw后台管理
                        </small>
                    </a>
                </div>
                <div class="navbar-buttons navbar-header " role="navigation">
                   <!--  -->
                </div>

                <!-- #section:basics/navbar.dropdown -->
                <div class="navbar-buttons navbar-header pull-right" role="navigation">
                    <ul class="nav ace-nav">
                        <!-- #section:basics/navbar.user_menu -->
                        <li class="light-blue">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">

                                <span class="user-info">
                                    <small></small>
                                    {{ Auth::user()->name }}
                                </span>

                                <i class="ace-icon fa fa-caret-down"></i>
                            </a>

                            <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                               <li>
                                    <a href="/admin/updatepassword" title="修改密码">
                                        <i class="ace-icon fa fa-cog"></i>
                                        修改密码
                                    </a>
                                </li>


                                <li>
                                    <a href="/logout">
                                        <i class="ace-icon fa fa-power-off"></i>
                                        退出
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- /section:basics/navbar.user_menu -->
                    </ul>
                </div>

                <!-- /section:basics/navbar.dropdown -->
            </div><!-- /.navbar-container -->
        </div>

        <!-- /section:basics/navbar.layout -->
        <div class="main-container" id="main-container">

            @include('admin._sidebar')

            <!-- /section:basics/sidebar -->
            <div class="main-content">
                <div class="main-content-inner">

                    @yield('content')

                    
                </div>
            </div><!-- /.main-content -->

            <div class="footer">
                <div class="footer-inner">
                    <!-- #section:basics/footer -->
                    <div class="footer-content">
                        <span class="bigger-120">
                            <span class="blue bolder">阿法科技</span>
                        </span>
                    </div>

                    <!-- /section:basics/footer -->
                </div>
            </div>

        </div><!-- /.main-container -->

    </body>
</html>

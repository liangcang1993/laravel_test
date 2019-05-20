<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title>阿法科技后台管理登录</title>

        <meta name="description" content="User login page" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="css/bootstrap.css" />
        <link rel="stylesheet" href="css/font-awesome.css" />

        <!-- text fonts -->
        <link rel="stylesheet" href="css/ace-fonts.css" />

        <!-- ace styles -->
        <link rel="stylesheet" href="css/ace.css" />

        <!--[if lte IE 9]>
            <link rel="stylesheet" href="css/ace-part2.css" />
        <![endif]-->
        <link rel="stylesheet" href="css/ace-rtl.css" />

        <!--[if lte IE 9]>
          <link rel="stylesheet" href="css/ace-ie.css" />
        <![endif]-->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <script src="js/respond.js"></script>
        <![endif]-->
    </head>

    <body class="login-layout">


    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <h1>
                                    <i class="ace-icon fa fa-leaf green"></i>
                                    <span class="red"></span>
                                    <span class="white" id="id-text2"></span>
                                </h1>
                                <h4 class="blue" id="id-company-text">&copy; 阿法科技</h4>
                            </div>

                            <div class="space-6"></div>

                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header blue lighter bigger">
                                                <i class="ace-icon fa fa-coffee green"></i>
                                                登录
                                            </h4>

                                            <div class="space-6"></div>

                                            <form method="POST" action="{{ URL('login') }}">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="form-control" placeholder="Email" name="email" value="{{old('email')}}" required autofocus />
                                                            <i class="ace-icon fa fa-user"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" class="form-control" placeholder="Password" name="password" id="password" required />
                                                            <i class="ace-icon fa fa-lock"></i>
                                                        </span>
                                                    </label>

                                                    <!--label class="block clearfix" >
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="col-xs-4" placeholder="验证码" name="captcha" id="captcha" />
                                                            &nbsp;&nbsp;
                                                            <img src="{{ Captcha::src() }}" id="verification" />
                                                            &nbsp;&nbsp;
                                                            <a class="text" href="javascript:void(0)" onclick="$('#verification').click();">换一张</a>
                                                        </span>
                                                    </label-->

                                                   <!--  <label class="block clearfix" >
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="form-control" placeholder="谷歌验证码" name="captcha" id="captcha" />
                                                        </span>
                                                    </label> -->

                                                    <div class="space"></div>

                                                    <div class="clearfix">
                                                        <label class="inline">
                                                            <input type="checkbox" class="ace" name="remember" />
                                                            <span class="lbl"> 自动登录</span>
                                                        </label>

                                                        <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                            <i class="ace-icon fa fa-key"></i>
                                                            <span class="bigger-110">登录</span>
                                                        </button>
                                                    </div>

                                                    <div class="space-4"></div>
                                                </fieldset>
                                            </form>



                                            <div class="space-6"></div>

                                        </div><!-- /.widget-main -->


                                    </div><!-- /.widget-body -->
                                </div><!-- /.login-box -->

                            </div><!-- /.position-relative -->


                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.main-content -->
        </div><!-- /.main-container -->

        <!-- basic scripts -->

        <!--[if !IE]> -->
        <script type="text/javascript">
            window.jQuery || document.write("<script src='js/jquery.js'>"+"<"+"/script>");
        </script>

        <!-- <![endif]-->

        <!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
        <script type="text/javascript">
            if('ontouchstart' in document.documentElement) document.write("<script src='js/jquery.mobile.custom.js'>"+"<"+"/script>");
        </script>

        <!-- inline scripts related to this page -->
        <script type="text/javascript">
            jQuery(function($) {
             $(document).on('click', '.toolbar a[data-target]', function(e) {
                e.preventDefault();
                var target = $(this).data('target');
                $('.widget-box.visible').removeClass('visible');//hide others
                $(target).addClass('visible');//show target
             });

                $("#verification").click(function(){
                    $("#verification").attr("src",'/captcha/default?' + Math.random());
                });
            });



            //you don't need this, just used for changing background
            jQuery(function($) {
             $('#btn-login-dark').on('click', function(e) {
                $('body').attr('class', 'login-layout');
                $('#id-text2').attr('class', 'white');
                $('#id-company-text').attr('class', 'blue');

                e.preventDefault();
             });
             $('#btn-login-light').on('click', function(e) {
                $('body').attr('class', 'login-layout light-login');
                $('#id-text2').attr('class', 'grey');
                $('#id-company-text').attr('class', 'blue');

                e.preventDefault();
             });
             $('#btn-login-blur').on('click', function(e) {
                $('body').attr('class', 'login-layout blur-login');
                $('#id-text2').attr('class', 'white');
                $('#id-company-text').attr('class', 'light-blue');

                e.preventDefault();
             });

            });
        </script>
    </body>
</html>

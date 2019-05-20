@extends('admin._default')
@section('content')
<link rel="stylesheet" href="/css/select2.css" />

                    <div class="breadcrumbs" id="breadcrumbs">

                        <ul class="breadcrumb">
                            <li>
                                <i class="ace-icon fa fa-cog home-icon"></i>
                                <a href="/updatepassword">修改密码</a>
                            </li>
                        </ul>

                    </div>

                    <div class="page-content">

                        <div class="page-header">
                            <h1>
                                修改密码
                                <small>
                                    规则: 必须8个字符以上，并且包含数字、大小写字母
                                </small>
                            </h1>
                        </div>

                        <div class="row">

                            <div class="col-xs-12">

                                @if (session('status'))
                                <div class="alert alert-block alert-success">
                                    {{ session('status') }}
                                </div>
                                @endif
                                
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form class="form-horizontal" id="form1" role="form" action="{{ URL('/admin/updatepassword') }}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 当前密码 </label>

                                        <div class="col-sm-9">
                                            <input type="password" name="oldpwd" placeholder="当前密码" class="col-xs-10 col-sm-5" value="" required />
                                        </div>
                                    </div>


                                    <div class="space-4"></div>


                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 新密码 </label>

                                        <div class="col-sm-9">
                                            <input type="password" name="password" placeholder="新密码" class="col-xs-10 col-sm-5" value="" required />
                                        </div>
                                    </div>

                                    <div class="space-4"></div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 确认密码 </label>

                                        <div class="col-sm-9">
                                            <input type="password" name="password_confirmation" placeholder="确认密码" class="col-xs-10 col-sm-5" value="" required />
                                        </div>
                                    </div>


                                    <div class="space-4"></div>
                                    
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button class="btn btn-info" type="submit">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                保存
                                            </button>

                                            &nbsp; &nbsp; &nbsp;
                                            <button class="btn" type="reset">
                                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                                重置
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

@endsection

@extends('admin._default')
@section('content')
    <link rel="stylesheet" href="/css/select2.css" />


    <div class="page-content">

        <div class="page-header">
            <h1>
                删除用户
            </h1>
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
                </div>
            </div>

        <div class="row">

            <div class="col-xs-12">


                <form class="form-horizontal" enctype="multipart/form-data"  id="form1" role="form" action="delUser" method="POST">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> udid </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control"   name="udid"   />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 是否解密: </label>

                        <div class="col-sm-9" style="padding-top: 7px">
                            <input name="en" type="radio" checked="checked" value="1"/>已解密
                            <input name="en" type="radio" value="0"/>未解密
                        </div>
                    </div>


                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit">
                                <i class="glyphicon glyphicon-remove"></i>
                                删除
                            </button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

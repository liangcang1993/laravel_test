@extends('admin._default')
@section('content')
<link rel="stylesheet" href="/css/select2.css" />

                   

                    <div class="page-content">

                        <div class="page-header">
                            <h1>
                                增加权限
                            </h1>
                        </div>

                        <div class="row">

                            <div class="col-xs-12">


                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form class="form-horizontal" id="form1" role="form" action="{{ URL('admin/admin_auth') }}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">


                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 名称 </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="name" placeholder="名称" class="col-xs-10 col-sm-5" value="{{old('name')}}" required />
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 控制器 </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="controller" placeholder="控制器" class="col-xs-10 col-sm-5" value="{{old('controller')}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 方法 </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="action" placeholder="方法" class="col-xs-10 col-sm-5" value="{{old('action')}}"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> uri </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="uri" placeholder="例如：manly/type" class="col-xs-10 col-sm-5" value="{{old('uri')}}"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> weight </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="weight" placeholder="0" class="col-xs-10 col-sm-5" value="{{old('weight')}}"/>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 状态 </label>

                                        <div class="col-sm-9">
                                            <select name="status">
                                                <option value="1">显示</option>
                                                <option value="0" selected="selected">不显示</option>
                                            </select>
                                            
                                        </div>
                                    </div>

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

        <script src="/js/chosen.jquery.js"></script>
        <script src="/js/bootstrap-tag.js"></script>
        <script src="/js/select2.js"></script>
        <script src="/js/bootstrap-multiselect.js"></script>
        <link rel="stylesheet" href="/css/chosen.css" />
@endsection

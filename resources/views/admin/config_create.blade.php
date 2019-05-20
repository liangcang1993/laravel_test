@extends('admin._default')
@section('content')
<link rel="stylesheet" href="/css/select2.css" />


                    <div class="page-content">
                        <div class="page-header">
                            <h1>
                                增加
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

                                <form class="form-horizontal" enctype="multipart/form-data"  id="form1" role="form" action="{{ URL('admin/'. $modelName) }}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> key </label>

                                        <div class="col-sm-9">
                                            <input type="text" class="col-xs-10 col-sm-4" name="key"  required />
                                        </div>
                                    </div>
                                    <div class="space-4"></div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">value </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="value"  class="col-xs-10 col-sm-4" value=''  />
                                            <a  target="_blank" href="http://www.qqe2.com/?json={}">json编辑</a></td>
                                        </div>
                                    </div>
                                    <div class="space-4"></div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">desc </label>

                                        <div class="col-sm-9">
                                            <textarea name="desc" style="height: 100px;width: 300px" class="col-xs-10 col-sm-4"></textarea>
                                        </div>
                                    </div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button class="btn btn-info" type="submit">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                保存
                                            </button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

@endsection

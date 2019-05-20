@extends('admin._default')

@section('content')

<link rel="stylesheet" href="/css/select2.css" />

                    <div class="breadcrumbs" id="breadcrumbs">

                        <ul class="breadcrumb">
                            <li>
                                <i class="ace-icon fa fa-user home-icon"></i>
                                <a href="/admin/user">用户管理</a>
                            </li>
                            <li class="active">修改用户</li>
                        </ul>

                    </div>

                    <div class="page-content">

                        <div class="page-header">
                            <h1>
                                修改用户
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

                                <form class="form-horizontal" role="form" action="{{ url('admin/admin_user/' . $user->id) }}" method="POST">
                                    <input name="_method" type="hidden" value="PUT">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="area_ids" id="area_ids" value="{{ $user->area_ids }}">
                                    <input type="hidden" name="school_ids" id="school_ids" value="{{ $user->school_ids }}">

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 姓名 </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="name" placeholder="姓名" class="col-xs-10 col-sm-5" value="{{ $user->name }}" required />
                                        </div>
                                    </div>



                                    

                                    <div class="space-4"></div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 密码 </label>

                                        <div class="col-sm-9">
                                            <input type="password" id="" name="password" placeholder="不修改请保持为空" class="col-xs-10 col-sm-5" value="" />
                                        </div>
                                    </div>

                                    <div class="space-4"></div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 手机 </label>

                                        <div class="col-sm-9">
                                            <input type="text" min="11" id="" name="phone" placeholder="手机" class="col-xs-10 col-sm-5" value="{{$user->phone}}" required />
                                        </div>
                                    </div>


                                    <div class="space-4"></div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 邮箱 </label>

                                        <div class="col-sm-9">
                                            <input type="email" id="" name="email" placeholder="邮箱" class="col-xs-10 col-sm-5" value="{{$user->email}}" />
                                        </div>
                                    </div>

                                    <div class="space-4"></div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 角色 </label>
                                        <div class="col-sm-9">
                                            <select name="role" id="">
                                                <option type="checkbox"  value="0" selected></option>
                                                @foreach($role as $r)
                                                    <option type="checkbox"  value="{{$r['id']}}" @if($r['id'] == $user['role_id']) selected @endif>{{$r['role_name']}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        
                                    </div>

                                    <div class="space-4"></div>

                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> </label>--}}

                                        {{--<div class="col-sm-9">--}}
                                                {{--<div class="checkbox">--}}
                                                    {{--@if (Auth::user()->is_super > 0)--}}
                                                    {{--<label>--}}
                                                        {{--<input name="is_super" type="checkbox" class="ace"  @if ($user->is_super == 1) checked="checked" @endif />--}}
                                                        {{--<span class="lbl"> 超级管理员 </span>--}}
                                                    {{--</label>--}}
                                                    {{--@endif--}}
        {{----}}
                                                {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}

 

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


            <script type="text/javascript">
            jQuery(function($){
                $('.select2').css('width','200px').select2({allowClear:true})
                $('#select2-multiple-style .btn').on('click', function(e){
                    var target = $(this).find('input[type=radio]');
                    var which = parseInt(target.val());
                    if(which == 2) $('.select2').addClass('tag-input-style');
                     else $('.select2').removeClass('tag-input-style');
                });
                $('.select2').change(function(){
                    var str = $('.select2').select2("val");
                    
                    $("#ext").empty();

                    $.each(str,function(n,value){
                        $.ajax({
                            url: '/admin/user/getExt',
                            data: 'proj_id='+value,
                            type: 'GET',
                            dataType: 'json',
                            success : function(response) {
                               
                                jQuery.each(response,function(k,v){
                                    $("#ext").append("<div class='space-4'></div><div class='form-group'><label class='col-sm-3 control-label no-padding-right' for='form-field-1'> "+v.attr_title+" </label><div class='col-sm-9'><input type='text' id='' name='ext["+v.id+"]' placeholder='"+v.attr_remark+"' class='col-xs-10 col-sm-5' value='"+v.attr_default+"'  /></div></div>"); 
                                })
                            },
                            error: function(response) {
                                //con
                            }
                        })
                    });
                });

                $('.multiselect').multiselect({
                 enableFiltering: true,
                 checkboxName: 'role_ids[]',
                 buttonClass: 'btn btn-white btn-primary',
                 templates: {
                    button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"></button>',
                    ul: '<ul class="multiselect-container dropdown-menu"></ul>',
                    filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" name="" type="text"></div></li>',
                    filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default btn-white btn-grey multiselect-clear-filter" type="button"><i class="fa fa-times-circle red2"></i></button></span>',
                    li: '<li><a href="javascript:void(0);"><label></label></a></li>',
                    divider: '<li class="multiselect-item divider"></li>',
                    liGroup: '<li class="multiselect-item group"><label class="multiselect-group"></label></li>'
                 }
                });

                $('.multiselect2').multiselect({
                 enableFiltering: true,
                 checkboxName: 'dept_ids[]',
                 buttonClass: 'btn btn-white btn-primary',
                 templates: {
                    button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"></button>',
                    ul: '<ul class="multiselect-container dropdown-menu"></ul>',
                    filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" name="" type="text"></div></li>',
                    filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default btn-white btn-grey multiselect-clear-filter" type="button"><i class="fa fa-times-circle red2"></i></button></span>',
                    li: '<li><a href="javascript:void(0);"><label></label></a></li>',
                    divider: '<li class="multiselect-item divider"></li>',
                    liGroup: '<li class="multiselect-item group"><label class="multiselect-group"></label></li>'
                 }
                });
            
                if(!ace.vars['touch']) {
                    $('.chosen-select').chosen({allow_single_deselect:true}); 
                    //resize the chosen on window resize
            
                    $(window)
                    .off('resize.chosen')
                    .on('resize.chosen', function() {
                        $('.chosen-select').each(function() {
                             var $this = $(this);
                             $this.next().css({'width': $this.parent().width()});
                        })
                    }).trigger('resize.chosen');
                    //resize chosen on sidebar collapse/expand
                    $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                        if(event_name != 'sidebar_collapsed') return;
                        $('.chosen-select').each(function() {
                             var $this = $(this);
                             $this.next().css({'width': $this.parent().width()});
                        })
                    });
            
            
                    $('#chosen-multiple-style .btn').on('click', function(e){
                        var target = $(this).find('input[type=radio]');
                        var which = parseInt(target.val());
                        if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
                         else $('#form-field-select-4').removeClass('tag-input-style');
                    });
                }
            });
            
        </script>
        <link rel="stylesheet" href="/css/chosen.css" />

@endsection

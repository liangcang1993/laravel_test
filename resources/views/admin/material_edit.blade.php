@extends('admin._default')
@section('content')
<link rel="stylesheet" href="/css/select2.css" />

<div class="page-content">

    <div class="page-header">
        <h1>
            编辑
            
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

            <form class="form-horizontal" enctype="multipart/form-data"  id="form1" role="form" action="{{ URL('admin/'. $modelName.'/'.$model['id']) }}" onsubmit="return dosubmit()" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="PATCH">

                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">type </label>
                    <div class="col-sm-9">
                       <select name="type" id="province">
                          <option value=""></option>
                      </select>
                  </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">sub_type </label>
                <div class="col-sm-9">
                  <select name="sub_type" id="city">
                    <option value=""></option>
                </select>
            </div>
        </div>

        
        <div class="form-group" id="uni">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 素材 </label>

            <div class="col-sm-9">
                @if( $model['small_pic'] != '')
                <img  style="width:150px" src='{{$model->smallPicUrl()}}'/>
                @endif
                <input type="file" id="" name="large_pic"  class="form-control"   value=''  />
            </div>
        </div>
        <div class="form-group" id="uni">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">is_vip</label>

            <div class="col-sm-9">
                <input type="text" id="" name="is_vip"  class="col-xs-10 col-sm-2"  value='{{ $model['is_vip']}}'  />
            </div>
        </div>

        <div class="form-group" id="uni">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">unique_name(后台)</label>

            <div class="col-sm-9">
                <input type="text" id="" name="unique_name"  class="col-xs-10 col-sm-2"  value='{{ $model['unique_name']}}'  />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">display_name </label>

            <div class="col-sm-9">
                <input type="text" id="" name="display_name"  class="col-xs-10 col-sm-2"   value="{{ $model['display_name']}}"  />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">display_name_cn </label>

            <div class="col-sm-9">
                <input type="text" id="" name="display_name_cn"  class="col-xs-10 col-sm-2"   value="{{ $model['display_name_cn']}}"  />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">display_name_tw </label>

            <div class="col-sm-9">
                <input type="text" id="" name="display_name_tw"  class="col-xs-10 col-sm-2"   value="{{ $model['display_name_tw']}}"  />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">display_name_jp </label>

            <div class="col-sm-9">
                <input type="text" id="" name="display_name_jp"  class="col-xs-10 col-sm-2"   value="{{ $model['display_name_jp']}}"  />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">display_name_ru </label>

            <div class="col-sm-9">
                <input type="text" id="" name="display_name_ru"  class="col-xs-10 col-sm-2"   value="{{ $model['display_name_ru']}}"  />
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">desc </label>

            <div class="col-sm-9">
                <textarea name="desc"  class="col-xs-10 col-sm-2">{{ $model['desc']}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">desc_cn </label>

            <div class="col-sm-9">
                <textarea name="desc_cn"  class="col-xs-10 col-sm-2">{{ $model['desc_cn']}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">desc_tw </label>

            <div class="col-sm-9">
                <textarea name="desc_tw"  class="col-xs-10 col-sm-2">{{ $model['desc_tw']}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">desc_jp </label>

            <div class="col-sm-9">
                <textarea name="desc_jp"  class="col-xs-10 col-sm-2">{{ $model['desc_jp']}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">desc_ru </label>

            <div class="col-sm-9">
                <textarea name="desc_ru"  class="col-xs-10 col-sm-2">{{ $model['desc_ru']}}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">解锁金币 </label>

            <div class="col-sm-9">
                <input type="text" name="coin"  class="col-xs-10 col-sm-2"   value="{{ $model['coin']}}"  >
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_20 </label>

            <div class="col-sm-9">
                <input type="text" name="level_20_coin"  class="col-xs-10 col-sm-2"   value="{{ $model['level_20_coin']}}"  >
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_24 </label>

            <div class="col-sm-9">
                <input type="text" name="level_24_coin"  class="col-xs-10 col-sm-2"   value="{{ $model['level_24_coin']}}"  >
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_48 </label>

            <div class="col-sm-9">
                <input type="text" name="level_48_coin"  class="col-xs-10 col-sm-2"   value="{{ $model['level_48_coin']}}"  >
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_70 </label>

            <div class="col-sm-9">
                <input type="text" name="level_70_coin"  class="col-xs-10 col-sm-2"   value="{{ $model['level_70_coin']}}"  >
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_108 </label>

            <div class="col-sm-9">
                <input type="text" name="level_108_coin"  class="col-xs-10 col-sm-2"   value="{{ $model['level_108_coin']}}"  >
            </div>
        </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_180 </label>

                    <div class="col-sm-9">
                        <input type="text" name="level_180_coin"  class="col-xs-10 col-sm-2"   value="{{ $model['level_180_coin']}}"  >
                    </div>
                </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_288 </label>

            <div class="col-sm-9">
                <input type="text" name="level_288_coin"  class="col-xs-10 col-sm-2"   value="{{ $model['level_288_coin']}}"  >
            </div>
        </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_336 </label>

                    <div class="col-sm-9">
                        <input type="text" name="level_336_coin"  class="col-xs-10 col-sm-2"   value="{{ $model['level_336_coin']}}"  >
                    </div>
                </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_432 </label>

            <div class="col-sm-9">
                <input type="text" name="level_432_coin"  class="col-xs-10 col-sm-2"   value="{{ $model['level_432_coin']}}"  >
            </div>
        </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_504 </label>

                    <div class="col-sm-9">
                        <input type="text" name="level_504_coin"  class="col-xs-10 col-sm-2"   value="{{ $model['level_504_coin']}}"  >
                    </div>
                </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_704 </label>

            <div class="col-sm-9">
                <input type="text" name="level_704_coin"  class="col-xs-10 col-sm-2"   value="{{ $model['level_704_coin']}}"  >
            </div>
        </div>

        
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">banner_color </label>

            <div class="col-sm-9">
                <input type="text"  name="display_color"  class="col-xs-10 col-sm-2"  value='{{ $model['display_color']}}'  />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">banner_color2 </label>

            <div class="col-sm-9">
                <input type="text"  name="display_color2"  class="col-xs-10 col-sm-2"  value='{{ $model['display_color2']}}'  />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">version </label>

            <div class="col-sm-9">
                <input type="text"  name="version"  class="col-xs-10 col-sm-2"  value='{{ $model['version']}}'  />
            </div>
        </div>
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit" id='submit'>
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    编辑
                </button>

            </div>
        </div>
    </form>
</div>
</div>
</div>


<script type="text/javascript">

        var isCommitted = false;//表单是否已经提交标识，默认为false

        function dosubmit(){
            if(isCommitted==false){
                isCommitted = true;//提交表单后，将表单是否已经提交标识设置为true
                $('#submit').attr("disabled","true");
                return true;//返回true让表单正常提交
            }else{
                return false;//返回false那么表单将不提交
            }
        }

        $(function(){
            //页面加载完毕后开始执行的事件
            var city_json='{!!$type_json!!}';
            var city_obj=eval('('+city_json+')');
            var oldptype = "{{$model['type']}}";
            var oldpsubtype = "{{$model['sub_type']}}";

            for (var key in city_obj)
            {
                if ( oldptype == key) 
                    {
                     $("#province").append("<option value='"+key+"' selected='selected'>"+key+"</option>"); 
                 }else {
                     $("#province").append("<option value='"+key+"'>"+key+"</option>");
                 }

                $("#city").html('<option value=""></option>');
                for(var k in city_obj[oldptype])
                {
                    var now_city=city_obj[oldptype][k];
                    if ( oldpsubtype == now_city) 
                    {
                       $("#city").append('<option value="'+now_city+'" selected="selected">'+now_city+'</option>'); 
                    }else {
                       $("#city").append('<option value="'+now_city+'">'+now_city+'</option>');
                    }
                }
            }
        });


 
    </script>

    @endsection

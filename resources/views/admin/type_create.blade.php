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
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">平台 </label>
					<div class="col-sm-9">
						<select name="dev" id="dev">
							<option value="0" @if(Session::get('dev') == 0) selected="selected" @endif>Ios</option>
							<option value="1" @if(Session::get('dev') == 1) selected="selected" @endif>Android</option>
							<option value="2" @if(Session::get('dev') > 1) selected="selected" @endif>all</option>
						</select>
					</div>
				</div>
				<div class="form-group" id="iosdiv">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">parentType </label>

					<div class="col-sm-9">
					   <select name='parent_id' id="type">
						<option value="" selected="selected">
						</option>
						@foreach ($types as $type)
						<option value="{{$type->id}}">
						  {{$type->name}}
					  </option>
					  @endforeach
				  </select>
			  </div>
		  </div>
				<div class="form-group" id="androiddiv" display="hidden">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">android_parentType </label>

					<div class="col-sm-9">
						<select name='android_parent_id' id="androidtype">
							<option value="" selected="selected">
							</option>

						</select>
					</div>
				</div>
		  <div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1" >name </label>

			<div class="col-sm-9">
				<input type="text" id="" name="name"  class="col-xs-10 col-sm-5"  required value=''  />
			</div>
		</div>
				<div class="form-group" id="androidnamediv" display="hidden">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1" >Android_name </label>

					<div class="col-sm-9">
						<input type="text" id="" name="androidname"  class="col-xs-10 col-sm-5" />
					</div>
				</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">weight </label>

			<div class="col-sm-9">
				<input type="text" id="" name="weight"  class="col-xs-10 col-sm-2"   value=''  />
			</div>
		</div>
				<div class="form-group"  id="androidweightdiv" display="hidden">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Android_weight </label>

					<div class="col-sm-9">
						<input type="text" id="" name="android_weight"  class="col-xs-10 col-sm-2"   value=''  />
					</div>
				</div>



		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 封面 </label>
			<div class="col-sm-9">
				<input type="file"  class="form-control" name="pic"   />
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> android封面 </label>
			<div class="col-sm-9">
				<input type="file"  class="form-control" name="android_pic"   />

			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ios封面框 </label>
			<div class="col-sm-9">
				<input type="file"  class="form-control" name="ios_pic_frame"   />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">is_vip </label>

			<div class="col-sm-9">
				<input type="text" id="" name="is_vip"  class="col-xs-10 col-sm-2"   value='0'  />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">包付费数 </label>

			<div class="col-sm-9">
				<input type="text" name="packagepay"  class="col-xs-10 col-sm-2"   value='0'  >
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">color1 </label>

			<div class="col-sm-9">
				<input type="text" id="" name="color1"  class="col-xs-10 col-sm-2"   value=''  />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">color2 </label>

			<div class="col-sm-9">
				<input type="text" id="" name="color2"  class="col-xs-10 col-sm-2"   value=''  />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">bg_color </label>

			<div class="col-sm-9">
				<input type="text" id="" name="bg_color"  class="col-xs-10 col-sm-2"   value=''  />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">简体中文 </label>

			<div class="col-sm-9">
				<input type="text" id="" name="name_cn"  class="col-xs-10 col-sm-5"   value=''  />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">繁体中文 </label>

			<div class="col-sm-9">
				<input type="text" id="" name="name_tw"  class="col-xs-10 col-sm-5"   value=''  />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">俄语 </label>

			<div class="col-sm-9">
				<input type="text" id="" name="name_ru"  class="col-xs-10 col-sm-5"   value=''  />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">日语 </label>

			<div class="col-sm-9">
				<input type="text" id="" name="name_jp"  class="col-xs-10 col-sm-5"   value=''  />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">西班牙语 </label>

			<div class="col-sm-9">
				<input type="text" id="" name="name_es"  class="col-xs-10 col-sm-5"   value=''  />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">英文描述 </label>

			<div class="col-sm-9">
				<textarea name="desc"  class="col-xs-10 col-sm-2"   value=''  style="width: 500px;height: 200px;"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述(简体中文) </label>

			<div class="col-sm-9">
				<textarea name="desc_cn"  class="col-xs-10 col-sm-2"   value=''  style="width: 500px;height: 200px;"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述(繁体中文) </label>

			<div class="col-sm-9">
				<textarea name="desc_tw"  class="col-xs-10 col-sm-2"   value=''  style="width: 500px;height: 200px;"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述(俄语) </label>

			<div class="col-sm-9">
				<textarea name="desc_ru"  class="col-xs-10 col-sm-2"   value=''  style="width: 500px;height: 200px;"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述(日语) </label>

			<div class="col-sm-9">
				<textarea name="desc_jp"  class="col-xs-10 col-sm-2"   value=''  style="width: 500px;height: 200px;"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述(西班牙语) </label>

			<div class="col-sm-9">
				<textarea name="desc_es"  class="col-xs-10 col-sm-2"   value=''  style="width: 500px;height: 200px;"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">is_new </label>

			<div class="col-sm-9">
				<input type="text" name="is_new"  class="col-xs-10 col-sm-2"   value='0'  >
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">is_new color </label>

			<div class="col-sm-9">
				<input type="text" name="is_new_color"  class="col-xs-10 col-sm-2"   value=''  >
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">字体 </label>

			<div class="col-sm-9">
				<input type="text" name="font"  class="col-xs-10 col-sm-2"   value=''  >
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">version </label>

			<div class="col-sm-9">
				<input type="text" name="version"  class="col-xs-10 col-sm-2"   value=''  >
			</div>
		</div>



		<div class="clearfix form-actions">
			<div class="col-md-offset-3 col-md-9">
				<input class="btn btn-info" type="submit" value="添加">
			</div>
		</div>
	</form>
</div>
</div>
</div>
<script>
	$("#androiddiv").hide();
	$("#androidnamediv").hide();
	$("#androidweightdiv").hide();
	$("#dev").change(function () {
		var dev = $("#dev").val();
		$.ajax({
			type: "get",
			url: "{{ URL('admin/getCreateTypes') }}",
			dataType: "jsonp",
			data:"dev="+dev+"&flag=type",
			jsonp:'callback',
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert(XMLHttpRequest.status);
				alert(XMLHttpRequest.readyState);
				alert(textStatus);
			},
			success: function(data) {
				if (dev == 2)
				{
					$("#androiddiv").show();
					$("#androidnamediv").show();
					$("#androidweightdiv").show();
					$("#androidtype").empty();
					$("#androidtype").append("<option value='' selected='selected'></option>");
					$.each(data.and, function (i, item) {
						$("#androidtype").append("<option value='" + i + "'>" + item + "</option>");
					});
					$("#type").empty();
					$("#type").append("<option value='' selected='selected'></option>");
					$.each(data.ios, function (i, item) {
						$("#type").append("<option value='" + i + "'>" + item + "</option>");
					});
				}else{
					$("#androiddiv").hide();
					$("#androidnamediv").hide();
					$("#androidweightdiv").hide();
					$("#type").empty();
					$("#type").append("<option value='' selected='selected'></option>");
					$.each(data, function (i, item) {
						$("#type").append("<option value='" + i + "'>" + item + "</option>");
					});
				}
				// $("#type").empty();
				// $.each(data, function (i, item) {
				//     $("#type").append("<option value='" + i + "'>" + item + "</option>");
				// });
			}
		});
	});
</script>
@endsection

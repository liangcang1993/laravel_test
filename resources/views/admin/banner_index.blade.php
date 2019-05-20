@extends('admin._default')

@section('content')


<div class="page-content">

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

			<div class="well well-sm">
				<form action="/admin/{{$modelName}}/" method="get">

					<select name='position'>
						<option value="">位置</option>

						@if($filter['position'] == 'home')
						<option value='home' selected="true">home </option>
						@else
						<option value='home'>home </option>
						@endif
						
						@if($filter['position'] == 'store')
						<option value='store' selected="true">store </option>
						@else
						<option value='store'>store </option>
						@endif
					</select>
					<select name='sort'>
						<option value="">排序</option>

						@if($filter['sort'] == 'id desc')
						<option value='id desc' selected="true">创建时间从近到远 </option>
						@else
						<option value='id desc'>创建时间从近到远 </option>
						@endif
						@if($filter['sort'] == 'id asc')
						<option value='id asc' selected="true">创建时间从远到近 </option>
						@else
						<option value='id asc'>创建时间从远到近 </option>
						@endif
					</select>
					<input type='hidden' name="dev" value="{{$filter['dev']}}">
					<button type="submit" class="btn btn-white">筛 选</button>

					<a class="btn btn-white btn-info btn-bold " href="/admin/{{$modelName}}/create">
						<i class="ace-icon fa glyphicon-plus bigger-120 blue"></i>
						增加
					</a>
					<a class="btn btn-white btn-info btn-bold " href="/admin/cleanCache">
						<i class="ace-icon fa  bigger-120 blue"></i>
						清除缓存
					</a>
					<a class="btn btn-white btn-info btn-bold " href="/admin/bannerBatch">
						<i class="ace-icon bigger-120 blue"></i>
						批量修改
					</a>
				</form> 
			</div>
			<table id="" class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>id</th>
						
						<th>position</th>
						<th>large_pic</th>
						<!-- <th>large_pic_cn</th> -->

						
						<!-- <th>type</th> -->
						<th>title</th>
						<th>英文描述</th>
						<th>中文描述</th>
						<th>繁体中文描述</th>
						<th>俄语描述</th>
						<th>日语描述</th>
						<th>西班牙语描述</th>
						<th>weight</th>
						<!-- <th>title</th> -->
						
						<th>color</th>
						
						<th>version</th>
						<!-- <th>operatids</th> -->
						<th>浏览数</th>
						<th>打开数</th>
						<th>打开率</th>
						<th>Android素材ID</th>
						<th>ios素材ID</th>
						<th>推荐date</th>
						<th>修改</th>
						<th>创建时间</th>
						<th>操作用户</th>
						<th>删除</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($list as $l)
					<form class="form-horizontal" enctype="multipart/form-data" role="form" action="{{ url('admin/' . $modelName .'/' . $l->id) }}" method="POST">
						<input name="_method" type="hidden" value="PUT">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<tr>
							<td>{{$l->id }}</td>
							
							<td>
								<select name='position'>

									@if($l->position == 'home')
									<option value='home' selected="true">首页 </option>
									@else
									<option value='home'>首页 </option>
									@endif
									@if($l->position == 'store')
									<option value='store' selected="true">商店 </option>
									@else
									<option value='store'>商店 </option>
									@endif
								</select>
							</td>
							<td>
								<img style="width:200px"  src='{{$l->largePicUrl()}}'/>
								<input type="file" id="" name="large_pic" style="width:100px" class="col-xs-10 col-sm-2"/>
							</td>
							

							
							
							<!-- <td>
								<input type="text" id="" style="width:100px" name="type"  class="col-xs-10 col-sm-2" value="{{$l->type}}" />
							</td> -->
							<td>
								<li>
									英文:<input type="text" id="" style="width:200px" name="title"  class="col-xs-10 col-sm-2" value="{{$l->title}}" />
								</li>
								<li>
									中文:<input type="text" id="" style="width:200px" name="title_cn"  class="col-xs-10 col-sm-2" value="{{$l->title_cn}}" />
								</li>
								<li>
									繁体:<input type="text" id="" style="width:200px" name="title_tw"  class="col-xs-10 col-sm-2" value="{{$l->title_tw}}" />
								</li>
								<li>
									日本:<input type="text" id="" style="width:200px" name="title_jp"  class="col-xs-10 col-sm-2" value="{{$l->title_jp}}" />
								</li>
								<li>
									俄语:<input type="text" id="" style="width:200px" name="title_ru"  class="col-xs-10 col-sm-2" value="{{$l->title_ru}}" />
								</li>
								<li>
									俄语:<input type="text" id="" style="width:200px" name="title_es"  class="col-xs-10 col-sm-2" value="{{$l->title_es}}" />
								</li>
                            
                            </td>
                            <td>
                                <textarea name="desc"  class="col-xs-10 col-sm-2"  style="width: 200px;height:300px;" >{{$l->desc}}</textarea>
                            </td>
                            <td>
                                <textarea name="desc_cn"  class="col-xs-10 col-sm-2"  style="width: 200px;height:300px;" >{{$l->desc_cn}}</textarea>
                            </td>
                            
                            <td>
                                <textarea name="desc_tw"  class="col-xs-10 col-sm-2"  style="width: 200px;height:300px;" >{{$l->desc_tw}}</textarea>
                            </td>
                            <td>
                                <textarea name="desc_ru"  class="col-xs-10 col-sm-2"  style="width: 200px;height:300px;" >{{$l->desc_ru}}</textarea>
                            </td>
                            <td>
                                <textarea name="desc_jp"  class="col-xs-10 col-sm-2"  style="width: 200px;height:300px;" >{{$l->desc_jp}}</textarea>
                            </td>
                            <td>
                                <textarea name="desc_es"  class="col-xs-10 col-sm-2"  style="width: 200px;height:300px;" >{{$l->desc_es}}</textarea>
                            </td>
							<td>
								<input type="text" id="" style="width:100px" name="weight"  class="col-xs-10 col-sm-2" value="{{$l->weight}}" />
							</td>
							

							<td>
								<input type="text" id="" style="width:100px" name="color"  class="col-xs-10 col-sm-2" value="{{$l->color}}" />
							</td>

							

							<td>
								<input type="text" id="" style="width:100px" name="version"  class="col-xs-10 col-sm-2" value="{{$l->version}}" />
							</td>
							<!-- <td>{{$l->operaterids}}</td> -->
							<td>{{$l->view_num}}</td>
							<td>{{$l->open_num}}</td>
							<td>{{$l->openrate}}%</td>
							<td>
								<input type="text" id="" style="width:100px" name="android_mid"  class="col-xs-10 col-sm-2" value="{{$l->android_mid}}" />
							</td>
							<td>
								<input type="text" id="" style="width:100px" name="ios_mid"  class="col-xs-10 col-sm-2" value="{{$l->ios_mid}}" />
							</td>
							<td>
								<input type="text" id="" style="width:100px" name="recommend_date"  class="col-xs-10 col-sm-2" value="{{$l->recommend_date}}" />
							</td>
							<td> 
								<button class="btn btn-info" style="width:100px" type="submit">
									<i class="ace-icon fa fa-check bigger-110"></i>
									修改
								</button>
								
							</td>
						</form>
						<td>{{$l->created_at}}</td>
						<td>{{$l->handle_user}}</td>
						<td>
							<div class="hidden-sm hidden-xs action-buttons">

								<form action="{{ URL('admin/' . $modelName .'/' .$l->id) }}" method="POST" style="display: inline;">
									<input name="_method" type="hidden" value="DELETE">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<button class="btn btn-white btn-warning btn-bold">
										<i class="ace-icon fa fa-trash-o bigger-120 orange"></i>删除
									</button>  
								</form>                                                              
							</div>
						</td>

					</tr>

					@endforeach
				</tbody>
			</table>

			<div class="pull-right ">
				共{{ $list->total() }}条记录{!! 
				$list->appends(['position' => $filter['position']])
				->appends(['sort' => $filter['sort']])
				->appends(['dev' => $filter['dev']])

				->render() !!}
			</div>
		</div>
	</div>
</div>

@endsection

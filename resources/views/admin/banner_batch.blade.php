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
					</tr>
				</thead>
				<tbody>
					@foreach ($list as $l)
					<form class="form-horizontal" enctype="multipart/form-data" role="form" action="{{ url('admin/updateBatch') }}" method="POST">
						<input name="_method" type="hidden" value="PUT">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id[]" value="{{$l->id}}"> 
						<tr>
							<td>{{$l->id }}</td>
							
							<td>
								<select name='position[]'>

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
								<input type="file" id="" name="large_pic[]" style="width:100px" class="col-xs-10 col-sm-2"/>
							</td>
							
							
							<!-- <td>
								<input type="text" id="" style="width:100px" name="type"  class="col-xs-10 col-sm-2" value="{{$l->type}}" />
							</td> -->
							<td>
								<li>
									英文:<input type="text" id="" style="width:200px" name="title[]"  class="col-xs-10 col-sm-2" value="{{$l->title}}" />
								</li>
								<li>
									中文:<input type="text" id="" style="width:200px" name="title_cn[]"  class="col-xs-10 col-sm-2" value="{{$l->title_cn}}" />
								</li>
								<li>
									繁体:<input type="text" id="" style="width:200px" name="title_tw[]"  class="col-xs-10 col-sm-2" value="{{$l->title_tw}}" />
								</li>
								<li>
									日本:<input type="text" id="" style="width:200px" name="title_jp[]"  class="col-xs-10 col-sm-2" value="{{$l->title_jp}}" />
								</li>
								<li>
									俄语:<input type="text" id="" style="width:200px" name="title_ru[]"  class="col-xs-10 col-sm-2" value="{{$l->title_ru}}" />
								</li>
								<li>
									俄语:<input type="text" id="" style="width:200px" name="title_es[]"  class="col-xs-10 col-sm-2" value="{{$l->title_es}}" />
								</li>
                            
                            </td>
                            <td>
                                <textarea name="desc[]"  class="col-xs-10 col-sm-2"  style="width: 200px;height:300px;" >{{$l->desc}}</textarea>
                            </td>
                            <td>
                                <textarea name="desc_cn[]"  class="col-xs-10 col-sm-2"  style="width: 200px;height:300px;" >{{$l->desc_cn}}</textarea>
                            </td>
                            
                            <td>
                                <textarea name="desc_tw[]"  class="col-xs-10 col-sm-2"  style="width: 200px;height:300px;" >{{$l->desc_tw}}</textarea>
                            </td>
                            <td>
                                <textarea name="desc_ru[]"  class="col-xs-10 col-sm-2"  style="width: 200px;height:300px;" >{{$l->desc_ru}}</textarea>
                            </td>
                            <td>
                                <textarea name="desc_jp[]"  class="col-xs-10 col-sm-2"  style="width: 200px;height:300px;" >{{$l->desc_jp}}</textarea>
                            </td>
                            <td>
                                <textarea name="desc_es[]"  class="col-xs-10 col-sm-2"  style="width: 200px;height:300px;" >{{$l->desc_es}}</textarea>
                            </td>
							<td>
								<input type="text" id="" style="width:100px" name="weight[]"  class="col-xs-10 col-sm-2" value="{{$l->weight}}" />
							</td>
							

							<td>
								<input type="text" id="" style="width:100px" name="color[]"  class="col-xs-10 col-sm-2" value="{{$l->color}}" />
							</td>

							

							<td>
								<input type="text" id="" style="width:100px" name="version[]"  class="col-xs-10 col-sm-2" value="{{$l->version}}" />
							</td>
							<!-- <td>{{$l->operaterids}}</td> -->
							<td>{{$l->view_num}}</td>
							<td>{{$l->open_num}}</td>
							<td>{{$l->openrate}}%</td>
							<td>
								<input type="text" id="" style="width:100px" name="android_mid[]"  class="col-xs-10 col-sm-2" value="{{$l->android_mid}}" />
							</td>
							<td>
								<input type="text" id="" style="width:100px" name="ios_mid[]"  class="col-xs-10 col-sm-2" value="{{$l->ios_mid}}" />
							</td>
							<td>
								<input type="text" id="" style="width:100px" name="recommend_date[]"  class="col-xs-10 col-sm-2" value="{{$l->recommend_date}}" />
							</td>
							@endforeach
							<button class="btn btn-info" style="width:100px" type="submit">
								<i class="ace-icon fa fa-check bigger-110"></i>
								修改
							</button>     


						</form>



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

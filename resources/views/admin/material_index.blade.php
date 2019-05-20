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
                    <form action="/admin/{{$modelName}}" method="get">
                        <label>素材管理</label>

                        <input class="input-middle" name="type" type="text" placeholder="id、type(支持模糊搜索)"
                               value="{{ $filter['type']}}" id="type"/>
                        <select id="typeselect">
                            <option value="" selected="selected"></option>

                        </select>

                        <input class="input-middle" name="sub_type" type="text" placeholder="sub_type(支持模糊搜索)"
                               value="{{ $filter['sub_type']}}"/>
                        <select name='is_vip'>
                            <option value=''>所有</option>
                            @if($filter['is_vip'] == '0')
                                <option value='0' selected="true">免费</option>
                            @else
                                <option value='0'>免费</option>
                            @endif
                            @if($filter['is_vip'] == '1')
                                <option value='1' selected="true">付费</option>
                            @else
                                <option value='1'>付费</option>
                            @endif
                        </select>
                        <select name='is_pass'>
                            <option value=''>全部is_pass</option>
                            @if($filter['is_pass'] == '0')
                                <option value='0' selected="true">no pass</option>
                            @else
                                <option value='0'>no pass</option>
                            @endif
                            @if($filter['is_pass'] == '1')
                                <option value='1' selected="true">pass</option>
                            @else
                                <option value='1'>pass</option>
                            @endif
                        </select>


                        <select name='sort'>
                            <option value="">排序</option>
                            @if($filter['sort'] == 'large_pic_size desc')
                                <option value='large_pic_size desc' selected="true">素材从大到小</option>
                            @else
                                <option value='large_pic_size desc'>素材从大到小</option>
                            @endif
                            @if($filter['sort'] == 'sort asc')
                                <option value='sort asc' selected="true">sort从低到高</option>
                            @else
                                <option value='sort asc'>sort从低到高</option>
                            @endif
                            @if($filter['sort'] == 'weight desc')
                                <option value='weight desc' selected="true">权重从高到低</option>
                            @else
                                <option value='weight desc'>权重从高到低</option>
                            @endif
                            @if($filter['sort'] == 'weight asc')
                                <option value='weight asc' selected="true">权重从低到高</option>
                            @else
                                <option value='weight asc'>权重从低到高</option>
                            @endif
                            @if($filter['sort'] == 'id desc')
                                <option value='id desc' selected="true">创建时间从近到远</option>
                            @else
                                <option value='id desc'>创建时间从近到远</option>
                            @endif
                            @if($filter['sort'] == 'id asc')
                                <option value='id asc' selected="true">创建时间从远到近</option>
                            @else
                                <option value='id asc'>创建时间从远到近</option>
                            @endif
                            @if($filter['sort'] == 'used_num desc')
                                <option value='used_num desc' selected="true">使用次数从高到低</option>
                            @else
                                <option value='used_num desc'>使用次数从高到低</option>
                            @endif

                            @if($filter['sort'] == 'used_day_num desc')
                                <option value='used_day_num desc' selected="true">24小时使用次数从高到低</option>
                            @else
                                <option value='used_day_num desc'>24小时使用次数从高到低</option>
                            @endif

                            @if($filter['sort'] == 'used_weak_num desc')
                                <option value='used_weak_num desc' selected="true">1周使用次数从高到低</option>
                            @else
                                <option value='used_weak_num desc'>1周使用次数从高到低</option>
                            @endif

                            {{--@if($filter['sort'] == 'paid_num desc')--}}
                            {{--<option value='paid_num desc' selected="true">付费数从高到低</option>--}}
                            {{--@else--}}
                            {{--<option value='paid_num desc'>付费数从高到低</option>--}}
                            {{--@endif--}}
                        </select>

                        <input type='hidden' name="dev" value="{{$filter['dev']}}">
                        <button type="submit" class="btn btn-white">筛 选</button>

                        {{--<a class="btn btn-white btn-info btn-bold" href="/admin/{{$modelName}}/create">--}}
                            {{--<i class="ace-icon fa glyphicon-plus bigger-120 blue"></i>--}}
                            {{--增加--}}
                        {{--</a>--}}
                        <a class="btn btn-white btn-info btn-bold " href="/admin/cleanCache">
                            <i class="ace-icon fa  bigger-120 blue"></i>
                            清除缓存
                        </a>
                        <a class="btn btn-white btn-info btn-bold" href="/admin/materialBatch">
                            <i class="ace-icon  bigger-120 blue"></i>
                            批量修改
                        </a>
                    </form>
                </div>
                <table id="" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>id</th>

                        <th>thumbnailUrl</th>
                         {{--<th>icon</th> --}}


                        <th>
                            <a href="/admin/{{$modelName}}/?sort=used_num desc&is_vip={{$filter['is_vip']}}&sub_type={{$filter['sub_type']}}&type={{$filter['type']}}&dev={{$filter['dev']}}">使用次数</a>
                        </th>

                        <th>
                            <a href="/admin/{{$modelName}}/?sort=used_weak_num desc&is_vip={{$filter['is_vip']}}&sub_type={{$filter['sub_type']}}&type={{$filter['type']}}&dev={{$filter['dev']}}">1周使用次数</a>
                        </th>
                        <th>
                            <a href="/admin/{{$modelName}}/?sort=used_day_num desc&sub_type={{$filter['sub_type']}}&is_vip={{$filter['is_vip']}}&type={{$filter['type']}}&dev={{$filter['dev']}}">24小时使用次数</a>
                        </th>
                        <th>统计</th>
                        {{--<th>--}}
                        {{--<a href="/admin/{{$modelName}}/?sort=paid_num desc&sub_type={{$filter['sub_type']}}&is_vip={{$filter['is_vip']}}&type={{$filter['type']}}">付费数</a>--}}
                        {{--</th>--}}

                        <th>
                            <a href="/admin/{{$modelName}}/?sort=weight desc&sub_type={{$filter['sub_type']}}&is_vip={{$filter['is_vip']}}&type={{$filter['type']}}&dev={{$filter['dev']}}">权重</a>
                        </th>
                        <th>
                            <a href="/admin/{{$modelName}}/?sort=score desc&sub_type={{$filter['sub_type']}}&is_vip={{$filter['is_vip']}}&type={{$filter['type']}}&dev={{$filter['dev']}}">得分</a>
                        </th>
                        <th>display_name_cn</th>
                        <th>display_name</th>
                        <th>
                            <a href="/admin/{{$modelName}}/?sort=rate20 desc">level_20通过次率</a>
                        </th>
                        <th>
                            <a href="/admin/{{$modelName}}/?sort=rate24 desc">level_24通过次率</a>
                        </th>
                        <th>
                            <a href="/admin/{{$modelName}}/?sort=rate48 desc">level_48通过次率</a>
                        </th>
                        <th>
                            <a href="/admin/{{$modelName}}/?sort=rate70 desc">level_70通过次率</a>
                        </th>
                        <th>
                            <a href="/admin/{{$modelName}}/?sort=rate108 desc">level_108通过次率</a>
                        </th>
                        <th>
                            <a href="/admin/{{$modelName}}/?sort=rate180 desc">level_180通过次率</a>
                        </th>
                        <th>
                            <a href="/admin/{{$modelName}}/?sort=rate288 desc">level_288通过次率</a>
                        </th>
                        <th>
                            <a href="/admin/{{$modelName}}/?sort=rate336 desc">level_336通过次率</a>
                        </th>
                        <th>
                            <a href="/admin/{{$modelName}}/?sort=rate432 desc">level_432通过次率</a>
                        </th>
                        <th>
                            <a href="/admin/{{$modelName}}/?sort=rate504 desc">level_504通过次率</a>
                        </th>
                        <th>
                            <a href="/admin/{{$modelName}}/?sort=rate704 desc">level_704通过次率</a>
                        </th>
                        <th>is_vip</th>
                        <th>is_new</th>
                        <th>is_pass</th>
                        <th>type</th>
                        <th>sub_type</th>
                        <th>unique_name</th>

                        <th>原图URL</th>
                        <th><a href="/admin/{{$modelName}}/?sort=id desc">创建时间</a></th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($list as $l)
                        <form class="form-horizontal" enctype="multipart/form-data" role="form"
                              action="{{ url('admin/' . $modelName .'/' . $l->id) }}" method="POST">
                            <input name="_method" type="hidden" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <tr>
                                <td>{{ $l->id }}</td>

                                <td style="height: 150px">
                                    {{--<img style="width:150px" src='{{$l->smallPicUrl()}}'/>--}}
                                    <img style="width:150px" src='{{$l->iconUrl()}}'/>
                                    {{--<input type="file" id="" name="small_pic" style="width:100px"   class="col-xs-10 col-sm-2"   />--}}
                                </td>

                                {{--<td style="height: 150px">--}}
                                
                                    {{--<img  style="width:150px" src='{{$l->largePicUrl()}}'/>--}}
                                    
                                {{--{{ byteFormat($l->large_pic_size,'KB')}}--}}
                                    {{--<br/>--}}
                                    {{--<input style="width:100px"   type="file" id="" name="large_pic"  class="col-xs-10 col-sm-2" />--}}
                                {{--</td> --}}


                                <td>{{$l->getUsedNum()}}</td>

                                <td>{{$l->getUsedNumWeak()}}</td>
                                <td>{{$l->getUsedNumDay()}}</td>
                                <td><a href="{{ url('admin/MaterialStatistic?id=' . $l->id) }}">统计</a></td>
                                {{--<td><a href="{{ url('admin/MaterialPaidStatistic?id=' . $l->id) }}">{{$l->paid_num}}</a>--}}
                                {{--</td>--}}
                                <td>{{$l->getWeight()}}</td>
                                <td>{{$l->score}}</td>
                                <td>{{$l->display_name_cn}}</td>
                                <td>{{$l->display_name}}</td>
                                <td>{{$l->rate20}} %({{$l->level_20_ok}})</td>
                                <td>{{$l->rate24}} %({{$l->level_24_ok}})</td>
                                <td>{{$l->rate48}} %({{$l->level_48_ok}})</td>
                                <td>{{$l->rate70}} %({{$l->level_70_ok}})</td>
                                <td>{{$l->rate108}} %({{$l->level_108_ok}})</td>
                                <td>{{$l->rate180}} %({{$l->level_180_ok}})</td>
                                <td>{{$l->rate288}} %({{$l->level_288_ok}})</td>
                                <td>{{$l->rate336}} %({{$l->level_336_ok}})</td>
                                <td>{{$l->rate432}} %({{$l->level_432_ok}})</td>
                                <td>{{$l->rate504}} %({{$l->level_504_ok}})</td>
                                <td>{{$l->rate704}} %({{$l->level_704_ok}})</td>

                                <!-- <td>
                                   <input class="btn btn-info" style="width:100px; margin-bottom: 5px" type="submit" name="local" value="修改本地">

                                   <input class="btn btn-info" style="width:100px" type="submit" name="all" value="修改全部">
                               </td> -->


                                {{--<td><input type="text" id="" style="width:50px" name="is_new"  class="col-xs-10 col-sm-2" value="{{$l->is_new}}" /></td>--}}
                                <th>{{$l->is_vip}}</th>
                                <td>{{$l->is_new}}</td>
                                <td>{{$l->is_pass}}</td>

                                <td>{{$l->type}}</td>
                                <td>{{$l->sub_type}}</td>
                                <td>{{$l->unique_name}}</td>

                                <td style="height: 10px"><a href="{{$l->largePicUrl()}}">{{$l->largePicUrl()}}</a></td>

                        </form>
                        <td>{{ $l->created_at }}@if (Auth::user()->is_super > 0)<br>操作用户：<p style="color: red;">{{$l->user}}</p>@endif</td>
                        <td>
                            <div class="hidden-sm hidden-xs action-buttons" style="margin-bottom: 10px">
                                <form action="{{ URL('admin/' . $modelName .'/' .$l->id) }}" method="POST"
                                      style="display: inline;">
                                    <input name="_method" type="hidden" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button class="btn btn-white btn-danger btn-bold">
                                        <i class="ace-icon fa fa-trash-o bigger-120 orange"></i>删除
                                    </button>
                                </form>
                            </div>
                            <div class="hidden-sm hidden-xs action-buttons" style="margin-bottom: 10px">
                                <form action="{{ URL('admin/' . $modelName .'/'. $l->id.'/edit/') }}" method="POST"
                                      style="display: inline;">
                                    <input name="_method" type="hidden" value="GET">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button class="btn btn-white btn-info btn-bold">
                                        <i class="ace-icon fa fa-trash-o bigger-120 "></i>编辑
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
       $list
       ->appends(['sort' => $filter['sort']])
       ->appends(['type' => $filter['type']])
       ->appends(['sub_type' => $filter['sub_type']])
       ->appends(['is_vip' => $filter['is_vip']])
       ->appends(['is_pass' => $filter['is_pass']])
       ->appends(['dev' => $filter['dev']])
       ->render() !!}
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            //页面加载完毕后开始执行的事件
            var city_json = '{!!$type_json!!}';
            var city_obj = eval('(' + city_json + ')');
            var filtertype = "{{$filter['type']}}";
            for (var key in city_obj) {
                if (filtertype == key) {
                    $("#typeselect").append("<option value='" + key + "' selected='selected'>" + key + "</option>");
                } else {
                    $("#typeselect").append("<option value='" + key + "'>" + key + "</option>");
                }
            }
            $("#typeselect").change(function () {
                var stype = $(this).val();
                $("#type").val(stype);

            });
            $("#type").mouseout(function () {
                var str = $("#type").val();
                var reg = new RegExp("[\\u4E00-\\u9FFF]+", "g");
                if (reg.test(str)) {
                    alert("请不要包含汉字！");
                    $("#type").val('');
                }

            });
            $("input[name='sub_type']").mouseout(function () {
                var str = $("input[name='sub_type']").val();
                var reg = new RegExp("[\\u4E00-\\u9FFF]+", "g");
                if (reg.test(str)) {
                    alert("请不要包含汉字！");
                    $("input[name='sub_type']").val('');
                }

            });
        });
    </script>
@endsection


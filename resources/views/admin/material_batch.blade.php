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
                <form action="/admin/materialBatch" method="get">
                   <label>素材管理</label>
                   <input class="input-middle" name="type" type="text" placeholder="type(支持模糊搜索)" value="{{ $filter['type']}}" />

                   <input class="input-middle" name="sub_type" type="text" placeholder="sub_type(支持模糊搜索)" value="{{ $filter['sub_type']}}" />
                   <select name='is_vip'>
                    <option value=''>所有</option>
                    @if($filter['is_vip'] == '0')
                    <option value='0' selected="true">免费 </option>
                    @else
                    <option value='0'>免费 </option>
                    @endif
                    @if($filter['is_vip'] == '1')
                    <option value='1' selected="true">付费 </option>
                    @else
                    <option value='1'>付费 </option>
                    @endif
                </select>

                <select name='sort'>
                    <option value="">排序</option>
                    @if($filter['sort'] == 'large_pic_size desc')
                    <option value='large_pic_size desc' selected="true">素材从大到小 </option>
                    @else
                    <option value='large_pic_size desc'>素材从大到小 </option>
                    @endif
                    @if($filter['sort'] == 'sort asc')
                    <option value='sort asc' selected="true">sort从低到高 </option>
                    @else
                    <option value='sort asc'>sort从低到高 </option>
                    @endif
                    @if($filter['sort'] == 'weight desc')
                    <option value='weight desc' selected="true">权重从高到低 </option>
                    @else
                    <option value='weight desc'>权重从高到低 </option>
                    @endif
                    @if($filter['sort'] == 'weight asc')
                    <option value='weight asc' selected="true">权重从低到高 </option>
                    @else
                    <option value='weight asc'>权重从低到高 </option>
                    @endif
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
                    @if($filter['sort'] == 'used_num desc')
                    <option value='used_num desc' selected="true">使用次数从高到低 </option>
                    @else
                    <option value='used_num desc'>使用次数从高到低 </option>
                    @endif
                    
                    @if($filter['sort'] == 'used_day_num desc')
                    <option value='used_day_num desc' selected="true">24小时使用次数从高到低 </option>
                    @else
                    <option value='used_day_num desc'>24小时使用次数从高到低 </option>
                    @endif
                    
                    @if($filter['sort'] == 'used_weak_num desc')
                    <option value='used_weak_num desc' selected="true">1周使用次数从高到低 </option>
                    @else
                    <option value='used_weak_num desc'>1周使用次数从高到低 </option>
                    @endif
                    
                    @if($filter['sort'] == 'download_num desc')
                    <option value='download_num desc' selected="true">下载次数从高到低 </option>
                    @else
                    <option value='download_num desc'>下载次数从高到低 </option>
                    @endif
                    
                    @if($filter['sort'] == 'download_day_num desc')
                    <option value='download_day_num desc' selected="true">24小时下载次数从高到低 </option>
                    @else
                    <option value='download_day_num desc'>24小时下载次数从高到低 </option>
                    @endif
                    
                    @if($filter['sort'] == 'download_weak_num desc')
                    <option value='download_weak_num desc' selected="true">1周下载次数从高到低 </option>
                    @else
                    <option value='download_weak_num desc'>1周下载次数从高到低 </option>
                    @endif
                    @if($filter['sort'] == 'use_rate desc')
                    <option value='use_rate desc' selected="true">使用率从高到低 </option>
                    @else
                    <option value='use_rate desc'>使用率从高到低 </option>
                    @endif
                </select>
                <select name='is_pass'>
                    <option value=''>全部is_pass </option>
                    @if($filter['is_pass'] == '0')
                    <option value='0'  selected="true">no pass </option>
                    @else
                    <option value='0'>no pass </option>
                    @endif
                    @if($filter['is_pass'] == '1')
                    <option value='1' selected="true">pass </option>
                    @else
                    <option value='1'>pass </option>
                    @endif
                </select>
                
                <button type="submit" class="btn btn-white">筛 选</button>

            </form> 
        </div>
        <table id="" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th>素材</th>

                    <th>type</th>
                    <th>sub_type</th>
                    <th><a href="/admin/{{$modelName}}/?sort=weight desc&sub_type={{$filter['sub_type']}}&is_vip={{$filter['is_vip']}}&type={{$filter['type']}}">权重</a></th>
                    <th>is_vip</th>
                    <th>is_pass</th>
                    <th>is_new</th>
                    <th>unique_name</th>
                    <th>display_color</th>
                    <th>display_name</th>
                    <th>display_name(简体中文)</th>
                    <th>display_name(繁体中文)</th>
                    <th>display_name(日语)</th>
                    <th>display_name(俄语)</th>
                    <th>描述</th>
                    <th>描述(简体中文)</th>
                    <th>描述(繁体中文)</th>
                    <th>描述(日语)</th>
                    <th>描述(俄语)</th>
                    <th>解锁</th>
                    <th>level_20</th>
                    <th>level_24</th>
                    <th>level_48</th>
                    <th>level_70</th>
                    <th>level_108</th>
                    <th>level_180</th>
                    <th>level_288</th>
                    <th>level_336</th>
                    <th>level_432</th>
                    <th>level_504</th>
                    <th>level_704</th>
                    <th>color2</th>
                    <th>version</th>

                </tr>
            </thead>
            <tbody>
             <form class="form-horizontal" enctype="multipart/form-data" role="form" action="{{ url('admin/updatebatch')}}" method="POST">
                @foreach ($list as $l)
                <input name="_method" type="hidden" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id[]" value="{{$l->id}}"> 
                <tr>
                    <td>{{ $l->id }}</td>
                    <td><img style="width:100px" src='{{$l->smallPicUrl()}}'/><br/><input type="file" id="" name="large_pic[]" style="width:100px"   class="col-xs-10 col-sm-2"   />
                    </td>

                    <td>{{$l->type}}</td>
                    <td>{{$l->sub_type}}</td>
                    <td><input type="text" id="" style="width:50px" name="weight[]"  class="col-xs-10 col-sm-2" value="{{$l->getWeight()}}" /></td>
                    <td><input type="text" id="" style="width:50px" name="is_vip[]"  class="col-xs-10 col-sm-2" value="{{$l->is_vip}}" /></td>
                    <td><input type="text" id="" style="width:50px" name="is_pass[]"  class="col-xs-10 col-sm-2" value="{{$l->is_pass}}" /></td>
                    <td><input type="text" id="" style="width:50px" name="is_new[]"  class="col-xs-10 col-sm-2" value="{{$l->is_new}}" /></td>


                    <td><input type="text" id="" style="width:80px" name="unique_name[]"  class="col-xs-10 col-sm-2" value="{{$l->unique_name}}" /></td>
                    <td><input type="text" id="" style="width:80px" name="display_color[]"  class="col-xs-10 col-sm-2" value="{{$l->display_color}}" /></td>

                    <td><input type="text" id="" style="width:150px" name="display_name[]"  class="col-xs-10 col-sm-2" value="{{$l->display_name}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="display_name_cn[]"  class="col-xs-10 col-sm-2" value="{{$l->display_name_cn}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="display_name_tw[]"  class="col-xs-10 col-sm-2" value="{{$l->display_name_tw}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="display_name_jp[]"  class="col-xs-10 col-sm-2" value="{{$l->display_name_jp}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="display_name_ru[]"  class="col-xs-10 col-sm-2" value="{{$l->display_name_ru}}" /></td>

                    <td><textarea  style="height: 100px;width: 200px" class="col-xs-10 col-sm-2" name="desc[]">{{ $l->desc}}</textarea></td>
                    <td><textarea  style="height: 100px;width: 200px" class="col-xs-10 col-sm-2" name="desc_cn[]">{{ $l->desc_cn}}</textarea></td>
                    <td><textarea  style="height: 100px;width: 200px" class="col-xs-10 col-sm-2" name="desc_tw[]">{{ $l->desc_tw}}</textarea></td>
                    <td><textarea  style="height: 100px;width: 200px" class="col-xs-10 col-sm-2" name="desc_jp[]">{{ $l->desc_jp}}</textarea></td>
                    <td><textarea  style="height: 100px;width: 200px" class="col-xs-10 col-sm-2" name="desc_ru[]">{{ $l->desc_ru}}</textarea></td>

                    <td><input type="text" id="" style="width:150px" name="coin[]"  class="col-xs-10 col-sm-2" value="{{$l->coin}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="level_20_coin[]"  class="col-xs-10 col-sm-2" value="{{$l->level_20_coin}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="level_24_coin[]"  class="col-xs-10 col-sm-2" value="{{$l->level_24_coin}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="level_48_coin[]"  class="col-xs-10 col-sm-2" value="{{$l->level_48_coin}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="level_70_coin[]"  class="col-xs-10 col-sm-2" value="{{$l->level_70_coin}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="level_108_coin[]"  class="col-xs-10 col-sm-2" value="{{$l->level_108_coin}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="level_180_coin[]"  class="col-xs-10 col-sm-2" value="{{$l->level_180_coin}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="level_288_coin[]"  class="col-xs-10 col-sm-2" value="{{$l->level_288_coin}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="level_336_coin[]"  class="col-xs-10 col-sm-2" value="{{$l->level_336_coin}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="level_432_coin[]"  class="col-xs-10 col-sm-2" value="{{$l->level_432_coin}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="level_504_coin[]"  class="col-xs-10 col-sm-2" value="{{$l->level_504_coin}}" /></td>
                    <td><input type="text" id="" style="width:150px" name="level_704_coin[]"  class="col-xs-10 col-sm-2" value="{{$l->level_704_coin}}" /></td>
                    <td><input type="text" id="" style="width:50px" name="display_color2[]"  class="col-xs-10 col-sm-2" value="{{$l->display_color2}}" /></td>
                    <td><input type="text" id="" style="width:50px" name="version[]"  class="col-xs-10 col-sm-2" value="{{$l->version}}" /></td>
                </tr>

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

@endsection

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
                    <input class="input-middle" name="name" type="text" placeholder="name" value="{{ $filter['name']}}" />
                    <input class="input-middle" name="parent_id" type="text" placeholder="parent_id" value="{{ $filter['parent_id']}}" />
                    <select name='sort'>
                        <option value="">排序</option>

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
                    </select>
                    <input type='hidden' name="dev" value="{{$filter['dev']}}">
                    <button type="submit" class="btn btn-white">筛 选</button>
                    <a class="btn btn-white btn-info btn-bold" href="/admin/{{$modelName}}/create">
                        <i class="ace-icon fa glyphicon-plus bigger-120 blue"></i>
                        增加
                    </a>
                    <a class="btn btn-white btn-info btn-bold" href="/admin/typeBatch">
                        <i class="ace-icon  bigger-120 blue"></i>
                        批量修改
                    </a>
                    <a class="btn btn-white btn-info btn-bold " href="/admin/cleanCache">
                        <i class="ace-icon fa  bigger-120 blue"></i>
                        清除缓存
                    </a>
                </form> 
            </div>

            <div class="well well-sm">

                <table id="" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>封面</th>

                            <th>android封面</th>
                            <th>ios封面框</th>
                            <th>平台</th>
                            <th>包付费数</th>
                            <th>is_vip</th>

                            <th>name</th>
                            <th>简体中文</th>
                            <th><a href="/admin/{{$modelName}}/?sign={{$sign}}&dev={{$filter['dev']}}" id="wei" data="0">权重</a></th>
                            <th><a href="/admin/{{$modelName}}/?sort=score desc&dev={{$filter['dev']}}">分数</a></th>
                            <th><a href="/admin/{{$modelName}}/?sort=is_new desc&dev={{$filter['dev']}}">is_new</a></th>

                            <th>繁体中文</th>
                            <th>俄语</th>
                            <th>日语</th>
                            <th>西班牙语</th>
                            <th>描述</th>
                            <th>描述(中文)</th>
                            <th>描述(繁体)</th>
                            <th>描述(俄语)</th>
                            <th>描述(日语)</th>
                            <th>描述(西班牙语)</th>
                            <th>color1</th>
                            
                            <th>color2</th>
                            <th>背景color</th>
                            <th>is_new_color</th>
                            <th>字体</th>
                            <th>version</th>

                            <th>修改</th>
                            <th>创建时间</th>
                            <th>操作用户</th>
                            <th>删除</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $l)
                            <form class="form-horizontal" enctype="multipart/form-data" role="form" action="{{ url('admin/type/' . $l->id) }}" method="POST">
                                <input name="_method" type="hidden" value="PUT">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <tr>
                                    <td>{{ $l->id }}</td>
                                    <td>
                                        <img style="width:100px" src='{{$l->picUrl()}}'/>
                                        <input type="file" id="" name="pic" style="width:100px"   class="col-xs-10 col-sm-2"   />
                                    </td>
                                    
                                    <td>
                                        <img style="width:100px" src='{{$l->picUrlAndroid()}}'/>
                                        <input type="file" id="" name="android_pic" style="width:100px" class="col-xs-10 col-sm-2"/>
                                    </td>
                                    <td>
                                        <img style="width:100px" src='{{$l->picUrlIos()}}'/>
                                        <input type="file" id="" name="ios_pic_frame" style="width:100px" class="col-xs-10 col-sm-2"/>
                                    </td>
                                    <td>
                                        <select name="device" class="col-xs-10 col-sm-2" style="width:50px">
                                            <option value="0" @if($l->device == 0) selected="selected" @endif>Ios</option>
                                            <option value="1" @if($l->device == 1) selected="selected" @endif>Android</option>
                                            <option value="2" @if($l->device == 2) selected="selected" @endif>All</option>
                                        </select>
                                    </td>
                                    <!-- <td><input type="text" id="" style="width:100px" name="parent_id"  class="col-xs-10 col-sm-2" value="{{$l->parent_id}}" /></td> -->
                                    <td><input type="text" id="" style="width:50px" name="packagepay"  class="col-xs-10 col-sm-2" value="{{$l->packagepay}}" /></td>
                                    <td><input type="text" id="" style="width:50px" name="is_vip"  class="col-xs-10 col-sm-2" value="{{$l->is_vip}}" /></td>

                                    <td>
                                        <input type="text" id="" style="width:200px" name="name"  class="col-xs-10 col-sm-2" value="{{$l->name}}" />
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="name_cn"  class="col-xs-10 col-sm-2" value="{{$l->name_cn}}" />
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="weight"  class="col-xs-10 col-sm-2" value="{{$l->weight}}" />
                                    </td>
                                    <td>
                                        {{$l->score}}
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="is_new"  class="col-xs-10 col-sm-2" value="{{$l->is_new}}" />
                                    </td>

                                    <td>
                                        <input type="text" id="" style="width:100px" name="name_tw"  class="col-xs-10 col-sm-2" value="{{$l->name_tw}}" />
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="name_ru"  class="col-xs-10 col-sm-2" value="{{$l->name_ru}}" />
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="name_jp"  class="col-xs-10 col-sm-2" value="{{$l->name_jp}}" />
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="name_es"  class="col-xs-10 col-sm-2" value="{{$l->name_es}}" />
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="desc"  class="col-xs-10 col-sm-2" value="{{$l->desc}}" />
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="desc_cn"  class="col-xs-10 col-sm-2" value="{{$l->desc_cn}}" />
                                    </td>
                                    
                                    <td>
                                        <input type="text" id="" style="width:100px" name="desc_tw"  class="col-xs-10 col-sm-2" value="{{$l->desc_tw}}" />
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="desc_ru"  class="col-xs-10 col-sm-2" value="{{$l->desc_ru}}" />
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="desc_jp"  class="col-xs-10 col-sm-2" value="{{$l->desc_jp}}" />
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="desc_es"  class="col-xs-10 col-sm-2" value="{{$l->desc_es}}" />
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="color1"  class="col-xs-10 col-sm-2" value="{{$l->color1}}" />
                                    </td>
                                    
                                    <td>
                                        <input type="text" id="" style="width:100px" name="color2"  class="col-xs-10 col-sm-2" value="{{$l->color2}}" />
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="bg_color"  class="col-xs-10 col-sm-2" value="{{$l->bg_color}}" />
                                    </td>

                                    <td>
                                        <input type="text" id="" style="width:100px" name="is_new_color"  class="col-xs-10 col-sm-2" value="{{$l->is_new_color}}" />
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="font"  class="col-xs-10 col-sm-2" value="{{$l->font}}" />
                                    </td>
                                    <td>
                                        <input type="text" id="" style="width:100px" name="version"  class="col-xs-10 col-sm-2" value="{{$l->version}}" />
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
                                                <i class="ace-icon fa fa-trash-o bigger-120 orange"></i>
                                                删除
                                            </button>  
                                        </form>                                                              
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right ">
                     共{{ $list->total() }}条记录
                     {!! $list->appends(['name' => $filter['name']])
                     ->appends(['parent_id' => $filter['parent_id']])
                     ->appends(['sort' => $filter['sort']])
                     ->appends(['dev' => $filter['dev']])
                     ->render()
                     !!}
                 </div>
             </div>
         </div>
     </div>
     @endsection

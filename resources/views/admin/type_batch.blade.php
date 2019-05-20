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
                        <input class="input-middle" name="name" type="text" placeholder="name"
                               value="{{ $filter['name']}}"/>
                        <input class="input-middle" name="parent_id" type="text" placeholder="parent_id"
                               value="{{ $filter['parent_id']}}"/>


                        <select name='sort'>
                            <option value="">排序</option>

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
                        </select>


                        <button type="submit" class="btn btn-white">筛 选</button>


                    </form>
                </div>

                <div class="well well-sm">

                    <table id="" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>封面</th>
                            <th>ios封面框</th>
                            <th>android封面</th>
                            <th>包付费数</th>
                            <th>is_vip</th>
                            <th>name</th>
                            <th>简体中文</th>
                            <th>权重</th>
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
                            <th>color</th>
                            <th>color2</th>
                            <th>背景color</th>
                            <th>version</th>
                        </tr>
                        </thead>
                        <tbody>
                        <form class="form-horizontal" enctype="multipart/form-data" role="form"
                              action="{{ url('admin/updateTypes')}}" method="POST">

                            @foreach ($list as $l)

                                <input name="_method" type="hidden" value="PUT">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id[]" value="{{$l->id}}">
                                <tr>
                                    <td>{{$l->id }}</td>
                                    <td><img style="width:100px" src='{{$l->picUrl()}}'/><input type="file" id=""
                                                                                                name="pic[]"
                                                                                                style="width:100px"
                                                                                                class="col-xs-10 col-sm-2"/></td>
                                    <td><img style="width:100px" src='{{$l->picUrlIos()}}'/><input type="file" id=""
                                                                                                       name="ios_pic_frame[]"
                                                                                                       style="width:100px"
                                                                                                       class="col-xs-10 col-sm-2"/></td>
                                    <td><img style="width:100px" src='{{$l->picUrlAndroid()}}'/><input type="file" id=""
                                                                                                       name="android_pic[]"
                                                                                                       style="width:100px"
                                                                                                       class="col-xs-10 col-sm-2"/></td>
                                    <td><input type="text" id="" style="width:50px" name="packagepay[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->packagepay}}"/></td>
                                    <td><input type="text" id="" style="width:50px" name="is_vip[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->is_vip}}"/></td>

                                    <td><input type="text" id="" style="width:200px" name="name[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->name}}"/></td>
                                    <td><input type="text" id="" style="width:100px" name="name_cn[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->name_cn}}"/></td>
                                    <td><input type="text" id="" style="width:100px" name="weight[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->weight}}"/></td>


                                    <td><input type="text" id="" style="width:100px" name="name_tw[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->name_tw}}"/></td>
                                    <td><input type="text" id="" style="width:100px" name="name_ru[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->name_ru}}"/></td>
                                    <td><input type="text" id="" style="width:100px" name="name_jp[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->name_jp}}"/></td>
                                    <td><input type="text" id="" style="width:100px" name="name_es[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->name_es}}"/></td>

                                    <td><input type="text" id="" style="width:100px" name="desc[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->desc}}"/></td>
                                    <td><input type="text" id="" style="width:100px" name="desc_cn[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->desc_cn}}"/></td>
                                    <td><input type="text" id="" style="width:100px" name="desc_tw[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->desc_tw}}"/></td>
                                    <td><input type="text" id="" style="width:100px" name="desc_ru[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->desc_ru}}"/></td>
                                    <td><input type="text" id="" style="width:100px" name="desc_jp[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->desc_jp}}"/></td>
                                    <td><input type="text" id="" style="width:100px" name="desc_es[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->desc_es}}"/></td>
                                    <td><input type="text" id="" style="width:100px" name="color1[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->color1}}"/></td>

                                    <td><input type="text" id="" style="width:100px" name="color2[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->color2}}"/></td>
                                    <td><input type="text" id="" style="width:100px" name="bg_color[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->bg_color}}"/></td>
                                    <td><input type="text" id="" style="width:100px" name="version[]"
                                               class="col-xs-10 col-sm-2" value="{{$l->version}}"/></td>

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
                         $list->appends(['name' => $filter['name']])
                         ->appends(['parent_id' => $filter['parent_id']])
                         ->appends(['sort' => $filter['sort']])
                         ->appends(['dev' => $filter['dev']])
                         ->render() !!}
                    </div>
                </div>
            </div>
        </div>

@endsection

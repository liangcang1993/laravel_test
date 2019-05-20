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
                        <input class="input-middle" name="id" type="text" placeholder="素材id"
                               value="{{ $filter['id']}}"/>
                        <select name='sort'>
                            <option value="">排序</option>

                            @if($filter['sort'] == 'date desc')
                                <option value='date desc' selected="true">时间从近到远</option>
                            @else
                                <option value='date desc'>时间从近到远</option>
                            @endif
                            @if($filter['sort'] == 'date asc')
                                <option value='date asc' selected="true">时间从远到近</option>
                            @else
                                <option value='date asc'>时间从远到近</option>
                            @endif
                        </select>

                        <button type="submit" class="btn btn-white">筛 选</button>

                    </form>
                </div>
                <table id="" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>素材id</th>
                        <th>pic</th>
                        <th>date</th>
                        {{--<th>下载量</th>--}}
                        <th>使用量</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($list as $l)
                        <input name="_method" type="hidden" value="PUT">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <tr>
                            <td>{{$l->material_id }}</td>
                            <td>@if (empty($l->picUrl()))图片跑丢啦~~@else<img style="width: 30px;height: 30px" src='{{$l->picUrl()}}'/>@endif
                            <td>{{$l->date}}</td>
                            {{--<td>{{$l->download_num}}</td>--}}
                            <td>{{$l->used_num}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="pull-right ">
                    共{{ $list->total() }}条记录{!!
                                               $list->appends(['id' => $filter['id']])
                                               ->appends(['sort' => $filter['sort']])
                                               ->render() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

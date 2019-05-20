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
                    <form action="/admin/loadList/" method="get" id="myform">
                        开始日期: <input style="width: 100px" type="text" id="start_date" name="start_date" value="{{$filter['start_date']}}">
                        结束日期: <input style="width: 100px" type="text" id="end_date" name="end_date" value="{{$filter['end_date']}}">
                        <input class="input-middle" name="id" type="text" placeholder="素材id"
                               value="{{ $filter['id']}}"/>
                        <select name='status'>
                            <option value=''>状态</option>
                            @if($filter['status'] == 'fail')
                                <option value='fail' selected="true">加载失败</option>
                            @else
                                <option value='fail'>加载失败</option>
                            @endif
                            @if($filter['status'] == 'success')
                                <option value='success' selected="true">加载成功</option>
                            @else
                                <option value='success'>加载成功</option>
                            @endif
                        </select>
                        <select name='sort'>
                            <option value="">排序</option>

                            @if($filter['sort'] == 'time desc')
                                <option value='time desc' selected="true">加载时间降序</option>
                            @else
                                <option value='time desc'>加载时间降序</option>
                            @endif
                        </select>
                        <input  name="download" type="hidden" value="0" id="dl"/>
                        <button type="submit" class="btn btn-white">筛 选</button>
                        <button type="button" class="btn btn-white" id="downloadfunc">导出</button>
                    </form>
                </div>
                <table id="" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>date</th>
                        <th>素材id</th>
                        <th>加载时长(s)</th>
                        <th>状态</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($list as $l)
                        <input name="_method" type="hidden" value="PUT">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <tr>
                            <td>{{$l['id'] }}</td>
                            <td>{{$l['created_at'] }}</td>
                            <td>{{$l['material_id'] }}</td>
                            <td>{{$l['loadtime'] }}</td>
                            <td>{{$l['loadevent'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="pull-right ">
                    {{--共{{ $list->total() }}条记录{!!--}}
                                               {{--$list->appends(['id' => $filter['id']])--}}
                                               {{--->appends(['sort' => $filter['sort']])--}}
                                               {{--->appends(['status' => $filter['status']])--}}
                                               {{--->render() !!}--}}
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        $(function () {
            $( "#start_date" ).datepicker({
                dateFormat: 'yy-mm-dd',
            });
            $( "#end_date" ).datepicker({
                dateFormat: 'yy-mm-dd',
            });

        });
        $("#downloadfunc").click(function(){
            $("#dl").val(1);
            $("#myform").submit();    //提交ID为myform的表单
            $("#dl").val(0);
        })


    </script>
@endsection


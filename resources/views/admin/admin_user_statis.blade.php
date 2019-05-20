@extends('admin._default')

@section('content')


    <div class="breadcrumbs" id="breadcrumbs">

        <ul class="breadcrumb">
            <li>
                <a href="/admin/admin_user">用户统计</a>
            </li>
        </ul>

    </div>

    <div class="page-content">

        {{--<div class="page-header">--}}
        <div role="tabpanel" class="tab-pane active" id="home">
            <div class="well well-sm">
                @include('admin.chooseDateForm')
                <br>
                <form class="form-horizontal" enctype="multipart/form-data" role="form" action="/admin/downLoadExcel" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type='hidden' id = "startDate1" name='startDate' value="{{$startDate}}" placeholder="" required />
                    <input type='hidden' id = "endDate1" name='endDate' value="{{$endDate}}" placeholder="" required />
                    <input type="hidden" name="system" value="{{$system}}" />
                    <button class="btn btn-info btn-sm" style="width:100px" type="submit">
                        <i class="glyphicon glyphicon-download-alt"></i>
                        下载
                    </button>

                </form>

            </div>
        </div>

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

                <table id="" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th rowspan="2">日期</th>
                        <th rowspan="2">DAU(当日启动用户数)</th>
                        <th rowspan="2">新增用户</th>
                        {{--<th >推送时间</th>--}}
                        {{--                        @foreach($lostRecallNotifiList as $k => $v)--}}
                        @for($i=2;$i<32;$i++)
                            <th colspan="2" >第{{$i}}天</th>
                        @endfor
                    </tr>
                    <tr>
                        @for($i=2;$i<32;$i++)
                            <td>留存数</td>
                            <td>留存率</td>
                        @endfor

                        {{--@endforeach--}}
                    </tr>
                    @foreach($data as $k=>$v)
                        <tr>
                            <td>{{$k}}</td>
                            <td>{{isset($v['dau'])?$v['dau']:0}}</td>
                            <td>{{isset($v[1])?$v[1]:0}}</td>
                            @for($i=2;$i<32;$i++)
                                <td>{{isset($v[$i])?$v[$i]:0}}</td>
                                <td>{{\App\Models\User::getPercentage($v,$i)}}</td>
                            @endfor
                        </tr>
                    @endforeach
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <div class="pull-right ">
                    {{--                    共{{ $users->total() }}条记录{!! $users->render() !!}--}}
                </div>
            </div>
        </div>
    </div>

@endsection

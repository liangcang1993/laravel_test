@extends('admin._default')

@section('content')

<style>

.tab-content{
    border: 0;
    padding: 0;
}
.well{
    margin-top: 10px;
}

</style>
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
<div>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">运营推送</a></li>
    <li role="presentation"><a href="{{ URL('admin/newUserTask')}}" >新手任务</a></li>
    <li role="presentation"><a href="{{ URL('admin/lostRecall')}}" >流失召回</a></li>
    <li role="presentation"><a href="{{ URL('admin/pushStrategy')}}" >推送策略</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">
        <div class="well well-sm">
            <form action="/admin/{{$modelName}}" method="get">
                <input class="input-middle" name="keyword" type="sid" placeholder="keyword" value="{{ $filter['keyword']}}" />
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
                <select name='app'>
                    <option value="">app</option>
                   
                    @if($filter['app'] == 'poto')
                        <option value='poto' selected="true">poto </option>
                    @else
                        <option value='poto'>poto </option>
                    @endif
                     @if($filter['sort'] == 'lipix')
                        <option value='lipix' selected="true">lipix </option>
                    @else
                        <option value='lipix'>lipix </option>
                    @endif
                </select>
                <select name='clt'>
                    <option value="">平台</option>
                   
                    @if($filter['clt'] == '1')
                        <option value='1' selected="true">ios </option>
                    @else
                        <option value='1'>ios </option>
                    @endif
                     @if($filter['clt'] == '2')
                        <option value='2' selected="true">android </option>
                    @else
                        <option value='2'>android </option>
                    @endif
                </select>
                <select name='type'>
                    <option value="">类型</option>
                   
                    @if($filter['type'] == '1')
                        <option value='1' selected="true">首次安装次日未登录 </option>
                    @else
                        <option value='1'>首次安装次日未登录 </option>
                    @endif
                     @if($filter['type'] == '2')
                        <option value='2' selected="true">连续3天未登录 </option>
                    @else
                        <option value='2'>连续3天未登录 </option>
                    @endif
                     @if($filter['type'] == '3')
                        <option value='3' selected="true">连续7天以上未登录 </option>
                    @else
                        <option value='3'>连续7天以上未登录 </option>
                    @endif
                </select>

                <button type="submit" class="btn btn-white">筛 选</button>
                    <a class="btn btn-white btn-info btn-bold" href="/admin/notifi/create">
                        <i class="ace-icon fa glyphicon-plus bigger-120 blue"></i>
                        增加推送
                    </a>

            </form> 
        </div>
        <table id="" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th>推送时间</th>
                    <th>推送内容</th>
                    <th>推送类型</th>
                    <th>out_id</th>
                    <th>action</th>
                    <th>平台</th>
                    <th>用户属性</th>
                    <th>最早注册时间</th>
                    <th>最晚注册时间</th>
                    <th>最后登陆时间</th>
                    <th>推送数</th>
                    <th>接收数</th>
                    <th>打开数</th>
                    <th>到达率</th>
                    <th>打开率</th>
                    <th>是否通过</th>
                    <th>推送状态</th>
                    <th>修改</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $l)
                    <form class="form-horizontal" enctype="multipart/form-data" role="form" action="{{ url('admin/' . $modelName .'/' . $l->id) }}" method="POST">
                    <tr>
                    <input name="_method" type="hidden" value="PUT">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" value="{{ $l->id }}">
                    <td>{{ $l->id }}</td>
                    <td class="col-md-2"><div class="input-group date form_datetime col-md-12" data-date="{{ $l->send_time }}" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                        <input class="form-control" size="16" type="text" value="{{ $l->send_time }}" readonly id="upload_time" name="send_time">
                        {{--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>--}}
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                    </td>
                    {{--<td>{{ $l->send_time }}</td>--}}
                    <td>{{ $l->msg }}</td>
                    <td>{{ $l->push_type }}</td>
                    <td>{{$l->out_id}}</td>
                    <td>{{$l->action}}</td>
                    <td>{{ $l->getTypeStr() }}</td> 
                    <td>{{ $l->getCltStr() }}</td> 
                    <td>{{ $l->createstar_time }}</td>

                    <td>{{$l->createend_time}}</td>
                    <td>{{$l->lastlogin_at}}</td>
                    <td>{{$l->num}}</td>
                    <td>{{$l->receive_num}}</td>
                    <td>{{$l->open_num}}</td>
                        {{--{{0==$l->is_pass?'未通过':'已通过'}}</td>--}}
                    <td>
                      @if(!empty($l->num))
                            {{ number_format($l->receive_num*100/$l->num,2)}}%
                        @else 

                        @endif
                    </td>
                    <td>
                        @if(!empty($l->receive_num))
                        {{ number_format($l->open_num*100/$l->receive_num,2)}}%
                        @endif
                        
                        {{--@if($l->clt == '1')--}}
                            {{--@if(!empty($l->num))--}}
                             {{--{{ number_format($l->open_num*100/$l->num,2)}}%--}}

                            {{--@endif--}}
                        
                        {{--@else--}}
                            {{--@if(!empty($l->receive_num))--}}
                             {{--{{ number_format($l->open_num*100/$l->receive_num,2)}}%--}}

                            {{--@endif--}}
                        {{--@endif--}}

                    </td>
                    <td>
                        <div >
                            <select name='is_pass'>
                                <option value='0' @if(isset($l) && $l['is_pass'] == 0) selected="true" @endif> 未通过</option>
                                <option value='1' @if(isset($l) && $l['is_pass'] == 1) selected="true" @endif>已通过</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div >
                            <select name='status'>
                                <option value='0' @if(isset($l) && $l['status'] == 0) selected="true" @endif>未推送</option>
                                <option value='1' @if(isset($l) && $l['status'] == 1) selected="true" @endif>已推送</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="hidden-sm hidden-xs action-buttons">

                                <button class="btn btn-white btn-warning btn-bold">
                                    <i class="ace-icon fa fa-trash-o bigger-120 orange"></i>编辑
                                </button>
                        </div>
                    </td>
                    </form>

                    <td>
                        <div class="hidden-sm hidden-xs action-buttons">

                            <form action="{{ URL('admin/'.$modelName.'/'.$l->id) }}" method="POST" style="display: inline;">
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
           $list->appends(['keyword' => $filter['keyword']])
           ->appends(['sort' => $filter['sort']])
           ->appends(['app' => $filter['app']])
           ->appends(['clt' => $filter['clt']])
           ->appends(['type' => $filter['type']])
           ->render() !!}
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="newUser">..jd.</div>
    <div role="tabpanel" class="tab-pane" id="lostRecall">..sdfe.</div>
  </div>

</div>


            
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){

        $('.form_datetime').datetimepicker({
            language:  'cn',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1,
            format: 'yyyy-mm-dd hh:ii',
            // startDate:getNowTime()
            // startDate:'2016-01-01 00:00:00'
            startDate:0
        });

        function getNowTime() {
            var nowtime =new Date(new Date()-24*60*60*1000)
            var year = nowtime.getFullYear();
            var month = padleft0(nowtime.getMonth() + 1);
            var day = padleft0(nowtime.getDate());
            var hour = padleft0(nowtime.getHours());
            var minute = padleft0(nowtime.getMinutes());
            var second = padleft0(nowtime.getSeconds());
            return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
        }
        //补齐两位数
        function padleft0(obj) {
            return obj.toString().replace(/^[0-9]{1}$/, "0" + obj);
        }
    });



</script>

@endsection

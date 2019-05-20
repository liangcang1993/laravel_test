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

table{
    text-align:center;
}

table tr th{
    text-align: center;
}

form .form-group label{
    text-align: left;
}

</style>

@include('admin.emptyModal')

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
    <li role="presentation" ><a href="{{ URL('admin/notifi')}}" >运营推送</a></li>
    <li role="presentation" ><a href="{{ URL('admin/newUserTask')}}">新手任务</a></li>
    <li role="presentation" ><a href="{{ URL('admin/lostRecall')}}" >流失召回</a></li>
    <li role="presentation" class="active" ><a href="#" >推送策略</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">

    <div role="tabpanel" class="tab-pane " id="newUser"> 新手任务</div>
    <div role="tabpanel" class="tab-pane active" id="lostRecall">
        <div class="well well-sm"> 
            <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#emptyModal" onclick="getHtmlFromLinkToModal('{{ url('admin/notifiTiming/create/') }}')" >添加推送</button>
        </div>

        <div class="center-block">
            新手任务
        </div>
        <table   id="" class="table table-striped table-bordered table-hover">       
            <tbody>
                <tr>
                    <th >id</th>
                    <th  >title</th>
                    <th  >推送内容</th>
                    <th  >推送间隔天数</th>
                    <th  >推送源</th>
                    <th  >clt</th>
                    <th  >应用</th>
                    <th  >落地页</th>
                    <th  >out_id</th>
                    <th  >是否通过</th>
                    <th  >操作</th>
                </tr>
                @if(isset($newUserTaskNotifiList))
                    @foreach($newUserTaskNotifiList as $k => $v)
                    <tr>
                        <td>{{$v->id}}</td>
                        <td>{{$v->title}}</td>
                        <td>{{$v->msg}}</td>
                        <td>{{$v->push_day}}</td>
                        <td>{{$v->push_from}}</td>
                        <td>{{$v->clt}}</td>
                        <td>{{$v->app}}</td>
                        <td>{{$v->action}}</td>
                        <td>{{$v->out_id}}</td>
                        <td>{{0==$v->is_pass?'未通过':'已通过'}}</td>

                        <td>
                            <a class="btn btn-warning btn-xs" href="" data-toggle="modal" data-target="#emptyModal" onclick="getHtmlFromLinkToModal('{{ url('admin/notifiTiming/'.$v->id.'/edit/') }}')"> 修改</a>
                                <a class="btn btn-danger btn-xs" onclick="deleteItem('{{ url('admin/notifiTiming/'.$v->id ) }}','{{ csrf_token() }}')">删除</a>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <div class="center-block">
            流失召回
        </div>
        <table   id="" class="table table-striped table-bordered table-hover">       
            <tbody>
                <tr>
                    <th >id</th>
                    <th  >title</th>
                    <th  >推送内容</th>
                    <th  >推送间隔天数</th>
                    <th  >推送源</th>
                    <th  >clt</th>
                    <th  >应用</th>
                    <th  >落地页</th>
                    <th  >out_id</th>
                    <th  >是否通过</th>

                    <th  >操作</th>
                </tr>
                @if(isset($lostRecallNotifiList))
                    @foreach($lostRecallNotifiList as $k => $v)
                    <tr>
                        <td>{{$v->id}}</td>
                        <td>{{$v->title}}</td>
                        <td>{{$v->msg}}</td>
                        <td>{{$v->push_day}}</td>
                        <td>{{$v->push_from}}</td>
                        <td>{{$v->clt}}</td>
                        <td>{{$v->app}}</td>
                        <td>{{$v->action}}</td>
                        <td>{{$v->out_id}}</td>
                        <td>{{0==$v->is_pass?'未通过':'已通过'}}</td>

                        <td>
                            <a class="btn btn-warning btn-xs" href="" data-toggle="modal" data-target="#emptyModal" onclick="getHtmlFromLinkToModal('{{ url('admin/notifiTiming/'.$v->id.'/edit/') }}')"> 修改</a>
                                <a class="btn btn-danger btn-xs" onclick="deleteItem('{{ url('admin/notifiTiming/'.$v->id ) }}','{{ csrf_token() }}')">删除</a>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
  </div>

</div>


            
        </div>
    </div>
</div>

@endsection

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

.tab-content{
    margin-top: 10px;
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
    <li role="presentation" class="active"><a href="#" >流失召回</a></li>
    <li role="presentation"><a href="{{ URL('admin/pushStrategy')}}" >推送策略</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane " id="home">
        ha
    </div>
    <div role="tabpanel" class="tab-pane " id="newUser"> 新手任务</div>
    <div role="tabpanel" class="tab-pane active" id="lostRecall">
    

        @if(isset($lostRecallNotifiList))
        <table   id="" class="table table-striped table-bordered table-hover">       
            <tbody>
                <tr>
                    <th >推送时间</th>
                    @foreach($lostRecallNotifiList as $k => $v)
                    <th colspan="5" >第{{$v->push_day}}天</th>
                    @endforeach
                </tr>
                <tr>
                    <td>注册时间</td>
                    @foreach($lostRecallNotifiList as $k => $v)
                    <td>推送数</td>
                    <td>接收数</td>
                    <td>打开数</td>
                    <td>触达率</td>
                    <td>打开率</td>
                    @endforeach
                </tr>
              
                @foreach($lostRecallList as $k => $v)
                    <tr>
                        <td>{{$k}}</td>
                        @foreach($lostRecallNotifiList as $kk => $vv)
                        <td>@if(isset($v[$vv->push_day]['pushNum'])){{$v[$vv->push_day]['pushNum']}}@else 0 @endif</td>
                        <td>@if(isset($v[$vv->push_day]['receiveNum'])){{$v[$vv->push_day]['receiveNum']}}@else 0 @endif</td>
                        
                        <td>@if(isset($v[$vv->push_day]['openNum'])){{$v[$vv->push_day]['openNum']}}@else 0 @endif</td>
                        
                        <td>
                            @if(isset($v[$vv->push_day]['pushNum']) && isset($v[$vv->push_day]['receiveNum']) && $v[$vv->push_day]['pushNum'] != 0)
                                {{ number_format($v[$vv->push_day]['receiveNum']*100 / $v[$vv->push_day]['pushNum'],2) }}%
                            @else 
                                0 
                            @endif
                        </td>

                        <td>
                            @if(isset($v[$vv->push_day]['pushNum']) && isset($v[$vv->push_day]['openNum']) && $v[$vv->push_day]['pushNum'] != 0)
                                {{ number_format($v[$vv->push_day]['openNum']*100 / $v[$vv->push_day]['pushNum'],2) }}%
                            @else 
                                0 
                            @endif
                        </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
        请先添加推送策略！！！
        @endif
    </div>
  </div>

</div>


            
        </div>
    </div>
</div>

@endsection

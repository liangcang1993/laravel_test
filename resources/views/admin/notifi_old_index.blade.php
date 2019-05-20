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
                                                    <th>推送类型</th>
                                                    <th>title</th>
                                                    <th>msg</th>
                                                    <th>icon</th>
                                                    <th>pic</th>
                                                    <th>action</th>
                                                    <th>out_id</th>
                                                    <th>类型</th>
                                                    <th>平台</th>
                                                    <th>范围</th>
                                                    <th>推送数</th>
                                                    <th>接收数</th>
                                                    <th>打开数</th>
                                                    <th>到达率</th>
                                                    <th>转化率</th>
                                                    <th>推送时间</th>
                                                    <th>最早注册时间</th>
                                                    <th>最晚注册时间</th>
                                                    <th>最后登陆时间</th>
                                                    <th>状态</th>
                                                    <th>创建时间</th>
                                                    <th>操作</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($list as $l)
                                                <tr>
                                                    <input name="_method" type="hidden" value="PUT">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <td>{{ $l->id }}</td>
                                                    <td>{{ $l->push_type }}</td>
                                                    <td>{{ $l->title }}</td>
                                                    <td>{{ $l->msg }}</td>
                                                    <td><img src='{{$l->iconUrl()}}'/></td>

                                                    <td><img src='{{$l->picUrl()}}'/></td>
                                                    <td>{{$l->action}}</td>
                                                    <td>{{$l->out_id}}</td>

                                                    <td>{{ $l->getTypeStr() }}</td>
                                                    <td>{{ $l->getCltStr() }}</td>
                                                    <td>{{ $l->range }}</td>

                                                    <td>{{$l->num}}</td>
                                                    <td>{{$l->receive_num}}</td>
                                                    <td>{{$l->open_num}}</td>
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
                                                    <td>{{ $l->send_time }}</td>
                                                    <td>{{ $l->createstart_time }}</td>
                                                    <td>{{ $l->createend_time }}</td>
                                                    <td>{{ $l->lastlogin_at }}</td>
                                                    <td>{{$l->getStatusStr()}}</td>
                                                    <td>{{$l->created_at}}</td>

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
                        </div>
                    </div>

@endsection

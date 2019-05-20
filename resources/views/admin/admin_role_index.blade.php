@extends('admin._default')

@section('content')


                     <div class="breadcrumbs" id="breadcrumbs">

                        <ul class="breadcrumb">
                            <li>
                                <a href="/admin/admin_role">用户角色管理</a>
                            </li>
                        </ul>

                    </div>

                    <div class="page-content">

                        <div class="page-header">
                            <a class="btn btn-white btn-info btn-bold" href="/admin/admin_role/create">
                                <i class="ace-icon fa glyphicon-plus bigger-120 blue"></i>
                                增加角色
                            </a>
                            {{--<a class="btn btn-white btn-info btn-bold" href="/admin/cleanRole">--}}
                                {{--<i class="ace-icon fa glyphicon-plus bigger-120 blue"></i>--}}
                                {{--清除角色缓存--}}
                            {{--</a>--}}
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
                                                    <th>ID</th>
                                                    <th>角色名</th>
                                                    <th>操作</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $user)
                                                <tr>
                                                    <td>{{ $user->id }}</td>
                                                    <td><a href="{{ URL('admin/admin_role/'.$user->id.'/edit') }}">{{ $user->role_name }}</a></td>
                                                    <td>
                                                            <div class="hidden-sm hidden-xs action-buttons">

                                                                <a class="btn btn-white btn-info btn-bold" href="{{ URL('admin/admin_role/'.$user->id.'/edit') }}">
                                                                    <i class="ace-icon fa fa-pencil bigger-130"></i>修改
                                                                </a>
                                                                <form action="{{ URL('admin/admin_role/'.$user->id) }}" method="POST" style="display: inline;">
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
                                               共{{ $users->total() }}条记录{!! $users->render() !!}
                                            </div>
                            </div>
                        </div>
                    </div>

@endsection

<div class="well well-sm">
    <form action="/admin/{{$modelName}}/" method="get">
        <input class="input-middle" name="key" type="text" placeholder="key"
               value="{{ $filter['key']}}"/>
        <select name='sort'>
            <option value="">排序</option>
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
        <input type='hidden' name="dev" value="{{$filter['dev']}}">
        <button type="submit" class="btn btn-white">筛 选</button>

        <a class="btn btn-white btn-info btn-bold" href="/admin/{{$modelName}}/create">
            <i class="ace-icon fa glyphicon-plus bigger-120 blue"></i>
            增加
        </a>
        <a class="btn btn-white btn-info btn-bold " href="/admin/cleanCache">
            <i class="ace-icon fa  bigger-120 blue"></i>
            清除缓存
        </a>
    </form>

    <form action="/admin/config/upload" method="post" enctype="multipart/form-data">
        <input class="input-middle" name="file" type="file" required=""/>

        <button type="submit" class="btn btn-white">导入</button>
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
        <a class="btn btn-white btn-info btn-bold" href="/admin/configExport">
            <i class="ace-icon bigger-120 blue"></i>
            导出
        </a>

    </form>

</div>
<table id="" class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>id</th>
        <!-- <th>app</th> -->
        <th>key</th>
        <th>value</th>
        <th>desc</th>
        <th>修改</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($list as $l)
        <form class="form-horizontal" enctype="multipart/form-data" role="form"
              action="{{ url('admin/' . $modelName .'/' . $l->id) }}" method="POST">
            <input name="_method" type="hidden" value="PUT">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <tr>
                <td>{{ $l->id }}</td>
            <!-- <td><input type="text" id="" style="width:200px" name="app"  class="col-xs-10 col-sm-2" value="{{$l->app}}" /></td> -->
                <td><input type="text" id="" style="width:200px" name="key" class="col-xs-10 col-sm-2"
                           value="{{$l->key}}"/></td>
                @if($l->isJson())
                    <td><textarea id="" style="width:200px;height:200px" name="value"
                                  class="col-xs-10 col-sm-2">{{$l->value}}</textarea> <a target="_blank"
                                                                                         href="http://www.qqe2.com/?json={{$l->value}}">json编辑</a>
                    </td>
                @else
                    <td><input type="text" id="" style="width:200px" name="value"
                               class="col-xs-20 col-sm-4" value="{{$l->value}}"/> <a target="_blank"
                                                                                     href="http://www.qqe2.com/?json={{$l->value}}">json编辑</a>
                    </td>
                @endif
                <td>
                                    <textarea name="desc" style="height: 100px;width: 250px"
                                              class="col-xs-20 col-sm-2">{{$l->desc}}</textarea>
                </td>
                <td>
                    <button class="btn btn-info" style="width:100px" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        修改
                    </button>
                </td>
        </form>
        <td>
            <div class="hidden-sm hidden-xs action-buttons">

                <form action="{{ URL('admin/' . $modelName .'/' .$l->id) }}" method="POST"
                      style="display: inline;">
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
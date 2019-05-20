<div class="well well-sm">


</div>
<table id="" class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>id</th>
        <!-- <th>app</th> -->
        <th>key</th>
        <th>value</th>
        <th>修改</th>
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
                <td>{{$l->key}}</td>
                <td><input id=""  name="value" class="col-xs-10 col-sm-2" value="{{$l->value}}">
                </td>
                <td>
                    <button class="btn btn-info" style="width:100px" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        修改
                    </button>
                </td>
        </form>

        </tr>

    @endforeach
    </tbody>
</table>
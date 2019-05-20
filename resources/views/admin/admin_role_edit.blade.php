@extends('admin._default')

@section('content')

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width,initial-scale=1.0" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <meta content="email=no" name="format-detection">
    {{--<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">--}}
    <link href="https://cdn.bootcss.com/bootstrap-table/1.11.1/bootstrap-table.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.bootcss.com/jquery-treegrid/0.2.0/css/jquery.treegrid.min.css">
    {{--模态框--}}
    <!-- Modal -->
    {{--<div class="modal fade">--}}
        {{--<div class="modal-dialog" role="document">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                    {{--<h4 class="modal-title" id="exampleModalLabel">新增权限</h4>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<form  action="{{ URL('admin/authAdd') }}" method="POST" id="myForm">--}}
                        {{--<input type="hidden" value="" id="hidepid" name="pid">--}}
                        {{--<input type="hidden" value="" id="hideid" name="id">--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="recipient-name" class="control-label">名称:</label>--}}
                            {{--<input type="text" class="form-control" id="recipient-name" name="name">--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="recipient-name" class="control-label">控制器:</label>--}}
                            {{--<input type="text" class="form-control" id="recipient-controller" name="controller">--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="recipient-name" class="control-label">方法:</label>--}}
                            {{--<input type="text" class="form-control" id="recipient-action" name="action">--}}
                        {{--</div><div class="form-group">--}}
                            {{--<label for="recipient-name" class="control-label">uri:</label>--}}
                            {{--<input type="text" class="form-control" id="recipient-uri" name="uri">--}}
                        {{--</div><div class="form-group">--}}
                            {{--<label for="recipient-name" class="control-label">weight:</label>--}}
                            {{--<input type="text" class="form-control" id="recipient-weight" name="weight">--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="recipient-name" class="control-label">状态:</label>--}}
                            {{--<select name="status" id="recipient-status">--}}
                                {{--<option value="0" selected>不显示</option>--}}
                                {{--<option value="1">显示</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                        {{--<div class="modal-footer">--}}
                            {{--<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>--}}
                            {{--<button type="submit" class="btn btn-primary" >确认</button>--}}
                        {{--</div>--}}
                    {{--</form>--}}
                {{--</div>--}}

            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--模态框end--}}
<div class="container">
    <div class="page-header">
        <h1>
            编辑角色
        </h1>
    </div>
    <form  class="form-horizontal" role="form" action="{{ url('admin/admin_role/' . $user['id']) }}" method="POST" >
        <input name="_method" type="hidden" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="authids" value="" id="selectIds">
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 角色名称： </label>

            <div class="col-sm-9">
                <input type="text" id="" name="role_name" placeholder="角色名称" class="col-xs-10 col-sm-5" value="{{$user['role_name']}}" required />
            </div>
        </div>
        <h4>选择权限</h4>
        <table id="table"></table>
        <br/>
        {{--<button onclick="clickIds()">选择</button>--}}
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <button type="submit" class="btn btn-primary" onclick="clickIds()">确认</button>
        </div>
    </form>
</div>
<script src="https://cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap-table/1.12.1/bootstrap-table.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap-table/1.12.0/extensions/treegrid/bootstrap-table-treegrid.js"></script>
<script src="https://cdn.bootcss.com/jquery-treegrid/0.2.0/js/jquery.treegrid.min.js"></script>
<script type="text/javascript">

    var $table = $('#table');
    $.ajax({
        type: "get",
        url: '{{url('/admin/getAuth')}}',
        dataType: "json",
        data: {

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.status);
            alert(XMLHttpRequest.readyState);
            alert(textStatus);
        },
        success: function(data) {
            // var dataItem = JSON.parse(data);
            // console.log(data)
            $(function() {
                var ids = '{{$user['authids']}}';
                var arr = ids.split(',');
                //控制台输出一下数据
                // console.log(data);

                $table.bootstrapTable({
                    data:data,
                    idField: 'id',
                    dataType:'jsonp',
                    columns: [
                        { field: 'check',  checkbox: true, formatter: function (value, row, index) {
                                $.each(arr,function(i,item)
                                {
                                    if(row.id == item){row.check= true ; }
                                });
                                if (row.check == true) {
                                    // console.log(row.serverName);
                                    //设置选中
                                    return {  checked: true };
                                }

                            }
                        },
                        // {field: 'id', title: '编号', sortable: true, align: 'center'},
                        { field: 'name',  title: '名称' },
                        // {field: 'pid', title: '所属上级'},
                        // { field: 'status',  title: '是否显示', sortable: true,  align: 'center', formatter: 'statusFormatter'  },
                        { field: 'permission', title: '权限值'  },
                        // { field: 'operate', title: '操作', align: 'center', events : operateEvents, formatter: 'operateFormatter' },
                    ],

                    // bootstrap-table-treegrid.js 插件配置 -- start

                    //在哪一列展开树形
                    treeShowField: 'name',
                    //指定父id列
                    parentIdField: 'pid',


                    onResetView: function(data) {
                        //console.log('load');
                        $table.treegrid({
                            // initialState: 'collapsed',// 所有节点都折叠
                            initialState: 'expanded',// 所有节点都展开，默认展开
                            treeColumn: 1,
                            // expanderExpandedClass: 'glyphicon glyphicon-minus',  //图标样式
                            // expanderCollapsedClass: 'glyphicon glyphicon-plus',
                            onChange: function() {
                                $table.bootstrapTable('resetWidth');
                            }
                        });

                        //只展开树形的第一级节点
                        // $table.treegrid('getRootNodes').treegrid('expand');

                    },
                    onCheck:function(row){
                        var datas = $table.bootstrapTable('getData');
                        // 勾选子类
                        selectChilds(datas,row,"id","pid",true);

                        // 勾选父类
                        selectParentChecked(datas,row,"id","pid")

                        // 刷新数据
                        $table.bootstrapTable('load', datas);
                    },

                    onUncheck:function(row){
                        var datas = $table.bootstrapTable('getData');
                        selectChilds(datas,row,"id","pid",false);
                        // $table.bootstrapTable('load', datas);

                    },
                    // bootstrap-table-treetreegrid.js 插件配置 -- end
                });
            });
        }
    });
    // 格式化按钮
    function operateFormatter(value, row, index) {
        return [
            '<button type="button" class="RoleOfadd btn-small  btn-primary"  data-toggle="modal" data-target="#myModal" style="margin-right:15px;"><i class="fa fa-plus" ></i>&nbsp;新增子分类</button>',
            '<button type="button" class="RoleOfedit btn-small   btn-primary"  data-toggle="modal" data-target="#myModal" style="margin-right:15px;"><i class="fa fa-pencil-square-o" ></i>&nbsp;修改</button>',
            '<button type="button" class="RoleOfdelete btn-small   btn-primary" style="margin-right:15px;"><i class="fa fa-trash-o" ></i>&nbsp;删除</button>'
        ].join('');

    }
    // 格式化类型
    function typeFormatter(value, row, index) {
        if (value === 'menu') {  return '菜单';  }
        if (value === 'button') {  return '按钮'; }
        if (value === 'api') {  return '接口'; }
        return '-';
    }
    // 格式化状态
    function statusFormatter(value, row, index) {
        if (value === 1) {
            return '<span class="label label-success">显示</span>';
        } else {
            return '<span class="label label-default">隐藏</span>';
        }
    }

    //初始化操作按钮的方法
    window.operateEvents = {
        'click .RoleOfadd': function (e, value, row, index) {
            add(row.id);
        },
        'click .RoleOfdelete': function (e, value, row, index) {
            del(row.id);
        },
        'click .RoleOfedit': function (e, value, row, index) {
            update(row.id);
        }
    };
</script>
<script>
    /**
     * 选中父项时，同时选中子项
     * @param datas 所有的数据
     * @param row 当前数据
     * @param id id 字段名
     * @param pid 父id字段名
     */
    function selectChilds(datas,row,id,pid,checked) {
        for(var i in datas){
            if(datas[i][pid] == row[id]){
                datas[i].check=checked;
                selectChilds(datas,datas[i],id,pid,checked);
            };
        }
    }

    function selectParentChecked(datas,row,id,pid){
        for(var i in datas){
            if(datas[i][id] == row[pid]){
                datas[i].check=true;
                selectParentChecked(datas,datas[i],id,pid);
            };
        }
    }

    function clickIds() {
        var selRows = $table.bootstrapTable("getSelections");
        // if(selRows.length == 0){
        //     alert("请至少选择一行");
        //     return;
        // }

        var postData = "";
        $.each(selRows,function(i) {
            postData +=  this.id;
            if (i < selRows.length - 1) {
                postData += ",";
            }
        });
        $('#selectIds').val(postData);
        // alert("你选中行的 id 为："+postData);

    }

</script>
@endsection
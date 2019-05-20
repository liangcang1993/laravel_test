<form class="form-horizontal" enctype="multipart/form-data" role="form" action="/admin/userStatis" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    开始日期：<input type='text' id = "startDate" name='startDate' value="{{$startDate}}" placeholder="" required />
    结束日期：<input type='text' id = "endDate" name='endDate' value="{{$endDate}}" placeholder="" required /> &nbsp
     <!-- <input name="_method" type="hidden" value="CREATE"> -->
    <input type="radio" name="system" {{$system==1?'checked':''}} value="1" />&nbsp IOS &nbsp
    <input type="radio" name="system" {{$system==2?'checked':''}} value="2" />&nbsp Android &nbsp
    <button class="btn btn-info btn-sm" style="width:100px" type="submit">
        <i class="ace-icon fa fa-check bigger-110"></i>
        查询
    </button>

</form>

<script type="text/javascript">
	$(function () {
	    $( "#startDate" ).datepicker({
	    dateFormat: 'yy-mm-dd',
	});
	    $( "#endDate" ).datepicker({
	    dateFormat: 'yy-mm-dd',
	});

	   
	    // $("input[name='start_time']").val(getNowTime());
	});
</script>

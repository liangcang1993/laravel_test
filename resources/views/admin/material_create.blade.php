@extends('admin._default')
@section('content')
    <link rel="stylesheet" href="/css/select2.css"/>

    <div class="page-content">

        <div class="page-header">
            <h1>
                增加
            </h1>
        </div>

        <div class="row">

            <div class="col-xs-12">

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


            </div>
        </div>
    </div>
    @include('admin._material_create')


    </div>


    <script type="text/javascript">

        var isCommitted = false;//表单是否已经提交标识，默认为false

        function dosubmit() {
            if (isCommitted == false) {
                isCommitted = true;//提交表单后，将表单是否已经提交标识设置为true
                $('#submit').attr("disabled", "true");
                return true;//返回true让表单正常提交
            } else {
                return false;//返回false那么表单将不提交
            }
        }

        $(function () {
            //页面加载完毕后开始执行的事件
            var city_json = '{!!$type_json!!}';
            var city_obj = eval('(' + city_json + ')');
            var acity_json = '{!!$atype_json!!}';
            var acity_obj = eval('(' + acity_json + ')');
            $.each(city_obj, function (i, item) {

                $("#province").append("<option value='" + i + "'>" + i + "</option>");
            });
            $("#province").change(function () {
                var now_province = $(this).val();
                $("#city").html('<option value=""></option>');
                for (var k in city_obj[now_province]) {
                    var now_city = city_obj[now_province][k];
                    // console.log(now_city);
                    $("#city").append('<option value="' + now_city + '">' + now_city + '</option>');
                }

            });
            $("#androidtype").change(function () {
                var androidtype = $(this).val();
                $("#androidsubtype").html('<option value=""></option>');
                for (var k in acity_obj[androidtype]) {
                    var now_city = acity_obj[androidtype][k];
                    // console.log(now_city);
                    $("#androidsubtype").append('<option value="' + now_city + '">' + now_city + '</option>');
                }

            });
        });


        var num = 1;

        function adddiv() {
            if (15 > num) {
                var content = $("#materialtemplet").html();
                $("#materialgroup").append(content);
                num += 1;
            }
            console.log(num);
        }

        function delme(s) {
            $(s).parent().parent().remove();
            num -= 1;
        }

        $("#dev").change(function () {
            var dev = $("#dev").val();
            if (dev == 2){
                $("#andoridtype").show();
                $("div[name='iosvip']").hide();
            }else{
                $("#andoridtype").hide();
            }
            if (dev == 0){
                $("div[name='iosvip']").show();
                $("div[name='avip']").hide();
            }else if(dev == 1){
                $("div[name='avip']").show();
                $("div[name='iosvip']").hide();
            }else{
                $("div[name='avip']").show();
                $("div[name='iosvip']").show();
            }
            $.ajax({
                type: "get",
                url: "{{ URL('admin/getCreateTypes') }}",
                dataType: "jsonp",
                data:"dev="+dev,
                jsonp:'callback',
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                },
                success: function(data) {
                    $("#province").empty();
                    $("#city").empty();
                    $.each(data, function (i, item) {
                        $("#province").append("<option value='" + i + "'>" + i + "</option>");
                    });
                    var now_province = $("#province").val()
                    $("#city").html('<option value=""></option>');
                    for (var k in data[now_province]) {
                        var now_city = data[now_province][k];
                        $("#city").append('<option value="' + now_city + '">' + now_city + '</option>');
                    }

                }
            });
            $.ajax({
                type: "get",
                url: "{{ URL('admin/getCreateTypes') }}",
                dataType: "jsonp",
                data:"dev="+dev+'&flag=android',
                jsonp:'callback',
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                },
                success: function(data) {

                        $("#androidtype").empty();
                        $("#androidsubtype").empty();
                        $("#androidtype").html('<option value=""></option>');
                        $.each(data, function (i, item) {
                            $("#androidtype").append("<option value='" + i + "'>" + i + "</option>");
                        });
                        var now_androidtype = $("#androidtype").val()
                        $("#androidsubtype").html('<option value=""></option>');
                        for (var k in data[now_androidtype]) {
                            var sub = data[now_androidtype][k];
                            $("#city").append('<option value="' + sub + '">' + sub + '</option>');
                        }


                }
            });
        });


    </script>

@endsection

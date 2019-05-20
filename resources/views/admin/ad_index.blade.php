@extends('admin._default')
<link href="https://cdn.bootcss.com/bootstrap-table/1.11.1/bootstrap-table.min.css" rel="stylesheet">
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
<script src="https://cdn.bootcss.com/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>
<style>
    .navbar-container .ace-nav{height: 1%}
</style>
@section('content')


    <div class="page-content">
        <div class="col-sm-12">
            <form action="/admin/userUseStatistics" method="POST" enctype="multipart/form-data" >
                {{--<select id="is_vip" name="is_vip">--}}
                    {{--<option value="all" @if(isset($is_vip) && $is_vip == 'all') selected="true" @endif>all</option>--}}
                    {{--<option value="0" @if(isset($is_vip) && $is_vip == '0') selected="true" @endif>没氪金的</option>--}}
                    {{--<option value="1" @if(isset($is_vip) && $is_vip == '1') selected="true" @endif>氪了金的</option>--}}
                {{--</select>--}}
                {{--<select id="country" name="country">--}}
                    {{--<option value="all" @if(isset($country) && $country == 'all') selected="true" @endif>all</option>--}}
                    {{--@foreach($countrys as $countryss){--}}
                            {{--<option value="{{$countryss}}" @if(isset($country) && $country == $countryss) selected="true" @endif>{{$countryss}}</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
                {{--@if(Session::get('app') == 'bodyApp')--}}
                {{--<select id="sex" name="sex">--}}
                    {{--<option value="0" @if(isset($sex) && $sex == '') selected="true" @endif>全部性别</option>--}}
                    {{--<option value="1" @if(isset($sex) && $sex == '1') selected="true" @endif>男</option>--}}
                    {{--<option value="2" @if(isset($sex) && $sex == '2') selected="true" @endif>女</option>--}}
                {{--</select>--}}
                {{--@endif--}}
                {{--<button type="submit" class="btn btn-white">筛 选</button>--}}
                {{----}}
                {{--<a class="btn btn-white btn-info btn-bold " href="/admin/userUseStatistics?export=1">--}}
                        {{--<i class="ace-icon fa  bigger-80 blue"></i>--}}
                        {{--导出--}}
                {{--</a>--}}
            </form>
        </div>
        @if($device == 'ios')
            @include('admin._ad_index_ios');
        @else
            @include('admin._ad_index_android');
        @endif

    </div>

@endsection

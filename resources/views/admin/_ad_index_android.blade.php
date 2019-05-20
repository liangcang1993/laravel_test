<div class="row">
    <div class="col-xs-12">
        <table id="" class="table table-striped table-bordered table-hover" data-toggle="table">
            <thead>
            <tr>
                <th data-rowspan=2>日期</th>
                @foreach($title as $kk=>$t)
                    <th data-colspan="4">{{$t}}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($title as $t)
                    <th>点击次数</th>
                    <th>展示次数</th>
                    <th>展示完成</th>
                    <th>展示成功率</th>
                @endforeach
            </tr>

            </thead>
            <tbody>
            @foreach($list as $kk=>$ll)
                <tr>
                    <td>{{$kk}}</td>
                    @foreach($title as $tt)
                        <td>{{isset($ll[$tt]['see'])?$ll[$tt]['see']:''}}</td>
                        <td>{{isset($ll[$tt]['see'])?$ll[$tt]['see']:''}}</td>
                        <td>{{isset($ll[$tt]['reward'])?$ll[$tt]['reward']:''}}</td>
                        <td>
                            @if(isset($ll[$tt]['reward']) && !empty($ll[$tt]['reward']) && isset($ll[$tt]['see']) && !empty($ll[$tt]['see']))
                                {{ number_format($ll[$tt]['reward']*100/$ll[$tt]['see'],2)}}%
                            @else
                                0
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach

            </tbody>
        </table>

    </div>
</div>
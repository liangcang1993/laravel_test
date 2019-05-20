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
                @if($filter['dev'] == 3)
                    @include('admin._config_vip_coeffic')
                @else
                    @include('admin._config')
                @endif

                <div class="pull-right ">
                    共{{ $list->total() }}条记录{!!
                                               $list->appends(['key' => $filter['key']])
                                               ->appends(['sort' => $filter['sort']])
                                               ->appends(['cover' => $filter['cover']])
                                               ->appends(['dev' => $filter['dev']])
                                               ->render() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

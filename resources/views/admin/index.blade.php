@extends('admin._default')

@section('content')


                    <div class="breadcrumbs" id="breadcrumbs">

                        <ul class="breadcrumb">
                            <li>
                                <i class="ace-icon fa fa-home home-icon"></i>
                                <a href="/admin">首页</a>
                            </li>
                        </ul>

                    </div>

                    <div class="page-content">

                        <div class="row">
                            <div class="col-xs-12">

                                <div class="alert alert-block alert-success">

                                    {{ Auth::user()->name }} Welcome 
                                    
                                </div>

                            </div>
                        </div>
                    </div>

@endsection

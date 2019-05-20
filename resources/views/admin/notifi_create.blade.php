@extends('admin._default')
@section('content')
<style>

</style>

<link rel="stylesheet" href="/css/select2.css" />

  <div class="page-content">

      <div class="page-header">
          <h1>
              增加推送
          </h1>
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
              @if(Session::get('app') == 'photable')
                  <form class="form-horizontal" enctype="multipart/form-data"  id="form1" role="form" action="" method="POST" name="form1">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">

                  {{--<div class="form-group">--}}
                      {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 标题 </label>--}}

                      {{--<div class="col-sm-6">--}}
                          {{--<input type="text"  style="width:300px" class="form-control" name="title"  />--}}
                      {{--</div>--}}
                  {{--</div>--}}
                  <div class="form-group">
                      <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 内容 </label>

                      <div class="col-sm-6">
                          <input type="text" style="width:800px" class="form-control" name="msg"  />
                      </div>
                  </div>
                   {{--<div class="form-group">--}}
                      {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> icon </label>--}}

                      {{--<div class="col-sm-6">--}}
                           {{--<input type="file"  style="width:300px"  class="form-control" name="icon"   />--}}
                      {{--</div>--}}
                  {{--</div>--}}
                  {{--<div class="form-group">--}}
                      {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> pic </label>--}}

                      {{--<div class="col-sm-6">--}}
                           {{--<input type="file"  style="width:300px"  class="form-control" name="pic"   />--}}
                      {{--</div>--}}
                  {{--</div>--}}
                   <div class="form-group">
                      <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> action </label>

                      <div class="col-sm-6">
                          <select name='action'>
                              <option value='home'> home</option>
                              <option value='material'>素材详情</option>
                          </select>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> out_id </label>

                      <div class="col-sm-6">
                          <input style="width: 100px" type="text" class="form-control" name="out_id"  />
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> clt </label>

                      <div class="col-sm-6">
                          <select name='clt'>
                              <option value='0'>all</option>
                              <option value='1'>ios</option>
                              <option value='2'> android</option>
                          </select>
                      </div>
                  </div>
                   <div class="form-group">
                      <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 范围 </label>

                      <div class="col-sm-6">
                          <select name='range'>
                              <option value='all'> 所有用户</option>
                              <option value='token'>单个用户</option>
                              <option value='vip'>已订阅用户</option>
                              <option value='free'>未订阅用户</option>
                          </select>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> token </label>
                      <div class="col-sm-6">
                          <input type="text" class="form-control"  style="width: 800px" name="token"  />
                      </div>
                  </div>



                 <div class="form-group">
                      <label class="col-sm-3 control-label no-padding-right" for="form-field-1">推送时间:</label>
                      <div class="input-group date form_datetime col-md-5" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                          <input class="form-control" size="16" type="text" value="" readonly id="upload_time" name="send_time">
                          <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                          <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                      </div>
                      <input type="hidden" id="dtp_input1" value="" /><br/>
                  </div>
                 <input type="hidden" value='poto' name='app'  />

                
                  <div class="clearfix form-actions">
                      <div class="col-md-offset-3 col-md-9">
                          <button class="btn btn-info" type="submit" onclick="form1.action='{{ URL('admin/notifi') }}'">
                              <i class="ace-icon fa fa-check bigger-110"></i>
                              发布
                          </button>

                        <!--   <button class="btn btn-info" type="submit" onclick="form1.action='{{ URL('admin/gif/gif?type=1') }}'">
                              <i class="ace-icon fa fa-check bigger-110"></i>
                              队列发布
                          </button>
-->
                      </div>
                  </div>
              </form>
              @elseif(Session::get('app') == 'manly'||Session::get('app') == 'facey'||Session::get('app') == 'bodyApp'||Session::get('app') == 'Everlook')
                  <form class="form-horizontal" enctype="multipart/form-data"  id="form1" role="form" action="" method="POST" name="form1">
                           <input type="hidden" name="_token" value="{{ csrf_token() }}">

                           <div class="form-group">
                               <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 内容 </label>

                               <div class="col-sm-6">
                                   <input type="text" style="width:800px" class="form-control" name="msg"  />
                               </div>
                           </div>

                           <div class="form-group">
                               <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> action </label>
                               <div class="col-sm-6">
                                   <select name='action'>
                                       <option value='home'> home</option>
                                       @if(Session::get('app') == 'facey')
                                           @foreach($list as $l)
                                               <option value="{{$l->name}}">{{$l->name}}</option>
                                           @endforeach
                                       @elseif(Session::get('app') == 'manly')
                                       <option value='material'>素材详情</option>
                                       @elseif(Session::get('app') == 'Everlook')
                                           <option value='material'>素材详情</option>
                                       @endif
                                   </select>
                               </div>
                           </div>
                           <div class="form-group">
                               <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> out_id </label>

                               <div class="col-sm-6">
                                   <input style="width: 100px" type="text" class="form-control" name="out_id"  />
                               </div>
                           </div>
                          <div class="form-group">
                              <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> is_pass </label>

                              <div class="col-sm-6">
                                  <select name='ispass'>
                                      <option value='0'> 未通过</option>
                                      <option value='1'> 已通过</option>
                                  </select>
                              </div>
                          </div>
                           {{--<div class="form-group">--}}
                               {{--<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> clt </label>--}}

                               {{--<div class="col-sm-6">--}}
                                   {{--<select name='clt'>--}}
                                       {{--<option value='2'> android</option>--}}
                                       {{--<option value='1'>ios</option>--}}
                                   {{--</select>--}}
                               {{--</div>--}}
                           {{--</div>--}}
                      <input type="hidden" value="1" name='clt'  />
                      <input type="hidden" value="all" name='range'  />
                      <input type="hidden" value="" name='title'  />
                      <input type="hidden" value="" name='token'  />
                      <input type="hidden" value={{Session::get('app')}} name='app'  />
                      <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 范围 </label>

                          <div class="col-sm-6">
                              <select name='range'>
                                  {{--<option value='token'>单个用户</option>--}}
                                  <option value='all'> 所有用户</option>
                                  <option value='vip'>已订阅用户</option>
                                  <option value='free'>未订阅用户</option>
                              </select>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 推送类型 </label>

                          <div class="col-sm-6">
                              <select name='push_type'>
                                  <option value='operations'> 运营推送</option>
                                  <option value='old'>老用户推送</option>
                                  <option value='new'>新用户推送</option>
                              </select>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1">最早注册时间:</label>
                          <div class="input-group date form_datetime col-md-5" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                              <input class="form-control" size="16" type="text" value="" readonly id="upload_time3" name="createstart_time">
                              <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                              <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                          </div>
                          <input type="hidden" id="dtp_input1" value="" /><br/>
                      </div>
                      <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1">最晚注册时间:</label>
                          <div class="input-group date form_datetime col-md-5" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                              <input class="form-control" size="16" type="text" value="" readonly id="upload_time2" name="createend_time">
                              <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                              <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                          </div>
                          <input type="hidden" id="dtp_input1" value="" /><br/>
                      </div>
                      <div class="form-group">
                          <label class="col-sm-3 control-label no-padding-right" for="form-field-1">最后登陆时间:</label>
                          <div class="input-group date form_datetime col-md-5" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                              <input class="form-control" size="16" type="text" value="" readonly id="upload_time1" name="lastlogin_at">
                              <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                              <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                          </div>
                          <input type="hidden" id="dtp_input1" value="" /><br/>
                      </div>
                           <div class="form-group">
                               <label class="col-sm-3 control-label no-padding-right" for="form-field-1">这条推送时间:</label>
                               <div class="input-group date form_datetime col-md-5" data-date="{{date("Y-m-d H:i:s")}}" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                                   <input class="form-control" size="16" type="text" value="" readonly id="upload_time" name="send_time">
                                   <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                   <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                               </div>
                               <input type="hidden" id="dtp_input1" value="" /><br/>
                           </div>


                           <div class="clearfix form-actions">
                               <div class="col-md-offset-3 col-md-9">
                                   <button class="btn btn-info" type="submit" onclick="form1.action='{{ URL('admin/notifi') }}'">
                                       <i class="ace-icon fa fa-check bigger-110"></i>
                                       发布
                                   </button>

                               <!--   <button class="btn btn-info" type="submit" onclick="form1.action='{{ URL('admin/gif/gif?type=1') }}'">
                              <i class="ace-icon fa fa-check bigger-110"></i>
                              队列发布
                          </button>
-->
                               </div>
                           </div>
                       </form>
              @endif

          </div>
      </div>
  </div>

  <script>
      $(document).ready(function(){

          $('.form_datetime').datetimepicker({
              language:  'cn',
              weekStart: 1,
              todayBtn:  1,
              autoclose: 1,
              todayHighlight: 1,
              startView: 2,
              forceParse: 0,
              showMeridian: 1,
              format: 'yyyy-mm-dd hh:ii',
              // startDate:getNowTime()
              // startDate:'2016-01-01 00:00:00'
              startDate:0
          });

          function getNowTime() {
              var nowtime =new Date(new Date()-24*60*60*1000)
              var year = nowtime.getFullYear();
              var month = padleft0(nowtime.getMonth() + 1);
              var day = padleft0(nowtime.getDate());
              var hour = padleft0(nowtime.getHours());
              var minute = padleft0(nowtime.getMinutes());
              var second = padleft0(nowtime.getSeconds());
              return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
          }
          //补齐两位数
          function padleft0(obj) {
              return obj.toString().replace(/^[0-9]{1}$/, "0" + obj);
          }
      });



  </script>

@endsection

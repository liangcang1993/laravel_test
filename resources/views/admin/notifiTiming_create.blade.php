<form class="form-horizontal" enctype="multipart/form-data" role="form" action="{{ $pageInfo['post_link'] }}" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @if(!empty($notifiInfo))
    <input name="_method" type="hidden" value="PUT">
    @endif
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{{$pageInfo['title']}}</h4>
    </div>
    <div class="modal-body">
        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label >推送标题</label>
                        <input type="text" class="form-control" name="title" value="@if (!empty($notifiInfo['title'])){{$notifiInfo['title']}}@endif"/>
                    </div>

                    <div class="form-group">
                        <label >推送内容:</label>
                        <textarea class="form-control" name="msg" rows="3" >@if (!empty($notifiInfo['msg'])){{$notifiInfo['msg']}}@endif</textarea>
                    
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2  no-padding-left " for="form-field-1" > 推送类型: </label>

                        <div >
                            <select name='type'>
                                <option value='newUserTask' @if(isset($notifiInfo) && $notifiInfo['type'] == 'newUserTask') selected="true" @endif > 新手任务</option>
                                <option value='lostRecall' @if(isset($notifiInfo) && $notifiInfo['type'] == 'lostRecall') selected="true" @endif >流失召回</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2  no-padding-left" for="form-field-1"> 推送源: </label>

                        <div >
                            <select name='push_from'>
                                <option value='notifi_token' @if(isset($notifiInfo) && $notifiInfo['push_from'] == 'notifi_token') selected="true" @endif>未推送用户</option>
                                <option value='user_push_statistic' @if(isset($notifiInfo) && $notifiInfo['push_from'] == 'user_push_statistic') selected="true" @endif>已推送用户</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2  no-padding-left" for="form-field-1"> action: </label>

                        <div >
                            <select name='action'>
                                <option value='home' @if(isset($notifiInfo) && $notifiInfo['action'] == 'home') selected="true" @endif > home</option>
                                <option value='days' @if(isset($notifiInfo) && $notifiInfo['action'] == 'days') selected="true" @endif > days</option>
                                <option value='material' @if(isset($notifiInfo) && $notifiInfo['action'] == 'material') selected="true" @endif>素材详情</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2  no-padding-left" for="form-field-1"> is_pass: </label>

                        <div >
                            <select name='ispass'>
                                <option value='0' @if(isset($notifiInfo) && $notifiInfo['is_pass'] == 0) selected="true" @endif> 未通过</option>
                                <option value='1' @if(isset($notifiInfo) && $notifiInfo['is_pass'] == 1) selected="true" @endif> 已通过</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label > 推送间隔天数: </label>

                        <div >
                            <input style="width: 100px" type="text" class="form-control" name="push_day" value="@if (!empty($notifiInfo['push_day'])){{$notifiInfo['push_day']}}@endif" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label > out_id: </label>

                        <div >
                            <input style="width: 100px" type="text" class="form-control" name="out_id"  value="@if (!empty($notifiInfo['out_id'])){{$notifiInfo['out_id']}}@endif" />
                        </div>
                    </div>

                    <input type="hidden" value="1" name='clt'  />
                    <input type="hidden" value={{Session::get('app')}} name='app'  />

                 
                </div>
            </div>
        </div>  
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="submit" class="btn btn-primary">保存</button>
    </div>
</form>
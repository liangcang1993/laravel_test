<form class="form-horizontal" enctype="multipart/form-data" id="form1" role="form"
      action="{{ URL('admin/'. $modelName) }}" onsubmit="return dosubmit()" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">平台 </label>
        <div class="col-sm-9">
            <select name="dev" id="dev">
                <option value="0" selected="selected">Ios</option>
                <option value="1">Android</option>
                <option value="2">all</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">type </label>
        <div class="col-sm-9">
            <select name="type" id="province">
                <option value=""></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">sub_type </label>
        <div class="col-sm-9">
            <select name="sub_type" id="city">
                <option value=""></option>
            </select>
        </div>
    </div>
    <div id="andoridtype" hidden >
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Android_type </label>
            <div class="col-sm-9">
                <select name="android_type" id="androidtype">
                    <option value=""></option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Android_sub_type </label>
            <div class="col-sm-9">
                <select name="android_sub_type" id="androidsubtype">
                    <option value=""></option>
                </select>
            </div>
        </div>
    </div>



    <div id='add'>
        <div id="add_div">
            <div class="form-group" style="Border:1px solid #000">

                @for($i = 0;$i<5;$i++)
                <div id="materialgroup">
                    <div class="col-sm-3"
                         style="border: #1e347b solid 1px;margin: 20px 0px 10px 20px;width: 90%; padding-top: 10px;">
                        <div class="form-group">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"
                                  style="float: right;padding-right: 5px;"></span>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 素材 </label>

                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="large_pic[]"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"
                                   for="form-field-1">unique_name(后台展示名) </label>

                            <div class="col-sm-9">
                                <input type="text" id="" name="unique_name[]" class="col-xs-10 col-sm-4" value=''/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">weight </label>

                            <div class="col-sm-9">
                                <input type="text" id="" name="weight[]" class="col-xs-10 col-sm-2" value='0'/>
                            </div>
                        </div>
                        <div class="form-group" hidden id="android_vip" name="avip">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">android_is_vip </label>

                            <div class="col-sm-9">
                                <input type="text" id="" name="is_vip[]" class="col-xs-10 col-sm-2" value='0'/>
                            </div>
                        </div>
                        <div class="form-group" id="ios_vip" name="iosvip">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">ios_is_vip </label>

                            <div class="col-sm-9">
                                <input type="text" id="" name="ios_is_vip[]" class="col-xs-10 col-sm-2" value='0'/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"
                                   for="form-field-1">display_name </label>

                            <div class="col-sm-9">
                                <input type="text" id="" name="display_name[]" class="col-xs-10 col-sm-4" value=''/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"
                                   for="form-field-1">display_name(简体中文) </label>

                            <div class="col-sm-9">
                                <input type="text" id="" name="display_name_cn[]" class="col-xs-10 col-sm-4" value=''/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"
                                   for="form-field-1">display_name(繁体中文) </label>

                            <div class="col-sm-9">
                                <input type="text" id="" name="display_name_tw[]" class="col-xs-10 col-sm-4" value=''/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"
                                   for="form-field-1">display_name(日语) </label>

                            <div class="col-sm-9">
                                <input type="text" id="" name="display_name_jp[]" class="col-xs-10 col-sm-4" value=''/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"
                                   for="form-field-1">display_name(俄语) </label>

                            <div class="col-sm-9">
                                <input type="text" id="" name="display_name_ru[]" class="col-xs-10 col-sm-4" value=''/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述 </label>

                            <div class="col-sm-9">
                                <textarea name="desc[]" class="col-xs-10 col-sm-8" value=''></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述(简体中文) </label>

                            <div class="col-sm-9">
                                <textarea name="desc_cn[]" class="col-xs-10 col-sm-8" value=''></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述(繁体中文) </label>

                            <div class="col-sm-9">
                                <textarea name="desc_tw[]" class="col-xs-10 col-sm-8" value=''></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述(日语) </label>

                            <div class="col-sm-9">
                                <textarea name="desc_jp[]" class="col-xs-10 col-sm-8" value=''></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述(俄语) </label>

                            <div class="col-sm-9">
                                <textarea name="desc_ru[]" class="col-xs-10 col-sm-8" value=''></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">解锁金币 </label>

                            <div class="col-sm-9">
                                <input type="text" name="coin[]" class="col-xs-10 col-sm-4" value='0'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_20 </label>

                            <div class="col-sm-9">
                                <input type="text" name="level_20_coin[]" class="col-xs-10 col-sm-4" value='2'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_24 </label>

                            <div class="col-sm-9">
                                <input type="text" name="level_24_coin[]" class="col-xs-10 col-sm-4" value='2'>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_48 </label>

                            <div class="col-sm-9">
                                <input type="text" name="level_48_coin[]" class="col-xs-10 col-sm-4" value='3'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_70 </label>

                            <div class="col-sm-9">
                                <input type="text" name="level_70_coin[]" class="col-xs-10 col-sm-4" value='3'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_108 </label>

                            <div class="col-sm-9">
                                <input type="text" name="level_108_coin[]" class="col-xs-10 col-sm-4" value='4'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_180 </label>

                            <div class="col-sm-9">
                                <input type="text" name="level_180_coin[]" class="col-xs-10 col-sm-4" value='4'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_288 </label>

                            <div class="col-sm-9">
                                <input type="text" name="level_288_coin[]" class="col-xs-10 col-sm-4" value='8'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_336 </label>

                            <div class="col-sm-9">
                                <input type="text" name="level_336_coin[]" class="col-xs-10 col-sm-4" value='8'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_432 </label>

                            <div class="col-sm-9">
                                <input type="text" name="level_432_coin[]" class="col-xs-10 col-sm-4" value='16'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_504 </label>

                            <div class="col-sm-9">
                                <input type="text" name="level_504_coin[]" class="col-xs-10 col-sm-4" value='16'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">level_704 </label>

                            <div class="col-sm-9">
                                <input type="text" name="level_704_coin[]" class="col-xs-10 col-sm-4" value='32'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">banner底色 </label>

                            <div class="col-sm-9">
                                <input type="text" id="" name="display_color[]" class="col-xs-10 col-sm-2" value=''/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">banner底色2 </label>

                            <div class="col-sm-9">
                                <input type="text" id="" name="display_color2[]" class="col-xs-10 col-sm-2" value=''/>
                            </div>
                        </div>

                    </div>
                </div>
                @endfor
                <!-- <div class="col-sm-3" >
                    <div class="form-group">
                        <img src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1534767752321&di=c54397f1ca6d6edc84ae36c67ceda750&imgtype=0&src=http%3A%2F%2Fimage.tupian114.com%2F20140417%2F09122960.png" style="width:50%;margin-top: 10%;" alt="" onclick="adddiv()">
                    </div>
                </div> -->
            </div>

        </div>

    </div>
    <div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-9">
            <input class="btn btn-info" type="submit" id='submit' value="添加">

        </div>
    </div>

</form>
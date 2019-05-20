@extends('admin._default')
@section('content')
<link rel="stylesheet" href="/css/select2.css" />


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

                                <form class="form-horizontal" enctype="multipart/form-data"  id="form1" role="form" action="{{ URL('admin/'. $modelName) }}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    
                                     <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> position </label>

                                        <div class="col-sm-3">
                                            <select name='position' class="col-sm-3">
                                                <option value="home" selected="selected">首页</option>
                                                <option value="store">商店</option>
                                           </select>
                                        
                                        </div>
                                    </div>
                                    
                                     <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> large_pic </label>

                                        <div class="col-sm-5">
                                            <input type="file" class="form-control"  name="large_pic" onchange="loadImage(this)"  />
                                        </div>
                                    </div>
                                  
                                    
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">title </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="title"  class="col-xs-10 col-sm-5"   value=''  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">简体中文 </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="title_cn"  class="col-xs-10 col-sm-5"   value=''  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">繁体中文 </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="title_tw"  class="col-xs-10 col-sm-5"   value=''  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">俄语 </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="title_ru"  class="col-xs-10 col-sm-5"   value=''  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">日语 </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="title_jp"  class="col-xs-10 col-sm-5"   value=''  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">西班牙语 </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="title_es"  class="col-xs-10 col-sm-5"   value=''  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">英文描述 </label>

                                        <div class="col-sm-9">
                                            <textarea name="desc"  class="col-xs-10 col-sm-2"   value=''  style="width: 500px;height: 200px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述(简体中文) </label>

                                        <div class="col-sm-9">
                                            <textarea name="desc_cn"  class="col-xs-10 col-sm-2"   value=''  style="width: 500px;height: 200px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述(繁体中文) </label>

                                        <div class="col-sm-9">
                                            <textarea name="desc_tw"  class="col-xs-10 col-sm-2"   value=''  style="width: 500px;height: 200px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述(俄语) </label>

                                        <div class="col-sm-9">
                                            <textarea name="desc_ru"  class="col-xs-10 col-sm-2"   value=''  style="width: 500px;height: 200px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述(日语) </label>

                                        <div class="col-sm-9">
                                            <textarea name="desc_jp"  class="col-xs-10 col-sm-2"   value=''  style="width: 500px;height: 200px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">描述(西班牙语) </label>

                                        <div class="col-sm-9">
                                            <textarea name="desc_es"  class="col-xs-10 col-sm-2"   value=''  style="width: 500px;height: 200px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">color </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="color"  class="col-xs-10 col-sm-2"   value=''  />
                                        </div>
                                    </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">推荐date</label>

                                            <div class="col-sm-9">
                                                <input  type="date" name="recommend_date" value=""/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Android素材ID </label>

                                            <div class="col-sm-9">
                                                <input type="text" id="" name="android_mid"  class="col-xs-10 col-sm-2"   value=''  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">iOS素材ID </label>

                                            <div class="col-sm-9">
                                                <input type="text" id="" name="ios_mid"  class="col-xs-10 col-sm-2"   value=''  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1">iOS中国素材ID </label>

                                            <div class="col-sm-9">
                                                <input type="text" id="" name="ios_cn_mid"  class="col-xs-10 col-sm-2"   value=''  />
                                            </div>
                                        </div>
                                    
                                    
                                     <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">weight </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="weight"  class="col-xs-10 col-sm-2"   value='0'  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">version </label>

                                        <div class="col-sm-9">
                                            <input type="text" id="" name="version"  class="col-xs-10 col-sm-2"   value='90909'  />
                                        </div>
                                    </div>
                                   
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button class="btn btn-info" type="submit">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                               添加
                                            </button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        
                        function loadImage(img) {
                            var filePath = img.value;
                            var fileExt = filePath.substring(filePath.lastIndexOf("."))
                                .toLowerCase();
                 
                            if (!checkFileExt(fileExt)) {
                                alert("您上传的文件不是图片,请重新上传！");
                                img.value = "";
                                return;
                            }
                            if (img.files && img.files[0]) {
                                var fsize = (img.files[0].size / 1024).toFixed(0);
                                if (fsize >1024) {
                                    img.value = "";
                                    alert('错误！！！请上传小于1M的文件');
                                    return
                                }
                                // alert('你选择的文件大小' + (img.files[0].size / 1024).toFixed(0) + "kb");
                
                            } else {
                                img.select();
                                var url = document.selection.createRange().text;
                                try {
                                    var fso = new ActiveXObject("Scripting.FileSystemObject");
                                } catch (e) {
                                    alert('如果你用的是ie8以下 请将安全级别调低！');
                                }
                                //alert("文件大小为：" + (fso.GetFile(url).size / 1024).toFixed(0) + "kb");
                            }
                        } 
                        function checkFileExt(ext) {
                            if (!ext.match(/.jpg|.gif|.png|.bmp/i)) {
                                return false;
                            }
                            return true;
                        }
                        
                        
                    </script>

@endsection


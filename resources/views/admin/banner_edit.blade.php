@extends('admin._default')
@section('content')
<link rel="stylesheet" href="/css/select2.css" />


                    <div class="page-content">

                        <div class="page-header">
                            <h1>
                                编辑
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

                                <form class="form-horizontal" enctype="multipart/form-data"  id="form1" role="form" action="{{ URL('admin/updateBanner') }}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input name="_method" type="hidden" value="PUT">
                                    <input type="hidden" name="pid" value="{{ $model->id }}">
                                    
                                     
                                        @for($i = 0;$i<12;$i++)
                                        
                                        <div class="form-group" style="Border:1px solid #000">
                                        <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">素材name </label>

                                        <div class="col-sm-9">
                                           <select name='material_name[]'>
                                            <option value="" >
                                                 
                                                </option>
                                            @foreach ($materialitem as $mitem)
                                                @if(isset($olddata['material_name'][$i]) && $mitem['item'] == $olddata['material_name'][$i])
                                                    <option value="{{$mitem['item']}}"  selected = "selected" >
                                                      {{$mitem['name']}}
                                                    </option>
                                                @elseif(isset($opitem[$i]) && $opitem[$i]->title == $mitem['item'])
                                                    <option value="{{$mitem['item']}}"  selected = "selected" >
                                                      {{$mitem['name']}}
                                                    </option>
                                                @else
                                                    <option value="{{$mitem['item']}}" >
                                                     {{$mitem['name']}}
                                                    </option>
                                                @endif
                                            @endforeach
                                           </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">中国区素材name </label>

                                        <div class="col-sm-9">
                                           <select name='material_name_cn[]' id="material_name_cn{{$i}}">
                                            <option value="" >
                                                 
                                                </option>
                                            @foreach ($materialcnitem as $cnitem)
                                                @if(isset($olddata['material_name_cn'][$i]) && $cnitem['item'] == $olddata['material_name_cn'][$i])
                                                    <option value="{{$cnitem['item']}}"  selected = "selected">
                                                      {{$cnitem['name']}}
                                                    </option>
                                                @elseif(isset($opitem[$i]) && $opitem[$i]->title_cn == $cnitem['item'])
                                                <option value="{{$cnitem['item']}}"  selected = "selected">
                                                  {{$cnitem['name']}}
                                                </option>
                                                @else
                                                <option  value="{{$cnitem['item']}}" >
                                                 {{$cnitem['name']}}
                                                </option>
                                                @endif
                                            @endforeach
                                           </select>
                                        </div>
                                    </div>
                                            <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 运营活动图 </label>

                                            <div class="col-sm-9">
                                                @if(isset($opitem[$i]))
                                                <img style="width:200px"  src='{{$opitem[$i]->largePicUrl()}}'/>
                                                <input type="file"  class="form-control" name="pic{{$i}}"  onchange="loadImage(this)"  />
                                                @else
                                                <input type="file"  class="form-control" name="pic{{$i}}"   onchange="loadImage(this)" />
                                                @endif
                                            </div>
                                            </div>

                                            <div class="form-group">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 运营活动图(中国) </label>

                                            <div class="col-sm-9">
                                                @if(isset($opitem[$i]))
                                                <img style="width:200px"  src='{{$opitem[$i]->largePicCnUrl()}}'/>
                                                <input type="file"  class="form-control" name="pic_cn{{$i}}"  onchange="loadImage(this)"  />
                                                @else
                                                <input type="file"  class="form-control" name="pic_cn{{$i}}"  onchange="loadImage(this)"  />
                                                @endif
                                            </div>
                                            </div>

                                            
                                            
                                            
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="color: red;">运营活动title </label>

                                                <div class="col-sm-9">
                                                    @if (isset($olddata['operat_title'][$i]))
                                                        <input type="text" id="" name="operat_title[]"  class="col-xs-10 col-sm-2"   value="{{$olddata['operat_title'][$i]}}"  />
                                                    @elseif(isset($opitem[$i]))
                                                    <input type="text" id="" name="operat_title[]"  class="col-xs-10 col-sm-2"   value='{{$opitem[$i]->operat_title}}'  />
                                                    @else
                                                    <input type="text" id="" name="operat_title[]"  class="col-xs-10 col-sm-2"   value=''  />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="color: red;">运营活动title_cn </label>

                                                <div class="col-sm-9">
                                                    @if (isset($olddata['operat_title_cn'][$i]))
                                                        <input type="text" id="" name="operat_title_cn[]"  class="col-xs-10 col-sm-2"   value="{{$olddata['operat_title_cn'][$i]}}"  />
                                                    @elseif(isset($opitem[$i]))
                                                    <input type="text" id="" name="operat_title_cn[]"  class="col-xs-10 col-sm-2"   value='{{$opitem[$i]->operat_title_cn}}'  />
                                                    @else
                                                    <input type="text" id="" name="operat_title_cn[]"  class="col-xs-10 col-sm-2"   value=''  />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="color: red;">运营活动title_tw </label>

                                                <div class="col-sm-9">
                                                    @if (isset($olddata['operat_title_tw'][$i]))
                                                        <input type="text" id="" name="operat_title_tw[]"  class="col-xs-10 col-sm-2"   value="{{$olddata['operat_title_tw'][$i]}}"  />
                                                    @elseif(isset($opitem[$i]))
                                                    <input type="text" id="" name="operat_title_tw[]"  class="col-xs-10 col-sm-2"   value='{{$opitem[$i]->operat_title_tw}}'  />
                                                    @else
                                                    <input type="text" id="" name="operat_title_tw[]"  class="col-xs-10 col-sm-2"   value=''  />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="color: red;">运营活动title_jp </label>

                                                <div class="col-sm-9">
                                                    @if (isset($olddata['operat_title_jp'][$i]))
                                                        <input type="text" id="" name="operat_title_jp[]"  class="col-xs-10 col-sm-2"   value="{{$olddata['operat_title_jp'][$i]}}"  />
                                                    @elseif(isset($opitem[$i]))
                                                    <input type="text" id="" name="operat_title_jp[]"  class="col-xs-10 col-sm-2"   value='{{$opitem[$i]->operat_title_jp}}'  />
                                                    @else
                                                    <input type="text" id="" name="operat_title_jp[]"  class="col-xs-10 col-sm-2"   value=''  />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="color: red;">运营活动title_ru </label>

                                                <div class="col-sm-9">
                                                    @if (isset($olddata['operat_title_ru'][$i]))
                                                        <input type="text" id="" name="operat_title_ru[]"  class="col-xs-10 col-sm-2"   value="{{$olddata['operat_title_ru'][$i]}}"  />
                                                    @elseif(isset($opitem[$i]))
                                                    <input type="text" id="" name="operat_title_ru[]"  class="col-xs-10 col-sm-2"   value='{{$opitem[$i]->operat_title_ru}}'  />
                                                    @else
                                                    <input type="text" id="" name="operat_title_ru[]"  class="col-xs-10 col-sm-2"   value=''  />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="color: blue;">运营活动desc</label>

                                                <div class="col-sm-9">
                                                    @if (isset($olddata['operat_desc'][$i]))
                                                        <input type="text" id="" name="operat_desc[]"  class="col-xs-10 col-sm-2"   value="{{$olddata['operat_desc'][$i]}}"  />
                                                    @elseif(isset($opitem[$i]))
                                                    <input type="text" id="" name="operat_desc[]"  class="col-xs-10 col-sm-2"   value='{{$opitem[$i]->operat_desc}}'  />
                                                    @else
                                                    <input type="text" id="" name="operat_desc[]"  class="col-xs-10 col-sm-2"   value=''  />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="color: blue;">运营活动desc_cn</label>

                                                <div class="col-sm-9">
                                                    @if (isset($olddata['operat_desc_cn'][$i]))
                                                        <input type="text" id="" name="operat_desc_cn[]"  class="col-xs-10 col-sm-2"   value="{{$olddata['operat_desc_cn'][$i]}}"  />
                                                    @elseif(isset($opitem[$i]))
                                                    <input type="text" id="" name="operat_desc_cn[]"  class="col-xs-10 col-sm-2"   value='{{$opitem[$i]->operat_desc_cn}}'  />
                                                    @else
                                                    <input type="text" id="" name="operat_desc_cn[]"  class="col-xs-10 col-sm-2"   value=''  />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="color: blue;">运营活动desc_tw</label>

                                                <div class="col-sm-9">
                                                    @if (isset($olddata['operat_desc_tw'][$i]))
                                                        <input type="text" id="" name="operat_desc_tw[]"  class="col-xs-10 col-sm-2"   value="{{$olddata['operat_desc_tw'][$i]}}"  />
                                                    @elseif(isset($opitem[$i]))
                                                    <input type="text" id="" name="operat_desc_tw[]"  class="col-xs-10 col-sm-2"   value='{{$opitem[$i]->operat_desc_tw}}'  />
                                                    @else
                                                    <input type="text" id="" name="operat_desc_tw[]"  class="col-xs-10 col-sm-2"   value=''  />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="color: blue;">运营活动desc_jp</label>

                                                <div class="col-sm-9">
                                                    @if (isset($olddata['operat_desc_jp'][$i]))
                                                        <input type="text" id="" name="operat_desc_jp[]"  class="col-xs-10 col-sm-2"   value="{{$olddata['operat_desc_jp'][$i]}}"  />
                                                    @elseif(isset($opitem[$i]))
                                                    <input type="text" id="" name="operat_desc_jp[]"  class="col-xs-10 col-sm-2"   value='{{$opitem[$i]->operat_desc_jp}}'  />
                                                    @else
                                                    <input type="text" id="" name="operat_desc_jp[]"  class="col-xs-10 col-sm-2"   value=''  />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1" style="color: blue;">运营活动desc_ru</label>

                                                <div class="col-sm-9">
                                                    @if (isset($olddata['operat_desc_ru'][$i]))
                                                        <input type="text" id="" name="operat_desc_ru[]"  class="col-xs-10 col-sm-2"   value="{{$olddata['operat_desc_ru'][$i]}}"  />
                                                    @elseif(isset($opitem[$i]))
                                                    <input type="text" id="" name="operat_desc_ru[]"  class="col-xs-10 col-sm-2"   value='{{$opitem[$i]->operat_desc_ru}}'  />
                                                    @else
                                                    <input type="text" id="" name="operat_desc_ru[]"  class="col-xs-10 col-sm-2"   value=''  />
                                                    @endif
                                                </div>
                                            </div>

                                            
                                        </div>
                                    @endfor

                                
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button class="btn btn-info" type="submit">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                               编辑
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

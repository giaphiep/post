@extends('vendor.giaphiep.admin.layout')

@section('head')
<link rel="stylesheet" href="{{asset('vendor/assets/css/bootstrap-fileinput.css')}}">
<link rel="stylesheet" href="{{asset('vendor/assets/css/bootstrap-tagsinput.css')}}">
@endsection

@section('content')

<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-dark">
            <i class="fa fa-newspaper-o" aria-hidden="true"></i>
            <span class="caption-subject bold uppercase"> Update a post</span>
        </div>

    </div>
    <div class="portlet-body">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="">Title (<span style="color: red">*</span>)</label>
                        <input type="text" class="form-control" id="title" placeholder="Title" name="title" value="{{$post->title}}">
                        <input type="hidden" name="old_title" value="{{$post->title}}" id="old_title">
                    </div>
                    <div class="form-group">
                        <label for="">Description (<span style="color: red">*</span>)</label>
                        <textarea class="form-control" name="description" id="description" cols="30" rows="4" placeholder="Description">{{$post->description}}</textarea> 
                    </div>
                    <div class="form-group">
                        <label for="">Content (<span style="color: red">*</span>)</label>
                        <textarea class="form-control" name="content" id="content" cols="30" rows="10" placeholder="Content">{{$post->content}}</textarea> 
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    
                    {{-- status --}}
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-bookmark font-green" aria-hidden="true"></i>
                                <span class="caption-subject font-green bold">Status</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <select class="form-control" name="status" id="status">
                                    <option @if($post->status == 1) selected @endif value="1">Publish</option>
                                    <option @if($post->status == 0) selected @endif value="0">Draft</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input id="is_featured" class="text-left" type="checkbox" @if($post->is_featured) checked @endif name="is_featured"> Featured

                                <button data-id="{{$post->id}}" id="edit-post" class="btn btn-sm green btn-circle" style="float: right;"> Update </button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- categories --}}
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-list font-green" aria-hidden="true"></i>
                                <span class="caption-subject font-green bold">Categories</span>
                            </div>
                        </div>
                        <div class="portlet-body">

                            @if(count($categories) > 0) @foreach($categories as $category)
                                @if($category->parent_id == 0)
                                    <div class="checkbox">
                                        <input type="checkbox" class="categories" name="categories[]" value="{{$category->id}}" @if (count($post->categories) > 0) @foreach ($post->categories as $cate) @if($cate->id == $category->id) checked @endif  @endforeach @endif>
                                            <label>
                                            {{$category->name}}
                                            </label>
                                    </div>
                                    @foreach($categories as $category1)
                                        @if($category1->parent_id == $category->id)
                                            <div class="checkbox" style="padding-left:40px;">
                                                <input type="checkbox"  class="categories" name="categories[]" value="{{$category1->id}}" @if (count($post->categories) > 0) @foreach ($post->categories as $cate) @if($cate->id == $category1->id) checked @endif  @endforeach @endif>
                                                    <label>
                                                    {{$category1->name}}
                                                    </label>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach @endif

                        </div>
                    </div>
                    
                    {{-- featured image  --}}
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-picture-o font-green" aria-hidden="true"></i>
                                <span class="caption-subject font-green bold">Thumbnail</span>
                            </div>
                        </div>
                        <div class="portlet-body">

                             <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 250px; height: 200px;">
                                    @if(empty($post->thumbnail))
                                    <img id="previewimg" src="{{url('images/no-image.png')}}" alt="No Image" />
                                    @else
                                    <img id="previewimg" src="{{asset($post->thumbnail)}}" alt="No Image" />
                                    @endif 
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 250px; max-height: 200px;"> </div>
                                <div style="margin-top: 10px;">
                                    <span class="input-group-btn">
                                      <a id="lfm" data-input="thumbnail" data-preview="previewimg" class="btn btn-primary">
                                        <i class="fa fa-picture-o"></i> Choose
                                      </a>
                                    </span>

                                </div>
                                <input type="hidden" id="thumbnail" name="image" >
                            </div>
                        </div>
                    </div>
                        
                    
                    {{-- Tags --}}
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-tags font-green" aria-hidden="true"></i>
                                <span class="caption-subject font-green bold">Tags</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                             <div class="form-group">
                                <select multiple name="tags[]" id="tags" data-role="tagsinput">
                                    @if(count($post->tags) > 0) @foreach($post->tags as $tag) 
                                        <option value="{{$tag->name}}">{{$tag->name}}</option>
                                    @endforeach @endif
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        
    </div>
</div>

@endsection

@section('footer')

<script src="{{asset('vendor/assets/js/tinymce/tinymce.min.js')}}"></script>
<script>
   var route_prefix = "{{ asset(config('lfm.url_prefix')) }}";
   var path_absolute = "{{asset('')}}";
</script>

<script>
  {!! \File::get(base_path('vendor/unisharp/laravel-filemanager/public/js/lfm.js')) !!}
</script>
<script>
  $('#lfm').filemanager('image', {prefix: route_prefix});
</script>

<script src="{{asset('vendor/assets/js/prism.js')}}"></script>

<script src="{{asset('vendor/assets/js/bootstrap-fileinput.js')}}"></script>

<script src="{{asset('vendor/assets/js/bootstrap-tagsinput.min.js')}}"></script>


<script>

    tinymce.init({
    selector: '#content',
    height: 850,
    theme: 'modern',
    menubar: false,
    // autosave_ask_before_unload: false,
    plugins: [
      "advlist autolink link image lists charmap",
      "wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
      "table contextmenu directionality template textcolor paste textcolor colorpicker textpattern codesample"
    ],
    toolbar1: "newdocument forecolor backcolor bullist numlist bold italic underline alignleft aligncenter alignright align justify image media | formatselect fontselect fontsizeselect table link anchor code  fullscreen codesample ",
    image_advtab: true,
    // content_css: [
    //   '//fonts.googleapis.com/css?family=Tahoma:300,300i,400,400i',
    //   '//www.tinymce.com/css/codepen.min.css'
    // ],
    setup: function (ed) {
        ed.on('init', function (e) {
            ed.execCommand("fontName", false, "Tahoma");
        });
    },
    relative_urls: false,
    remove_script_host : false,
    file_browser_callback : function(field_name, url, type, win) {
      var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
      var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

      var cmsURL = route_prefix + '?field_name=' + field_name;
      if (type == 'image') {
        cmsURL = cmsURL + "&type=Images";
      } else {
        cmsURL = cmsURL + "&type=Files";
      }

      tinyMCE.activeEditor.windowManager.open({
        file : cmsURL,
        title : 'Image manager',
        width : x * 0.9,
        height : y * 0.9,
        resizable : "yes",
        close_previous : "no"
      });
    }
   });
</script>

<script>

    $(function() {

        $('#edit-post').on('click', function (e) {

            var id = $(this).data('id');

            var title = $('#title').val();
            var description = $('#description').val();
            var content = tinymce.get('content').getContent();
            var status = $('#status').val();
            var tags = $('#tags').val();
            var thumbnail = $('#thumbnail').val();
            var old_title = $('#old_title').val();

            var is_featured = 0;
            if($('#is_featured').is(':checked')) {
                is_featured = 1;
            }
            
            var categories = [];
            $.each($("input[name='categories[]']:checked"), function(){            
                categories.push($(this).val());
            });

            $.ajax({
                type: "put",
                url: "{{URL::asset('')}}admin/posts/" + id,
                data: {
                    title: title,
                    description: description,
                    content: content,
                    status: status,
                    categories: categories,
                    tags: tags,
                    thumbnail: thumbnail,
                    is_featured: is_featured,
                    old_title: old_title,
                },
                success: function (response) {

                    if(!response.error) {

                        toastr.success('Update success');

                        setTimeout(function () {
                            window.location.href="{{route('posts.index')}}";
                        }, 800);

                    } else {

                        if (!IsNull(response.message.title)) {
                    
                            toastr.error(response.message.title[0]);
                        }
                        if (!IsNull(response.message.description)) {
                    
                            toastr.error(response.message.description[0]);
                        }
                        if (!IsNull(response.message.content)) {
                    
                            toastr.error(response.message.content[0]);
                        }
                    }

                }, error: function (xhr, ajaxOptions, thrownError) {
                    toastr.error(thrownError);
                }
            });

        });
    });
    
</script>

@endsection
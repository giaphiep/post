@extends('vendor.giaphiep.admin.layout')

@section('head')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/assets/css/prism.css')}}">
<link rel="stylesheet" href="{{asset('vendor/assets/css/datatables.bootstrap.css')}}">

<style>

  span{
    line-height: 2 !important;
  }
  img {
    display: block !important;
    max-width: 100%;
  }
</style>
@endsection

@section('content')

<div class="portlet light bordered">
<div class="portlet-title">
    <div class="caption font-green uppercase">
        <i class="fa fa-file-text" aria-hidden="true"></i> <a class="font-green uppercase" href="{{route('posts.index')}}"> List Post </a></div>
   
</div>
<div class="row">
    <div class="col-xs-12 col-sm-4 col-md-6 col-lg-5">
        <a href="{{route('posts.create')}}"><button class="btn green btn-circle"><i class="fa fa-plus"></i> Add new</button></a>
    </div>
</div>
<div class="portlet-body">
    <table class="table table-striped table-bordered table-hover" id="posts-table">
        <thead>
            <tr>
               <th class="stl-column color-column">#</th>
               {{-- <th class="stl-column color-column">Thumbnail</th> --}}
               <th class="stl-column color-column">Title</th>
               <th class="stl-column color-column">Author</th>
               <th class="stl-column color-column">Featured</th>
               <th class="stl-column color-column">Status</th>
               {{-- <th class="stl-column color-column">Thể loại</th> --}}
               <th class="stl-column color-column">Created at</th>
               <th class="stl-column color-column">Action</th>
            </tr>
        </thead>
    </table>

    
</div>
</div>


@endsection

@section('footer')


<script src="{{asset('vendor/assets/js/prism.js')}}"></script>
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="{{asset('vendor/assets/js/datatables.bootstrap.js')}}" type="text/javascript"></script>

<script>
$(function() {
    $('#posts-table').DataTable({
        processing: false,
        serverSide: true,
        order: [], 
        pageLength: 25,
        ajax: '{!! route('posts.list') !!}',
        columns: [
            {data: 'id', name: 'id'},
            // {data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false},
            {data: 'title', name: 'title'},
            {data: 'author', name: 'author'},
            {data: 'featured', name: 'featured'},
            {data: 'status', name: 'status'},
            // {data: 'category', name: 'category'},
            {data: 'created', name: 'created'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });
});
</script>


<script>


    // delete theory
    function alertDel ( id ) {

      var path = "{{URL::asset('')}}admin/posts/" + id;

        swal({
            title: "Are you sure ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            cancelButtonText: "No",
            confirmButtonText: "Yes",
        },
        function(isConfirm) {
            if (isConfirm) {  

	            $.ajax({
	                  type: "DELETE",
	                  url: path,
	                  success: function(res)
	                  {
	                    if(!res.error) {
	                        toastr.success('Success');

	                        setTimeout(function () {
	                            location.reload();
	                        }, 800)                   
	                    }
	                  },
	                  error: function (xhr, ajaxOptions, thrownError) {
	                    toastr.error(thrownError);
	                  }
	            });
                
            }
        });
    }
    

 </script>

@endsection

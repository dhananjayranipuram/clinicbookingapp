@extends('layouts.admin')

@section('content')
<style>
    .card{
        height: 100%;
    }
</style>
<section class="section">
    <div class="row">
        <div class="col-lg-6">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Languages</h5>
              <div style="text-align: right;"><a style="padding-right: 10px; cursor: pointer;" class="addNew" onclick="addLanguages();"><i class="bi bi-bookmark-star"></i> Add New Language</a></div>
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                        <th>Lang ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($spec as $key => $value)
                        <tr >
                            <td>{{$value->id}}</td>
                            <td>{{$value->name}}</td>
                            <td>{{$value->activeName}}</td>
                            <td><div >
                              <a class="btn btn-default editLang" data-active="{{$value->active}}" data-id="{{$value->id}}" data-value="{{$value->name}}"><i class="fa fa-edit"></i></a>
                              <a class="btn btn-default deleteLang" data-id="{{$value->id}}"><i class="fa fa-trash"></i></a>
                          </div></td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>

        <div class="col-lg-6">

        <div class="card" id="edit" style="display:none;">
            <div class="card-body">
                <h5 class="card-title">Edit Language</h5>

                <!-- Vertical Form -->
                <form class="row g-3"  method="POST" action="{{ url('/admin/edit-language') }}">
                    @csrf <!-- {{ csrf_field() }} -->
                    <div class="col-12">
                        <label for="language" class="form-label">Language Name</label>
                        <input type="text" class="form-control" name="language" id="editLanguagenName">
                        <input type="hidden" name="languageId" id="editLanguageId">
                    </div>
                    <div class="col-12">
                        <input class="form-check-input" type="checkbox" name="editActive" id="editActive">
                        <label class="form-check-label" for="active">
                            Active
                        </label>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                    </div>
                    <div class="col-12" style="color:red;">
                        @if ($errors->any())
                            <label>{{ $errors }}</label>
                        @endif
                    </div>
                </form><!-- Vertical Form -->
            </div>
        </div>
        <div class="card" id="add">
            <div class="card-body">
                <h5 class="card-title">Add Language</h5>

                <!-- Vertical Form -->
                <form class="row g-3" method="POST" action="{{ url('/admin/add-language') }}">
                    @csrf <!-- {{ csrf_field() }} -->
                    <div class="col-12">
                        <label for="inputNanme4" class="form-label">Language Name</label>
                        <input type="text" class="form-control" name="language" value="{{old('language')}}">
                    </div>
                    <div class="col-12">
                        <input class="form-check-input" type="checkbox" name="active" checked>
                        <label class="form-check-label" for="active">
                            Active
                        </label>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                    </div>
                    <div class="col-12" style="color:red;">
                        @if ($errors->any())
                            <label>{{ $errors }}</label>
                        @endif
                    </div>
                </form><!-- Vertical Form -->
            </div>
        </div>
    </div>
</section>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
function addLanguages(){
    $("#add").show();
    $("#edit").hide();
}

$(document).ready(function () { 
    $('.editLang').click(function(){
        $("#editLanguagenName").val($(this).attr("data-value"));
        $("#editLanguageId").val($(this).attr("data-id"));
        if($(this).attr("data-active")==1){
            $("#editActive").prop('checked', true);
        }else{
            $("#editActive").prop('checked', false);
        }
        $("#add").hide();
        $("#edit").show();
    });

    $('.deleteLang').click(function(){
        if(confirm("Do you want to delete this Speciality?")){
            $.ajax({
                url: baseUrl + '/admin/delete-lang',
                type: 'post',
                data: {'id':$(this).attr("data-id")},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( html ) {
                    if(html=='1'){
                        location.reload();
                    }
                }
            });
        }
    });
});
</script>
@endsection
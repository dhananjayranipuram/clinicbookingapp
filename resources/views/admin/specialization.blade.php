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
              <h5 class="card-title">Specializatios</h5>
              <div style="text-align: right;"><a style="padding-right: 10px; cursor: pointer;" class="addNew" onclick="addSpecialization();"><i class="bi bi-bookmark-star"></i> Add New Specialization</a></div>
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                        <th>Spec ID</th>
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
                              <a class="btn btn-default editSpec" data-active="{{$value->active}}" data-id="{{$value->id}}" data-value="{{$value->name}}"><i class="fa fa-edit"></i></a>
                              <a class="btn btn-default"><i class="fa fa-trash"></i></a>
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
                <h5 class="card-title">Edit Specialization</h5>

                <!-- Vertical Form -->
                <form class="row g-3"  method="POST" action="{{ url('/admin/edit-specialization') }}">
                    @csrf <!-- {{ csrf_field() }} -->
                    <div class="col-12">
                        <label for="inputNanme4" class="form-label">Specialization Name</label>
                        <input type="text" class="form-control" name="specialization" id="editSpecializationName">
                        <input type="hidden" name="specializationId" id="editSpecializationId">
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
                <h5 class="card-title">Add Specialization</h5>

                <!-- Vertical Form -->
                <form class="row g-3" method="POST" action="{{ url('/admin/add-specialization') }}">
                    @csrf <!-- {{ csrf_field() }} -->
                    <div class="col-12">
                        <label for="inputNanme4" class="form-label">Specialization Name</label>
                        <input type="text" class="form-control" name="specialization" value="{{old('specialization')}}">
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
function addSpecialization(){
    $("#add").show();
    $("#edit").hide();
}

$(document).ready(function () { 
    $('.editSpec').click(function(){
        $("#editSpecializationName").val($(this).attr("data-value"));
        $("#editSpecializationId").val($(this).attr("data-id"));
        if($(this).attr("data-active")==1){
            $("#editActive").prop('checked', true);
        }else{
            $("#editActive").prop('checked', false);
        }
        $("#add").hide();
        $("#edit").show();
    });
});
</script>
@endsection
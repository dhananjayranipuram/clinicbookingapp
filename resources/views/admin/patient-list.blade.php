@extends('layouts.admin')

@section('content')
<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Patients Registered</h5>
              <!-- <div style="text-align: right;"><a style="padding-right: 10px;" href="{{ url('/admin/add-doctor') }}" class="addNew"><i class="bi bi-person-plus"></i> Add New Doctor</a></div> -->
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Patient ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($list as $key => $value)
                        <tr >
                            <td>{{$value->id}}</td>
                            <td>{{$value->patient_name}}</td>
                            <td>{{$value->gender}}</td>
                            <td>{{$value->email}}</td>
                            <td>{{$value->mobile}}</td>
                            <td>{{$value->activeName}}</td>
                            <td><div >
                              <a href="{{ url('/admin/edit-patient') }}?id={{base64_encode($value->id)}}" class="btn btn-default"><i class="fa fa-edit"></i></a>
                              <a href="#" class="btn btn-default deleteDoc" data-id="{{$value->id}}"><i class="fa fa-trash"></i></a>
                          </div></td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
$(document).ready(function () { 
    

    $('.deleteDoc').click(function(){
        if(confirm("Do you want to delete this Doctor?")){
            $.ajax({
                url: baseUrl + '/admin/delete-patient',
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
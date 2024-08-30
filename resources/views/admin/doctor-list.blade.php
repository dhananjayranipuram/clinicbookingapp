@extends('layouts.admin')

@section('content')
<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Doctors Available</h5>
              <div style="text-align: right;"><a style="padding-right: 10px;" href="{{ url('/admin/add-doctor') }}" class="addNew"><i class="bi bi-person-plus"></i> Add New Doctor</a></div>
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Doctor ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Speciality</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($list as $key => $value)
                        <tr >
                            <td>{{$value->id}}</td>
                            <td>{{$value->doctor_name}}</td>
                            <td>{{$value->gender}}</td>
                            <td>{{$value->email}}</td>
                            <td>{{$value->Speciality}}</td>
                            <td><div >
                              <a href="{{ url('/admin/edit-doctor') }}?id={{$value->id}}" class="btn btn-default"><i class="fa fa-edit"></i></a>
                              <a href="#" class="btn btn-default"><i class="fa fa-trash"></i></a>
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
@endsection
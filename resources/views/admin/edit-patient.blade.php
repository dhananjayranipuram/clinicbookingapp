@extends('layouts.admin')

@section('content')
<section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="{{asset('admin_assets/img/user-image.jpg')}}" alt="Profile" class="rounded-circle">
              <h2>{{$patient_name}}</h2>
              <div class="social-links mt-2">
                
              </div>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#appointment-details">Appointments</button>
                </li>
              </ul>
              <div class="tab-content pt-2">

                

                <div class="tab-pane fade show active profile-overview" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form action="{{url('/admin/update-patient-profile')}}" method="post">
                  @csrf <!-- {{ csrf_field() }} -->
                    

                    <div class="row mb-3">
                      <label for="first_name" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="id" type="hidden" value="{{$id}}" >
                        <input name="first_name" type="text" class="form-control" value="{{$first_name}}" >
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="last_name" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="last_name" type="text" class="form-control" value="{{$last_name}}" >
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="gender" class="col-md-4 col-lg-3 col-form-label">Gender</label>
                      <div class="col-md-8 col-lg-9">
                        <select name="gender" class="form-select">
                            <option value="">Choose Gender</option>
                            <option value="male" @if($gender == 'male' || $gender == 'Male') selected @endif>Male</option>
                            <option value="female" @if($gender == 'female' || $gender == 'Female') selected @endif>Female</option>
                            <option value="others" @if($gender == 'others' || $gender == 'Others') selected @endif>Others</option>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="specialization" class="col-md-4 col-lg-3 col-form-label">Mobile Number</label>
                      <div class="col-md-8 col-lg-9">
                            <input name="mobile" type="text" class="form-control" value="{{$mobile}}" >
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="languages" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                            <input name="email" type="text" class="form-control" value="{{$email}}" >
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="languages" class="col-md-4 col-lg-3 col-form-label">DOB</label>
                      <div class="col-md-8 col-lg-9">
                            <input type="date" name="dob" value="{{$dob}}" class="form-control form-control-lg inputs" placeholder="DOB" max="{{ date('Y-m-d') }}"/>
                      </div>
                    </div>

                    <div class="row mb-3">
                        <label for="languages" class="col-md-4 col-lg-3 col-form-label"> </label>
                        <div class="col-md-8 col-lg-9">
                            <input class="form-check-input" type="checkbox" name="status" @if($active == '1') checked else  @endif> Active
                        </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade appointment-details pt-4" id="appointment-details">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <table class="table datatable">
                                <thead>
                                <tr>
                                    <th>Appt ID</th>
                                    <th>Doctor Name</th>
                                    <th>Speciality</th>
                                    <th data-type="date" data-format="YYYY/MM/DD">Appt Date</th>
                                    <th>Appt Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $key => $value)
                                        <tr>
                                        <td style="text-align:center;">{{$value->appointment_id}}</td>
                                        <td>{{$value->doctor_name}}</td>
                                        <td>{{$value->speciclity}}</td>
                                        <td>{{$value->book_date}}</td>
                                        <td>{{$value->book_time}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>
@endsection
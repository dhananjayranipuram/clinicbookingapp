@extends('layouts.doctor')

@section('content')
<section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="{{asset($profile_pic)}}" alt="Profile" class="rounded-circle">
              <h2>{{$doctor_name}}</h2>
              <h3>{{$specialization}}</h3>
              <div class="social-links mt-2">
                <!-- <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a> -->
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
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">About</h5>
                  <p class="small fst-italic">Sunt est soluta temporibus accusantium neque nam maiores cumque temporibus. Tempora libero non est unde veniam est qui dolor. Ut sunt iure rerum quae quisquam autem eveniet perspiciatis odit. Fuga sequi sed ea saepe at unde.</p>

                  <h5 class="card-title">Profile Details</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                    <div class="col-lg-9 col-md-8">{{$doctor_name}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Gender</div>
                    <div class="col-lg-9 col-md-8">{{$gender}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Specialization</div>
                    <div class="col-lg-9 col-md-8">{{$specialization}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Languages Known</div>
                    <div class="col-lg-9 col-md-8">{{$languages}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8">{{$email}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Available days</div>
                    <div class="col-lg-9 col-md-8">
                      @foreach($data as $key => $value)
                        <div class="form-check" >{{$value->day}} - {{$value->start_time_label}} to {{$value->end_time_label}}</div>
                      @endforeach
                    </div>
                  </div>

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form action="{{url('/doctor/update-profile')}}" method="post" enctype="multipart/form-data">
                  @csrf <!-- {{ csrf_field() }} -->
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                      <div class="col-md-8 col-lg-9">
                        <img src="{{asset($profile_pic)}}" class="prewiew-profile-pic" alt="Profile">
                        <div class="pt-2">
                          <a class="btn btn-primary btn-sm profile-update" title="Upload new profile image"><i class="bi bi-upload"></i> Upload</a>
                          <!-- <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a> -->
                          <input class="form-control profile-pic" type="file" name="profile_pic" accept="image/png, image/jpeg" style="display:none;">
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="honor" class="col-md-4 col-lg-3 col-form-label">Honor</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="honor" type="text" class="form-control" value="{{$honor}}" >
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="first_name" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                      <div class="col-md-8 col-lg-9">
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
                      <label for="specialization" class="col-md-4 col-lg-3 col-form-label">Specialization</label>
                      <div class="col-md-8 col-lg-9">
                        <select name="specialization" class="form-select">
                            <option value="">Choose Specialization</option>
                            @foreach($spec as $key => $value)
                                <option value="{{$value->id}}" @if($spec_id == $value->id) selected @endif>{{$value->name}}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="languages" class="col-md-4 col-lg-3 col-form-label">Languages</label>
                      <div class="col-md-8 col-lg-9">
                        <select name="languages[]" multiple class="form-select">
                        @foreach($lang as $key => $value)
                            <option value="{{$value->id}}" @if(in_array($value->id,$lang_select)) selected @endif>{{$value->name}}</option>
                        @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="available_days" class="col-md-4 col-lg-3 col-form-label">Available Days</label>
                      <div class="col-md-8 col-lg-9">
                        <select class="form-select" multiple aria-label="multiple select example" name="available_days[]">
                            <option>Select Available Week Days</option>
                            <option value="0" @if(in_array('0',$days_available)) selected @endif>Sunday</option>
                            <option value="1" @if(in_array('1',$days_available)) selected @endif>Monday</option>
                            <option value="2" @if(in_array('2',$days_available)) selected @endif>Tuesday</option>
                            <option value="3" @if(in_array('3',$days_available)) selected @endif>Wednesday</option>
                            <option value="4" @if(in_array('4',$days_available)) selected @endif>Thursday</option>
                            <option value="5" @if(in_array('5',$days_available)) selected @endif>Friday</option>
                            <option value="6" @if(in_array('6',$days_available)) selected @endif>Saturday</option>
                        </select>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="start" class="col-md-4 col-lg-3 col-form-label">Start Time</label>
                      <div class="col-md-8 col-lg-9">
                        <input type="time" class="form-control" name="start" value="{{$start_time}}">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="end" class="col-md-4 col-lg-3 col-form-label">End Time</label>
                      <div class="col-md-8 col-lg-9">
                        <input type="time" class="form-control" name="end" value="{{$end_time}}">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="duration" class="col-md-4 col-lg-3 col-form-label">Duration</label>
                      <div class="col-md-8 col-lg-9">
                        <input type="time" class="form-control" name="duration" value="{{$duration}}">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="Email" value="{{$email}}">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form id="changePassword" method="post">

                    <div class="row mb-3">
                      <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="password" type="password" class="form-control" id="currentPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newpassword" type="password" class="form-control" id="newPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                      </div>
                    </div>
                    <div class="row mb-3" id="errMsgs">
                      
                    </div>
                    <div class="text-center">
                      <button type="button" id="changePwdButton" class="btn btn-primary">Change Password</button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
var BASE_URL = {!! json_encode(url('/')) !!}
$( document ).ready(function() {
  $('.profile-update').click(function(){
    $('.profile-pic').click();
  });
});   

$('.profile-pic').change(function(){
  file = this.files[0];
  if (file) {
      let reader = new FileReader();
      reader.onload = function (event) {
          $(".prewiew-profile-pic").attr("src", event.target.result);
      };
      reader.readAsDataURL(file);
  }
});

$('#changePwdButton').click(function(){
    var data = [];
    data = {
        'currentPassword' : $('#currentPassword').val(),
        'newPassword' : $('#newPassword').val(),
        'renewPassword' : $('#renewPassword').val(),
    }
    if(data['newPassword']!=data['renewPassword']){
        
        $("#errMsgs").html('<p class="errMsgs" style="color:red">Passwords not matching.</p>');
        setTimeout(function () {
            $(".errMsgs").fadeOut();
        }, 2500);
    }else{
      $.ajax({
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          method: "POST",
          url: BASE_URL+'/doctor/change-password',
          data: data,
          dataType: "json",
          success: function(html){
              if(html == 1){
                $("#errMsgs").html('<p class="errMsgs" style="color:green">Passwords changed.</p>');
                setTimeout(function () {
                    $(".errMsgs").fadeOut();
                }, 2500);
              }else if(html == 2){
                $("#errMsgs").html('<p class="errMsgs" style="color:red">Wrong current password.</p>');
                setTimeout(function () {
                    $(".errMsgs").fadeOut();
                }, 2500);
              }else{
                $("#errMsgs").html('<p class="errMsgs" style="color:red">Something went wrong.</p>');
                setTimeout(function () {
                    $(".errMsgs").fadeOut();
                }, 2500);
              }
          }
      });
    }
});   
</script>    
@endsection
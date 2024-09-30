@extends('layouts.admin')

@section('content')

<section class="add-doctor">
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Add New Doctor</h5>
            <form method="post" action="{{ url('/admin/add-doctor') }}" enctype="multipart/form-data">
            @csrf <!-- {{ csrf_field() }} -->
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="honor" class="form-label">Honor</label>
                        <input type="text" class="form-control" name="honor" value="{{old('honor')}}">
                        <label></label>
                    </div>
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" value="{{old('first_name')}}">
                    </div>
                    <div class="col-md-4">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" value="{{old('last_name')}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{old('email')}}">
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" value="{{old('password')}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="" selected>Choose Gender</option>
                            <option value="male" @if(old('gender') == 'male') selected @endif>Male</option>
                            <option value="female" @if(old('gender') == 'female') selected @endif>Female</option>
                            <option value="others" @if(old('gender') == 'others') selected @endif>Others</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="specialization" class="form-label">Specialization</label>
                        <select name="specialization" class="form-select">
                            <option value="" selected>Choose Specialization</option>
                            @foreach($speciality as $key => $value)
                                <option value="{{$value->id}}" @if(old('specialization') == $value->id) selected @endif>{{$value->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="inputNumber" class="form-label">Languages</label>
                        <select name="languages[]" multiple class="form-select">
                        @foreach($lang as $key => $value)
                            <option value="{{$value->id}}" @if(old('specialization') == $value->id) selected @endif>{{$value->name}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="inputNumber" class="form-label">Profile picture</label>
                        <div class="col-sm-12">
                            <input class="form-control" type="file" name="profile_pic" accept="image/png, image/jpeg">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Available Days</label>
                        <select class="form-select" multiple aria-label="multiple select example" name="available_days[]">
                            <option value="" selected>Select Available Week Days</option>
                            <option value="0">Sunday</option>
                            <option value="1">Monday</option>
                            <option value="2">Tuesday</option>
                            <option value="3">Wednesday</option>
                            <option value="4">Thursday</option>
                            <option value="5">Friday</option>
                            <option value="6">Saturday</option>
                        </select>
                    </div>

  

                    <div class="col-md-3">
                        <label for="start" class="form-label">Start Time</label>
                        <input type="time" class="form-control" name="start" value="{{old('start')}}">
                    </div>
                    <div class="col-md-3">
                        <label for="end" class="form-label">End Time</label>
                        <input type="time" class="form-control" name="end" value="{{old('end')}}">
                    </div>
                    <div class="col-md-3">
                        <label for="duration" class="form-label">Duration (hh:mm)</label>
                        <input type="text" class="form-control" name="duration" value="{{old('duration')}}">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                <div class="col-12" style="color:red;">
                    @if ($errors->any())
                    <label>{{ $errors }}</label>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    
</script>
@endsection
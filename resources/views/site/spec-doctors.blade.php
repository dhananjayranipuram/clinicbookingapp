@extends('layouts.app')

@section('content')
        <form method="POST" action="{{ url('/speciality-doctors') }}">
            @csrf <!-- {{ csrf_field() }} -->
            <div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="model-search-content">
							<div class="row" style="padding-left: 50px;">
								
                                <input type="hidden" name="speciality" value="{{$spec}}">

                                <div class="col-lg-offset-1 col-lg-4 col-sm-12">
									<div class="single-model-search">
										<h2>select Language</h2>
										<div class="model-select-icon">
											<select class="form-control" name="language">  
                                                <option value="0">Select Language</option>
                                                @foreach($languages as $key => $value)
                                                    <option value="{{$value->id}}" @if(old('language') == $value->id) selected @endif>{{$value->name}}</option>
                                                @endforeach
											</select>
										</div>
									</div>
								</div>
								
								<div class="col-lg-offset-1 col-lg-4 col-sm-12">
									<div class="single-model-search">
										<h2>select Gender</h2>
										<div class="model-select-icon">
											<select class="form-control" name="gender">
											  	<option value="0">Select Gender</option>
											  	<option value="Male" @if(old('gender') == 'Male') selected @endif>Male</option>
											  	<option value="Female"@if(old('gender') == 'Female') selected @endif>Female</option>
											</select>
										</div>
									</div>
								</div>
                                
								<div class="col-lg-4 col-sm-12">
									<div class="single-model-search text-center">
                                        <h2>&nbsp;</h2>
										<button class="welcome-btn">search</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </form>

<!-- Team Start -->
<div class="container-fluid py-5">
    <div class="container" style="margin-bottom: 50px;">
        <div class="row g-5">
            <div class="col-lg-3 wow slideInUp" data-wow-delay="0.1s">
                <div class="section-title bg-light rounded h-100 p-5" style="padding: 2rem !important;">
                    <h5 class="position-relative d-inline-block text-primary text-uppercase">Our Doctors</h5>
                    <h4 class="display-6 mb-4">Meet Our Certified & Experienced Doctors</h4>
                    <!-- <a href="appointment.html" class="btn btn-primary py-3 px-5">Appointment</a> -->
                </div>
            </div>

            @foreach($doctors as $key => $value)
            <div class="col-lg-3 wow slideInUp" data-wow-delay="0.3s">
                <a href="{{ url('/available-slot') }}?id={{$value->id}}">
                    <div class="team-item">
                        <div class="position-relative rounded-top" style="z-index: 1;">
                            <img class="img-fluid rounded-top w-100 doctor-image" src="{{asset($value->profile_pic)}}" alt="">
                        </div>
                        <div class="team-text position-relative bg-light text-center rounded-bottom p-4" style="min-height: 200px;">
                            <h4 class="mb-2">{{$value->name}}</h4>
                            <p class="text-primary mb-0">({{$value->specialized}})</p>
                            Languages Known:<p class="text-primary">{{$value->languages}}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Team End -->
@endsection
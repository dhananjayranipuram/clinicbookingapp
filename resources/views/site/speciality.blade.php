@extends('layouts.app')

@section('content')
<!-- Service Start -->
<div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container" style="margin-bottom: 50px;">
            <div class="row g-5 mb-5">
                
                <div class="col-lg-12">
                    <div class="section-title mb-5">
                        <h5 class="position-relative d-inline-block text-primary text-uppercase">Specialities</h5>
                    </div>
                    <div class="row g-5">

                        @foreach($speciality as $key => $value)
                            <div class="col-md-3 service-item wow zoomIn" data-wow-delay="0.6s">
                                <a href="{{ url('/speciality-doctors') }}?id={{$value->id}}">
                                <!-- <div class="rounded-top overflow-hidden">
                                    <img class="img-fluid" src="img/service-1.jpg" alt="">
                                </div> -->
                                <div class="position-relative bg-light rounded-bottom text-center p-4">
                                    <h5 class="m-0">{{$value->name}}</h5>
                                </div>
                                </a>
                            </div>
                        @endforeach
                        
                    </div>
                </div>
            </div>


        </div>
    </div>
    <!-- Service End -->
@endsection
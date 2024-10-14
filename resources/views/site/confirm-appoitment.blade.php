@extends('layouts.app')

@section('content')

<style>
    .confirm-appointment-outer{
        width:50%; 
        margin:0 auto;
        top: 50%;
        bottom:50%;
        min-height: 500px;
        padding: 70px 0;
        text-align: center;    
        font-weight: bold;
    }
</style>
<!-- About Start -->
<div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s" id="aboutus-section">
    <div class="container">
        <div class="row g-5">
            <div class="confirm-appointment-outer">
                <div style="color:green;text-align: center;">
                    <i class="fa fa-check-circle fa-10x" aria-hidden="true"></i>
                </div><br>
                <div style="text-align: center;">Your appointment is booked <br>on {{$date}}, {{$time}}</div><br>
                <div style="text-align: center;">Booking ID is:{{$id}}</div><br>

                <div style="text-align: center;">Note : Please be at Clinic 15 minutes before the apponintment time.</div>
            </div>
        </div>
    </div>
</div>
<!-- About End -->

@endsection
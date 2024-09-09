@extends('layouts.doctor')

@section('content')

<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
    <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
            <div class="row">
                <!-- Reports -->
                <div class="col-12">
                    <div class="card">

                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                        </li>

                        <li><a class="dropdown-item" href="#">Today</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Reports <span>/This Year</span></h5>

                        <!-- Line Chart -->
                        <div id="reportsChart"></div>

                        <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            new ApexCharts(document.querySelector("#reportsChart"), {
                                series: [{
                                    name: "Desktops",
                                    data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
                                }],
                                chart: {
                                    height: 350,
                                    type: 'line',
                                    zoom: {
                                        enabled: false
                                    },
                                    toolbar: {
                                        show: false
                                    }
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                stroke: {
                                curve: 'straight'
                                },
                                title: {
                                text: 'Appoinments',
                                align: 'left'
                                },
                                grid: {
                                row: {
                                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                                    opacity: 0.5
                                },
                                },
                                xaxis: {
                                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                                }
                            }).render();
                        });
                        </script>
                        <!-- End Line Chart -->

                    </div>

                    </div>
                </div><!-- End Reports -->

            </div>
        </div><!-- End Left side columns -->


    </div>
</section>
@endsection
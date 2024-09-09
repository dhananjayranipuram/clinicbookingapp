<?php

namespace App\Http\Controllers;
use App\Mail\AppointmentConfirmed;
use Illuminate\Http\Request;
use App\Models\Site;
use Session;
use Storage;
use Mail;

class SiteController extends Controller
{
    // public function sendTestEmail(){
    //     $input = [];
    //     $input['time'] = "10:20";
    //     $input['date'] = "29-08-2024";
    //     $input['doctor'] = "1";
    //     $input['id'] = "10000";
    //     $input['userName'] = "Dhananjay";
    //     Mail::to(config('app.constants.MAIL_TO_ADDRESS'))->send(new AppointmentConfirmed($input));
    // }

    public function index(){
        return view('site/home');
    }

    public function speciality(){
        $site = new Site();
        $data['speciality'] = $site->getSpeciality();
        // echo '<pre>';print_r($data);exit;
        return view('site/speciality',$data);
    }

    public function specDoctors(Request $request){
        $queries = [];
        parse_str($_SERVER['QUERY_STRING'], $queries);
        $site = new Site();
        $data['languages'] = $site->getLanguages();
        $request->flash();
        if(!empty($request->post())){
            $input = [];
            $input['lang'] = $request->post('language');
            $data['spec'] = $input['spec'] = $request->post('speciality');
            $input['gender'] = $request->post('gender');
            $data['doctors'] = $site->getDoctorsFilter($input);
        }else{
            $data['spec'] = $input['spec'] = $queries['id'];
            $data['doctors'] = $site->getDoctorsFilter($input);
        }
        return view('site/spec-doctors',$data);
    }
    
    public function doctors(Request $request){
        $site = new Site();
        $data['languages'] = $site->getLanguages();
        $data['speciality'] = $site->getSpeciality();
        $request->flash();
        if(!empty($request->post())){
            $input = [];
            $input['lang'] = $request->post('language');
            $input['spec'] = $request->post('speciality');
            $input['gender'] = $request->post('gender');
            $data['doctors'] = $site->getDoctorsFilter($input);
        }else{
            $data['doctors'] = $site->getDoctors();
        }
        return view('site/doctors',$data);
    }

    public function registerEndUser(Request $request){
        $site = new Site();
        $response = [];
        if(!empty($request->post())){
            $input = [];
            $input['firstName'] = $request->post('firstName');
            $input['lastName'] = $request->post('lastName');
            $input['emailAddress'] = $request->post('emailAddress');
            $input['phoneNumber'] = $request->post('phoneNumber');
            $input['dob'] = $request->post('dob');
            $input['gender'] = $request->post('gender');
            $input['password'] = $request->post('password');
            if($site->getUserExist($input)[0]->cnt<=0){
                if($site->saveEndUserData($input)){
                    $response['status'] = '200';
                    $response['message'] = 'User created succesfully.';
                }else{
                    $response['status'] = '500';
                    $response['message'] = 'Something went wrong.';
                }
            }else{
                $response['status'] = '200';
                $response['message'] = 'User already exist.';
            }
        }else{
            $response['status'] = '500';
            $response['message'] = 'Something went wrong.';
        }
        return json_encode($response);
    }

    public function loginEndUser(Request $request){
        $site = new Site();
        if(!empty($request->post())){

            $credentials = $request->validate([
                'email' => ['required'],
                'password' => ['required'],
            ]);
            $res = $site->getUserLoginCheck($credentials);
            if(!empty($res)){
                $response['status'] = '200';
                $response['message'] = 'Success.';
                Session::put('userName', $res[0]->user_name);
                Session::put('userData', $res[0]);
            }else{
                $response['status'] = '500';
                $response['message'] = 'Invalid email or password.';
            }
        }else{
            $response['status'] = '500';
            $response['message'] = 'Something went wrong.';
        }
        return json_encode($response);
    }

    public function logOutEndUser(){
        Session::flush();
        return redirect()->back();
        // if(Session::get('userName')){
        //     return 'false';
        // }else{
        //     return 'true';
        // }
    }

    public function checkUser(){
        if(Session::get('userName')){
            return 'true';
        }else{
            return 'false';
        }
    }

    public function contact(){
        return view('site/contact');
    }

    public function availableSlot(Request $request){
        $site = new Site();
        $queries = [];
        parse_str($_SERVER['QUERY_STRING'], $queries);
        $temp = ['gotoMonth' => date('Y-m-01')];
        $data['docId'] = $input['docId'] = $queries['id'];
        $data['docData'] = $site->getDoctorDetains($input);
        $data['calendarStr'] = $this->generateCalendar($temp);
        return view('site/available-slot',$data);
    }

    public function getSelectDocCalendar(Request $request){
        $temp = ['gotoMonth' => date('Y-m-01')];
        $data['calendarStr'] = $this->generateCalendar($temp);
        return view('site/calendar',$data);
    }

    public function getCalendar(Request $request){
        $temp = ['gotoMonth' => $request->post('gotoMonth')];
        return $this->generateCalendar($temp);
    }

    public function makeAppointments(Request $request){
        $userData = Session::get('userData');
        $site = new Site();
        $input = $data = [];
        if(isset($_POST)){
            $input['time'] = $request->post('appointmenttime');
            $input['date'] = $request->post('appointmentdate');
            $input['doctor'] = $request->post('doctor');
            $input['user'] = $userData->id;
            // print_r($input);exit;
            $res = $site->saveAppointments($input);
            $input['id'] = $data['id'] = $res;
            $input['userName'] = Session::get('userName');
            if($res!='Slot not available.'){
                Mail::to(config('app.constants.MAIL_TO_ADDRESS'))->send(new AppointmentConfirmed($input));
                $data['date'] = $input['date'];
                $data['time'] = substr($input['time'],0,11);
                return view('site/confirm-appoitment',$data);
            }else{
                return view('site/error-appoitment');
            }
            
        }else{
            return view('site/home');
        }
    }

    public function getAppointments(Request $request){
        
        $site = new Site();
        $input['docId'] = $request->post('docId');
        $input['day'] = date('w', strtotime($request->post('date')));
        $input['date'] = $request->post('date');
        $res = $site->getSlots($input);
        $app = $site->getDocAppointments($input);
        $appointments = [];
        if(!empty($app)){
            foreach ($app as $key => $value) {
                array_push($appointments,$value->book_time);
            }
        }
        return $this->generateTimeSlot($res,$appointments,$request);
    }

    public function getDoctorAppointments(Request $request){
        
        $site = new Site();
        $input['day'] = date('w', strtotime($request->post('date')));
        $input['date'] = $request->post('date');
        $docs = $site->getAvailableDocs($input);
        
        $res = $site->getSlots($input);
        $app = $site->getDocAppointments($input);
        
        $appointments = [];
        if(!empty($app)){
            foreach ($app as $key => $value) {
                if(!isset($appointments[$value->doc_id]))
                    $appointments[$value->doc_id] = [];
                array_push($appointments[$value->doc_id],$value->book_time);
            }
        }
        $slots = [];
        if(!empty($res)){
            foreach ($res as $key => $value) {
                if(!isset($slots[$value->doc_id]))
                    $slots[$value->doc_id] = [];
                $slots[$value->doc_id] = ['start_time'=>$value->start_time,'end_time'=>$value->end_time,'duration'=>$value->duration];
            }
        }
        return $this->generateDoctorTimeSlot($docs,$slots,$appointments,$request);
    }

    public function generateDoctorTimeSlot($docs,$res,$appointments,$request){
        
        $date = date("F d, Y", strtotime($request->post('date')));
        $dateValue = date("Y-m-d", strtotime($request->post('date')));
        if(!empty($docs)){
            
            $str = '<div class="booked-appt-list">
                    <h2>
                        <span>Available Doctors on </span>
                        <strong>'.$date.'</strong>
                        <span></span>
                    </h2>';
            foreach ($docs as $key => $value) {
                $t1 = strtotime($res[$value->id]['start_time']);
                $t2 = strtotime($res[$value->id]['end_time']);
                $duration = strtotime($res[$value->id]['duration']) - strtotime('00:00:00');
                $slotStr = '<select class="form-select" id="timeslot_'.$value->id.'">';
                while ($t1 < $t2) {
                    $timeSlot = date('h:i:s A', $t1) .' - '.date('h:i:s A', $duration+ $t1);
                    $t1 = $duration+ $t1;
                    if(!empty($appointments[$value->id])){
                        if(in_array($timeSlot,$appointments[$value->id])){
                            continue;
                        }
                    }
                    $slotStr .= '<option value="'.$timeSlot.'">'.$timeSlot.'</option>';
                }
                $slotStr .= '</select>';
                $str .='<div class="container">
                            <div class="row">
                                <div class="col-lg-2"><img class="doctor-image-calendar" src="'.asset($value->profile_pic).'"></div>
                                <div class="col-lg-4"><span>Name : '.$value->name.'</span></div>
                                <div class="col-lg-3">'.$slotStr.'</div>
                                <div class="col-lg-3">
                                    <button class="new-appt button" data-date="'.$dateValue.'" data-doc="'.$value->id.'">
                                        <span class="button-text">Book Appointment</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        ';
            }
            
            $str .= '</div>';
        }else{
            $str = '<div class="booked-appt-list">
                    <h2>
                        <span>No Doctors Available on </span>
                        <strong>'.$date.'</strong>
                        <span></span>
                    </h2>
                    </div>';
        }
        return $str;
    }

    public function generateTimeSlot($res,$appointments,$request){
        $str = '';
        $date = date("F d, Y", strtotime($request->post('date')));
        $dateValue = date("Y-m-d", strtotime($request->post('date')));
        if(!empty($res)){
            $result = $res[0];
            $t1 = strtotime($result->start_time);
            $t2 = strtotime($result->end_time);
            $duration = strtotime($result->duration) - strtotime('00:00:00');

            $str = '<div class="booked-appt-list">
                    <h2>
                        <span>Available Appointments on </span>
                        <strong>'.$date.'</strong>
                        <span></span>
                    </h2>';
            while ($t1 < $t2) {
                
                $timeSlot = date('h:i:s A', $t1) .' - '.date('h:i:s A', $duration+ $t1);
                $t1 = $duration+ $t1;
                if(in_array($timeSlot,$appointments)){
                    continue;
                }
                
                $str .='<div class="timeslot bookedClearFix">
                        <span class="timeslot-time">
                            <span class="timeslot-range">
                                <i class="booked-icon booked-icon-clock"></i>
                                '.$timeSlot.'
                            </span>
                        </span>
                        <span class="timeslot-people">
                            <button data-calendar-id="0" data-title="" data-timeslot="'.$timeSlot.'" data-date="'.$dateValue.'" class="new-appt button">
                                <span class="button-timeslot">'.$timeSlot.'</span>
                                <span class="button-text">Book Appointment</span>
                            </button>
                        </span>
                    </div>';
            }
            $str .= '</div>';
            
        }else{
            $str = '<div class="booked-appt-list">
                    <h2>
                        <span>No Slots Available on </span>
                        <strong>'.$date.'</strong>
                        <span></span>
                    </h2>
                    </div>';
        }
        return $str;
    }

    public function generateCalendar($data){
        $list=[];
        $goToMonth = strtotime($data['gotoMonth']);
        $month = date("m" ,$goToMonth);
        $year = date("Y",$goToMonth);
        $firstDay = date("D",$goToMonth);
        $blankDates = 0;
        switch ($firstDay) {
            case 'Mon':
                $blankDates = 0;
                break;
            case 'Tue':
                $blankDates = 1;
                break;
            case 'Wed':
                $blankDates = 2;
                break;
            case 'Thu':
                $blankDates = 3;
                break;
            case 'Fri':
                $blankDates = 4;
                break;
            case 'Sat':
                $blankDates = 5;
                break;
            case 'Sun':
                $blankDates = 6;
                break;
            default:
                $blankDates = 0;
                break;
        }
        
        for($d=1; $d<=31; $d++){
            $time=mktime(12, 0, 0, $month, $d, $year);          
            if (date('m', $time)==$month){      
                $list[]=date('Y-m-d', $time);
            }
        }
        $today = date("Y-m-d");
        $nextMonth = date('Y-m-d', strtotime('+1 month', strtotime($data['gotoMonth'])));
        $prevMonth = date('Y-m-d', strtotime('-1 month', strtotime($data['gotoMonth'])));
        
        $str = '<table class="booked-calendar">
                <thead>
                    <tr>
                        <th colspan="7">';
        if($data['gotoMonth']!=$today){
            $str .='<a href="" data-goto="'.$prevMonth.'" class="page-left">
                <i class="booked-icon booked-icon-arrow-left"></i>
            </a>';
        }
        $str .='<span class="calendarSavingState">
                                <i class="booked-icon booked-icon-spinner-clock booked-icon-spin"></i>
                            </span>
                            <span class="monthName">
                                '.date("F" ,$goToMonth).' '.date("Y" ,$goToMonth).'
                            </span>
                            <a href="" data-goto="'.$nextMonth.'" class="page-right">
                                <i class="booked-icon booked-icon-arrow-right"></i>
                            </a>
                        </th>
                    </tr>
                    <tr class="days">
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                        <th>Sun</th>
                    </tr>
                </thead><tbody>';
        for($i=0;$i<$blankDates;$i++){
            if($i == 0){
                $str .= '<tr class="week">';
            }
            $str .= '<td data-date="" class="prev-month prev-date">
                    <span class="date tooltipster">
                        <span class="number"></span>
                    </span>
                </td>';
        }
        $dayCount = 0;
        foreach ($list as $key => $value) {
            if(date("D",strtotime($value)) == 'Mon'){
                $str .= '<tr class="week">';
            }
            $className = '';
            if(strtotime($value)<strtotime($today)){
                $className = 'prev-date';
            }else if(strtotime($value)==strtotime($today)){
                $className = 'today';
            }else{
                $className = '';
            }

            $str .= '<td data-date="'.$value.'" class="'.$className.'">
                    <span class="date tooltipster">
                        <span class="number">'.date("d",strtotime($value)).'</span>
                    </span>
                </td>';
            $dayCount++;
            if(date("D",strtotime($value)) == 'Sun'){
                $str .= '</tr>';
                $dayCount = 0;
            }
        }
        
        for($i=0;$i<7-$dayCount;$i++){
            $str .= '<td data-date="" class="next-month prev-date">
                    <span class="date tooltipster">
                        <span class="number"></span>
                    </span>
                </td>';
        }
        $str .= '</tr>
                </tbody>
            </table>';
        return $str;
    }

    
}

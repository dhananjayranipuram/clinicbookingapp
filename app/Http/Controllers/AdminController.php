<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Site;
use App\Mail\AppointmentConfirmed;
use App\Mail\AppointmentCancelled;
use Session;
use Redirect;
use Storage;
use File;
use \Illuminate\Http\UploadedFile;
use URL;
use Mail;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.session');
    }

    public function appointmentCalendar(){
        $admin = new Admin();
        $data['spec'] = $admin->getAllSpeciality(); 
        $data['docs'] = $admin->getDoctorsData();
        $temp = ['gotoMonth' => date('Y-m-01')];
        $data['calendarStr'] = $this->generateCalendar($temp);
        return view('admin/appointment-calendar',$data);
    }

    public function getDoctorAppointments(Request $request){
        
        $admin = new Admin();
        $input['day'] = date('w', strtotime($request->post('date')));
        $input['date'] = $request->post('date');
        $input['docId'] = $request->post('docId');
        $input['specId'] = $request->post('specId');
        $docs = $admin->getAvailableDocs($input);
        
        $res = $admin->getAvailableSlots($input);
        $app = $admin->getDocAppointments($input);
        // print_r($app);exit;
        $appointments = [];
        if(!empty($app)){
            foreach ($app as $key => $value) {
                if(!isset($appointments[$value->doc_id]))
                    $appointments[$value->doc_id] = [];
                array_push($appointments[$value->doc_id],['slot'=>$value->book_time,'status'=>$value->status,'id'=>$value->id]);
            }
        }
        // print_r($appointments);exit;
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

    public function slotNotAvailable(Request $request){
        $admin = new Admin();
        $input['date'] = $request->post('date');
        $input['docId'] = $request->post('docId');
        $input['time'] = $request->post('time');
        $docs = $admin->saveSlotNotAvailable($input);
        return $docs;
    }

    public function enableSlot(Request $request){
        $admin = new Admin();
        $input['id'] = $request->post('id');
        $docs = $admin->enableSlotData($input);
        return json_encode($docs);
    }

    public function cancelAppointment(Request $request){
        $admin = new Admin();
        $site = new Site();
        $input['id'] = $request->post('id');
        $docs = $admin->cancelAppointmentData($input);
        $emailData = $site->getEmailData($input['id']);
        $this->sendCanceledEmails($emailData[0]);
        return json_encode($docs);
    }

    public function bookAppointment(Request $request){
        $site = new Site();
        $input['date'] = $request->post('date');
        $input['docId'] = $request->post('docId');
        $input['time'] = $request->post('time');
        $input['userId'] = $request->post('userId');
        
        $input['firstName'] = $request->post('firstName');
        $input['lastName'] = $request->post('lastName');
        $input['emailAddress'] = $request->post('emailAddress');
        $input['phoneNumber'] = $request->post('phoneNumber');
        $input['dob'] = $request->post('dob');
        $input['gender'] = $request->post('gender');
        $input['password'] = $request->post('phoneNumber');
        $response = [];
        $ex = $site->getUserExist($input);
        if($ex[0]->cnt<=0){
            $reg = $site->saveEndUserData($input);
            if($reg){
                $input['userId'] = $reg;
                
                $response['status'] = '200';
                $response['message'] = 'User created succesfully.';
                $response['appId'] = $this->saveAppointment($input);
                $emailData = $site->getEmailData($response['appId']);
                $this->sendEmails($emailData[0]);
            }else{
                $response['status'] = '500';
                $response['message'] = 'Something went wrong.';
            }
        }else{
            $input['userId'] = $ex[0]->id;
            
            $response['status'] = '200';
            $response['message'] = 'User already exist.';
            $response['appId'] = $this->saveAppointment($input);
            $emailData = $site->getEmailData($response['appId']);
            $this->sendEmails($emailData[0]);
        }
        return json_encode($response);
    }

    public function saveAppointment($input){
        $admin = new Admin();
        return $admin->saveUserAppointment($input);
    }

    public function dashboard(){
        if(Session::get('userAdminData')){
            $admin = new Admin();
            $input = ['from' => date('Y-m-d'),'to' => date('Y-m-d'),'prev_from' => date('Y-m-d',strtotime("-1 days")),'prev_to' => date('Y-m-d',strtotime("-1 days"))]; //Today's data
            // print_r($input);exit;
            $data['list'] = $admin->getLatestAppointmentData($input);
            $bookingRes = $admin->getBookingData($input);
            $data['booking'] = (object)['today_cnt'=>$bookingRes[0]->cnt,'increase'=>$this->increasePercentage($bookingRes[1]->cnt,$bookingRes[0]->cnt)];

            $customerRes = $admin->getCustomerData($input);
            $data['customer'] = (object)['today_cnt'=>$customerRes[0]->cnt,'increase'=>$this->increasePercentage($customerRes[1]->cnt,$customerRes[0]->cnt)];
            $data['doc_appt'] = $admin->getDocWiseAppointmentData($input);
            // echo '<pre>';print_r($data);exit;
            return view('admin/dashboard',$data);
        }else{
            return view('admin/pagenotfound');
        }
    }

    public function getDashboardBooking(Request $request){
        if(Session::get('userAdminData')){
            $admin = new Admin();
            $input = [];
            switch ($request->post('period')) {
                case 'today':
                    $input = ['from' => date('Y-m-d'),'to' => date('Y-m-d'),'prev_from' => date('Y-m-d',strtotime("-1 days")),'prev_to' => date('Y-m-d',strtotime("-1 days"))]; //Today's data
                    break;
                case 'thismonth':
                    $input = ['from' => date('Y-m-01'),'to' => date('Y-m-t'),'prev_from' => date('Y-m-d',strtotime('first day of previous month')),'prev_to' => date('Y-m-d',strtotime('last day of previous month'))]; //Today's data
                    break;
                case 'thisyear':
                    $input = ['from' => date('Y-01-01'),'to' => date('Y-12-31'),'prev_from' => date('Y-01-01',strtotime("-1 years")),'prev_to' => date('Y-12-31',strtotime("-1 years"))]; //Today's data
                    break;
                default:
                    $input = ['from' => date('Y-m-d'),'to' => date('Y-m-d'),'prev_from' => date('Y-m-d',strtotime("-1 days")),'prev_to' => date('Y-m-d',strtotime("-1 days"))]; //Today's data
                    break;
            }
            // print_r($input);exit;
            switch ($request->post('card')) {
                case 'booking-count':
                    $bookingRes = $admin->getBookingData($input);
                    $data['booking'] = (object)['today_cnt'=>$bookingRes[0]->cnt,'increase'=>$this->increasePercentage($bookingRes[1]->cnt,$bookingRes[0]->cnt)];
                    break;
                case 'customer-count':
                    $customerRes = $admin->getCustomerData($input);
                    $data['customer'] = (object)['today_cnt'=>$customerRes[0]->cnt,'increase'=>$this->increasePercentage($customerRes[1]->cnt,$customerRes[0]->cnt)];
                    break;
                case 'pie-chart':
                    $data['doc_appt'] = $admin->getDocWiseAppointmentData($input);
                    break;
                case 'recent-appt':
                    $data['list'] = $admin->getLatestAppointmentData($input);
                    break;
                default:
                    # code...
                    break;
            }
            return $data;
        }else{
            return view('admin/pagenotfound');
        }

    }

    public function getAppointments(Request $request){
        $request->flash();
        $admin = new Admin();
        if($request->method() == 'POST'){
            $filterData = $request->validate([
                'doctor' => [''],
                'speciality' => [''],
                'from' => [''],
                'to' => [''],
            ]);
            $data['list'] = $admin->getAppointmentData($filterData);
        }else{
            date_default_timezone_set('Asia/Calcutta');
            $filterData = [
                'doctor' => '',
                'speciality' => '',
                'from' => date('Y-m-d', time()),
                'to' => date('Y-m-d', time()),
            ];
            $data['list'] = $admin->getAppointmentData($filterData);
        }
        $data['spec'] = $admin->getAllSpeciality(); 
        $data['docs'] = $admin->getDoctorsData();
        if(Session::get('userAdminData')){
            return view('admin/appointments',$data);
        }else{
            return view('admin/pagenotfound');
        }
        
    }

    public function showDoctorList(){
        if(Session::get('userAdminData')){
            $admin = new Admin();
            $data['list'] = $admin->getDoctorsData();
            return view('admin/doctor-list',$data);
        }else{
            return view('admin/pagenotfound');
        }
    }

    public function login(){
        return view('admin/login');
    }

    public function authenticateAdmin(Request $request){
        
        $credentials = $request->validate([
            'username' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $admin = new Admin();
        $data = $admin->authenticateAdmin($credentials);     
        if(!empty($data)){
            Session::put('userAdminData', $data[0]);
            return Redirect::to('/admin/dashboard');
        }else{
            return back()->withErrors(["error" => "Invalid email or password."]);
        }
    }

    public function addDoctor(Request $request){
        try{
            if(Session::get('userAdminData')){
                $admin = new Admin();
                if($request->method() == 'POST'){

                    $request->flash();
                    $credentials = $request->validate([
                        'honor' => ['required'],
                        'first_name' => ['required'],
                        'last_name' => [],
                        'profile_pic' => [],
                        'gender' => ['required'],
                        'email' => ['required', 'email'],
                        'password' => ['required'],
                        'specialization' => ['required'],
                        'available_days' => ['required'],
                        'start' => ['required'],
                        'end' => ['required'],
                        'duration' => ['required','regex:/^(0?[1-9]|1[0-2]):[0-5][0-9]$/'],
                        'languages' => ['required'],
                    ]);
                    print_r($credentials);exit;
                    $file = $request->file('profile_pic');
                    $credentials['profile_pic'] = 'storage/'.$file->store('uploads', 'public');
                    $res = $admin->saveDoctorData($credentials);
                    if($res){
                        return Redirect::to('/admin/doctor-list');
                    }
                }else{
                    $data['speciality'] = $admin->getSpecializationList();
                    $data['lang'] = $admin->getLanguages();
                    return view('admin/add-doctor',$data);
                }
            }else{
                return view('admin/pagenotfound');
            }
        } catch (\Exception $e) {
            return back()->withErrors(["error" => $e->getMessage()]);
        }
    }

    public function editDoctor(){
        if(Session::get('userAdminData')){
            $queries = [];
            parse_str($_SERVER['QUERY_STRING'], $queries);
            $admin = new Admin();
            $data = $admin->getDocProfile((object)$queries);
            $details['lang_select'] = explode(',',$data[0]->lang_id);
            $details['spec'] = $admin->getAllSpeciality(); 
            $details['lang'] = $admin->getLanguages();
            $details['data'] = $admin->getSlots((object)$queries);
            $details['days_available'] = [];
            $details['start_time'] = '';
            $details['end_time'] = '';
            $details['duration'] = '';
            foreach ($details['data'] as $key => $value) {
                array_push($details['days_available'],$value->working_days);
                $details['start_time'] = $value->start_time;
                $details['end_time'] = $value->end_time;
                $details['duration'] = $value->duration;
            }
            return view('admin/edit-doctor',(array)$data[0],$details);
        }else{
            return view('admin/pagenotfound');
        }
    }

    public function updateDoctorProfile(Request $request){
        
        $admin = new Admin();
        if($request->method() == 'POST'){

            $request->flash();
            $credentials = $request->validate([
                'docId' => ['required'],
                'honor' => ['required'],
                'first_name' => ['required'],
                'last_name' => [],
                'gender' => ['required'],
                'email' => ['required', 'email'],
                'specialization' => ['required'],
                'available_days' => ['required'],
                'start' => ['required'],
                'end' => ['required'],
                'duration' => ['required','regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/'],
                'languages' => ['required'],
            ]);
            // $file = $request->file('profile_pic');
            // $credentials['profile_pic'] = 'storage/'.$file->store('uploads', 'public');
            $res = $admin->updateDoctorData($credentials);
            if($res){
                return Redirect::to('/admin/edit-doctor?id='.$res);
            }
        }   
        
    }

    public function deleteDoctor(){
        if(Session::get('userAdminData')){
            $input['docId'] = $_POST['id'];
            $admin = new Admin();
            $data = $admin->deleteDoctorData($input);
            return json_encode($data);
        }else{
            return view('admin/pagenotfound');
        }
    }

    public function deleteAppointment(){
        // if(Session::get('userAdminData')){
            $input['id'] = $_POST['id'];
            $admin = new Admin();
            $data = $admin->deleteAppointment($input);
            return json_encode($data);
        // }else{
        //     return view('admin/pagenotfound');
        // }
    }

    public function updateAppointment(Request $request){
        if(Session::get('userAdminData')){
            $admin = new Admin();
            $credentials = $request->validate([
                'docId' => ['required'],
                'appDate' => ['required'],
                'timeSlot' => ['required'],
                'appId' => ['required'],
            ]);
            // print_r($credentials);exit;
            $res = $admin->updateAppointmentData($credentials);
            // if($res){
                return Redirect::to('/admin/appointments');
            // }
        }else{
            return view('admin/pagenotfound');
        }
    }

    public function editAppointment(){
        if(Session::get('userAdminData')){
            $queries = [];
            parse_str($_SERVER['QUERY_STRING'], $queries);
            $admin = new Admin();
            $data['det'] = $admin->getAppointmentDataDetailed((object)$queries);
            $data['spec'] = $admin->getAllSpeciality(); 
            $data['docs'] = $admin->getDoctorsData();

            $input['id'] = $input['docId'] = $data['det'][0]->doc_id;
            $input['date'] = $data['det'][0]->book_date;
            $res = $admin->getSlots((object)$input);
            $app = $admin->getDocAppointments($input);
            
            $data['timeslotselect'] = $this->generateTimeSlotSelect($res,$app,$input);
            // echo '<pre>';print_r($data);exit;
            return view('admin/edit-appointment',$data);
        }else{
            return view('admin/pagenotfound');
        }
    }

    public function getTimeSlots(Request $request){

        $admin = new Admin();
        $input['id'] = $input['docId'] = $request->post('docId');
        $input['day'] = date('w', strtotime($request->post('appDate')));
        $input['date'] = $request->post('appDate');
        $res = $admin->getSlots((object)$input);
        $app = $admin->getDocAppointments($input);

        return $this->generateTimeSlotSelect($res,$app,$input);
    }

    public function generateTimeSlotSelect($res,$app,$input){
        date_default_timezone_set("UTC");
        $timestamp = strtotime($input['date']);
        $day = date('l', $timestamp);
        $dayKey = $this->in_array_day($day,$res);
        $dateValue = date("Y-m-d", strtotime($input['date']));
        $slotStr = '';
        $appointments = [];
        if(!empty($app)){
            foreach ($app as $key => $value) {
                if(!isset($appointments))
                    $appointments = [];
                array_push($appointments,$value->book_time);
            }
        }
        // print_r($appointments);exit;
        // print_r($res[$dayKey]);exit;
        if($dayKey!='not found'){
            $t1 = strtotime($res[$dayKey]->start_time);
            $t2 = strtotime($res[$dayKey]->end_time);
            $duration = strtotime($res[$dayKey]->duration) - strtotime('00:00:00');
            $slotStr = '<select class="form-select" id="timeslot" name="timeSlot">';
            while ($t1 < $t2) {
                $startTime = date('H:i:s', $t1);
                $endTime = date('H:i:s', $t1 + $duration);

                $timeSlot = date('h:i:s A', $t1) .' - '.date('h:i:s A', $duration+ $t1);
                $t1 = $duration+ $t1;
                if($this->checkTime($startTime,$endTime,$dateValue) != 'true'){
                    continue;
                }
                if(!empty($appointments)){
                    if(in_array($timeSlot,$appointments)){
                        continue;
                    }
                }
                $slotStr .= '<option value="'.$timeSlot.'">'.substr($timeSlot,0,11).'</option>';
                
            }
            $slotStr .= '</select>';
            if($slotStr == '<select class="form-select" id="timeslot" name="timeSlot"></select>'){
                $slotStr = '<select class="form-select" id="timeslot"><option value="0" selected disabled>Slot not available</option></select>';                
            }
        }else{
            $slotStr = '<select class="form-select" id="timeslot"><option value="0" selected disabled>Slot not available</option></select>';
        }
        return $slotStr;
    }

    public function viewAdminProfile(){
        if(Session::get('userAdminData')){
            $admin = new Admin();
            $userData = Session::get('userAdminData');
            $data = $admin->getAdminProfile($userData);
            return view('admin/admin-profile',(array)$data[0]);
        }else{
            return view('admin/pagenotfound');
        }
    }

    public function logout(){
        Session::flush();
        return Redirect::to('/admin/login');
    }

    public function addSpecialization(Request $request){
        if(Session::get('userAdminData')){
            $admin = new Admin();
            if($request->method() == 'POST'){
                $request->flash();
                $credentials = $request->validate([
                    'specialization' => ['required'],
                ]);
                if($request->post('active')=='on'){
                    $credentials['active'] = 1;
                }else{
                    $credentials['active'] = 0;
                }
                $data = $admin->addSpecialization($credentials);
                if($data==1){
                    return Redirect::to('/admin/add-specialization');
                }else{
                    return back()->withErrors(["error" => $data]);
                }
            }else{
                $data['spec'] = $admin->getAllSpeciality();   
                return view('admin/specialization',$data);
            }
        }else{
            return view('admin/pagenotfound');
        }
    }

    public function editSpecialization(Request $request){
        if($request->method() == 'POST'){
            $credentials = $request->validate([
                'specialization' => ['required'],
                'specializationId' => ['required'],
            ]);
            if($request->post('editActive')=='on'){
                $credentials['active'] = 1;
            }else{
                $credentials['active'] = 0;
            }
            $admin = new Admin();
            $data = $admin->editSpecialization($credentials);
            if($data==1){
                return Redirect::to('/admin/add-specialization');
            }else{
                return back()->withErrors(["error" => $data]);
            }
        }
    }

    public function deleteSpecialization(){
        if(Session::get('userAdminData')){
            $input['specializationId'] = $_POST['id'];
            $admin = new Admin();
            $data = $admin->deleteSpecialization($input);
            return json_encode($data);
        }else{
            return view('admin/pagenotfound');
        }
    }

    public function addLanguages(Request $request){
        if(Session::get('userAdminData')){
            $admin = new Admin();
            if($request->method() == 'POST'){
                $request->flash();
                $credentials = $request->validate([
                    'language' => ['required'],
                ]);
                if($request->post('active')=='on'){
                    $credentials['active'] = 1;
                }else{
                    $credentials['active'] = 0;
                }
                $data = $admin->addLanguage($credentials);
                if($data==1){
                    return Redirect::to('/admin/add-language');
                }else{
                    return back()->withErrors(["error" => $data]);
                }
            }else{
                $data['spec'] = $admin->getAllLanguages();   
                return view('admin/language',$data);
            }
        }else{
            return view('admin/pagenotfound');
        }
    }

    public function editLanguages(Request $request){
        if($request->method() == 'POST'){
            $credentials = $request->validate([
                'language' => ['required'],
                'languageId' => ['required'],
            ]);
            if($request->post('editActive')=='on'){
                $credentials['active'] = 1;
            }else{
                $credentials['active'] = 0;
            }
            $admin = new Admin();
            $data = $admin->editLanguage($credentials);
            if($data==1){
                return Redirect::to('/admin/add-language');
            }else{
                return back()->withErrors(["error" => $data]);
            }
        }
    }

    public function deleteLanguages(){
        if(Session::get('userAdminData')){
            $input['languageId'] = $_POST['id'];
            $admin = new Admin();
            $data = $admin->deleteLanguage($input);
            return json_encode($data);
        }else{
            return view('admin/pagenotfound');
        }
    }

    public function increasePercentage($old, $new, int $precision = 2): float
    {
        if ($old == 0) {
            $old++;
            $new++;
        }
        $change = (($new - $old) / $old) * 100;

        return round($change, $precision);
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

    public function generateDoctorTimeSlot($docs,$res,$appointments,$request){
        // print_r($res);
        // print_r($appointments);exit;
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
                date_default_timezone_set("UTC");
                $t1 = strtotime($res[$value->id]['start_time']);
                $t2 = strtotime($res[$value->id]['end_time']);
                $duration = strtotime($res[$value->id]['duration']) - strtotime('00:00:00');

                $cnt = 0;
                $innerStr = '';
                while ($t1 < $t2) {

                    $startTime = date('H:i:s', $t1);
                    $endTime = date('H:i:s', $t1 + $duration);

                    $cnt++;
                    $timeSlot = date('h:i:s A', $t1) .' - '.date('h:i:s A', $t1 + $duration);
                    $t1 = $t1 + $duration;
                    if($this->checkTime($startTime,$endTime,$dateValue) == 'false'){
                        continue;
                    }
                    if(!empty($appointments[$value->id])){
                        $check = $this->in_array_r($timeSlot,$appointments[$value->id]);
                        if($check!='not found'){
                            $classStstus = ($appointments[$value->id][$check]['status']=='Booked')?'badge bg-success':'badge bg-secondary';
                            $innerStr .='<div class="col-lg-2 '.$cnt.'_'.$value->id.'">'.substr($timeSlot,0,11).'</div>
                                <div class="col-lg-4 '.$cnt.'_'.$value->id.'" data-el="'.$cnt.'_'.$value->id.'">
                                    <span style="cursor:pointer;" data-id="'.$appointments[$value->id][$check]['id'].'" class="'.$classStstus.' '.str_replace(' ', '-',strtolower($appointments[$value->id][$check]['status'])).'-slot">'.$appointments[$value->id][$check]['status'].'</span>
                                </div>';
                                continue;
                        }
                    }
                    $innerStr .='<div class="col-lg-2">'.substr($timeSlot,0,11).'</div>
                    <div class="col-lg-4 '.$cnt.'_'.$value->id.'" data-el="'.$cnt.'_'.$value->id.'">
                        <button class="new-appt button" data-el="'.$cnt.'_'.$value->id.'" data-date="'.$dateValue.'"  data-time="'.$timeSlot.'" data-doc="'.$value->id.'" data-bs-toggle="modal" data-bs-target="#registrationModal">
                            <span class="button-text">Book</span>
                        </button>
                        <button class="not-available button" data-el="'.$cnt.'_'.$value->id.'" data-date="'.$dateValue.'"  data-time="'.$timeSlot.'" data-doc="'.$value->id.'">
                            <span class="button-text">Not Available</span>
                        </button>
                    </div>';
                }


                if($innerStr == ''){
                    $innerStr = '<h2>
                                    <span>No slots Available for this doctor </span>
                                    <span></span>
                                </h2>';
                }

                $str .='<div class="container" style="width:100%;">
                            <div class="row">
                                <div class="col-lg-2"><img class="doctor-image-calendar" src="'.asset($value->profile_pic).'"><br><span>Name : '.$value->name.'</span></div>
                                <div class="col-lg-10"><div class="row">'.$innerStr;
                                

                $str .='</div></div>
                        </div>
                        </div>';
                if($key+1!=count($docs)){
                    $str .='</br><div class="dropdown-divider"></div></br>';
                }else{
                    $str .='</br>';
                }
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

    public function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $key => $item) {
            if ( $item['slot'] == $needle) {
                return $key;
            }
        }
    
        return 'not found';
    }

    public function checkTime($startTime,$endTime,$dateValue){

        date_default_timezone_set("Asia/Calcutta");
        $currentTime = date('H:i:s', time());
        $currentDate = date("Y-m-d", time());
        date_default_timezone_set("UTC");
        if($currentTime>$endTime && $dateValue==$currentDate){
            return 'false';
        }else if($currentTime>$endTime && $dateValue<$currentDate ){
            return 'false';
        }else{
            return 'true';
        }
    }

    public function in_array_day($needle, $haystack, $strict = false) {
        foreach ($haystack as $key => $item) {
            if ( strtoupper($item->day) == strtoupper($needle)) {
                return $key;
            }
        }
    
        return 'not found';
    }

    public function getUserData(Request $request){
        $admin = new Admin();
        if($request->method() == 'POST'){
            $credentials = $request->validate([
                'emailAddress' => [''],
                'mobile' => [''],
            ]);
            
            $data = $admin->getUserDataFilter($credentials);
            return json_encode($data);
        }
    }

    public function getDoctorsList(Request $request){
        
        $admin = new Admin();
        $credentials = $request->validate([
            'spec' => [''],
        ]);
        $data = $admin->getDoctorsData($credentials);
        return json_encode($data);
    }

    public function showPatientList(){
        if(Session::get('userAdminData')){
            $admin = new Admin();
            $data['list'] = $admin->getPatientData();
            // print_r($data);exit;
            return view('admin/patient-list',$data);
        }else{
            return view('admin/pagenotfound');
        }
    }

    public function deletePatient(){
        $input['id'] = $_POST['id'];
        $admin = new Admin();
        $data = $admin->deletePatientData($input);
        return json_encode($data);
    }

    public function editPatient(){
        if(Session::get('userAdminData')){
            $queries = [];
            parse_str($_SERVER['QUERY_STRING'], $queries);
            $admin = new Admin();
            $input['id'] = base64_decode($queries['id']);
            $details = $admin->getPatientData((object)$input);
            $data['data'] = $admin->getUserAppointments((object)$input);
            // print_r($data);exit;
            return view('admin/edit-patient',(array)$details[0],$data);
        }else{
            return view('admin/pagenotfound');
        }
    }

    public function updatePatientProfile(Request $request){
        
        $admin = new Admin();
        if($request->method() == 'POST'){

            $request->flash();
            $credentials = $request->validate([
                'id' => ['required'],
                'first_name' => ['required'],
                'last_name' => [],
                'gender' => ['required'],
                'email' => ['required', 'email'],
                'mobile' => ['required'],
                'status' => [],
            ]);
            if($request->post('status')=='on'){
                $credentials['active'] = 1;
            }else{
                $credentials['active'] = 0;
            }
            $res = $admin->updatePatientData($credentials);
            return Redirect::to('/admin/edit-patient?id='.base64_encode($credentials['id']));
        }   
        
    }

    public function sendEmails($emailData){
        Mail::to(config('app.constants.MAIL_TO_ADDRESS'))->send(new AppointmentConfirmed($emailData,'admin'));
        Mail::to($emailData->email)->send(new AppointmentConfirmed($emailData,'customer'));
    }

    public function sendCanceledEmails($emailData){
        Mail::to(config('app.constants.MAIL_TO_ADDRESS'))->send(new AppointmentCancelled($emailData,'admin'));
        Mail::to($emailData->email)->send(new AppointmentCancelled($emailData,'customer'));
    }
}

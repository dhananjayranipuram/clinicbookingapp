<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Site;
use Session;
use Redirect;
use Storage;
use File;
use \Illuminate\Http\UploadedFile;
use URL;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.session');
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
                        'duration' => ['required'],
                        'languages' => ['required'],
                    ]);
                    
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
                'duration' => ['required'],
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
}

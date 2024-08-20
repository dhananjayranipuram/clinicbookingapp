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
            return view('admin/dashboard');
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
                return Redirect::to('/admin/login');
            }
        } catch (\Exception $e) {
            return back()->withErrors(["error" => $e->getMessage()]);
        }
    }

    public function editDoctor(){
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

    public function viewAdminProfile(){
        $admin = new Admin();
        $userData = Session::get('userAdminData');
        $data = $admin->getAdminProfile($userData);
        return view('admin/admin-profile',(array)$data[0]);
    }

    public function logout(){
        Session::flush();
        return Redirect::to('/admin/login');
    }

    public function addSpecialization(Request $request){
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

    public function addLanguages(Request $request){
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
}

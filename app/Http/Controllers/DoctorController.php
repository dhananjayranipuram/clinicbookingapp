<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use Session;
use Redirect;

class DoctorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.session');
    }

    public function dashboard(){
        if(Session::get('userDoctorData')){
            return view('doctor/dashboard');
        }else{
            return view('doctor/pagenotfound');
        }
    }

    public function login(){
        return view('doctor/login');
    }

    public function getAppointments(){
        if(Session::get('userDoctorData')){
            $data = $inputs = [];
            $inputs['id'] = Session::get('userDoctorData')->id;
            $doc = new Doctor();
            $data['list'] = $doc->getAppointmentData($inputs);
            return view('doctor/appointments',$data);
        }else{
            return view('doctor/pagenotfound');
        }
    }

    public function getProfile(){

        $userData = Session::get('userDoctorData');
        $doc = new Doctor();
        $data = $doc->getDocProfile($userData);
        $details['lang_select'] = explode(',',$data[0]->lang_id);
        $details['spec'] = $doc->getSpecializationList(); 
        $details['lang'] = $doc->getLanguages();
        $details['data'] = $doc->getSlots($userData);
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
        
        return view('doctor/profile',(array)$data[0],$details);
    }

    public function updateProfile(Request $request){
        $doc = new Doctor();
        if($request->method() == 'POST'){

            $request->flash();
            $credentials = $request->validate([
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
                'profile_pic' => [],
            ]);
            $credentials['docId'] = Session::get('userDoctorData')->id;
            if(!empty($_FILE['profile_pic'])){
                $file = $request->file('profile_pic');
                $credentials['profile_pic'] = 'storage/'.$file->store('uploads', 'public');
            }
            $res = $doc->updateDoctorData($credentials);
            // if($res){
                return Redirect::to('/doctor/profile');
            // }
        }
        
    }

    public function changePassword(Request $request){
        $credentials = $request->validate([
            'newPassword' => ['required'],
            'currentPassword' => ['required'],
        ]);
        $credentials['docId'] = Session::get('userDoctorData')->id;
        $doc = new Doctor();
        return $doc->updateDoctorPassword($credentials);
        
    }

    public function authenticateDoctor(Request $request){
        
        $credentials = $request->validate([
            'username' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $doc = new Doctor();
        $data = $doc->authenticateDoctor($credentials);     
        if(!empty($data)){
            Session::put('userDoctorData', $data[0]);
            return Redirect::to('/doctor/dashboard');
        }else{
            return back()->withErrors(["error" => "Invalid email or password."]);
        }
    }

    public function logout(){
        Session::flush();
        return Redirect::to('/doctor/login');
    }
}

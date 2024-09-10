<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    public function getLanguages(){
        return DB::select("SELECT id,name FROM languages WHERE active=1 ORDER BY name;");
    }

    public function getSpeciality(){
        return DB::select("SELECT s.id,s.name,COUNT(d.id) cnt FROM speciality s 
                            LEFT JOIN doctor d ON s.id=d.specialization
                            WHERE s.active=1 AND d.deleted=0 AND d.active=1
                            GROUP BY s.id
                            ORDER BY NAME;");
    }

    public function getDoctors(){
        return DB::select("SELECT d.id,CONCAT(d.honor,d.first_name,' ',d.last_name) 'name',gender,profile_pic,sp.name 'specialized',GROUP_CONCAT(l.name) AS languages
                            FROM doctor d
                            LEFT JOIN speciality sp ON d.specialization = sp.id
                            LEFT JOIN doctor_languages dl ON d.id=dl.doctor_id
                            LEFT JOIN languages l ON dl.lang_id = l.id
                            WHERE d.active=1 AND d.deleted=0
                            GROUP BY d.id
                            ORDER BY d.first_name,d.last_name;");
    }

    public function getDoctorsFilter($data){
        // print_r($data);exit;
        $condition = '';
        if(!empty($data['lang'])){
            $condition .= " AND dl.lang_id = $data[lang]";
        }
        if(!empty($data['spec'])){
            $condition .= " AND d.specialization IN ($data[spec])";
        }
        if(!empty($data['gender'])){
            $condition .= " AND d.gender = '$data[gender]'";
        }
        // echo $condition;exit;
        return DB::select("SELECT d.id,CONCAT(d.honor,d.first_name,' ',d.last_name) 'name',gender,profile_pic,sp.name 'specialized',GROUP_CONCAT(l.name) AS languages
                            FROM doctor d
                            LEFT JOIN speciality sp ON d.specialization = sp.id
                            LEFT JOIN doctor_languages dl ON d.id=dl.doctor_id
                            LEFT JOIN languages l ON dl.lang_id = l.id
                            WHERE d.active=1 AND d.deleted=0 $condition
                            GROUP BY d.id
                            ORDER BY d.first_name,d.last_name;");
    }
    
    public function saveEndUserData($data){
        return DB::INSERT("INSERT INTO enduser (first_name,last_name,mobile,email,pword,dob,gender) VALUES ('$data[firstName]','$data[lastName]','$data[phoneNumber]','$data[emailAddress]','$data[password]','$data[dob]','$data[gender]');");
    }

    public function getUserExist($data){
        return DB::select("SELECT COUNT(*) cnt FROM enduser WHERE email='$data[emailAddress]';");
    }

    public function getUserLoginCheck($data){
        return DB::select("SELECT CONCAT(first_name,' ',last_name) user_name,id FROM enduser WHERE email='$data[email]' AND pword='$data[password]';");
    }

    public function saveAppointments($data){
        $res = DB::select("SELECT * FROM appointments WHERE doc_id='$data[doctor]' AND book_date='$data[date]' AND book_time='$data[time]';");
        if(empty($res)){
            DB::INSERT("INSERT INTO appointments (doc_id,enduser_id,book_date,book_time) VALUES ('$data[doctor]','$data[user]','$data[date]','$data[time]');");
            return DB::getPdo()->lastInsertId();
        }else{
            return 'Slot not available.';
        }
    }

    public function getSlots($data){
        if(isset($data['docId'])){
            return DB::select("SELECT start_time,end_time,duration FROM duty_slab WHERE doc_id='$data[docId]' AND working_days='$data[day]';");
        }else{
            return DB::select("SELECT doc_id,start_time,end_time,duration FROM duty_slab;");
        }
    }

    public function getDocAppointments($data){
        if(isset($data['docId'])){
            return DB::select("SELECT book_time FROM appointments WHERE doc_id='$data[docId]' AND book_date='$data[date]';");
        }else{
            return DB::select("SELECT doc_id,book_time FROM appointments WHERE book_date='$data[date]';");
        }
    }
    public function getAvailableDocs($data){
        $dayofweek = date('w', strtotime($data['date']));
        return DB::select("SELECT doc.id,CONCAT(doc.honor,doc.first_name,' ',doc.last_name) 'name',profile_pic FROM duty_slab slab
            LEFT JOIN doctor doc ON slab.doc_id=doc.id
            WHERE slab.working_days=$dayofweek AND doc.deleted=0 AND doc.active=1");
    }

    public function getDoctorDetains($data){
        return DB::select("SELECT d.id,CONCAT(d.honor,d.first_name,' ',d.last_name) 'name',gender,profile_pic,sp.name 'specialized',GROUP_CONCAT(l.name) AS languages
                            FROM doctor d
                            LEFT JOIN speciality sp ON d.specialization = sp.id
                            LEFT JOIN doctor_languages dl ON d.id=dl.doctor_id
                            LEFT JOIN languages l ON dl.lang_id = l.id
                            WHERE d.active=1 AND d.id='$data[docId]'
                            GROUP BY d.id
                            ORDER BY d.first_name,d.last_name;");
    }

    public function getEmailData($id){
        return DB::select("SELECT CONCAT(eu.first_name,' ',eu.last_name) patient_name,CONCAT(dc.honor,dc.first_name,' ',dc.last_name) 'doctor_name',sp.name 'speciality',DATE_FORMAT(ap.book_date, '%d-%b-%Y') book_date,LEFT(ap.book_time,11) book_time FROM appointments ap
                        LEFT JOIN doctor dc ON dc.id=ap.doc_id
                        LEFT JOIN speciality sp ON dc.specialization = sp.id
                        LEFT JOIN enduser eu ON eu.id=ap.enduser_id
                        WHERE ap.id=$id;");
    }
}

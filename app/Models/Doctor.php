<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    public function getAppointmentData($data){
        $condition = '';
        if(!empty($data['from']) && !empty($data['to'])){
            $condition .= " AND (ap.book_date between '$data[from]' and '$data[to]')";
        }
        return DB::select("SELECT ap.id appointment_id,CONCAT(eu.first_name,' ',eu.last_name) patient_name,eu.mobile patient_mobile,DATE_FORMAT(ap.book_date, '%d-%b-%Y') book_date,LEFT(ap.book_time,11) book_time FROM appointments ap
                        LEFT JOIN doctor dc ON dc.id=ap.doc_id
                        LEFT JOIN enduser eu ON eu.id=ap.enduser_id
                        WHERE ap.doc_id=$data[id] $condition ORDER BY ap.id DESC;");
    }

    public function getDocProfile($data){
        return DB::select("SELECT d.id docId,d.honor,d.first_name,d.last_name,CONCAT(d.honor,' ',d.first_name,' ',d.last_name) doctor_name,email,d.gender,d.profile_pic,d.specialization spec_id,sp.name specialization,GROUP_CONCAT(l.id) AS lang_id,GROUP_CONCAT(l.name) AS languages 
                        FROM doctor d
                        LEFT JOIN speciality sp ON sp.id=d.specialization
                        LEFT JOIN doctor_languages dl ON d.id=dl.doctor_id
                        LEFT JOIN languages l ON dl.lang_id = l.id
                        WHERE d.id = $data->id AND d.deleted=0
                        GROUP BY d.id;");
    }

    public function getSpecializationList(){
        return DB::select("SELECT id,name FROM speciality WHERE active=1;");
    }

    public function getLanguages(){
        return DB::select("SELECT id,name FROM languages WHERE active=1;");
    }

    public function getSlots($data){
        return DB::select("SELECT CASE WHEN working_days = 0 THEN 'SUNDAY'
                        WHEN working_days = 1 THEN 'MONDAY'
                        WHEN working_days = 2 THEN 'TUESDAY'
                        WHEN working_days = 3 THEN 'WEDNESDAY'
                        WHEN working_days = 4 THEN 'THURSDAY'
                        WHEN working_days = 5 THEN 'FRIDAY'
                        WHEN working_days = 6 THEN 'SATURDAY' END 'day',working_days,
                        TIME_FORMAT(start_time,'%h:%i %p') start_time_label,TIME_FORMAT(end_time,'%h:%i %p') end_time_label,end_time,start_time,duration FROM duty_slab WHERE doc_id='$data->id' ORDER BY working_days;");
                                
    }

    public function updateDoctorData($data){
        DB::beginTransaction();
        $docId = $data['docId'];
        // print_r($data);exit;
        try {
            DB::UPDATE("UPDATE doctor SET honor='$data[honor]',first_name='$data[first_name]',last_name='$data[last_name]',email='$data[email]',gender='$data[gender]',specialization='$data[specialization]',profile_pic='$data[profile_pic]' WHERE id='$data[docId]';");
            
            DB::DELETE("DELETE FROM duty_slab WHERE doc_id='$docId';");
            foreach ($data['available_days'] as $key => $value) {
                DB::INSERT("INSERT INTO duty_slab (doc_id,working_days,start_time,end_time,duration) VALUES ('$docId','$value','$data[start]','$data[end]','$data[duration]');");
            }

            DB::DELETE("DELETE FROM doctor_languages WHERE doctor_id='$docId';");
            foreach ($data['languages'] as $key => $value) {
                DB::INSERT("INSERT INTO doctor_languages (doctor_id,lang_id) VALUES ('$docId','$value');");
            }

            DB::commit();
            return $docId;
        } catch (\Exception $e) {
            return DB::rollback();
        }
    }

    public function authenticateDoctor($data){
        return DB::select("SELECT id,CONCAT(honor,' ',first_name,' ',last_name) doctor_name,first_name,profile_pic FROM doctor WHERE email='$data[username]' AND password='$data[password]' AND deleted=0 AND active=1;");
    }

    public function updateDoctorPassword($data){
        $doc = DB::select("SELECT id FROM doctor WHERE id='$data[docId]' AND password='$data[currentPassword]';");
        if(!empty($doc)){
            return DB::UPDATE("UPDATE doctor SET password='$data[newPassword]' WHERE id='$data[docId]' AND password='$data[currentPassword]';");
        }else{
            return 2;
        }
    }
}
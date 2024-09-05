<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    public function getAppointmentData($data){
        $condition = '';
        if(!empty($data['doctor'])){
            $condition .= " AND dc.id = $data[doctor]";
        }
        if(!empty($data['speciality'])){
            $condition .= " AND dc.specialization = $data[speciality]";
        }
        if(!empty($data['from']) && !empty($data['to'])){
            $condition .= " AND (ap.book_date between '$data[from]' and '$data[to]')";
        }
        return DB::select("SELECT ap.id appointment_id,CONCAT(eu.first_name,' ',eu.last_name) patient_name,eu.mobile patient_mobile,ap.book_date,ap.book_time,sp.name 'speciclity',CONCAT(dc.honor,' ',dc.first_name,' ',dc.last_name) 'doctor_name' FROM appointments ap
                        LEFT JOIN doctor dc ON dc.id=ap.doc_id
                        LEFT JOIN enduser eu ON eu.id=ap.enduser_id 
                        LEFT JOIN speciality sp ON sp.id=dc.specialization
                        WHERE dc.active=1 $condition
                        ORDER BY ap.book_date ASC;");
    }

    public function getLatestAppointmentData($data){
        return DB::select("SELECT ap.id appointment_id,CONCAT(eu.first_name,' ',eu.last_name) patient_name,eu.mobile patient_mobile,ap.book_date,ap.book_time FROM appointments ap
                        LEFT JOIN doctor dc ON dc.id=ap.doc_id
                        LEFT JOIN enduser eu ON eu.id=ap.enduser_id
                        WHERE ap.book_date between '$data[from]' and '$data[to]'
                        ORDER BY ap.book_date ASC
                        LIMIT 5;");
    }

    public function getDocWiseAppointmentData($data){
        return DB::select("SELECT d.id,COUNT(a.doc_id) 'value',CONCAT(d.honor,d.first_name,' ',d.last_name) 'name' FROM doctor d
                        LEFT JOIN appointments a ON d.id=a.doc_id
                        WHERE a.book_date between '$data[from]' and '$data[to]'
                        GROUP BY d.id;");
    }

    public function getBookingData($data){
        return DB::select("SELECT 'Today' AS 'label',COUNT(id) cnt FROM appointments a
                        WHERE a.book_date between '$data[from]' and '$data[to]'

                        UNION

                        SELECT 'Yesterday' AS 'label',COUNT(id) cnt FROM appointments a
                        WHERE a.book_date between '$data[prev_from]' and '$data[prev_to]';");
    }

    public function getCustomerData($data){
        return DB::select("SELECT 'Today' AS 'label',COUNT(u.id) cnt FROM enduser u
                        WHERE u.created_at between '$data[from]' and '$data[to]'

                        UNION

                        SELECT 'Yesterday' AS 'label',COUNT(u.id) cnt FROM enduser u
                        WHERE u.created_at between '$data[prev_from]' and '$data[prev_to]';");
    }

    public function getAllSpeciality(){
        return DB::select("SELECT id,name,active,CASE WHEN active = 1 THEN 'Active' ELSE 'Inactive' END 'activeName' FROM speciality WHERE deleted=0 ORDER BY name;");
    }

    public function getAllLanguages(){
        return DB::select("SELECT id,name,active,CASE WHEN active = 1 THEN 'Active' ELSE 'Inactive' END 'activeName' FROM languages WHERE deleted=0 ORDER BY name;");
    }

    public function authenticateAdmin($data){
        return DB::select("SELECT id,name FROM users WHERE email='$data[username]' AND password='$data[password]';");
    }

    public function getDoctorsData(){
        return DB::select("SELECT d.id,CONCAT(d.honor,' ',d.first_name,' ',d.last_name) doctor_name,d.email,d.gender,d.specialization,sp.name Speciality 
        FROM doctor d
        LEFT JOIN speciality sp ON sp.id=d.specialization;");
    }

    public function getSpecializationList(){
        return DB::select("SELECT id,name FROM speciality WHERE active=1;");
    }

    public function getLanguages(){
        return DB::select("SELECT id,name FROM languages WHERE active=1;");
    }

    public function saveDoctorData($data){
        DB::beginTransaction();
        // print_r($data);exit;
        try {
            DB::INSERT("INSERT INTO doctor (honor,first_name,last_name,email,password,gender,specialization,profile_pic) VALUES ('$data[honor]','$data[first_name]','$data[last_name]','$data[email]','$data[password]','$data[gender]','$data[specialization]','$data[profile_pic]');");
            $docId = DB::getPdo()->lastInsertId();
            foreach ($data['available_days'] as $key => $value) {
                DB::INSERT("INSERT INTO duty_slab (doc_id,working_days,start_time,end_time,duration) VALUES ('$docId','$value','$data[start]','$data[end]','$data[duration]');");
            }
            foreach ($data['languages'] as $key => $value) {
                DB::INSERT("INSERT INTO doctor_languages (doctor_id,lang_id) VALUES ('$docId','$value');");
            }
            DB::commit();
            return $docId;
        } catch (\Exception $e) {
            return DB::rollback();
        }
    }

    public function addSpecialization($data){

        $res = DB::select("SELECT id FROM speciality WHERE name LIKE '%$data[specialization]%';");
        if(empty($res)){
            return DB::INSERT("INSERT INTO speciality (name,active) VALUES ('$data[specialization]','$data[active]');");
        }else{
            return 'Specialization already exist.';
        }
    }

    public function editSpecialization($data){
        return DB::UPDATE("UPDATE speciality SET name='$data[specialization]',active='$data[active]' WHERE id='$data[specializationId]';");
    }

    public function deleteSpecialization($data){
        return DB::UPDATE("UPDATE speciality SET deleted='1' WHERE id='$data[specializationId]';");
    }

    public function addLanguage($data){

        $res = DB::select("SELECT id FROM languages WHERE name LIKE '%$data[language]%';");
        if(empty($res)){
            return DB::INSERT("INSERT INTO languages (name,active) VALUES ('$data[language]','$data[active]');");
        }else{
            return 'Language already exist.';
        }
    }

    public function editLanguage($data){
        return DB::UPDATE("UPDATE languages SET name='$data[language]',active='$data[active]' WHERE id='$data[languageId]';");
    }

    public function deleteLanguage($data){
        return DB::UPDATE("UPDATE languages SET deleted='1' WHERE id='$data[languageId]';");
    }

    public function getDocProfile($data){
        return DB::select("SELECT d.id docId,d.honor,d.first_name,d.last_name,CONCAT(d.honor,' ',d.first_name,' ',d.last_name) doctor_name,email,d.gender,d.profile_pic,sp.id spec_id,sp.name specialization,GROUP_CONCAT(l.id) AS lang_id,GROUP_CONCAT(l.name) AS languages FROM doctor d
                        LEFT JOIN speciality sp ON sp.id=d.specialization
                        LEFT JOIN doctor_languages dl ON d.id=dl.doctor_id
                        LEFT JOIN languages l ON dl.lang_id = l.id
                        WHERE d.id = $data->id
                        GROUP BY d.id;");
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
            DB::INSERT("UPDATE doctor SET honor='$data[honor]',first_name='$data[first_name]',last_name='$data[last_name]',email='$data[email]',gender='$data[gender]',specialization='$data[specialization]' WHERE id='$data[docId]';");
            
            DB::INSERT("DELETE FROM duty_slab WHERE doc_id='$data[docId]';");
            foreach ($data['available_days'] as $key => $value) {
                DB::INSERT("INSERT INTO duty_slab (doc_id,working_days,start_time,end_time,duration) VALUES ('$docId','$value','$data[start]','$data[end]','$data[duration]');");
            }

            DB::INSERT("DELETE FROM doctor_languages WHERE doctor_id='$data[docId]';");
            foreach ($data['languages'] as $key => $value) {
                DB::INSERT("INSERT INTO doctor_languages (doctor_id,lang_id) VALUES ('$docId','$value');");
            }

            DB::commit();
            return $docId;
        } catch (\Exception $e) {
            return DB::rollback();
        }
    }

    public function getAdminProfile($data){
        return DB::select("SELECT id,name,email FROM users WHERE id = '$data->id';");
    }
}
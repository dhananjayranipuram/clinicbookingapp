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
        return DB::select("SELECT ap.id appointment_id,CONCAT(eu.first_name,' ',eu.last_name) patient_name,eu.mobile patient_mobile,DATE_FORMAT(ap.book_date, '%d-%b-%Y') book_date,LEFT(ap.book_time,11) book_time,sp.name 'speciclity',CONCAT(dc.honor,' ',dc.first_name,' ',dc.last_name) 'doctor_name' FROM appointments ap
                        LEFT JOIN doctor dc ON dc.id=ap.doc_id
                        LEFT JOIN enduser eu ON eu.id=ap.enduser_id 
                        LEFT JOIN speciality sp ON sp.id=dc.specialization
                        WHERE dc.active=1 AND ap.status > '-1' $condition
                        ORDER BY ap.book_date ASC;");
    }

    public function getAppointmentDataDetailed($data){
        
        return DB::select("SELECT ap.id appointment_id,CONCAT(eu.first_name,' ',eu.last_name) patient_name,eu.mobile patient_mobile,DATE_FORMAT(ap.book_date, '%Y-%m-%d') book_date,LEFT(ap.book_time,11) book_time,sp.name 'speciclity',dc.specialization 'spec_id',dc.id 'doc_id',CONCAT(dc.honor,' ',dc.first_name,' ',dc.last_name) 'doctor_name' FROM appointments ap
                        LEFT JOIN doctor dc ON dc.id=ap.doc_id
                        LEFT JOIN enduser eu ON eu.id=ap.enduser_id 
                        LEFT JOIN speciality sp ON sp.id=dc.specialization
                        WHERE dc.active=1 AND ap.status > '-1' AND ap.id='$data->id';");
    }

    public function getLatestAppointmentData($data){
        return DB::select("SELECT ap.id appointment_id,CONCAT(eu.first_name,' ',eu.last_name) patient_name,eu.mobile patient_mobile,DATE_FORMAT(ap.book_date, '%d-%b-%Y') book_date,LEFT(ap.book_time,11) book_time FROM appointments ap
                        LEFT JOIN doctor dc ON dc.id=ap.doc_id
                        LEFT JOIN enduser eu ON eu.id=ap.enduser_id
                        WHERE (ap.book_date between '$data[from]' and '$data[to]') AND ap.status > '-1'
                        ORDER BY ap.book_date ASC
                        LIMIT 5;");
    }

    public function getDocWiseAppointmentData($data){
        return DB::select("SELECT d.id,COUNT(a.doc_id) 'value',CONCAT(d.honor,d.first_name,' ',d.last_name) 'name' FROM doctor d
                        LEFT JOIN appointments a ON d.id=a.doc_id
                        WHERE (a.book_date between '$data[from]' and '$data[to]') AND a.status > '-1'
                        GROUP BY d.id;");
    }

    public function getBookingData($data){
        return DB::select("SELECT 'Today' AS 'label',COUNT(id) cnt FROM appointments a
                        WHERE (a.book_date between '$data[from]' and '$data[to]') AND a.status > '-1'

                        UNION

                        SELECT 'Yesterday' AS 'label',COUNT(id) cnt FROM appointments a
                        WHERE (a.book_date between '$data[prev_from]' and '$data[prev_to]') AND a.status > '-1';");
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
        LEFT JOIN speciality sp ON sp.id=d.specialization
        WHERE d.deleted=0;");
    }

    public function getSpecializationList(){
        return DB::select("SELECT id,name FROM speciality WHERE active=1;");
    }

    public function getLanguages(){
        return DB::select("SELECT id,name FROM languages WHERE active=1;");
    }

    public function saveDoctorData($data){
        DB::beginTransaction();
        try {
            DB::INSERT("INSERT INTO doctor (honor,first_name,last_name,email,password,gender,specialization,profile_pic) VALUES ('$data[honor]','$data[first_name]','$data[last_name]','$data[email]','$data[password]','$data[gender]','$data[specialization]','$data[profile_pic]');");
            $docId = DB::getPdo()->lastInsertId();
            foreach ($data['available_days'] as $key => $value) {
                if($value!=""){
                    DB::INSERT("INSERT INTO duty_slab (doc_id,working_days,start_time,end_time,duration) VALUES ('$docId','$value','$data[start]','$data[end]','$data[duration]');");
                }
            }
            foreach ($data['languages'] as $key => $value) {
                if($value!=""){
                    DB::INSERT("INSERT INTO doctor_languages (doctor_id,lang_id) VALUES ('$docId','$value');");
                }
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

    public function deleteDoctorData($data){
        return DB::UPDATE("UPDATE doctor SET deleted='1' WHERE id='$data[docId]';");
    }

    public function deleteAppointment($data){
        return DB::UPDATE("UPDATE appointments SET status='-1' WHERE id='$data[id]';");
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
                        WHERE d.id = $data->id AND d.deleted=0
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
                        TIME_FORMAT(start_time,'%h:%i %p') start_time_label,TIME_FORMAT(end_time,'%h:%i %p') end_time_label,end_time,start_time,TIME_FORMAT(duration,'%h:%i') 'duration' FROM duty_slab WHERE doc_id='$data->id' ORDER BY working_days;");
                                
    }

    public function updateDoctorData($data){
        DB::beginTransaction();
        $docId = $data['docId'];
        try {
            DB::INSERT("UPDATE doctor SET honor='$data[honor]',first_name='$data[first_name]',last_name='$data[last_name]',email='$data[email]',gender='$data[gender]',specialization='$data[specialization]' WHERE id='$data[docId]';");
            
            DB::INSERT("DELETE FROM duty_slab WHERE doc_id='$data[docId]';");
            foreach ($data['available_days'] as $key => $value) {
                if($value!=""){
                    DB::INSERT("INSERT INTO duty_slab (doc_id,working_days,start_time,end_time,duration) VALUES ('$docId','$value','$data[start]','$data[end]','$data[duration]');");
                }
            }

            DB::INSERT("DELETE FROM doctor_languages WHERE doctor_id='$data[docId]';");
            foreach ($data['languages'] as $key => $value) {
                if($value!=""){
                    DB::INSERT("INSERT INTO doctor_languages (doctor_id,lang_id) VALUES ('$docId','$value');");
                }
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

    public function getAvailableDocs($data){
        $condition = '';
        if(!empty($data['docId'])){
            $condition .= " AND doc.id = $data[docId]";
        }
        if(!empty($data['specId'])){
            $condition .= " AND doc.specialization = $data[specId]";
        }
        $dayofweek = date('w', strtotime($data['date']));
        return DB::select("SELECT doc.id,CONCAT(doc.honor,' ',doc.first_name,' ',doc.last_name) 'name',profile_pic FROM duty_slab slab
            LEFT JOIN doctor doc ON slab.doc_id=doc.id
            WHERE slab.working_days=$dayofweek AND doc.deleted=0 AND doc.active=1 $condition");
    }

    public function getAvailableSlots($data){
        if(isset($data['docId'])){
            return DB::select("SELECT doc_id,start_time,end_time,duration FROM duty_slab WHERE doc_id='$data[docId]' AND working_days='$data[day]';");
        }else{
            return DB::select("SELECT doc_id,start_time,end_time,duration FROM duty_slab;");
        }
    }

    public function getDocAppointments($data){
        if(isset($data['docId'])){
            return DB::select("SELECT book_time,'Booked' as 'status',id FROM appointments WHERE doc_id='$data[docId]' AND book_date='$data[date]' AND status > '-1'
                                UNION
                                SELECT book_time,'Not Available' as 'status',id FROM slot_not_available WHERE doc_id='$data[docId]' AND book_date='$data[date]' AND status>-1;");
        }else{
            return DB::select("SELECT doc_id,book_time,'Booked' as 'status',id FROM appointments WHERE book_date='$data[date]' AND status > '-1'
                                UNION
                                SELECT doc_id,book_time,'Not Available' as 'status',id FROM slot_not_available WHERE book_date='$data[date]'  AND status>-1;");
        }
    }
    public function saveSlotNotAvailable($data){
        DB::INSERT("INSERT INTO slot_not_available (doc_id,book_date,book_time) VALUES ('$data[docId]','$data[date]','$data[time]');");
        return DB::getPdo()->lastInsertId();
    }

    public function enableSlotData($data){
        DB::INSERT("UPDATE slot_not_available sl SET sl.status='-1' WHERE sl.id='$data[id]';");
        return DB::select("SELECT doc_id,book_date,book_time FROM slot_not_available WHERE id='$data[id]';");
    }

    public function saveUserAppointment($data){
        $res = DB::select("SELECT * FROM appointments WHERE doc_id='$data[docId]' AND book_date='$data[date]' AND book_time='$data[time]' AND status > '-1';");
        if(empty($res)){
            DB::INSERT("INSERT INTO appointments (doc_id,enduser_id,book_date,book_time) VALUES ('$data[docId]','$data[userId]','$data[date]','$data[time]');");
            return DB::getPdo()->lastInsertId();
        }else{
            return 'Slot not available.';
        }
    }

    public function updateAppointmentData($data){
        return DB::UPDATE("UPDATE appointments SET doc_id='$data[docId]',book_date='$data[appDate]',book_time='$data[timeSlot]' WHERE id='$data[appId]';");
    }

    public function getUserDataFilter($data){
        $condition = '';
        if(!empty($data['emailAddress'])){
            $condition = "WHERE email = '$data[emailAddress]'";
        }
        if(!empty($data['mobile'])){
            $condition = "WHERE mobile = '$data[mobile]'";
        }
        return DB::select("SELECT first_name,last_name,mobile,gender,dob,email FROM enduser $condition;");
    }
}
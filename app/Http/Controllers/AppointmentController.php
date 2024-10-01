<?php

namespace App\Http\Controllers;

use App\Models\AddSelectDoctor;
use App\Models\Booking;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
        // appointment section
        public function appointment() {
            $user=Auth::user();
            $addSelect=AddSelectDoctor::get();
            return view('main-site.pages.appointment',['select'=>$addSelect],compact('user'));
        }
        public function appointmentData (Request $req){
            $req->validate([
                'doctor'=>'required',
                'name'=>'required',
                'email'=>'required|email:rfc,dns',
                'phone'=>'required',
                'sex'=>'required|in:Male,female,custome',
                'date'=>'required',
                'time'=>'required',
            ],
            [
    
             'name.required'=>'Tyep your name.',
    
             'email.required'=>'Tyep your email.',
             'email.email'=>'Your email in-invalid.',
    
             'phone.required'=>'Tyep your phone.',
    
             'sex.required' => 'Selectet the sex.',
             'sex.in' => 'Selectet the sex.',
             
             'date.required'=>'Tyep your date.',
             'time.required'=>'Tyep your time.',
            ]);
                $data=[
                    'user_id'=>Auth::user()->id,
                    'doctor'=>$req->doctor,
                    'name'=>$req->name,
                    'email'=>$req->email,
                    'phone'=>$req->phone,
                    'sex'=>$req->sex,
                    'date'=>$req->date,
                    'time'=>$req->time,
                ];
            
                Booking::create($data);
        dd($data);
            //  return redirect()->route('user');
        }
           public function appointmentTable(){
            $user=Auth::user();
            $bookingData=Booking::get();
            return view('admin-site.pages.table.appointmentTable', ['appointment'=>$bookingData],compact('user'));
        }
        public function addSelectDoctorName(){
            $user=Auth::user();
            return view('admin-site.pages.from.AddSelectDoctor', compact('user'));
        }
        public function addSelectDoctorPush(Request $req){
            $addSelect=[
             'name'=>$req->name,
            ];
            AddSelectDoctor::create($addSelect);
            return redirect()->back();
        }
}

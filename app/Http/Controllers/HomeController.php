<?php

namespace App\Http\Controllers;

use App\Models\AddSelectDoctor;
use App\Models\DoctorTeam;
use App\Models\Testimonial;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Auth;

class HomeController extends Controller
{
      //main-site-pages section
      public function home() {
        $getService=Service::get();
        $testimonialStor=Testimonial::get();
        $doctorData=DoctorTeam::get();
        $addSelect=AddSelectDoctor::get();
        $user=Auth::user();
        return view('main-site.pages.home',['ServseStor'=>$getService,'doctorStor'=>$doctorData,'testimonialData'=>$testimonialStor,'select'=>$addSelect], compact('user'));
    }

    public function about() {
        $user=Auth::user();
        return view('main-site.pages.about',compact('user'));
    }
    public function testimonial() {
        $testimonialStor=Testimonial::get();
        $user=Auth::user();
        return view('main-site.pages.testimonial',['testimonialData'=>$testimonialStor],compact('user'));
    }
}

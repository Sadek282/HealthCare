<?php

namespace App\Http\Controllers;

use App\Models\Service;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicesController extends Controller
{
        //Services section
        public function service() {
            $getService=Service::get();
            $user=Auth::user();
            return view('main-site.pages.service',['ServseStor'=>$getService],compact('user'));
        }

        public function serviceFrom() {
            $user=Auth::user();
            return view('admin-site.pages.from.addServiceFrom',compact('user'));
        }
        
        public function serviceData(Request $req) {
            $req->validate([
                'image' => 'required',
                'servesTitle' => 'required',
                'description' => 'required',
            ],
            [
            'servesTitle.required' => 'Enter the Serves Title.',
            'description.required' => 'Enter your Description.',
            ]);
    
            if(! is_dir(public_path('admin-site/img/new'))){
              mkdir(public_path('admin-site/img/new'), 0777, true);
            }
    
            $serviceData=[
                'servesTitle'=>$req->servesTitle,
                'description'=>$req->description,
            ];
    
            if ($req->hasFile('image')) {
                $image = $req->file('image');
                $name = $image->getClientOriginalName();
                $imageName = time() . '_' . $name;
            
                $image->move(public_path('admin-site/img/new'), $imageName);
            
                $serviceData['image'] = 'admin-site/img/new/' . $imageName;
            } 
            Service::create($serviceData);
            Alert::success('Success', 'Your data was sent successfully.');
            return redirect()->back();
        }
        public function serviceDataEdit($id){
            $dataService=Service::where(['id'=>$id])->first();
            $user=Auth::user();
            return view('admin-site.pages.from.serviceFromUpdate',['storingService'=>$dataService],compact('user'));
        }
        public function serviceDataUpdate(Request $req){
    
            if(! is_dir(public_path('admin-site/img/new'))){
              mkdir(public_path('admin-site/img/new'), 0777, true);
            }
    
            $serviceData=[
                'servesTitle'=>$req->servesTitle,
                'description'=>$req->description,
            ];
    
            if ($req->hasFile('image')) {
                $image = $req->file('image');
                $name = $image->getClientOriginalName();
                $imageName = time() . '_' . $name;
            
                $image->move(public_path('admin-site/img/new'), $imageName);
            
                $serviceData['image'] = 'admin-site/img/new/' . $imageName;
            } 
            Service::where(['id'=>$req->id])->update($serviceData);
            return redirect()->route('serviceTable');
        }
        public function serviceTable() {
            $user=Auth::user();
           $getService=Service::get();
            return view('admin-site.pages.table.serviceTable', ['ServseStor'=>$getService],compact('user'));
        }
        public function serviceDatadelete($id){
            $dataService=Service::where(['id'=>$id])->delete();
            return redirect()->back();
        }
}

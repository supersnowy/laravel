<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Breed;
use App\Models\Parrot;
use Auth;
use App\Http\Controllers\Controller;

class ParrotController extends Controller
{
    //
    public function index(){

        $parrots = Auth::user()->parrots->load('breed');
        
        return view('admin.parrot.index')->with('parrots',$parrots);
    }
    public function create(){

        // $str = 'GTCAG';
        // //check length
        // if (!(1 <= strlen($str) && (strlen($str)<=1000)))
        //     return;
        // //check valid characters
        // $complement_map = ['G'=>'C','C'=>'G','T'=>'A','A'=>'T'];
        
        // $rev_str = strrev($str);
        // $arr_char = str_split($rev_str);
        // $ret_val = "";
        // foreach ($arr_char as $key => $val) {
        //     $ret_val = $ret_val . $complement_map[$val];
        // }
        // dd($ret_val);
        // dd(request()->route()->getPrefix());
       
        $breeds = Breed::all();
        return view('admin.parrot.create')->with('breeds',$breeds);
    }

    public function destroy($id){
        
        $parrot = Parrot::find($id);
       
        if (is_null($parrot)){
            return "failed";
        }
        $parrot->delete();
        return "ok";
    }
    //
    public function show($id){
        $breeds = Breed::all();
        $parrot = Parrot::findOrFail($id);
        return view('admin.parrot.show')->with('current_parrot',$parrot)
                                    ->with('breeds',$breeds);    
    }

    public function edit($id){
        $breeds = Breed::all();
        $parrot = Parrot::findOrFail($id);
        //store couple
        // $is_couple = $parrot->male_couple || $parrot->female_couple;

        return view('admin.parrot.edit')->with('current_parrot',$parrot)
                                    ->with('breeds',$breeds);
                                    
    }
    public function store(Request $request){
        
        
        if (isset($request->id)){ //edit
            $parrot = Parrot::findOrFail($request->id);
            
        }
        else{
            $parrot = new Parrot();
            $parrot->parrot_id = strtoupper(uniqid()) . date('y');
        }
            $request->validate([
            'name'=>'required',
            'color' => 'required', 
            // 'date_of_birth' => 'required',
            
            ]);
        
        if($request->hasFile('profileImage'))
        {
            @unlink("uploads/parrots/".$parrot->photo);
            $file= $_FILES['profileImage'];
            $fileName = $_FILES['profileImage']['name'];
            $fileTmp = $_FILES['profileImage']['tmp_name'];
            $fileSize = $_FILES['profileImage']['size'];
            $filesError = $_FILES['profileImage']['error'];
            $fileType = $_FILES['profileImage']['type'];
            
            $fileExt = explode('.',$_FILES['profileImage']['name']);
            $fileActualExt = strtolower(end($fileExt));
            $allowed = array('jpg','jpeg','png');
            
            if(in_array($fileActualExt,$allowed)){
                if($_FILES['profileImage']['error'] ===  0){
                    if($_FILES['profileImage']['size'] < 2000000){
                                   
                        $fileNameNew = time() .".".$fileActualExt;
                        @mkdir("uploads/parrots",0777);
                        $fileDestination = 'uploads/parrots/'.$fileNameNew;
                        $parrot->photo = $fileNameNew;
                        move_uploaded_file($_FILES['profileImage']['tmp_name'],$fileDestination);
                      
                    }else{
                        return redirect()->back()->withErrors(['profileImage' => [trans('parrot.size_limit_2mb')]]);
                    }
                }else{
                    echo "You have an error uploading photo file!";
                }
            }else{
                return redirect()->back()->withErrors(['profileImage' => [trans('parrot.allow_png_jpg')]]);
            }
        }
        
        $parrot->name = $request->name;
        $parrot->date_of_birth = $request->date_of_birth;

        if ($request->rna!='')
            $parrot->RNA = strtoupper($request->rna);
 
        $parrot->color = $request->color;
        $parrot->breed_id = $request->breed;

        $is_couple = $parrot->male_couple || $parrot->female_couple;
        if (!$is_couple)
            $parrot->gender = $request->gender;
        $parrot->registered_by = Auth::user()->id;
        $parrot->save();
        
        if (isset($request->id)){ //edit
            return redirect()->route('parrot.index')->withSuccess(trans('parrot.updated_success'));
        }
        return redirect()->route('parrot.show',$parrot->id)->withSuccess(trans('parrot.success_to_add_parrot'));

    }
}

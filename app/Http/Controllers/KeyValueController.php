<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\KeyVal;

use DB;

use Carbon\Carbon;
use Illuminate\Database\QueryException;

class KeyValueController extends Controller
{
    
    public function index()
    {

        $now = Carbon::now();
        $data = [];
        $res = array();

        if (isset($_GET['keys'])) {
            $keys = explode(",", $_GET["keys"]);
            DB::table('key_vals')->whereIn('key',$keys)->update(array('updated_at' => $now));
            $data =  KeyVal::whereIn('key',$keys)->get();
        } else {

            $data =  KeyVal::all();
            DB::table('key_vals')->update(array('updated_at' => $now));
        }


        foreach ($data  as $key => $value) {
            $res = array_merge($res,$value->key_val);
        }

        
        return response()->json($res,200);  
      
            
    }


   
    public function store(Request $request)
    {

        try{
            $data = $request->all();
            $res =  array();
            foreach ($data as $key => $value) {
                $key_val = array($key=>$value);
                KeyVal::create(['key'=>$key,'key_val'=>$key_val]);
                $res = array_merge($res,$key_val);
             }

             if(count($res)>0){
                return response()->json($res,201);
             }
        }
        
        catch (QueryException $e) {
            return response()->json(['error'=>''.$e->errorInfo[2]],409);
        }
         

    }

    
    public function update(Request $request)
    {
         $data = $request->all();
         $now = Carbon::now();
         $res =  array();
         foreach ($data as $key => $value) {
            $key_val = array($key=>$value);
            $old_data = KeyVal::where('key',$key)->get();
            if(count($old_data) == 0){
               return response()->json(['error'=>$key.' not found. Please update value with valid key'],404); 
            }
            $id = $old_data[0]->id;
            $key_value = KeyVal::find($id);
            $key_value->key_val = $key_val; 
            if($key_value->update()){
                $res = array_merge($res,$key_val);
                DB::table('key_vals')->where('key',$key)->update(['updated_at' => $now]);
            }
            
         }

        if(count($res)>0){
            return response()->json($res,200);
        }  
    }

    
}

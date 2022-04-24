<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\JenisKomoditas;
use Validator;
use Exception;
use Illuminate\Support\Facades\Storage;

class KomoditasController extends Controller
{
    //
    public function create(Request $request){
        $id = auth()->id();
        $user = User::find($id);

        if($user["role_id"] == 2){
            $admin = Admin::where('user_id', $user["id"])->first();
            $validated = Validator::make(
                $request->all(),
                [
                    'nama_komoditas' => 'required|unique:jenis_komoditas',
                ],
                [
                    'nama_komoditas.required' => 'name is needed',
                    'nama_komoditas.unique' => 'name is already created',
                ]);

            if($validated->fails()) {          
                return response()->json(['error'=>$validated->errors()], 400);                        
            } 
            
            $fileName = null;
            if($request->file('foto')){
                $fileName = $request->file('foto')->store('komoditas');
            }
            $komoditas = $admin->komoditas()->firstOrCreate([
                'nama_komoditas' => $request->input('nama_komoditas'),
                'foto' => $fileName
            ]);

            return response()->json([
                'message' => 'komoditas ' . $request->input('nama_komoditas') . ' successfully added',
                'success' => true
            ], 201); 
            
        }

        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ]);
    }

    public function update(Request $request, $komoditas_id){
        $id = auth()->id();
        $user = User::find($id);
        
        if($user["role_id"] == 2){
            try {
                $validated = Validator::make(
                    $request->all(),
                    [
                        'nama_komoditas' => 'required',
                    ],
                    [
                        'nama_komoditas.required' => 'name is needed',
                    ]);

                if($validated->fails()) {          
                    return response()->json(['error'=>$validated->errors()], 400);                        
                } 
                
                $updatedKomoditas = JenisKomoditas::where('id', $komoditas_id)->firstOrFail();
                
                $fileName = null;
                if($request->file('foto')){
                    if($updatedKomoditas["foto"]){
                        Storage::delete($updatedKomoditas["foto"]);
                    }
                    $fileName = $request->file('foto')->store('komoditas');
                }

                $updatedKomoditas->update([
                    'nama_komoditas' => $request->input('nama_komoditas'),
                    'foto' => $fileName
                ]);

                return response()->json([
                    'message' => 'komoditas ' . $request->input('nama_komoditas') . ' successfully updated',
                    'success' => true
                ], 200); 
            } catch (Exception $e) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'success' => false
                ]);
            }
        }

        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ]);
    }

    public function delete($komoditas_id){
        $id = auth()->id();
        $user = User::find($id);
        
        if($user["role_id"] == 2){
            try {

                $komoditas = JenisKomoditas::findOrFail($komoditas_id);
                if($komoditas["foto"]){
                    Storage::delete($komoditas["foto"]);
                }
                $komoditas->delete();
                
                return response()->json([
                    'message' => 'komoditas ' . $komoditas["nama_komoditas"] . ' successfully deleted',
                    'success' => true
                ], 200); 
            } catch (Exception $e) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'success' => false
                ], 404); 
            }
        }      
        return response()->json([
            'message' => 'Unauthorized, make sure using admin account'
        ]);
    }

    public function getJenisKomoditas(){
        $komoditas = JenisKomoditas::all();

        return response()->json([
            "success" => true,
            "count" => count($komoditas),
            "data" => $komoditas
        ], 200);
    }

    public function getJenisKomoditasById($komoditas_id){
        try {
            $komoditas = JenisKomoditas::findOrFail($komoditas_id);

            return response()->json([
                "success" => true,
                "data" => $komoditas
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "success" => true,
                "message" => $komoditas_id . ' is invalid id'
            ], 404);
        }
    }
}

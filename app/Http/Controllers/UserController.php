<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Alamat;
use App\Models\DaftarMemberBaru;
use App\Models\District;
use App\Models\ManagerKebun;
use App\Models\Role;
use App\Models\Regency;
use App\Models\Province;
use Error;
use Illuminate\Database\Capsule\Manager;

class UserController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
            'status' => 200
        ];


        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [])
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, 404);
    }

    function listKota()
    {
        $kota = Regency::with('province')->get();

        return response()->json(
            [
                'kota' => $kota
            ]
        );
    }

    //create manager kebun
    function createManager(Request $request)
    {
        if (User::where('email', '=', $request->input('email'))->exists()) {
            return response()->json(
                [
                    "message" => "failed, email exist",
                    "status" => "400"
                ],
                400
            );
        } else {
            $alamat = Alamat::create([
                'alamat' => $request->input('alamat'),
                'regencies_id' => $request->input('regencies_id')
            ]);

            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => '1'
            ]);

            $dataManager = $user->managerKebun()->create([
                'nama' => $request->nama,
                'email' => $request->email,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'alamat_id' => $alamat['id']
            ]);
            $dataManager['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(
                [
                    "message" => "success create user",
                    "status" => 200,
                    "user" => $dataManager,
                ],
                200
            );
        }
    }

    public function dataManager($id)
    {
        // $user = User::findOrFail($id);
        $managerKebun = ManagerKebun::with('alamat', 'alamat.regency', 'alamat.regency.province')->where('id', $id)->first();
        //$user = ManagerKebun::with('alamat')->find($id);
        //with('managerKebun.alamat')->findOrFail($id);

        return response()->json(
            [
                "user" => $managerKebun,
            ]
        );
    }

    public function updateManager(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->input('nama'),
        ]);

        $managerKebun = ManagerKebun::where('user_id', '=', $id)->first();
        $managerKebun->update([
            'nama' => $request->input('nama'),
            'jenis_kelamin' => $request->input('jenis_kelamin'),
            'no_hp' => $request->input('no_hp')
        ]);
        return $this->sendResponse($managerKebun, 'Manager Kebun updated successfully.');
    }

    public function deleteManager($id)
    {
        $user = User::with('managerKebun.alamat')->findOrFail($id);
        $user->delete();

        return $this->sendResponse([], 'User deleted successfully.');
    }

    function allManagerKebun()
    {
        $manager_kebun = ManagerKebun::with('alamat', 'alamat.regency', 'alamat.regency.province')->get();
        $jumlah_manager = $manager_kebun->count();

        // $res = [];
        // foreach ($manager_kebun as $manager) {
        //     $alamat_id = $manager->alamat_id;
        //     $alamat = Alamat::find($alamat_id);

        //     $regencies_id = $alamat->regencies_id;
        //     $regency = Regency::find($regencies_id);

        //     $provice_id = $regency->province_id;
        //     $province =  Province::find($provice_id);

        //     $alamat_jalan = $alamat->alamat;
        //     $alamat_kota = $regency->name;
        //     $alamat_provinsi = $province->name;
        //     $alamat_lengkap = "$alamat_jalan , $alamat_kota, $alamat_provinsi";

        //     array_push($res, [
        //         'id' => $manager->id,
        //         'nama' => $manager->nama,       //get nama from id
        //         'no_hp' => $manager->no_hp,
        //         'alamat' => $alamat_lengkap

        //     ]);
        // }

        return response()->json([
            'jumlah_manager' => $jumlah_manager,
            'daftar_manager_kebun' => $manager_kebun
        ]);
    }

    //create admin
    function createAdmin(Request $request)
    {

        if (User::where('email', '=', $request->input('email'))->exists()) {
            return response()->json(
                [
                    "message" => "failed, email exist",
                    "status" => 400
                ],
                400
            );
        } else {
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => '2'
            ]);

            $admin = $user->admin()->create([
                'nama' => $request->nama,

            ]);

            $user['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(
                [
                    "message" => "success create admin",
                    "status" => 200,
                    "user" => $user,

                ],
                200
            );
        }
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['name'] =  $user->name;
            $success['role'] = $user->role->nama_role;

            return $this->sendResponse($success, 'Login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function getUser()
    {
        if (Auth::user()) {
            $user =  Auth::user();
            $user = User::where('id', '=', $user->id)->with('managerKebun', 'admin')->first();
            //with('managerKebun.alamat')->findOrFail($id);

            return response()->json(
                [
                    "user" => $user
                ]
            );
        }
    }

    function daftarMember(Request $request)
    {
        if (User::where('email', '=', $request->input('email'))->exists()) {
            return response()->json(
                [
                    "message" => "failed, email exist",
                    "status" => "400"
                ],
                400
            );
        } else {
            $alamat = Alamat::create([
                'alamat' => $request->input('alamat'),
                'regencies_id' => $request->input('regencies_id')
            ]);

            $daftarMember = DaftarMemberBaru::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'alamat_id' => $alamat['id']
            ]);

            return response()->json(
                [
                    "message" => "Register Success",
                    "status" => 200,
                    "user" => $daftarMember,
                ],
                200
            );
        }
    }

    function memberTerdaftar()
    {
        $member = DaftarMemberBaru::with('alamat', 'alamat.regency', 'alamat.regency.province')->get();
        $jumlah_member = $member->count();


        return response()->json([
            'jumlah_member_terdaftar' => $jumlah_member,
            'daftar_member_terdaftar' => $member
        ]);
    }

    public function prosesMemberBaru(Request $request, $id)
    {
        $member = DaftarMemberBaru::findOrFail($id);
        $member->update([
            'status' => 'sudah diproses',
        ]);

        $memberBaru = DaftarMemberBaru::where('id', '=', $id)->first();
        $user = User::create([
            'name' => $memberBaru->nama,
            'email' => $memberBaru->email,
            'password' => bcrypt('12345678'),
            'role_id' => '1'
        ]);

        $dataMember = $user->managerKebun()->create([
            'nama' => $memberBaru->nama,
            'email' => $memberBaru->email,
            'jenis_kelamin' => $memberBaru->jenis_kelamin,
            'no_hp' => $memberBaru->no_hp,
            'alamat_id' => $memberBaru->alamat_id
        ]);
        $dataMember['token'] =  $user->createToken('MyApp')->accessToken;
        return response()->json(
            [
                "message" => "success add new member",
                "status" => 200,
                "user" => $dataMember,
                "password" => '12345678',
            ],
            200
        );
    }
}

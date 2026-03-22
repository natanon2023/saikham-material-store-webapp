<?php

namespace App\Http\Controllers\Admin;

use App;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;
use App\Actions\Fortify\PasswordValidationRules;
use App\Models\UserProfile;
use App\Models\ThaiProvince;
use App\Models\ThaiTambon;
use App\Models\ThaiAmphure;


class UserController extends Controller
{
    use PasswordValidationRules;


    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->get();

        return view('admin.users.index', compact('users'));
    }


    public function create()
    {
        $amphures = ThaiAmphure::all();
        $provinces = ThaiProvince::all();
        return view('admin.users.create', compact('provinces', 'amphures'));
    }


    public function store(Request $request)
    {
        

        try {
            $user = User::create([
                'name'         => $request->name,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'role'         => $request->role,
                'last_name'    => $request->last_name,
                'nickname'     => $request->nickname,
                'phone_number' => $request->phone_number,
            ]);

            UserProfile::create([
                'user_id'      => $user->id,
                'house_number' => $request->house_number,
                'moo'          => $request->moo,
                'alley'        => $request->alley,
                'road'         => $request->road,
                'village'      => $request->village,
                'province_id'  => $request->province_id,
                'amphure_id'   => $request->amphure_id,
                'tambon_id'    => $request->tambon_id,
                'birth_date'   => $request->birth_date,
            ]);

            return redirect()->route('admin.users.index')->with('success', 'เพิ่มผู้ใช้สำเร็จแล้ว');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function getAmphures($province_id)
    {
        $amphures = ThaiAmphure::where('province_id', $province_id)->get();
        return response()->json($amphures);
    }

    public function getTambons($amphure_id)
    {
        $tambons = ThaiTambon::where('amphure_id', $amphure_id)->get();
        return response()->json($tambons);
    }



    public function edit($id)
    {
        $user = User::with(['profile.province', 'profile.amphure', 'profile.tambon'])->find($id);
        $provinces = ThaiProvince::with(['amphures.tambons'])->get();
        $amphures = ThaiAmphure::with('tambons')->get();
        return view('admin.users.edit', compact('user', 'provinces', 'amphures'));
    }


    public function update(Request $request, $id)
    {
        $user = User::with('profile')->find($id);

    

        $userData = [
            'name'         => $request->name,
            'email'        => $request->email,
            'role'         => $request->role,
            'last_name'    => $request->last_name,
            'nickname'     => $request->nickname,
            'phone_number' => $request->phone_number,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        if (!$user->profile) {
            UserProfile::create([
                'user_id'      => $user->id,
                'house_number' => $request->house_number,
                'moo'          => $request->moo,
                'road'         => $request->road,
                'village'      => $request->village,
                'alley'        => $request->alley,
                'province_id'  => $request->province_id,
                'amphure_id'   => $request->amphure_id,
                'tambon_id'    => $request->tambon_id,
                'birth_date'   => $request->birth_date,
            ]);
        } else {
            $user->profile->update([
                'house_number' => $request->house_number,
                'moo'          => $request->moo,
                'road'         => $request->road,
                'village'      => $request->village,
                'alley'        => $request->alley,
                'province_id'  => $request->province_id,
                'amphure_id'   => $request->amphure_id,
                'tambon_id'    => $request->tambon_id,
                'birth_date'   => $request->birth_date,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'แก้ไขข้อมูลผู้ใช้สำเร็จแล้ว');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('admin.users.index')->with('success', 'ลบผู้ใช้สำเร็จ');
    }


    public function trash()
    {
        $users = User::onlyTrashed()->get();
        return view('admin.users.trash', compact('users'));
    }


    public function restore($id)
    {
        User::onlyTrashed()->find($id)->restore();
        return redirect()->route('admin.users.index')->with('success', 'กู้คืนผู้ใช้สำเร็จ');
    }

    public function show($id)
    {
        $users = User::with([
            'profile.province',
            'profile.amphure',
            'profile.tambon'
        ])->find($id);

        return view('admin.users.show', compact('users'));
    }
}

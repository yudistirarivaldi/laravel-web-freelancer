<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Dashboard\Profile\UpdateProfileRequest;
use App\Http\Requests\Dashboard\Profile\UpdateDetailUserRequest;

// Buat nyimpan foto
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

use File;
use Auth;

use App\Models\User;
use App\Models\DetailUser;
use App\Models\ExperienceUser;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        // Mengambil data user yang lagi login dan hanya satu yang dipanggil ya itu satu user yang hanya login
        $user = User::where('id', Auth::user()->id)->first();

        // Karena sudah di buat relasi jadi apabila ingin mengambil detail user id harus lewat $user
        $experience_user = ExperienceUser::where('detail_user_id', $user->detail_user->id)->orderBy('id', 'asc')->get();
        return view('pages.dashboard.profile', compact('user', 'experience_user'));

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request_profile, UpdateDetailUserRequest $request_detail_user)
    {
        // data_profile isi nya adalah data user
        $data_profile = $request_profile->all();
        // data_detail_user isinya adalah data detail user
        $data_detail_user = $request_detail_user->all();

        // ngambil foto atau untuk input foto
        $get_photo = DetailUser::where('users_id', Auth::user()->id)->first();


        // penghapus foto sebelumnya untuk digantikan foto baru. harus di beri if isset ngecek apakah foto nya ada
        if(isset($data_detail_user['photo'])){
            // validasi tambahan untuk mengecek file apakah ada di storage
            $data = 'storage/'.$get_photo['photo'];

            // kalo file lama nya ada maka akan di hapus
            if(File::exists($data)){
                File::delete($data);
            }else{
                // Penghapusan file lebih detail/untuk path yang tidak terbaca
                File::delete('storage/app/public/'.$get_photo['photo']);
            }
        }

        // Store/nyimpan ke storage apabila file nya ada akan di store jika tidak ada biarkan saja
        if(isset($data_detail_user['photo'])){
            $data_detail_user['photo'] = $request_detail_user->file('photo')->store('assets/photo', 'public');
        }

        // Proses save user
        $user = User::find(Auth::user()->id);
        $user->update($data_profile);

        // Proses save detail user
        $detail_user = DetailUser::find($user->detail_user->id);
        $detail_user->update($data_detail_user);

        // proses save experience user
        $experience_user_id = ExperienceUser::where('detail_user_id', $detail_user['id'])->first();

        // Ini untuk data lama
        if (isset($experience_user_id)) {
            foreach ($data_profile['experience'] as $key => $value) {
                $experience_user = ExperienceUser::find($key);
                $experience_user->detail_user_id = $detail_user['id'];
                $experience_user->experience = $value;
                $experience_user->save();
            }

            // Ini untuk dat abaru
        } else {
            foreach ($data_profile['experience'] as $key => $value) {
                if(isset($value)){
                $experience_user = new ExperienceUser;
                $experience_user->detail_user_id = $detail_user['id'];
                $experience_user->experience = $value;
                $experience_user->save();
                }
        }
    }

    // Cara menggunakan sweetalert pada laravel
    toast()->success('Update has been success');
    return back();

}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return abort(404);
    }

    public function delete()
    {
        // Menghapus photo yang ada
        $get_user_photo = DetailUser::where('users_id', Auth::user()->id)->first();
        $path_photo = $get_user_photo['photo'];

        // update dulu ke null baru storage nya di hapus
        $data = DetailUser::find($get_user_photo['id']);
        $data->photo = NULL;
        $data->save();

        // delete storage
        $data = 'storage/'.$path_photo;
        if(File::exists($data)){
            File::delete($data);
        }else{
            File::delete('storage/app/public/'.$path_photo);
        }

        toast()->success('Delete has been success');
        return back();

    }
}

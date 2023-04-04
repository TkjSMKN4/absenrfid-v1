<?php

namespace App\Http\Controllers;

use App\Models\APIModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\UsersModel;
use App\Models\MuridModel;
use App\Models\GuruModel;
use App\Models\MapelModel;
use App\Models\KelasModel;

class AddDataController extends Controller
{
    //

    public function addUser(Request $request){
        info('controller AddDataController addUser ----------');

        $validated = $request->validate([
            'nama' => 'required',
            'level' => 'required',
            'username' => 'required|unique:tb_users,username,NULL',
            'password' => 'required',
        ]);

        $data = $request->except('_token');

        UsersModel::insert($data);

        return redirect('/data_users');
    }

    public function addMurid(Request $request){
        info('controller AddDataController addMurid ----------');

        $validated = $request->validate([
            'nama' => 'required',
            'id_kelas' => 'required|exists:tb_kelas,id_kelas'
        ]);

        $data = $request->except('_token');

        $data['id_kartu'] = '';

        MuridModel::insert($data);

        return response(json_encode(['true', 'Data Berhasil Ditambahkan']));
    }

    public function addKelas(Request $request){
        info('controller AddDataController addKelas ----------');

        $validated = $request->validate([
            'kelas' => 'required',
            'guru' => 'required|exists:tb_guru,nama'
        ]);

        $kelas = $request->input('kelas');
        $guru = $request->input('guru');
        $id_guru = GuruModel::where('nama', $guru)->get('id_guru');

        KelasModel::insert(['kelas' => $kelas, 'id_guru' => $id_guru[0]->id_guru]);

        return back()->with('success', 'Data Berhasil Ditambahkan');
    }

    public function addGuru(Request $request){
        info('controller AddDataController addGuru ----------');

        $validated = $request->validate([
            'nama' => 'required',
            'mapel' => 'required|exists:tb_mapel,mapel'
        ]);

        $nama = $request->input('nama');
        $mapel = $request->input('mapel');
        $id_mapel = '';

        foreach($mapel as $x){
            $dbMapel = DB::table('tb_mapel')->where('mapel', $x)->get('id_mapel');
            $id_mapel .= $dbMapel[0]->id_mapel . '|';
        }

        DB::table('tb_guru')->insert(['nama' => $nama, 'id_mapel' => $id_mapel]);

        return back()->with('success', 'Data Berhasil Ditambahkan');
    }

    public function addApi(Request $request){
        info('controller AddDataController addApi ----------');

        $validated = $request->validate([
            'nama' => 'required'
        ]);

        $nama = $request->input('nama');
        $token = 'absenrfid-'.Str::random(45);

        APIModel::insert(['token' => $token, 'nama' => $nama, 'counter' => 0]);

        return back()->with('success', 'Data Berhasil Ditambahkan');
    }
}

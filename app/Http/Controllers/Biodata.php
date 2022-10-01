<?php

namespace App\Http\Controllers;

use App\tbl_biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Biodata extends Controller
{
    public function buat(Request $req){
        $this->validate($req, [
            'foto' => 'required|mimes:jpg,jpeg,png|max:2048'
        ]);
        $foto = $req->file('foto');
        $namaFoto = time()."_".$foto->getClientOriginalName();
        $dirFoto = 'foto';
        $foto->move($dirFoto, public_path($namaFoto));
        $data = tbl_biodata::create([
            'nama' => $req->nama,
            'no_hp' => $req->no_hp,
            'alamat' => $req->alamat,
            'hobi' => $req->hobi,
            'foto' => $namaFoto
        ]);
        $res['message'] = 'Berhasil ditambah';
        $res['value'] = $data;
        
        return response($res);
    }
    public function hapus($id){
        $data = DB::table('tbl_biodata')->where('id', $id)->get();
        if(count($data)>0){
            foreach($data as $biodata){
                if(file_exists(public_path('foto/'.$biodata->foto))){
                    @unlink(public_path('foto/'.$biodata->foto));
                }
                DB::table('tbl_biodata')->where('id', $biodata->id)->delete();
            }
            $res['message'] = 'Berhasil dihapus';
            return response($res);
        }else{
            $res['message'] = 'Data tidak ada';
            return response($res);
        }
    }
    public function ubah(Request $req, $id){
        $data = DB::table('tbl_biodata')->where('id', $id)->get();
        if(count($data)>0){
            $this->validate($req, [
                'foto' => 'required|mimes:jpg,jpeg,png|max:2048'
            ]);
            $foto = $req->file('foto');
            $namaFoto = time()."_".$foto->getClientOriginalName();
            $dirFoto = 'foto';
            $foto->move($dirFoto, $namaFoto);
            foreach($data as $biodata){
                if(file_exists(public_path('foto/'.$biodata->foto))){
                    @unlink(public_path('foto/'.$biodata->foto));
                }
                DB::table('tbl_biodata')->where('id', $biodata->id)->update([
                    'nama' => $req->nama,
                    'no_hp' => $req->no_hp,
                    'alamat' => $req->alamat,
                    'hobi' => $req->hobi,
                    'foto' => $namaFoto
                ]);
            }
            $res['message'] = 'Berhasil diubah';
            $res['value'] = $data;
            return response($res);
        }else{
            $res['message'] = 'Data tidak ada';
            return response($res);
        }
    }
    public function lihatData($id){
        if($id!=null&&$id>0){
            $data = DB::table('tbl_biodata')->where('id', $id)->get();
        }else{
            // '..data/x' -> x<=0
            $data = DB::table('tbl_biodata')->get();
        }
        if(count($data)>0){
            $res['message'] = 'Berhasil';
            $res['value'] = $data;
            return response($res);
        }else{
            $res['message'] = 'Data tidak ada';
            return response($res);
        }
    }
}

<?php

namespace App\Controllers;

use App\Models\NasabahModel;
use App\Models\PinjamanModel;
use App\Models\CicilanModel;

class Pinjaman extends BaseController
{
    public function __construct()
    {
        $this->nasabahModel = new NasabahModel();
        $this->pinjamanModel = new PinjamanModel();
        $this->cicilanModel = new CicilanModel();
    }

    public function index()
    {
        
        return view('pinjaman/index');
    }

    public function data()
    {
        $pinjaman = $this->pinjamanModel->where("status", $this->request->getPost("lunas"))->findAll();
        for ($i = 0; $i < count($pinjaman); $i++) {
            $pinjaman[$i]["nama"] = $this->nasabahModel->where("nik", $pinjaman[$i]["nik"])->first()["nama"];
        }
        echo json_encode($pinjaman);
    }

    public function getData()
    {
        echo json_encode($this->transaksiModel->where("nik", $this->request->getPost("nik"))->findAll());
    }

    public function tambah()
    {

        $data = [
            "nik" => $this->request->getPost("nik"),
            "nominal" => $this->request->getPost("nominal"),
            "keterangan" => $this->request->getPost("keterangan"),
            "petugas" => "moham"
        ];

        $this->pinjamanModel->insert($data);


        echo json_encode("");
    }


    public function dataCicilan()
    {
        echo json_encode($this->cicilanModel->where("idPinjaman", $this->request->getPost('idPinjaman'))->findAll());
    }


    public function tambahCicilan()
    {
        $update = false;
        $pinjaman = $this->pinjamanModel->where("id", $this->request->getPost("idPinjaman"))->first();
        $status = $pinjaman["status"];
        $sisa = $pinjaman["nominal"] - ($pinjaman["cicilan"] + $this->request->getPost("nominal"));
        $cicilan = $pinjaman["cicilan"] + $this->request->getPost("nominal");

        $data = [
            "idPinjaman" => $this->request->getPost("idPinjaman"),
            "nominal" => $this->request->getPost("nominal"),
            "petugas" => "moham",
            "sisa" => $sisa
        ];
        $this->cicilanModel->save($data);

        $this->pinjamanModel->update($this->request->getPost("idPinjaman"), ["cicilan" => $cicilan]);

        if ($sisa <= 0) {
            if ($status == 0) {
                $this->pinjamanModel->update($this->request->getPost("idPinjaman"), ["status" => 1]);
                $update = true;
            }
        }
        echo json_encode("");
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\HutangModel;
use App\Models\HutangAngsModel;
use App\Models\ModelPelanggan;

class Hutang extends BaseController
{
    public function __construct()
    {
        $this->modelPelanggan = new ModelPelanggan();
        $this->hutangModel = new HutangModel();
        $this->hutangAngsModel = new HutangAngsModel();
    }

    public function index()
    {
        return view('hutang/index');
    }

    public function data()
    {
        $hutang = $this->hutangModel->getRekap();

        echo json_encode($hutang);
    }

    public function dataDetail()
    {
        $hutang = $this->hutangModel->where("pelid", $this->request->getPost("pelid"))->where("status", $this->request->getPost("lunas"))->find();
        for ($i = 0; $i < count($hutang); $i++) {
            $hutang[$i]['pelnama'] = $this->modelPelanggan->where("pelid", $hutang[$i]["pelid"])->first()['pelnama'];
        }
        echo json_encode($hutang);
    }

    public function dataAngsuran()
    {
        echo json_encode($this->hutangAngsModel->where("hutangid", $this->request->getPost('hutangid'))->findAll());
    }

    public function tambahAngsuran()
    {
        $update = false;
        $hutang = $this->hutangModel->where("id", $this->request->getPost("hutangid"))->first();

        $status = $hutang["status"];
        $sisa = $hutang["nominal"] - ($hutang["angsuran"] + $this->request->getPost("nominal"));
        $angsuran = $hutang["angsuran"] + $this->request->getPost("nominal");

        $data = [
            "hutangid" => $this->request->getPost("hutangid"),
            "nominal" => $this->request->getPost("nominal"),
            "petugas" => "moham",
            "sisa" => $sisa
        ];
        $this->hutangAngsModel->save($data);

        $this->hutangModel->update($this->request->getPost("hutangid"), ["angsuran" => $angsuran]);

        if ($sisa <= 0) {
            if ($status == 0) {
                $this->hutangModel->update($this->request->getPost("hutangid"), ["status" => 1]);
                $update = true;
            }
        }
        echo json_encode("");
    }
}

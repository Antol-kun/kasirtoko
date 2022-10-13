<?php

namespace App\Controllers;

use App\Models\NasabahModel;

class Nasabah extends BaseController
{
    public function __construct()
    {
        $this->nasabahModel = new NasabahModel();
    }

    public function index()
    {
        if (!session()->get('nama')) {
            return redirect()->to(base_url() . "/");
        }

        return view('nasabah');
    }

    public function data()
    {
        echo json_encode($this->nasabahModel->where("status", $this->request->getPost("aktifTidak"))->findAll());
    }

    public function dataAktif()
    {
        echo json_encode($this->nasabahModel->where("status", 1)->findAll());
    }

    public function getData()
    {
        echo json_encode($this->nasabahModel->where("nik", $this->request->getPost("nik"))->first());
    }

    public function tambah()
    {
        $data = [
            "nik" => $this->request->getPost("nik"),
            "nama" => $this->request->getPost("nama"),
            "telp" => $this->request->getPost("telp"),
            "alamat" => $this->request->getPost("alamat"),
            "status" => $this->request->getPost("status")
        ];

        if ($this->request->getPost("nikLama") == "false") {
            $this->nasabahModel->insert($data);
        } else {
            unset($data["nik"]);
            $this->nasabahModel->update($this->request->getPost("nikLama"), $data);
        }
        echo json_encode($this->request->getPost("nikLama"));
    }

    public function upload()
    {
        $data = array();

        $validation = \Config\Services::validation();

        $validation->setRules([
            'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,jpg,jpeg,gif,png,webp],'
        ]);

        if ($validation->withRequest($this->request)->run() == FALSE) {
            $data['success'] = 0;

            $data['error'] = $validation->getError('file'); // Error response
        } else {
            if ($file = $this->request->getFile('file')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $name = $file->getName();
                    $ext = $file->getClientExtension();

                    $nik = $this->request->getPost("nik");

                    $namaFoto = str_replace(" ", "", strtolower($nik) . "." . $ext);

                    $file->move('./public/upload', $namaFoto, true);

                    $this->nasabahModel->update($nik, ["foto" => $namaFoto]);

                    $data['success'] = 1;
                    $data['message'] = 'Foto Berhasil diupload :)';
                } else {
                    $data['success'] = 2;
                    $data['message'] = 'Foto gagal diupload.';
                }
            } else {
                $data['success'] = 2;
                $data['message'] = 'Foto gagal diupload.';
            }
        }
        return $this->response->setJSON($data);
    }
}

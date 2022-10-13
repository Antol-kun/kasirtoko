<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modeluser;
use \Hermawan\DataTables\DataTable;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;
use PHPUnit\Util\Json;

class Users extends BaseController
{
    public function data()
    {
        return view('users/data');
    }
    function listData()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();
            $builder = $db->table('users')->select('userid,usernama,levelnama,levelid,useraktif')
                ->join('levels', 'levelid=userlevelid');

            return DataTable::of($builder)
                ->addNumbering('nomor')
                ->add('statususer', function ($row) {
                    if ($row->useraktif == '1') {
                        return "<span class='badge badge-success'>Active</span>";
                    } else {
                        return "<span class='badge badge-danger'>Non Active</span>";
                    }
                })
                ->add('aksi', function ($row) {
                    if ($row->levelid != '1') {
                        return "<button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"edit('" . $row->userid . "')\">
                            <i class=\"fa fa-edit\"></i>
                        </button>";
                    }
                })
                ->toJson(true);
        }
    }

    public function formUsers()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();
            $dataLevel = $db->table('levels')->where('levelid !=', '1')->get();

            $data = [
                'datalevel' => $dataLevel
            ];
            return view('users/modaltambah', $data);
        }
    }

    public function simpan()
    {
        if ($this->request->isAJAX()) {
            $iduser = $this->request->getVar('iduser');
            $namalengkap = $this->request->getVar('namalengkap');
            $level = $this->request->getVar('level');

            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'iduser' => [
                    'rules' => 'required|is_unique[users.userid]',
                    'label' => 'ID User',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'is_unique' => '{field} sudah ada, gunakan {field} yang lain'
                    ]
                ],
                'namalengkap' => [
                    'rules' => 'required',
                    'label' => 'Nama Lengkap',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
                'level' => [
                    'rules' => 'required',
                    'label' => 'Level',
                    'errors' => [
                        'required' => '{field} Harus di pilih',
                    ]
                ]
            ]);

            if (!$valid) {
                $error = [
                    'iduser' => $validation->getError('iduser'),
                    'namalengkap' => $validation->getError('namalengkap'),
                    'level' => $validation->getError('level')
                ];

                $json = [
                    'error' => $error
                ];
            } else {
                $modelUser = new Modeluser();
                // Simpan data user 
                $modelUser->insert([
                    'userid' => $iduser,
                    'usernama' => $namalengkap,
                    'userlevelid' => $level,
                    'useraktif' => 1
                ]);

                $json = [
                    'sukses' => 'User baru berhasil ditambahkan'
                ];
            }

            echo json_encode($json);
        }
    }

    public function formedit()
    {
        if ($this->request->isAJAX()) {
            $userid = $this->request->getPost('userid');

            $modelUser = new Modeluser();
            $rowUser = $modelUser->find($userid);
            if ($rowUser) {

                $db = \Config\Database::connect();
                $dataLevel = $db->table('levels')->where('levelid !=', '1')->get();

                $data = [
                    'iduser' => $userid,
                    'namalengkap' => $rowUser['usernama'],
                    'level' => $rowUser['userlevelid'],
                    'stt' => $rowUser['useraktif'],
                    'datalevel' => $dataLevel
                ];

                return view('users/modaledit', $data);
            }
        }
    }

    public function update()
    {
        if ($this->request->isAJAX()) {
            $iduser = $this->request->getVar('iduser');
            $namalengkap = $this->request->getVar('namalengkap');
            $level = $this->request->getVar('level');


            $modelUser = new Modeluser();
            // Simpan data user 
            $modelUser->update($iduser, [
                'usernama' => $namalengkap,
                'userlevelid' => $level,
            ]);

            $json = [
                'sukses' => 'User berhasil diupdate'
            ];

            echo json_encode($json);
        }
    }

    function updateStatus()
    {
        if ($this->request->isAJAX()) {
            $iduser = $this->request->getVar('iduser');
            $modelUser = new Modeluser();

            $rowUser = $modelUser->find($iduser);
            $sttUser = $rowUser['useraktif'];

            if ($sttUser == '1') {
                $modelUser->update($iduser, ['useraktif' => 0]);
            } else {
                $modelUser->update($iduser, ['useraktif' => 1]);
            }

            $json = [
                'sukses' => 'Status berhasil di ubah'
            ];
            echo json_encode($json);
        }
    }
    function hapusUser()
    {
        if ($this->request->isAJAX()) {
            $iduser = $this->request->getVar('iduser');
            $modelUser = new Modeluser();

            $rowUser = $modelUser->find($iduser);
            if ($rowUser) {
                $modelUser->delete($iduser);
            }

            $json = [
                'sukses' => 'Status berhasil di Hapus'
            ];
            echo json_encode($json);
        }
    }
}
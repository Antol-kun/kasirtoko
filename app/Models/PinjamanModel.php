<?php

namespace App\Models;

use CodeIgniter\Model;

class PinjamanModel extends Model
{

    protected $table = "pinjaman";
    protected $primaryKey = 'id';
    protected $allowedFields = ['nik', 'nominal', 'petugas', 'keterangan', 'status', 'cicilan'];
}

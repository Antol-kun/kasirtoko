<?php

namespace App\Models;

use CodeIgniter\Model;

class NasabahModel extends Model
{

    protected $table = "nasabah";
    protected $primaryKey = 'nik';
    protected $allowedFields = ['nik', 'nama', 'telp', 'alamat', 'foto', 'saldo', 'status'];
}

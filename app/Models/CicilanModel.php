<?php

namespace App\Models;

use CodeIgniter\Model;

class CicilanModel extends Model
{

    protected $table = "cicilan";
    protected $primaryKey = 'id';
    protected $allowedFields = ['idPinjaman', 'nominal', 'petugas', 'tanggal', 'sisa'];
}

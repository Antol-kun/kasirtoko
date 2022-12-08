<?php

namespace App\Models;

use CodeIgniter\Model;

class HutangAngsModel extends Model
{

    protected $table = "hutang_angs";
    protected $primaryKey = 'id';
    protected $allowedFields = ['hutangid', 'nominal', 'tanggal', 'petugas', 'sisa'];
}

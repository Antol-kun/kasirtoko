<?php

namespace App\Models;

use CodeIgniter\Model;

class HutangRekapsModel extends Model
{
    protected $table = "hutang_rekaps";
    protected $primaryKey = 'id';
    protected $allowedFields = ['pelid', 'total', 'limit'];
}

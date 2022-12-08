<?php

namespace App\Models;

use CodeIgniter\Model;

class HutangModel extends Model
{
    protected $table = "hutang";
    protected $primaryKey = 'id';
    protected $allowedFields = ['pelid', 'pelnama', 'faktur', 'nominal', 'tanggal', 'angsuran', 'status', 'tempo_hutang', 'ket'];

    public function getRekap()
    {
        $this->select('pelid, pelnama, COUNT(pelid) as trx, SUM(nominal) as sumnom, sum(angsuran) as sumangs, (sum(nominal)-sum(angsuran)) as sisa');
        $this->groupBy('pelid, pelnama');
        $this->orderBy('pelnama', 'DESC');
        $query = $this->get();
        return $query->getResultArray();
    }

    public function getDetail($id)
    {
        
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class UntungModel extends Model
{
    protected $table                = 'detail_untung';
    protected $primaryKey           = 'det_barangkelid';
    protected $allowedFields        = ['det_barangkelid', 'detfaktur', 'detbrgkode', 'dethargabeli', 'dethargajual', 'detjml', 'untung'];

    public function setModal($key, $field)
    {
        $this->set('dethargabeli', $field);
        $this->where('detbrgkode', $key);
        $this->update();
    }

    public function setUntung($key, $field)
    {
        $this->set('untung', $field);
        $this->where('det_barangkelid', $key);
        $this->update();
    }

    public function ceklastquery()
    {
        $this->db->getLastQuery();
    }
    public function laporanPerPeriode($tglawal, $tglakhir)
    {
        // SELECT du.*, bk.tglfaktur as tglfaktur FROM `detail_untung` as du JOIN barangkeluar as bk ON du.detfaktur=bk.faktur;
        $this->select('*');
        $this->join('barangkeluar', 'detfaktur=faktur');
        $this->where('tglfaktur >=', $tglawal);
        $this->where('tglfaktur <=', $tglakhir);
        // $query = $this->getCompiledSelect();
        $query = $this->get();
        return $query;
        // return $this->table('barangmasuk')->where('tglfaktur >=', $tglawal)->where('tglfaktur <=', $tglakhir)->get();
    }
}

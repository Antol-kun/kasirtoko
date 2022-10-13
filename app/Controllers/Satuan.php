<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelkategori;

class Satuan extends BaseController
{
    public function __construct()
    {
        $this->kategori = new Modelkategori();
        helper(['form']);
    }

    public function index()
    {
        $modelkategori = new Modelkategori();
        return view('satuan/viewsatuan');
    }

    public function simpan()
    {
        $modelkategori = new Modelkategori();
    }
}
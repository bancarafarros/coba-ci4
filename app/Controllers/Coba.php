<?php

namespace App\Controllers;

class Coba extends BaseController
{
    public function index()
    {
        echo "Ini controller Coba method index";
    }

    public function about($nama = "", $umur = 0)
    {
        echo "Halo nama saya $nama, saya berusia $umur tahun";
    }

    public function produk($id = 0)
    {
        echo "ID produk adalah $id";
    }
}

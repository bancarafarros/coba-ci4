<?php
// nama file model disamain sama tabel
// nama folder view disamain sama controller
namespace App\Models;

use CodeIgniter\Model;

class KomikModel extends Model
{
    protected $table = 'komik';
    // protected $primaryKey = 'id'; // dikomen karena sama kayak di file induk model
    protected $useTimestamps = true;

    public function getKomik($slug = false)
    {
        if ($slug == false) {
            return $this->findAll();
        }

        return $this->where(['slug' => $slug])->first();
    }
}
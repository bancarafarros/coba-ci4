<?php

namespace App\Controllers;

use App\Models\KomikModel;
use PharIo\Manifest\Url;

class Komik extends BaseController
{
    protected $komikModel;

    public function __construct()
    {
        $this->komikModel = new KomikModel();
    }

    public function index()
    {
        // $komik = $this->komikModel->findAll();

        $data = [
            'title' => 'Daftar Komik',
            // 'komik' => $komik
            'komik' => $this->komikModel->getKomik()
        ];

        // $komikModel = new \App\Models\KomikModel(); // alternatif pake model
        // $komikModel = new KomikModel(); // dipindahin ke construct

        // dd($komik);


        // connect db tanpa model
        // $db = \Config\Database::connect();
        // $komik = $db->query("SELECT * FROM komik");
        // dd($komik);
        // foreach ($komik->getResultArray() as $row) {
        //     dd($row);
        // }

        return view('komik/index', $data);
    }

    public function detail($slug)
    {
        $data = [
            'title' => 'Detail Komik',
            'komik' => $this->komikModel->getKomik($slug)
        ];

        if (empty($data['komik'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul komik ' . $slug . ' tidak ditemukan');
        }

        return view('komik/detail', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Form Tambah Data Komik',
            'validation' => \Config\Services::validation()
        ];

        return view('komik/create', $data);
    }

    public function save()
    {
        // validasi input
        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[komik.judul]',
                'errors' => [
                    'required' => '{field} komik harus diisi',
                    'is_unique' => '{field} komik sudah terdaftar'
                ]
            ]
        ])) {
            $validation = \Config\Services::validation(); // ngambil pesan kesalahan
            return redirect()->to('/Komik/create')->withInput()->with('validation', $validation);
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);

        $this->komikModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $this->request->getVar('sampul')
        ]);

        session()->setFlashdata('message', 'Data berhasil ditambah');

        return redirect()->to('/Komik');
    }

    public function delete($id)
    {
        $this->komikModel->delete($id);
        session()->setFlashdata('message', 'Data berhasil dihapus');
        return redirect()->to('/Komik');
    }

    public function edit($slug)
    {
        $data = [
            'title' => 'Form Ubah Data Komik',
            'validation' => \Config\Services::validation(),
            'komik' => $this->komikModel->getKomik($slug)
        ];

        return view('komik/edit', $data);
    }

    public function update($id)
    {
        // cek judul
        // $komikLama = $this->komikModel->getKomik($this->request->getVar('slug'));
        // if ($komikLama['judul'] == $this->request->getVar('judul')) {
        //     $rule_judul = 'required';
        // } else {
        //     $rule_judul = 'required|is_unique[komik.judul]';
        // }

        // validasi input
        // if (!$this->validate([
        //     'judul' => [
        //         'rules' => $rule_judul,
        //         'errors' => [
        //             'required' => '{field} komik harus diisi',
        //             'is_unique' => '{field} komik sudah terdaftar'
        //         ]
        //     ]
        // ])) {
        //     $validation = \Config\Services::validation(); // ngambil pesan kesalahan
        //     return redirect()->to('/Komik/edit/' . $this->request->getVar('slug'))->withInput()->with('validation', $validation);
        // }

        $slug = url_title($this->request->getVar('judul'), '-', true);

        $this->komikModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $this->request->getVar('sampul')
        ]);

        session()->setFlashdata('message', 'Data berhasil diubah');

        return redirect()->to('/Komik');
    }
}

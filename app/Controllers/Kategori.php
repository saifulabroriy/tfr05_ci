<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KategoriModel;

class Kategori extends BaseController
{
    protected $barang;
    protected $kategori;
    protected $request;

    public function __construct()
    {
        $this->kategori = new KategoriModel();
        $this->kategori->protect(false);
        $this->request = \Config\Services::request();
        session()->start();
    }

    public function index()
    {
        // Mendapatkan Query Params
        $cari = $this->request->getVar('q');
        $entri = $this->request->getVar('entri');
        $page = $this->request->getVar('page');
        $entri = isset($entri) ? $entri : 10;

        // Filter Pencarian
        // NOTE : kurang pagination
        if (isset($cari)) {
            $kategori = $this->kategori->builder()->select(
                '*',
                false
            )->like('nama', "%$cari%")->get();
        } else {
            $kategori = $this->kategori->builder()->select(
                '*',
                false
            )->get();
        }

        // Data yang akan dikirim
        $data = [
            'jumlah_kategori' => $this->kategori->paginate($entri),
            'pager' => $this->kategori->pager,
            'q' => $cari,
            'entri' => $entri,
            'page' => $page,
            'kategori' => $kategori,
        ];

        return view('kategori/index', $data);
    }

    public function create()
    {
        return view('kategori/create', []);
    }

    public function store()
    {
        $this->kategori->save([
            'kategori' => $this->request->getPost('kategori'),
        ]);

        return redirect()->to('admin/kategori')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kategori = $this->kategori->find($id);
        return view('kategori/edit', [
            'kategori' => $kategori,
        ]);
    }

    public function update($id)
    {
        $this->kategori->update($id, [
            'kategori' => $this->request->getPost('kategori'),
        ]);

        return redirect()->to('admin/kategori')->with('success', 'Kategori berhasil diubah');
    }

    public function delete($id)
    {
        $this->kategori->delete($id);
        return redirect()->to('admin/kategori')->with('success', 'Kategori berhasil dihapus');
    }
}

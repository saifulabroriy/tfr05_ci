<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use CodeIgniter\HTTP\IncomingRequest;

class Barang extends BaseController
{
    protected $barang;
    protected $kategori;
    protected $request;

    public function __construct()
    {
        $this->barang = new BarangModel();
        $this->barang->protect(false);
        $this->kategori = new KategoriModel();
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
            $barang = $this->barang->builder()->select(
                'barang.id,
                kategori.kategori as kategori,
                barang.nama,
                barang.harga,
                barang.stock,
                barang.created_at,
                barang.updated_at',
                false
            )->join("kategori", "kategori.id = barang.idkategori", '', false)->like('nama', "%$cari%")->get();
        } else {
            $barang = $this->barang->builder()->select(
                'barang.id,
                kategori.kategori as kategori,
                barang.nama,
                barang.harga,
                barang.stock,
                barang.created_at,
                barang.updated_at',
                false
            )->join("kategori", "kategori.id = barang.idkategori", '', false)->get();
        }

        // Data yang akan dikirim
        $data = [
            'jumlah_barang' => $this->barang->paginate($entri),
            'pager' => $this->barang->pager,
            'q' => $cari,
            'entri' => $entri,
            'page' => $page,
            'barang' => $barang,
        ];

        return view('barang/index', $data);
    }

    public function create()
    {
        $kategori = $this->kategori->findAll();

        return view('barang/create', [
            'kategori' => $kategori,
        ]);
    }

    public function store()
    {
        $this->barang->save([
            'idkategori' => $this->request->getPost('kategori'),
            'nama' => $this->request->getPost('nama'),
            'harga' => $this->request->getPost('harga'),
            'stock' => $this->request->getPost('stok'),
        ]);

        return redirect()->to('admin/barang')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit($id)
    {

        $kategori = $this->kategori->findAll();
        $barang = $this->barang->find($id);
        // dd($barang);
        return view('barang/edit', [
            'kategori' => $kategori,
            'barang' => $barang
        ]);
    }

    public function update($id)
    {
        $this->barang->update($id, [
            'idkategori' => $this->request->getPost('kategori'),
            'nama' => $this->request->getPost('nama'),
            'harga' => $this->request->getPost('harga'),
            'stock' => $this->request->getPost('stok'),
        ]);

        return redirect()->to('admin/barang')->with('success', 'Barang berhasil diubah');
    }

    public function delete($id)
    {
        $this->barang->delete($id);
        return redirect()->to('admin/barang')->with('success', 'Barang berhasil dihapus');
    }
}

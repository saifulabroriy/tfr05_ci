<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PelangganModel;

class Pelanggan extends BaseController
{
    protected $pelanggan;
    protected $request;

    public function __construct()
    {
        $this->pelanggan = new PelangganModel();
        $this->pelanggan->protect(false);
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

        if (isset($cari)) {
            $pelanggan = $this->pelanggan->builder()->select(
                '*',
                false
            )->like('nama', "%$cari%")->get();
        } else {
            $pelanggan = $this->pelanggan->builder()->select(
                '*',
                false
            )->get();
        }

        // Data yang akan dikirim
        $data = [
            'jumlah_pelanggan' => $this->pelanggan->paginate($entri),
            'pager' => $this->pelanggan->pager,
            'q' => $cari,
            'entri' => $entri,
            'page' => $page,
            'pelanggan' => $pelanggan,
        ];

        return view('pelanggan/index', $data);
    }

    public function create()
    {
        return view('pelanggan/create');
    }

    public function store()
    {
        $this->pelanggan->save([
            'nama' => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'notelp' => $this->request->getPost('notelp'),
        ]);

        return redirect()->to('admin/pelanggan')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pelanggan = $this->pelanggan->find($id);

        return view('pelanggan/edit', [
            'pelanggan' => $pelanggan,
        ]);
    }

    public function update($id)
    {
        $this->pelanggan->update($id, [
            'pelanggan' => $this->request->getPost('pelanggan'),
        ]);

        return redirect()->to('admin/pelanggan')->with('success', 'Pelanggan berhasil diubah');
    }

    public function delete($id)
    {
        $this->pelanggan->delete($id);
        return redirect()->to('admin/pelanggan')->with('success', 'Pelanggan berhasil dihapus');
    }
}

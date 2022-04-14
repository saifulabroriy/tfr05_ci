<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PelangganModel;
use App\Models\LogUserModel;

class Pelanggan extends BaseController
{
    protected $logUser;
    protected $pelanggan;
    protected $request;

    public function __construct()
    {
        $this->logUser = new LogUserModel();
        $this->logUser->protect(false);
        $this->pelanggan = new PelangganModel();
        $this->pelanggan->protect(false);
        $this->request = \Config\Services::request();
        session()->start();
    }

    public function index()
    {
        // Mendapatkan Query Params
        $cari = $this->request->getVar('q') ?? '';
        $entri = $this->request->getVar('entri');
        $page = $this->request->getVar('page') ?? 1;
        $entri = isset($entri) ? $entri : 10;
        $offset = ($page - 1) * $entri;

        $dataCount = 0;
        $dataCount = $this->pelanggan->builder()->select(['COUNT(pelanggan.id) AS jml'])
            ->like('nama', "%$cari%")
            ->get()
            ->getRow()
            ->jml;
        if (isset($cari)) {
            $pelanggan = $this->pelanggan->builder()->select(
                '*',
                false
            )->like('nama', "%$cari%")->get($entri, $offset);
        } else {
            $pelanggan = $this->pelanggan->builder()->select(
                '*',
                false
            )->get($entri, $offset);
        }
        $pager = service('pager'); //instantiate pager
        $pager->makeLinks($page, $entri, $dataCount);

        // Data yang akan dikirim
        $data = [
            'jumlah_pelanggan' => $this->pelanggan->paginate($entri),
            'pager' => $this->pelanggan->pager,
            'q' => $cari,
            'entri' => $entri,
            'page' => $page,
            'pelanggan' => $pelanggan,
            'offset' => $offset
        ];

        return view('pelanggan/index', $data);
    }

    public function create()
    {
        return view('pelanggan/create');
    }

    public function store()
    {
        $nama = $this->request->getPost('nama');
        $alamat = $this->request->getPost('alamat');
        $notelp = $this->request->getPost('notelp');

        $this->pelanggan->save([
            'nama' => $nama,
            'alamat' => $alamat,
            'notelp' => $notelp,
        ]);

        $after = [
            'nama' => $nama,
            'alamat' => $alamat,
            'notelp' => $notelp,
        ];

        $log = [
            'iduser' => session()->get('id'),
            'menu' => 'Pelanggan',
            'keterangan' => 'Menambah pelanggan',
            'before' => '',
            'after' => json_encode($after),
        ];

        $this->logUser->insert($log);

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
        $pelanggan = $this->pelanggan->find($id);

        $before = [
            'nama' => $pelanggan['nama'],
            'alamat' => $pelanggan['alamat'],
            'notelp' => $pelanggan['notelp'],
        ];

        $nama = $this->request->getPost('nama');
        $alamat = $this->request->getPost('alamat');
        $notelp = $this->request->getPost('notelp');

        $this->pelanggan->update($id, [
            'nama' => $nama,
            'alamat' => $alamat,
            'notelp' => $notelp,
        ]);

        $after = [
            'nama' => $nama,
            'alamat' => $alamat,
            'notelp' => $notelp,
        ];

        $log = [
            'iduser' => session()->get('id'),
            'menu' => 'Pelanggan',
            'keterangan' => 'Mengubah pelanggan',
            'before' => json_encode($before),
            'after' => json_encode($after),
        ];

        $this->logUser->insert($log);

        return redirect()->to('admin/pelanggan')->with('success', 'Pelanggan berhasil diubah');
    }

    public function delete($id)
    {
        $pelanggan = $this->pelanggan->find($id);

        $before = [
            'nama' => $pelanggan['nama'],
            'alamat' => $pelanggan['alamat'],
            'notelp' => $pelanggan['notelp'],
        ];

        $log = [
            'iduser' => session()->get('id'),
            'menu' => 'Pelanggan',
            'keterangan' => 'Menghapus pelanggan',
            'before' => json_encode($before),
            'after' => '',
        ];

        $this->pelanggan->delete($id);

        $this->logUser->insert($log);

        return redirect()->to('admin/pelanggan')->with('success', 'Pelanggan berhasil dihapus');
    }
}

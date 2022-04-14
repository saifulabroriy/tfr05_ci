<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\PelangganModel;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use Exception;
use PhpParser\Node\Stmt\TryCatch;

class Penjualan extends BaseController
{
    protected $barang;
    protected $pelanggan;
    protected $request;

    public function __construct()
    {
        $this->pelanggan = new PelangganModel();
        $this->barang = new BarangModel();
        $this->request = \Config\Services::request();
        session()->start();
    }
    //
    public function create()
    {
        // $barang = $this->barang->->find(1);
        // dd($barang);
        $iduser = session()->get('id');
        $head = session()->get($iduser . '_penjualan');
        // dd($head);
        // session(['cart' => []]);
        // dd(session('cart'));
        $pelanggan = $this->pelanggan->asObject()->findAll();
        return view('penjualan/create', [
            'pelanggan' => $pelanggan
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $db = \Config\Database::connect();
        $req = $this->request;
        $iduser = session()->get('id');
        $cart = array(session()->get($iduser . '_cart') ?? []);
        foreach ($cart as $c => $cart) {
            $cart = array_map(function ($cart) use ($req) {
                $cart['jumlah'] = $req->getPost("jumlah_" . $cart['id']);
                return $cart;
            }, $cart);
        }

        $idpelanggan = $req->getPost('idpelanggan');
        $tgl = $req->getPost('tgl');
        $nofaktur = $this->getNofaktur();
        $bayar = $req->getPost('bayar');
        $kembali = $req->getPost('kembali');

        $head = new PenjualanModel();
        $head->protect(false);

        $after = [
            'nofaktur' => $nofaktur,
        ];

        $log = [
            'iduser' => $iduser,
            'menu' => 'Penjualan',
            'keterangan' => 'Melakukan Penjualan',
            'before' => '',
            'after' => json_encode($after),
        ];

        $db->transBegin();
        if ($db->transStatus() === false) {
            $db->transRollback();
            // return redirect('/admin/penjualan')->with('error', 'Terjadi Kesalahan saat melakukan Penjualan ' . $th->getMessage());
            return redirect()->to('admin/penjualan')->with('error', 'Terjadi Kesalahan saat melakukan Penjualan');
        } else {
            $head->save([
                'iduser' => $iduser,
                'idpelanggan' => $idpelanggan,
                'tgl' => $tgl,
                'nofaktur' => $nofaktur,
                'bayar' => $bayar,
                'kembali' => $kembali,
            ]);
            $idpenjualan = $head->getInsertID();
            foreach ($cart as $i => $item) {
                $detail = new PenjualanDetailModel();
                $detail->protect(false);
                $detail->save([
                    'idpenjualan' => $idpenjualan,
                    'idbarang' => $item['id'],
                    'harga' => $item['harga'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['harga'] * $item['jumlah'],
                ]);
            }
            $db->table('log_user')->insert($log);
            $db->transCommit();
            session()->set([$iduser . '_cart' => []]);
            session()->set([$iduser . '_penjualan' => []]);
            return redirect()->to('/admin/penjualan')->with('success', 'Penjualan berhasil');
        }
    }

    protected function getNoFaktur()
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d');
        $data = $db->query("SELECT IF(ISNULL(MAX(nofaktur)),\"0001\",LPAD(CONVERT(RIGHT(MAX(nofaktur), 4), UNSIGNED INT)+1, 4, 0))AS nofaktur
        FROM penjualan WHERE tgl BETWEEN \"$now 00:00:00\" AND \"$now 23:59:59\"")->getResult();
        $urut = $data[0]->nofaktur;
        $tgl = date('Ymd');
        return "PJ" . $tgl . $urut;
    }

    public function pilihBarang()
    {
        $cari = $this->request->getGet('q');
        $entri = $this->request->getGet('entri') ?: 10;
        $page = $this->request->getGet('page') ?: 1;
        // dd($page, $entri);
        $offset = ($page - 1) * $entri;

        $dataCount = $this->barang->builder()->select(['COUNT(barang.id) AS jml'])
            ->join("kategori", "kategori.id = barang.idkategori", '', false)
            ->like('nama', "%$cari%")
            ->get()
            ->getRow()
            ->jml;
        $barang = $this->barang->builder()->select(
            'barang.id,
            kategori.kategori as kategori,
            barang.nama,
            barang.harga,
            barang.stock,
            barang.created_at,
            barang.updated_at',
            false
        )->join("kategori", "kategori.id = barang.idkategori", '', false)
            ->like('nama', "%$cari%")
            ->get($entri, $offset)
            ->getResult();

        $pager = service('pager'); //instantiate pager
        $pager->makeLinks($page, $entri, $dataCount);

        return view('penjualan/pilihbarang', [
            'data' => $barang,
            'pager' => $pager,
        ]);
    }

    public function centang()
    {
        // $res = [
        //     'status' => 1,
        //     'message' => 'Berhasil Gan',
        //     'data' => $this->request->getPost()
        // ];

        $barang = $this->request->getPost();
        $iduser = session()->get('id');
        $current = session()->get($iduser . '_cart') ?? [];

        $current[] = array_merge($barang, ['jumlah' => 1]); // Push new data
        session()->set([$iduser . '_cart' => $current]);
        return json_encode([
            'status' => 1,
            'message' => "Berhasil centang"
        ]);
    }

    public function uncentang()
    {
        $barang = $this->request->getPost();
        $iduser = session()->get('id');
        $current = session()->get($iduser . '_cart') ?? []; // Getting old data

        $filtered = array_filter($current, function ($el) use ($barang) {
            return $el['id'] != $barang['id'];
        });
        session()->set([$iduser . '_cart' => $filtered]);
        return json_encode([
            'status' => 1,
            'message' => "Berhasil Uncentang"
        ]);
    }

    public function setSession()
    {
        $data = $this->request->getPost();
        $iduser = session()->get('id');

        session()->set([$iduser . '_penjualan' => $data]);
        return json_encode([
            'status' => 1,
            'message' => "Berhasil set session",
            'data' => $data,
            'iduser' => $iduser
        ]);
    }

    public function hapusBarang()
    {
        $data = $this->request->getPost();
        $iduser = session()->get('id');
        $id = $data['id'];

        $current = (session()->get($iduser . '_cart') ?: []);
        $filtered = array_filter($current, function ($el) use ($id) {
            return $el['id'] != $id;
        });
        session()->set([$iduser . '_cart' => $filtered]);
        return json_encode([
            'status' => 1,
            'message' => "Berhasil Hapus Keranjang"
        ]);
    }

    public function cekStok()
    {
        $stokCukup = true;
        $message = '';
        $data = $this->request->getPost('data');
        foreach ($data as $item) {
            $id = $item['id'];
            $jml = $item['jml'];
            $nama = $item['nama'];

            $barang = $this->barang->asObject()->find($id);
            $stok = $barang->stock;
            if ($stok < $jml) {
                $stokCukup = false;
                $message = "Stok tidak cukup untuk barang {$nama}, stok tersisa {$stok}";
                break;
            }
        }
        return json_encode([
            'status' => $stokCukup ? 1 : 0,
            'message' => $message,
            'data' => $data
        ]);
    }
}

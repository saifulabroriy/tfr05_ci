<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PelangganModel;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;

class Penjualan extends BaseController
{
    public function __construct()
    {
        $this->pelanggan = new PelangganModel();
        $this->request = \Config\Services::request();
        session()->start();
    }
    //
    public function create()
    {
        $iduser = session()->get('id');
        $head = session($iduser . '_penjualan');
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
    public function store(Request $request)
    {
        $iduser = auth()->user()->id;
        $cart = collect(session($iduser . '_cart', []));
        $cart = $cart->map(function ($item) use ($request) {
            $item['jumlah'] = $request->input("jumlah_" . $item['id']);
            return $item;
        });

        $head = new Penjualan();

        $head->iduser = $iduser;
        $head->idpelanggan = $request->input("idpelanggan");
        $head->tgl = $request->input("tgl");
        $head->nofaktur = $this->getNofaktur();
        $head->bayar = $request->input("bayar");
        $head->kembali = str_replace(",", "", $request->input("kembali"));
        // dd($request->all(), $cart, $head);
        $after = [
            'nofaktur' => $head->nofaktur,
        ];

        $log = [
            'iduser' => $iduser,
            'menu' => 'Penjualan',
            'keterangan' => 'Melakukan Penjualan',
            'before' => '',
            'after' => json_encode($after),
        ];

        DB::beginTransaction();
        try {
            //code...
            $head->save();
            $idpenjualan = $head->id;
            $cart->each(function ($item) use ($idpenjualan) {
                $detail = new PenjualanDetail();
                $detail->idpenjualan = $idpenjualan;
                $detail->idbarang = $item['id'];
                $detail->harga = $item['harga'];
                $detail->jumlah = $item['jumlah'];
                $detail->subtotal = $item['harga'] * $item['jumlah'];
                $detail->save();
                // PenjualanDetail::create([
                //     'idpenjualan' => $idpenjualan,
                //     'idbarang' => $item['id'],
                //     'harga' => $item['harga'],
                //     'jumlah' => $item['jumlah']
                // ]);
            });

            DB::table('log_user')->insert($log);
            DB::commit();
            // Clear Session
            session([$iduser . '_cart' => []]);
            session([$iduser . '_penjualan' => []]);
            return redirect('/admin/penjualan')->with('success', 'Penjualan berhasil');
        } catch (Exception $th) {
            DB::rollBack();
            return redirect('/admin/penjualan')->with('error', 'Terjadi Kesalahan saat melakukan Penjualan ' . $th->getMessage());
        }
    }

    protected function getNoFaktur()
    {
        $now = date('Y-m-d');
        $data = DB::select("SELECT IF(ISNULL(MAX(nofaktur)),\"0001\",LPAD(CONVERT(RIGHT(MAX(nofaktur), 4), UNSIGNED INT)+1, 4, 0))AS nofaktur
            FROM penjualan WHERE tgl BETWEEN \"$now 00:00:00\" AND \"$now 23:59:59\"");
        $urut = $data[0]->nofaktur;
        $tgl = date('Ymd');
        return "PJ" . $tgl . $urut;
    }

    public function pilihBarang()
    {
        $cari = request('q');
        $entri = request('entri', 10);

        $barang = Barang::select(
            "barang.id",
            "kategori.kategori as kategori",
            "barang.nama",
            "barang.harga",
            "barang.stock",
            "barang.created_at",
            "barang.updated_at"
        )->join("kategori", "kategori.id", "=", "barang.idkategori")
            ->when(!empty($cari), function ($query) use ($cari) {
                return $query->where('nama', 'like', "%$cari%");
            })
            ->paginate($entri)
            ->withQueryString();
        // if ($cari) {

        // } else {
        //     $barang = barang::select(
        //         "barang.id",
        //         "kategori.kategori as kategori",
        //         "barang.nama",
        //         "barang.harga",
        //         "barang.stock",
        //         "barang.created_at",
        //         "barang.updated_at"
        //     )->join("kategori", "kategori.id", "=", "barang.idkategori")->paginate($entri)->withQueryString();
        // }
        // dd($barang);
        //
        $queryParams = request()->all();
        $builtQuery = http_build_query($queryParams);
        // dd($builtQuery);

        return view('penjualan/pilihbarang', [
            'data' => $barang,
            'params' => $builtQuery // Passing query params saat ini
        ]);
    }

    public function centang(Request $request)
    {
        // $res = [
        //     'status' => 1,
        //     'message' => 'Berhasil Gan',
        //     'data' => $request->all()
        // ];

        $barang = $request->all();
        $iduser = auth()->user()->id;
        $current = session($iduser . '_cart', []);

        $current[] = array_merge($barang, ['jumlah' => 1]); // Push new data
        session([$iduser . '_cart' => $current]);
        return json_encode([
            'status' => 1,
            'message' => "Berhasil centang"
        ]);
    }

    public function uncentang(Request $request)
    {
        $barang = $request->all();
        $iduser = auth()->user()->id;
        $current = collect(session($iduser . '_cart', [])); // Getting old data

        $filtered = $current->filter(function ($el) use ($barang) {
            return $el['id'] != $barang['id'];
        });
        session([$iduser . '_cart' => $filtered]);
        return json_encode([
            'status' => 1,
            'message' => "Berhasil Uncentang"
        ]);
    }

    public function setSession(Request $request)
    {
        $data = $request->all();
        $iduser = auth()->user()->id;

        session([$iduser . '_penjualan' => $data]);
        return json_encode([
            'status' => 1,
            'message' => "Berhasil set session",
            'data' => $data,
            'iduser' => $iduser
        ]);
    }

    public function hapusBarang(Request $request)
    {
        $data = $request->all();
        $iduser = auth()->user()->id;
        $id = $data['id'];

        $current = collect(session($iduser . '_cart', []));
        $filtered = $current->filter(function ($el) use ($id) {
            return $el['id'] != $id;
        });
        session([$iduser . '_cart' => $filtered]);
        return json_encode([
            'status' => 1,
            'message' => "Berhasil Hapus Keranjang"
        ]);
    }

    public function cekStok(Request $request)
    {
        $stokCukup = true;
        $message = '';
        $data = $request->data;
        foreach ($data as $item) {
            $id = $item['id'];
            $jml = $item['jml'];
            $nama = $item['nama'];

            $barang = DB::table('barang')
                ->where('id', '=', $id)
                // ->where('stock', '>=', $jml)
                ->get(['id', 'stock']);
            $stok = $barang[0]->stock;
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

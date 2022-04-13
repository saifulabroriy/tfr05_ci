<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Admin extends BaseController
{
    public function index()
    {
        // Mengambil 10 barang dengan pengadaan terbanyak.
        $barangUrutStok = $this->db->table('barang')
            ->orderBy('stock', 'desc')
            ->limit(10)
            ->get(['id', 'nama', 'stock']);

        $kategoriUrutStok = $this->db->table('barang', 'b')
            ->join('kategori as k', 'k.id', '=', 'b.idkategori')
            ->groupBy(['b.idkategori', 'k.kategori'])
            ->orderBy('jumlah_stok', 'desc')
            ->get(['b.idkategori', 'k.kategori', DB::raw('sum(b.stock) as jumlah_stok')]);

        // Mengambil Data Barang Terlaris.
        $barangTerlaris = DB::table('penjualan_detail', 'pd')
            ->join('barang as b2', 'b2.id', '=', 'pd.idbarang')
            ->groupBy(['pd.idbarang', 'b2.nama'])
            ->orderBy('terjual', 'desc')
            ->limit(10)
            ->get(['idbarang', DB::raw('SUM(pd.jumlah) as terjual'), 'b2.nama']);

        $customerLoyal = DB::table('penjualan', 'pj')
            ->join('pelanggan as p', 'p.id', '=', 'pj.idpelanggan')
            ->groupBy(['pj.idpelanggan', 'p.nama'])
            ->orderBy('jml_transaksi', 'desc')
            ->limit(10)
            ->get(['pj.idpelanggan', DB::raw('count(pj.id) as jml_transaksi'), 'p.nama']);
        // dd($customerLoyal);
        return view('admin.index', [
            'barang' => $barangUrutStok,
            'kategori' => $kategoriUrutStok,
            'barangTerlaris' => $barangTerlaris,
            'customerLoyal' => $customerLoyal,
        ]);
    }
}

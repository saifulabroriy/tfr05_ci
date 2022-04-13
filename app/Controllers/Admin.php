<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Admin extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Mengambil 10 barang dengan pengadaan terbanyak.
        $barangUrutStok = $db->table('barang')->select('id, nama, stock')
            ->orderBy('stock', 'desc')
            ->limit(10)
            ->get();

        $kategoriUrutStok = $db->table('barang')->select('barang.idkategori, kategori.kategori as kategori, SUM(barang.stock) AS jumlah_stok')
            ->join("kategori", "kategori.id = barang.idkategori", '', false)
            ->groupBy(['barang.idkategori', 'kategori'])
            ->orderBy('jumlah_stok', 'desc')
            ->get();

        // Mengambil Data Barang Terlaris.
        $barangTerlaris = $db->table('penjualan_detail')->select('penjualan_detail.idbarang, SUM(penjualan_detail.jumlah) AS terjual, barang.nama')
            ->join('barang', 'barang.id = penjualan_detail.idbarang', '', false)
            ->groupBy(['penjualan_detail.idbarang', 'barang.nama'])
            ->orderBy('terjual', 'desc')
            ->limit(10)
            ->get();

        $customerLoyal = $db->table('penjualan as pj')->select('pj.idpelanggan, COUNT(pj.id) AS jml_transaksi, p.nama')
            ->join('pelanggan as p', 'p.id = pj.idpelanggan')
            ->groupBy(['pj.idpelanggan', 'p.nama'])
            ->orderBy('jml_transaksi', 'desc')
            ->limit(10)
            ->get();

        return view('admin/index', [
            'barang' => $barangUrutStok,
            'kategori' => $kategoriUrutStok,
            'barangTerlaris' => $barangTerlaris,
            'customerLoyal' => $customerLoyal,
        ]);
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\LogUserModel;
use CodeIgniter\HTTP\IncomingRequest;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Barang extends BaseController
{
    protected $barang;
    protected $kategori;
    protected $logUser;
    protected $request;

    public function __construct()
    {
        $this->barang = new BarangModel();
        $this->barang->protect(false);
        $this->logUser = new LogUserModel();
        $this->logUser->protect(false);
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
        $idkategori = $this->request->getPost('kategori');
        $nama = $this->request->getPost('nama');
        $harga = $this->request->getPost('harga');
        $stock = $this->request->getPost('stok');

        $this->barang->save([
            'idkategori' => $idkategori,
            'nama' => $nama,
            'harga' => $harga,
            'stock' => $stock,
        ]);

        $kategori = $this->kategori->find($idkategori);

        $after = [
            'kategori' => $kategori['kategori'],
            'nama' => $nama,
            'harga' => $harga,
            'stock' => $stock,
        ];

        $log = [
            'iduser' => session()->get('id'),
            'menu' => 'Barang',
            'keterangan' => 'Menambah barang',
            'before' => '',
            'after' => json_encode($after),
        ];

        $this->logUser->insert($log);

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
        $barang = $this->barang->find($id);
        $kategori = $this->kategori->find($barang['idkategori']);

        $before = [
            'kategori' => $kategori['kategori'],
            'nama' => $barang['nama'],
            'harga' => $barang['harga'],
            'stock' => $barang['stock'],
        ];

        $idkategori = $this->request->getPost('kategori');
        $nama = $this->request->getPost('nama');
        $harga = $this->request->getPost('harga');
        $stock = $this->request->getPost('stok');

        $this->barang->update($id, [
            'idkategori' => $idkategori,
            'nama' => $nama,
            'harga' => $harga,
            'stock' => $stock,
        ]);

        $kategori = $this->kategori->find($idkategori);

        $after = [
            'kategori' => $kategori['kategori'],
            'nama' => $nama,
            'harga' => $harga,
            'stock' => $stock,
        ];

        $log = [
            'iduser' => session()->get('id'),
            'menu' => 'Barang',
            'keterangan' => 'Mengubah barang',
            'before' => json_encode($before),
            'after' => json_encode($after),
        ];

        $this->logUser->insert($log);

        return redirect()->to('admin/barang')->with('success', 'Barang berhasil diubah');
    }

    public function delete($id)
    {
        $barang = $this->barang->find($id);
        $kategori = $this->kategori->find($barang['idkategori']);

        $before = [
            'kategori' => $kategori['kategori'],
            'nama' => $barang['nama'],
            'harga' => $barang['harga'],
            'stock' => $barang['stock'],
        ];

        $log = [
            'iduser' => session()->get('id'),
            'menu' => 'Barang',
            'keterangan' => 'Menghapus barang',
            'before' => json_encode($before),
            'after' => '',
        ];

        $this->barang->delete($id);

        $this->logUser->insert($log);

        return redirect()->to('admin/barang')->with('success', 'Barang berhasil dihapus');
    }

    public function previewPDF()
    {
        $cari = $this->request->getVar('q');
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
    }

    public function exportPDF()
    {
        $cari = $this->request->getVar('q');
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

        $dompdf = new Dompdf();
        $html = view('barang/exportpdf', ['barang' => $barang]);
        // dd($dompdf);
        $dompdf->load_html($html);
        $dompdf->render();
        // ob_end_clean();
        $dompdf->stream('Laporan Data Barang.pdf', array("Attachment" => false));
        exit(0);
    }

    public function exportExcel()
    {
        $cari = $this->request->getVar('q');
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

        $spreadsheet = new Spreadsheet();

        // Merge Cell untuk judul
        $spreadsheet->getActiveSheet()->mergeCells('A1:F1');

        // Judul
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Laporan Data Barang');

        // Header / Nama Kolom
        $spreadsheet->setActiveSheetIndex(0)
            // ->setCellValue('A3', 'Nomor')
            ->setCellValue('A3', 'Kategori')
            ->setCellValue('B3', 'Nama Barang ')
            ->setCellValue('C3', 'Harga')
            ->setCellValue('D3', 'Stok')
            ->setCellValue('E3', 'Tanggal Dibuat')
            ->setCellValue('F3', 'Tanggal Diperbarui');

        $column = 4;
        // Data Barang
        foreach ($barang->getResult() as $i => $barang) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $column, $barang->kategori)
                ->setCellValue('B' . $column, $barang->nama)
                ->setCellValue('C' . $column, $barang->harga)
                ->setCellValue('D' . $column, $barang->stock)
                ->setCellValue('E' . $column, $barang->created_at)
                ->setCellValue('F' . $column, $barang->updated_at);
            $column++;
        }

        // Format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Laporan Data Barang';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}

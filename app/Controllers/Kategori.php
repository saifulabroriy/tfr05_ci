<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use App\Models\LogUserModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Kategori extends BaseController
{
    protected $logUser;
    protected $kategori;
    protected $request;

    public function __construct()
    {
        $this->logUser = new LogUserModel();
        $this->logUser->protect(false);
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
            )->like('kategori', "%$cari%")->get();
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
        $kategori = $this->request->getPost('kategori');

        $this->kategori->save([
            'kategori' => $kategori,
        ]);

        $after = [
            'kategori' => $kategori,
        ];

        $log = [
            'iduser' => session()->get('id'),
            'menu' => 'Kategori',
            'keterangan' => 'Menambah Kategori',
            'before' => '',
            'after' => json_encode($after),
        ];

        $this->logUser->insert($log);

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
        $kategori = $this->kategori->find($id);

        $before = [
            'kategori' => $kategori['kategori']
        ];

        $kategori = $this->request->getPost('kategori');

        $this->kategori->update($id, [
            'kategori' => $kategori,
        ]);

        $after = [
            'kategori' => $kategori,
        ];

        $log = [
            'iduser' => session()->get('id'),
            'menu' => 'Kategori',
            'keterangan' => 'Mengubah Kategori',
            'before' => json_encode($before),
            'after' => json_encode($after),
        ];

        $this->logUser->insert($log);

        return redirect()->to('admin/kategori')->with('success', 'Kategori berhasil diubah');
    }

    public function delete($id)
    {
        $kategori = $this->kategori->find($id);

        $before = [
            'kategori' => $kategori['kategori'],
        ];

        $log = [
            'iduser' => session()->get('id'),
            'menu' => 'Kategori',
            'keterangan' => 'Menghapus Kategori',
            'before' => json_encode($before),
            'after' => '',
        ];

        $this->kategori->delete($id);

        $this->logUser->insert($log);

        return redirect()->to('admin/kategori')->with('success', 'Kategori berhasil dihapus');
    }

    public function exportPDF()
    {
        $cari = $this->request->getVar('q');
        if (isset($cari)) {
            $kategori = $this->kategori->builder()->select(
                '*',
                false
            )->like('kategori', "%$cari%")->get();
        } else {
            $kategori = $this->kategori->builder()->select(
                '*',
                false
            )->get();
        }

        $data = [
            'kategori' => $kategori,
        ];

        $dompdf = new Dompdf();
        $html = view('kategori/exportpdf', $data);
        // dd($dompdf);
        $dompdf->load_html($html);
        $dompdf->render();
        // ob_end_clean();
        $dompdf->stream('Laporan Data Kategori.pdf', array("Attachment" => false));
        exit(0);
    }

    public function exportExcel()
    {
        $cari = $this->request->getVar('q');
        if (isset($cari)) {
            $kategori = $this->kategori->builder()->select(
                '*',
                false
            )->like('kategori', "%$cari%")->get();
        } else {
            $kategori = $this->kategori->builder()->select(
                '*',
                false
            )->get();
        }

        $spreadsheet = new Spreadsheet();

        // Merge Cell untuk judul
        $spreadsheet->getActiveSheet()->mergeCells('A1:C1');

        // Judul
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Laporan Data Kategori');

        // Header / Nama Kolom
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A3', 'Kategori')
            ->setCellValue('B3', 'Tanggal Dibuat')
            ->setCellValue('C3', 'Tanggal Diperbarui');

        $column = 4;
        // Data Kategori
        foreach ($kategori->getResult() as $i => $kategori) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $column, $kategori->kategori)
                ->setCellValue('B' . $column, $kategori->created_at)
                ->setCellValue('C' . $column, $kategori->updated_at);
            $column++;
        }

        // Format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Laporan Data Kategori';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}

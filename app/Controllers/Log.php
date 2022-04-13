<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogUserModel;
use Dompdf\Dompdf;

class Log extends BaseController
{
    protected $logUser;
    protected $request;

    public function __construct()
    {
        $this->logUser = new LogUserModel();
        $this->logUser->protect(false);
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
            $logUser = $this->logUser->builder()->select(
                'log_user.id,
                user.nama as user,
                log_user.menu,
                log_user.keterangan,
                log_user.created_at',
                false
            )->join("user", "user.id = log_user.iduser", '', false)->like('menu', "%$cari%")->get();
        } else {
            $logUser = $this->logUser->builder()->select(
                'log_user.id,
                user.nama as user,
                log_user.menu,
                log_user.keterangan,
                log_user.created_at',
                false
            )->join("user", "user.id = log_user.iduser", '', false)->get();
        }

        // Data yang akan dikirim
        $data = [
            'jumlah_logUser' => $this->logUser->paginate($entri),
            'pager' => $this->logUser->pager,
            'q' => $cari,
            'entri' => $entri,
            'page' => $page,
            'log' => $logUser,
        ];

        return view('log/index', $data);
    }

    public function previewPDF()
    {
        $cari = $this->request->getVar('q');
        if (isset($cari)) {
            $logUser = $this->logUser->builder()->select(
                'log_user.id,
                user.nama as user,
                log_user.menu,
                log_user.keterangan,
                log_user.created_at',
                false
            )->join("user", "user.id = log_user.iduser", '', false)->like('menu', "%$cari%")->get();
        } else {
            $logUser = $this->logUser->builder()->select(
                'log_user.id,
                user.nama as user,
                log_user.menu,
                log_user.keterangan,
                log_user.created_at',
                false
            )->join("user", "user.id = log_user.iduser", '', false)->get();
        }
    }

    public function exportPDF()
    {
        $cari = $this->request->getVar('q');
        if (isset($cari)) {
            $logUser = $this->logUser->builder()->select(
                'log_user.id,
                user.nama as user,
                log_user.menu,
                log_user.keterangan,
                log_user.created_at',
                false
            )->join("user", "user.id = log_user.iduser", '', false)->like('menu', "%$cari%")->get();
        } else {
            $logUser = $this->logUser->builder()->select(
                'log_user.id,
                user.nama as user,
                log_user.menu,
                log_user.keterangan,
                log_user.created_at',
                false
            )->join("user", "user.id = log_user.iduser", '', false)->get();
        }

        $dompdf = new Dompdf();
        $html = view('log/exportpdf', ['log' => $logUser]);
        // dd($dompdf);
        $dompdf->load_html($html);
        $dompdf->render();
        // ob_end_clean();
        $dompdf->stream('Laporan Log User.pdf', array("Attachment" => false));
        exit(0);
    }
}

<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php

if (session()->has('success')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif ?>

<h3 class="mb-3">Log User</h3>

<?= form_open(base_url('/admin/log/exportpdf'), ['class' => 'd-inline']) ?>
<?php csrf_field() ?>
<input type="hidden" name="q" value="<?= $q ?>">
<button type="submit" class="btn btn-warning"><i class="fa fa-print"></i> Export PDF</button>
</form>

<div class="datatable-wrapper shadow-lg rounded mt-4">
    <div class="datatable-content p-4">
        <div class="datatable-search-wrap d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <p class="mb-0">Menampilkan</p>

                <form action="<?= base_url('') ?>/admin/log" class="mx-2">
                    <input type="hidden" name="page" value="<?= $page ?>">
                    <input type="hidden" name="entri" value="<?= $q ?>">
                    <select onchange="this.form.submit()" name="entri" id="" class="form-control py-0 px-2">
                        <option value="10" <?= $entri == 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= $entri == 25 ? 'selected' : '' ?>>25</option>
                    </select>
                </form>

                <p class="mb-0">entri</p>
            </div>
            <div class="d-flex align-items-center">
                <p class="mb-0 mr-2">Pencarian: </p>
                <form action="<?= base_url('') ?>/admin/log" method="get">
                    <input type="hidden" name="page" value="<?= $page ?>">
                    <input type="hidden" name="entri" value="<?= $entri ?>">
                    <input type="text" name="q" id="" class="form-control" value="<?= $q ?>" />
                </form>
            </div>
        </div>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">User</th>
                    <th scope="col">Menu</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($log->getResult() as $i => $log) : ?>
                    <tr>
                        <th scope="row"><?= $offset + $i + 1 ?></th>
                        <td> <?= $log->user ?></td>
                        <td> <?= $log->menu ?></td>
                        <td> <?= $log->keterangan ?></td>
                        <td> <?= $log->created_at ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?= $pager->links() ?>
    </div>

</div>

<?= $this->endSection() ?>
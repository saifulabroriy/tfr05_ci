<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php

if (session()->has('success')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif ?>
<h3 class="mb-3">Data Kategori</h3>

<a href="<?= base_url('') ?>/admin/kategori/create" class="btn btn-success"><i class="fa fa-plus"></i> Tambah</a>
<?= form_open(base_url('/admin/kategori/exportpdf'), ['class' => 'd-inline']) ?>
<?php csrf_field() ?>
<input type="hidden" name="q" value="<?= $q ?>">
<button type="submit" class="btn btn-warning"><i class="fa fa-print"></i> Export PDF</button>
</form>
<?= form_open(base_url('/admin/kategori/exportexcel'), ['class' => 'd-inline']) ?>
<?php csrf_field() ?>
<input type="hidden" name="q" value="<?= $q ?>">
<button type="submit" class="btn btn-warning"><i class="fa fa-file-excel"></i> Export Excel</button>
</form>

<div class="datatable-wrapper shadow-lg rounded mt-4">
    <div class="datatable-heading p-4 border-bottom">
        <b>Data Kategori</b>
    </div>

    <div class="datatable-content p-4">
        <div class="datatable-search-wrap d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <p class="mb-0">Menampilkan</p>

                <form action="<?= base_url('') ?>/admin/kategori" class="mx-2">
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
                <form action="<?= base_url('') ?>/admin/kategori" method="get">
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
                    <th scope="col">Kategori</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kategori->getResult() as $i => $kategori) : ?>
                    <tr>
                        <th scope="row"><?= $i + 1 ?></th>
                        <td> <?= $kategori->kategori ?></td>
                        <td>
                            <div class="d-flex">
                                <a href="<?= base_url('') ?>/admin/kategori/<?= $kategori->id ?>/edit" class="btn btn-warning mr-2"><i class="fa fa-edit"></i> Edit</a>

                                <?php echo form_open("admin/kategori/" . $kategori->id) ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <?php csrf_field() ?>
                                <button onclick="return confirm('Apakah anda yakin ingin menghapus data?')" type="submit" class="btn btn-danger"><i class="fa fa-trash"></i>
                                    Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?= $pager->links() ?>
    </div>

</div>

<?= $this->endSection() ?>
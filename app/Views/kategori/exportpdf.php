<?= $this->extend('layout-pdf') ?>
<?= $this->section('content') ?>
<h3 id="title">Laporan Data Kategori</h3>

<body>
    <table class="table mt-4">
        <thead>
            <tr style="background-color: transparent;">
                <th>No.</th>
                <th>Kategori</th>
            </tr>
        </thead>
        <tbody>
            <?= dd($kategori->getNumRows()) ?>
            <?php foreach ($kategori->getResult() as $i => $kategori) : ?>
                <tr>
                    <th scope="row"><?= $i + 1 ?></th>
                    <td> <?= $kategori->kategori ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
<?= $this->endSection() ?>
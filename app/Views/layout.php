<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('front/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('front/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('front/css/bootstrap-select.min.css') ?>">
    <link rel="shortcut icon" href="<?= base_url('front/img/app-logo.jpg') ?>" type="image/x-icon">
    <title>POS</title>
    <!-- Font Awesome Script -->
    <script src="https://kit.fontawesome.com/bc14fa0285.js" crossorigin="anonymous"></script>
    <script src="<?= base_url('front/js/jquery-3.6.0.min.js') ?>"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary" style="position: relative;">
        <a class="navbar-brand" href="<?= '' ?>">POS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav mr-auto">

            </ul>

            <form class="form-inline my-2 my-lg-0" action="<?= '' ?>" style="position: relative;" autocomplete="off">
                <input class="form-control mr-sm-2" type="text" id="searchbar" name="q" placeholder="Search" value="<?= !empty($_GET['q']) ? $_GET['q'] : '' ?>" required>
                <button class="btn btn-danger my-2 my-sm-0" type="submit">Search</button>

                <!-- Form Popup Pencarian Ajax -->
                <div id="popup-search" style="position: absolute;top:40px;left:0;right:0;z-index: 1;" class="bg-white shadow"></div>
            </form>

            <ul class="navbar-nav ml-auto">
                <?php if (!session()->get('id')) : ?>
                    <li class="nav-item">
                        <a href="<?= '#' ?>" class="nav-link">Login</a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= '#' ?>" class="nav-link">Register</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link "><?= session()->get('nama') ? session()->get('nama') : 'Guest' ?></a>
                    </li>
                    <li class="nav-item">
                        <?= form_open(base_url('/logout'), ['class' => 'd-inline', 'id' => 'form-logout']) ?>
                        <?php csrf_field(); ?>
                        <a href="#" onclick="logout()" class="nav-link">Logout</a>
                        </form>
                    </li>

                    <!-- Menu khusus untuk Admin Web MOOVEE. -->
                    <?php if (session('role') == 1) : ?>
                        <li class="nav-item">
                            <a href="<?= '' ?>" class="nav-link">Admin Page</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

        </div>

    </nav>

    <script>
        function logout() {
            $('#form-logout').submit();
        }

        $(document).ready(function() {
            $('#searchbar').on('input', function(e) {
                // console.log('onChange gan', e.target.value)
                let q = e.target.value

                if (q == '') {
                    // Kolom pencarian kosong,
                    $("#popup-search").empty() // Kosongkan container
                    return
                }
                $.ajax({
                    url: `<?= '' ?>/site/searchajax/${q}`,
                    dataType: 'JSON',
                    success: function(res) {
                        // console.log('res ajax', typeof res)

                        $("#popup-search").empty() // Kosongkan container
                        for (let i = 0; i < res.length; i++) {
                            const film = res[i];
                            // Append elemen hasil pencarian
                            // console.log('film gan', film)
                            $("#popup-search").append(`
                            <a href="<?= '' ?>/site/movie/${film.id}">
                                <div class="p-1" style="display: flex;flex-direction: row;">
                                    <div class="thumbnail-wrap mr-2" style="flex: 0.2;">
                                        <!-- Image Overlay -->
                                        <div class="overlay">
                                            <i style="font-size: 48px;color:white" class="fas fa-play-circle"></i>
                                        </div>
                                        <img width="100%" style="height:72px !important;padding: 0" src="<?= '' ?>/img/thumbnails/${film.id}.jpg" class="img-thumbnail rounded" alt="...">
                                    </div>
                                    <div class="" style="flex: 0.8;">
                                        <h6>${film.judul}</h6>
                                        <p style="font-size: 12px;color: #aaa;"><i class="fas fa-star"></i> ${film.rating}, Dec 23, 2021, <i class="fas fa-clock"></i> ${film.durasi} menit</p>
                                    </div>
                                </div>
                            </a>
                            `)
                        }
                    }
                });
            })
        })
    </script>
    <!-- Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Opening Tag Container -->
    <?php $this->request = \Config\Services::request(); ?>
    <div id="main-wrap" class="d-flex">
        <div id="sidebar" class="p-3" style="flex:0.15">
            <div class="sidebar-section-group border-top border-bottom border-light">
                <p class="sidebar-section-category mb-2">DASHBOARD</p>
                <a href="<?= base_url('') ?>/admin" class="d-block py-2 <?= $this->request->getPath() == '/admin' ? 'active' : '' ?>">
                    <i class="fa fa-home menu-icon"></i>
                    <p class="sidebar-item mb-0 d-inline">Home</p>
                </a>
            </div>

            <div class="sidebar-section-group border-top border-bottom border-light">
                <p class="sidebar-section-category mb-2">MASTER</p>
                <a href="<?= base_url('') ?>/admin/kategori" class="d-block py-2 <?= $this->request->getPath() == '/admin/kategori' ? 'active' : '' ?>">
                    <i class="fa fa-chart-bar menu-icon"></i>
                    <p class="sidebar-item mb-0 d-inline">Kategori</p>
                </a>
                <a href="<?= base_url('') ?>/admin/barang" class="d-block py-2 <?= $this->request->getPath() == '/admin/barang' ? 'active' : '' ?>">
                    <i class="fa fa-briefcase menu-icon"></i>
                    <p class="sidebar-item mb-0 d-inline">Barang</p>
                </a>
                <a href="<?= base_url('') ?>/admin/pelanggan" class="d-block py-2 <?= $this->request->getPath() == '/admin/pelanggan' ? 'active' : '' ?>">
                    <i class="fa fa-briefcase menu-icon"></i>
                    <p class="sidebar-item mb-0 d-inline">Pelanggan</p>
                </a>
            </div>

            <!-- {{-- Menu-menu Transaksi --}} -->
            <div class="sidebar-section-group border-top border-bottom border-light">
                <p class="sidebar-section-category mb-2">TRANSAKSI</p>
                <a href="<?= base_url('') ?>/admin/penjualan" class="d-block py-2 <?= $this->request->getPath() == '/admin/penjualan' ? 'active' : '' ?>">
                    <i class="fa fa-solid fa-cash-register menu-icon"></i>
                    <p class="sidebar-item mb-0 d-inline">Penjualan</p>
                </a>
            </div>

            <!-- {{-- Menu Logging --}} -->
            <div class="sidebar-section-group border-top border-bottom border-light">
                <p class="sidebar-section-category mb-2">LOGGING</p>
                <a href="<?= base_url('') ?>/admin/log" class="d-block py-2 <?= $this->request->getPath() == '/admin/log' ? 'active' : '' ?>">
                    <i class="fa fa-solid fa-book menu-icon"></i>
                    <p class="sidebar-item mb-0 d-inline">Log User</p>
                </a>
            </div>

        </div>
        <div id="main" style="flex:0.85">
            <div class="container pt-5 pb-5">
                <?= $this->renderSection('content') ?>
            </div>

            <div id="footer" class="bg-light pt-4 pb-4">
                <h6 class="text-dark text-center m-0">Copyright <?= date('Y') ?> POS.</h6>
            </div>
        </div>
    </div>
    <!-- Closing Tag Container -->

    <script src="<?= base_url('front/js/popper.min.js') ?>"></script>
    <script src="<?= base_url('front/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('front/js/bootstrap-select.min.js') ?>"></script>
    <script src="<?= base_url('front/js/script.js') ?>"></script>
</body>

</html>
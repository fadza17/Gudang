<?php
require "function.php";
require "cek.php";
require "admin.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Produk</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">Inventory</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Main
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link" href="produk.php">produk</a>
                                    <a class="nav-link" href="warehouse.php">Gudang</a>
                                    <a class="nav-link" href="stok.php">Stok</a>
                                    <a class="nav-link" href="management_user.php">Management User</a>
                                </nav>
                            </div>
                            <a class="nav-link" href="masuk.php">Barang Masuk</a>
                            <a class="nav-link" href="keluar.php">Barang Keluar</a>
                            <a class="nav-link" href="order.php">Order</a>
                            <a class="nav-link" href="logout.php">Logout</a>
                            
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?php echo htmlspecialchars($_SESSION["user_email"]); ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Produk</h1>
                        <div class="card mb-4">
                            <div class="card-header">
                                <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah Produk
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Produk</th>
                                                <th>Keterangan</th>
                                                <th>Satuan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ambilsemuastore=mysqli_query($sambung,"SELECT * FROM stock");
                                            $i=1; // Initialize counter outside the loop
                                            while($data=mysqli_fetch_array($ambilsemuastore)){
                                                $namabenda=$data['namabarang'];
                                                $keterangan=$data['keterangan'];
                                                $tipesatuan=$data['satuan'];
                                                $idb=$data['idstock'];
                                            ?>

                                            <tr>
                                                <td><?=$i++?></td>
                                                <td><?=$namabenda?></td>
                                                <td><?=$keterangan?></td>
                                                <td><?=$tipesatuan?></td>
                                                <td>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?=$idb?>">
                                                        Edit Produk
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?=$idb?>">
                                                        Hapus Produk
                                                    </button>
                                                </td>
                                            </tr>
                                                <!-- Edit  Modal -->
    
                                                <div class="modal fade" id="edit<?=$idb?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                        
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Edit Produk</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            
                                                            <!-- Modal body -->
                                                            <form method="post">
                                                                <div class="modal-body">
                                                                    <input type="TEXT" name="produk" value="<?=$namabenda;?>" style="width: 100%; margin-bottom:15px;" required>
                                                                    <input type="varchar" name="keterangan" value="<?=$keterangan;?>"style="width: 100%; margin-bottom:15px;" required>
                                                                    <select name="satuan" value="<?=$satuan;?>" style="width: 100%; margin-bottom:15px;" required>
                                                                        <option value="" disabled selected>Pilih Satuan</option>
                                                                        <option value="Buah">Buah</option>
                                                                        <option value="Pcs">Pcs</option>
                                                                        <option value="Meter">Meter</option>
                                                                    </select>
                                                                    <input type="hidden" name="idb" value="<?=$idb;?>">
                                                                    <div style="text-align: right;">
                                                                        <button type="Submit" class="btn btn-warning" name="updatebarang">Update</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- delete  Modal -->
    
                                                <div class="modal fade" id="delete<?=$idb?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                        
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Hapus Produk</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            
                                                            <!-- Modal body -->
                                                            <form method="post">
                                                                <div class="modal-body">
                                                                    apakah yakin akan menghapus <?=$namabenda;?> ?
                                                                    <input type="hidden" name="idb" value="<?=$idb;?>">
                                                                    <div style="text-align: right;">
                                                                        <button type="Submit" class="btn btn-danger" name="Hapusbarang">Hapus</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            };
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
    <!-- The Modal -->
    
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Produk</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                <form method="post">
                    <div class="modal-body">
                        <input type="TEXT" name="namabarang" placeholder="Nama Barang" style="width: 100%; margin-bottom:15px;" required>
                        <input type="varchar" name="keterangan" placeholder="Keterangan" style="width: 100%; margin-bottom:15px;" required>
                        <select name="satuan" style="width: 100%; margin-bottom:15px;" required>
                            <option value="" disabled selected>Pilih Satuan</option>
                            <option value="Buah">Buah</option>
                            <option value="Pcs">Pcs</option>
                            <option value="Meter">Meter</option>
                        </select>
                        <div style="text-align: right;">
                            <button type="Submit" class="btn btn-primary" name="Submitproduk">Submit</button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</html>

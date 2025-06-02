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
        <title>Barang Keluar</title>
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
                        <h1 class="mt-4">Barang Keluar</h1>
                        <div class="card mb-4">
                            <div class="card-header">
                                <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Barang Keluar
                                </button>
                                <?php
                                // Check if the 'a' parameter exists and equals 1
                                if(isset($_GET['a']) && $_GET['a'] == 1){
                                    // Get the product and warehouse values if they exist
                                    $pilihproduknya = isset($_GET['i']) ? $_GET['i'] : '';
                                    $pilihgudangnya = isset($_GET['d']) ? $_GET['d'] : '';
                                    $ambildata = mysqli_query($sambung,"SELECT * FROM keluar k JOIN warehouse w ON w.idgudang = k.idgudang JOIN stock t ON t.idstock = k.idstock");
                                    $ambil=mysqli_fetch_array($ambildata);
                                    $produk=$ambil['namabarang'];
                                    $gudang=$ambil['namagudang'];
                                ?>
                                    <div class="alert alert-danger alert-dismissible fade show">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        <strong>Perhatian!</strong> Order Produk <?php echo htmlspecialchars($produk); ?> dari gudang <?php echo htmlspecialchars($gudang); ?> melebihi jumlah barang yang tersedia.
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No tiket</th>
                                                <th>Nama Gudang</th>
                                                <th>Nama Produk</th>
                                                <th>Barang Keluar</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ambilsemuastore=mysqli_query($sambung,"SELECT * FROM keluar k JOIN warehouse w ON w.idgudang = k.idgudang JOIN stock t ON t.idstock = k.idstock ORDER BY idkeluar DESC");
                                            $i=1; // Initialize counter outside the loop
                                            while($data=mysqli_fetch_array($ambilsemuastore)){
                                                $namabenda=$data['namabarang'];
                                                $namasimpanan=$data['namagudang'];
                                                $revokekeluar=$data['qty'];
                                                $idk=$data['idkeluar'];
                                                $simpananrevokekeluar = $data['idgudang'];
                                                $bendarevokekeluar = $data['idstock'];
                                                $tiketno=$data['ticket'];
                                            ?>

                                            <tr>
                                                <td><?=$i++?></td>
                                                <td><?=$tiketno?></td>
                                                <td><?=$namasimpanan?></td>
                                                <td><?=$namabenda?></td>
                                                <td><?=$revokekeluar?></td>
                                                <td>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#revoke<?=$idk?>">
                                                        revoke
                                                    </button>
                                                </td>
                                            </tr>
                                               <!-- The Modal -->
    
                                                <div class="modal fade" id="revoke<?=$idk?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                        
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Revoke Barang Keluar</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            
                                                            <!-- Modal body -->
                                                            <form method="post">
                                                                <div class="modal-body">
                                                                    Apakah anda yakin ingin menarik data keluar ini ?
                                                                    Jumlah stok akan bertambah <?=$revokekeluar?> 
                                                                    <input type="hidden" name="idk" value="<?=$idk;?>">
                                                                    <input type="hidden" name="revokekeluar" value="<?=$revokekeluar;?>">
                                                                    <input type="hidden" name="simpananrevokekeluar" value="<?=$simpananrevokekeluar;?>">
                                                                    <input type="hidden" name="bendarevokekeluar" value="<?=$bendarevokekeluar;?>">
                                                                    <div style="text-align: right;">
                                                                        <button type="Submit" class="btn btn-danger" name="Submitrevokekeluar">revoke</button>
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
                    <h4 class="modal-title">Barang Keluar</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                <form method="post">
                    <div class="modal-body">
                        <select name="penyimpanan" style="width: 100%; margin-bottom:15px;">
                            <?php
                            $ambildb = mysqli_query($sambung, "SELECT * FROM warehouse");
                            while($fetcharray=mysqli_fetch_array($ambildb)){
                                $namagudang = $fetcharray['namagudang'];
                                $idgudang = $fetcharray['idgudang'];
                            
                            ?>

                            <option value="<?=$idgudang;?>"><?=$namagudang;?></option>

                            <?php
                            }

                            ?>
                        </select>
                        <select name="prodak" style="width: 100%; margin-bottom:15px;">
                            <?php
                            $ambilbarangdb = mysqli_query($sambung, "SELECT * FROM stock");
                            while($fetcharray=mysqli_fetch_array($ambilbarangdb)){
                                $namaproduk = $fetcharray['namabarang'];
                                $idbarang = $fetcharray['idstock'];
                            
                            ?>

                            <option value="<?=$idbarang;?>"><?=$namaproduk;?></option>

                            <?php
                            }

                            ?>
                        </select>
                        <input type="number" name="qtykeluar" placeholder="Qty" style="width: 100%; margin-bottom:15px;" required>
                        <div style="text-align: right;">
                            <button type="Submit" class="btn btn-primary" name="Submitkeluar">Submit</button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</html>

<?php
require "function.php";
require "cek.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - SB Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="outside.php">Inventory</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Logout</div>
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
                        <h1 class="mt-4">jumlah barang</h1>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Barang Order
                                </button>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="user_email" value="<?php echo htmlspecialchars($_SESSION["user_email"] ?? ''); ?>">
                                    <button type="submit" class="btn btn-success ml-2" name="generate">
                                        Generate Ticket
                                    </button>
                                </form>
                                <h4 class="mt-4">your ticket : 
                                    <?php 
                                    if (isset($_SESSION["user_email"])) {
                                        $user_email = mysqli_real_escape_string($sambung, $_SESSION["user_email"]);
                                        
                                        // Query to select ticket where email matches session email and tersedia is 'ya'
                                        $ambilindb = mysqli_query($sambung, "SELECT no_order FROM ticket WHERE email = '$user_email' AND tersedia = 'ya'");
                                        
                                        // Check if query was successful and has any results
                                        if ($ambilindb && mysqli_num_rows($ambilindb) > 0) {
                                            $simpandb = mysqli_fetch_array($ambilindb);
                                            $tersedia = htmlspecialchars($simpandb['no_order']);
                                            echo $tersedia;
                                        } else {
                                            echo "No ticket found";
                                        }
                                    } else {
                                        echo "Not logged in";
                                    }
                                    ?>
                                </h4>
                                <form method="post" class="d-flex justify-content-end">
                                    <?php
                                    $idticketku = '';
                                    if (isset($_SESSION["user_email"])) {
                                        $user_email = mysqli_real_escape_string($sambung, $_SESSION["user_email"]);
                                        $ambiltix = mysqli_query($sambung, "SELECT * FROM ticket WHERE email = '$user_email' AND tersedia = 'ya' LIMIT 1");
                                        if ($ambiltix && mysqli_num_rows($ambiltix) > 0) {
                                            $file = mysqli_fetch_array($ambiltix);
                                            $idticketku = $file['idticket'];
                                        }
                                    }
                                    ?>
                                    <input type="hidden" name="idtix" value="<?php echo htmlspecialchars($idticketku); ?>">
                                    <button type="submit" class="btn btn-success ml-2" name="kirim" <?php echo empty($idticketku) ? 'disabled' : ''; ?>>
                                        Kirim
                                    </button>
                                </form>
                            </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No Ticket</th>
                                                <th>Nama Gudang</th>
                                                <th>Nama Produk</th>
                                                <th>jumlah</th>
                                                <th>status</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php
                                        $tixdas = mysqli_query($sambung, "SELECT * FROM ticket WHERE email = '$user_email' AND dash = 'ya' LIMIT 1");
                                        $ada= mysqli_fetch_array($tixdas);
                                        $tixdk = $ada['idticket'];
                                        $ambilsemuapesan=mysqli_query($sambung,"SELECT * FROM pesan p JOIN warehouse w ON w.idgudang = p.idgudang JOIN stock t ON t.idstock = p.idstock JOIN ticket i ON i.idticket = p.idticket WHERE i.dash = 'ya' AND i.idticket = $tixdk;");
                                        $i=1; // Initialize counter outside the loop
                                        while($data=mysqli_fetch_array($ambilsemuapesan)){
                                            $namabenda=$data['namabarang'];
                                            $namasimpanan=$data['namagudang'];
                                            $ketersediaan=$data['qty'];
                                            $cekstatus=$data['status'];
                                            $notix=$data['no_order'];
                                        ?>

                                        <tr>
                                            <td><?=$i++?></td>
                                            <td><?=$notix?></td>
                                            <td><?=$namasimpanan?></td>
                                            <td><?=$namabenda?></td>
                                            <td><?=$ketersediaan?></td>
                                            <td><?=$cekstatus?></td>
                                        </tr>

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
                    <h4 class="modal-title">Barang Order</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                <form method="post">
                    <div class="modal-body">
                        <select name="ticketkel" style="width: 100%; margin-bottom:15px;">
                            <?php
                            $ambildbtix = mysqli_query($sambung, "SELECT * FROM ticket WHERE email = '$user_email' AND tersedia = 'ya'");
                            while($fetcharraya=mysqli_fetch_array($ambildbtix)){
                                $namawo = $fetcharraya['no_order'];
                                $idticket = $fetcharraya['idticket'];
                            
                            ?>

                            <option value="<?=$idticket;?>"><?=$namawo;?></option>

                            <?php
                            }

                            ?>
                        </select>
                        <select name="Gudangku" style="width: 100%; margin-bottom:15px;">
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
                        <select name="barangku" style="width: 100%; margin-bottom:15px;">
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
                        <input type="number" name="qtyorder" placeholder="Qty" style="width: 100%; margin-bottom:15px;" required>
                        <div style="text-align: right;">
                            <button type="Submit" class="btn btn-primary" name="Submitorder">Submit</button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</html>

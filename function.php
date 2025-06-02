<?php
session_start();
//menyambungkan ke db
$sambung = mysqli_connect("localhost","root","","gudang");

// menambahkan gudang
if(isset($_POST['Submitgudang'])){
    $gudang = mysqli_real_escape_string($sambung, $_POST['namagudang']);

    $inputdb = mysqli_query($sambung,"insert into warehouse (namagudang) values('$gudang')");
    if($inputdb){
        header("location:warehouse.php");
    }else{
        echo "gagal";
        header("location:warehouse.php");
    };
};

//menambahkan produk
if(isset($_POST['Submitproduk'])){
    $namabarang = mysqli_real_escape_string($sambung, $_POST['namabarang']);
    $keterangan = mysqli_real_escape_string($sambung, $_POST['keterangan']);
    $satuan = mysqli_real_escape_string($sambung, $_POST['satuan']);

    $indb = mysqli_query($sambung,"insert into stock (namabarang,keterangan,satuan) values('$namabarang','$keterangan','$satuan')");
    if($indb){
        header("location:produk.php");
    }else{
        echo "gagal";
        header("location:produk.php");
    };
};

//menambahkan stock di store
if(isset($_POST['Submitstok'])){
    $pilihgudang = mysqli_real_escape_string($sambung, $_POST['gudangnya']);
    $pilihproduk = mysqli_real_escape_string($sambung, $_POST['produknya']);
    $stocknya = mysqli_real_escape_string($sambung, $_POST['stockbarang']);

    $masukdb = mysqli_query($sambung,"insert into store (idgudang,idstock,stock) values('$pilihgudang','$pilihproduk','$stocknya')");
    if($indb){
        header("location:stok.php");
    }else{
        echo "gagal";
        header("location:stok.php");
    };
};

//barang masuk
if(isset($_POST['Submitmasuk'])){
    $pilihgudangnya = mysqli_real_escape_string($sambung, $_POST['simpanan']);
    $pilihproduknya = mysqli_real_escape_string($sambung, $_POST['barang']);
    $masuknya = mysqli_real_escape_string($sambung, $_POST['qty']);

    //ambil data ke db menggunakan query AND karena 2 syarat yaitu idgudang dan idproduk
    $cekstock = mysqli_query($sambung,"SELECT * FROM `store` WHERE idgudang='$pilihgudangnya' AND idstock='$pilihproduknya'");
    $cekdata = mysqli_num_rows($cekstock);
    if($cekdata>0){
        $ambildatanya=mysqli_fetch_array($cekstock);
        //ambil kolom spesifik yaitu nilai stock pada case ini lalu buat query masuk yaitu jumlah
        $stoksekarang = $ambildatanya['stock'];
        $tambahstokdenganqty=$stoksekarang+$masuknya;
        //update stock masuk
        $updatestockmasuk=mysqli_query($sambung,"UPDATE store SET stock='$tambahstokdenganqty' WHERE idgudang='$pilihgudangnya' AND idstock='$pilihproduknya'");
        //masukan data perpindahan ke dalam db sendiri
        $masukdb = mysqli_query($sambung,"insert into masuk (idgudang,idstock,qty) values('$pilihgudangnya','$pilihproduknya','$masuknya')");
    }else{
        $masukdb = mysqli_query($sambung,"insert into store (idgudang,idstock,stock) values('$pilihgudangnya','$pilihproduknya','$masuknya')");
        //masukan data perpindahan ke dalam db sendiri
        $masukdb = mysqli_query($sambung,"insert into masuk (idgudang,idstock,qty) values('$pilihgudangnya','$pilihproduknya','$masuknya')");
    };
        
    if($masukdb&&$updatestockmasuk){
        header("location:masuk.php");
    }else{
        echo "gagal";
        header("location:masuk.php");
    };
};

//barang keluar
if(isset($_POST['Submitkeluar'])){
    $pilihgudangnya = mysqli_real_escape_string($sambung, $_POST['penyimpanan']);
    $pilihproduknya = mysqli_real_escape_string($sambung, $_POST['prodak']);
    $keluarnya = mysqli_real_escape_string($sambung, $_POST['qtykeluar']);

    
    //ambil data ke db menggunakan query AND karena 2 syarat yaitu idgudang dan idproduk
    $cekstock = mysqli_query($sambung,"SELECT * FROM `store` WHERE idgudang='$pilihgudangnya' AND idstock='$pilihproduknya'");
    $ambildatanya=mysqli_fetch_array($cekstock);
    
    //ambil kolom spesifik yaitu nilai stock pada case ini lalu buat query masuk yaitu jumlah
    $stoksekarang = $ambildatanya['stock'];
    $kurangistokdenganqty=$stoksekarang-$keluarnya;
    //masukan data perpindahan ke dalam db sendiri

    if($kurangistokdenganqty < 1){
        header("location:keluar.php?a=1&i=$pilihproduknya&d=$pilihgudangnya");
    }else{
        //update stock masuk
        $updatestockmasuk=mysqli_query($sambung,"UPDATE store SET stock='$kurangistokdenganqty' WHERE idgudang='$pilihgudangnya' AND idstock='$pilihproduknya'");
        //masukan data perpindahan ke dalam db sendiri
        $masukdb = mysqli_query($sambung,"INSERT into keluar (ticket,idgudang,idstock,qty) values('0','$pilihgudangnya','$pilihproduknya','$keluarnya')");
        header("location:keluar.php");
    };
};
//membuat ticket
if(isset($_POST['generate'])) {
    $ambilemail = mysqli_real_escape_string($sambung, $_POST['user_email']);
    $updateTickets = mysqli_query($sambung, "UPDATE ticket SET dash = 'tidak', tersedia = 'tidak' WHERE email = '$ambilemail' AND dash = 'ya'");

    $notiket=mysqli_query($sambung, "SELECT number_of_ticket FROM ticket ORDER BY idticket DESC LIMIT 1;");
    $cek=mysqli_num_rows($notiket);
    if($cek>0){
        $simpantiket = mysqli_fetch_array($notiket);
        $ambilno = $simpantiket['number_of_ticket'];
        $nextTicketNumber = intval($ambilno) + 1;
    }else{
        $nextTicketNumber= 10000;
    };

    $no_order = "IN$nextTicketNumber";

    $uptix=mysqli_query($sambung, "INSERT INTO `ticket` (`idticket`, `no_order`, `number_of_ticket`, `email`, `tersedia`, `dash`) VALUES (NULL, '$no_order', '$nextTicketNumber', '$ambilemail', DEFAULT, DEFAULT);");
    if($uptix) {
        // Store the ticket information in session to display on the dashboard
        $_SESSION['latest_ticket'] = $no_order;
    } else {
        $_SESSION['latest_ticket'] = "Failed to generate ticket";
    };
    header("Location: outside.php");
    exit();
}
//update produk
if(isset($_POST['updatebarang'])){
    $idproduk = mysqli_real_escape_string($sambung, $_POST['idb']);
    $namaproduk = mysqli_real_escape_string($sambung, $_POST['produk']);
    $upkete = mysqli_real_escape_string($sambung, $_POST['keterangan']);
    $upsat = mysqli_real_escape_string($sambung, $_POST['satuan']);
    
    $updateproduk = mysqli_query($sambung,"UPDATE stock SET namabarang='$namaproduk', keterangan='$upkete', satuan='$upsat' WHERE idstock='$idproduk'");

    //cek apakah berhasil
    if($updateproduk){
        header("location:produk.php");
    }else{
        echo "gagal";
        header("location:produk.php");
    };
};

//menghapus produk
if(isset($_POST['Hapusbarang'])){
    $idprodukhapus = mysqli_real_escape_string($sambung, $_POST['idb']);

    $hapusproduk = mysqli_query($sambung,"DELETE FROM stock WHERE idstock='$idprodukhapus'");
    $hapuskeluar=mysqli_query($sambung, "DELETE FROM keluar WHERE idstock='$idprodukhapus'");
    $hapusmasuk=mysqli_query($sambung, "DELETE FROM masuk WHERE idstock='$idprodukhapus'");
    $hapuskeluar=mysqli_query($sambung, "DELETE FROM store WHERE idstock='$idprodukhapus'");

    //cek apakah berhasil
    if($hapusproduk){
        header("location:produk.php");
    }else{
        echo "gagal";
        header("location:produk.php");
    };
};

//update Gudang
if(isset($_POST['updategudang'])){
    $idgudang = mysqli_real_escape_string($sambung, $_POST['idg']);
    $namagudang = mysqli_real_escape_string($sambung, $_POST['namagudang']);
    
    $updategudang = mysqli_query($sambung,"UPDATE warehouse SET namagudang='$namagudang' WHERE idgudang='$idgudang'");

    //cek apakah berhasil
    if($updategudang){
        header("location:warehouse.php");
    }else{
        echo "gagal";
        header("location:warehouse.php");
    };
};

//menghapus gudang
if(isset($_POST['Hapusgudang'])){
    $idgudanghapus = mysqli_real_escape_string($sambung, $_POST['idg']);

    $hapusgudang = mysqli_query($sambung,"DELETE FROM warehouse WHERE idgudang='$idgudanghapus'");
    $hapuskeluar = mysqli_query($sambung,"DELETE FROM keluar WHERE idgudang='$idgudanghapus'");
    $hapusmasuk = mysqli_query($sambung,"DELETE FROM masuk WHERE idgudang='$idgudanghapus'");
    $hapusstore = mysqli_query($sambung,"DELETE FROM store WHERE idgudang='$idgudanghapus'");

    //cek apakah berhasil
    if($hapusproduk){
        header("location:warehouse.php");
    }else{
        echo "gagal";
        header("location:warehouse.php");
    };
};

//menghapus produk
if(isset($_POST['Hapusstok'])){
    $idstokhapus = mysqli_real_escape_string($sambung, $_POST['ids']);

    $hapusstok = mysqli_query($sambung,"DELETE FROM store WHERE idstore='$idstokhapus'");

    //cek apakah berhasil
    if($hapusstok){
        header("location:stok.php");
    }else{
        echo "gagal";
        header("location:stok.php");
    };
};

//revoke barang masuk
if(isset($_POST['Submitrevoke'])){
    $idbarangmasuk = mysqli_real_escape_string($sambung, $_POST['idm']);
    $jumlahrevokemasuk = mysqli_real_escape_string($sambung, $_POST['revokemasuk']);
    $namagudangrevoke = mysqli_real_escape_string($sambung, $_POST['simpananrevoke']);
    $namabendarevoke = mysqli_real_escape_string($sambung, $_POST['bendarevoke']);
    
    //ambil data ke db menggunakan query AND karena 2 syarat yaitu idgudang dan idproduk
    $cekstock = mysqli_query($sambung,"SELECT * FROM `store` WHERE idgudang='$namagudangrevoke' AND idstock='$namabendarevoke'");
    $ambildatanya=mysqli_fetch_array($cekstock);
    
    //ambil kolom spesifik yaitu nilai stock pada case ini lalu buat query masuk yaitu jumlah
    $stoksekarang = $ambildatanya['stock'];
    $kurangistokdenganrevoke=$stoksekarang-$jumlahrevokemasuk;
    //update stock masuk
    $revokestockmasuk=mysqli_query($sambung,"UPDATE store SET stock='$kurangistokdenganrevoke' WHERE idgudang='$namagudangrevoke' AND idstock='$namabendarevoke'");
    //hapus data revoke dari db sendiri
    
    $hapusdatamasuk = mysqli_query($sambung,"DELETE FROM masuk WHERE idmasuk='$idbarangmasuk'");
    
    if($revokestockmasuk&&$hapusdatamasuk){
        header("location:masuk.php");
    }else{
        echo "gagal";
        header("location:masuk.php");
    };
};

//revoke barang keluar
if(isset($_POST['Submitrevokekeluar'])){
    $idbarangkeluar = mysqli_real_escape_string($sambung, $_POST['idk']);
    $jumlahrevokekeluar = mysqli_real_escape_string($sambung, $_POST['revokekeluar']);
    $idgudangkeluarrevoke = mysqli_real_escape_string($sambung, $_POST['simpananrevokekeluar']);
    $idbarangkeluarrevoke = mysqli_real_escape_string($sambung, $_POST['bendarevokekeluar']);

    //ambil data ke db menggunakan query AND karena 2 syarat yaitu idgudang dan idproduk
    $cekstockkeluar = mysqli_query($sambung,"SELECT * FROM `store` WHERE idgudang='$idgudangkeluarrevoke' AND idstock='$idbarangkeluarrevoke'");
    $ambildatakeluar=mysqli_fetch_array($cekstockkeluar);
    
    //ambil kolom spesifik yaitu nilai stock pada case ini lalu buat query masuk yaitu jumlah
    $stoksekarang = $ambildatakeluar['stock'];
    $jumlahstokdenganrevoke=$stoksekarang+$jumlahrevokekeluar;
    //update stock masuk
    $revokestockkeluar=mysqli_query($sambung,"UPDATE store SET stock='$jumlahstokdenganrevoke' WHERE idgudang='$idgudangkeluarrevoke' AND idstock='$idbarangkeluarrevoke'");
    //hapus data revoke dari db sendiri
    
    $hapusdatakeluar = mysqli_query($sambung,"DELETE FROM keluar WHERE idkeluar='$idbarangkeluar'");
    
    if($revokestockkeluar&&$hapusdatakeluar){
        header("location:keluar.php");
    }else{
        echo "gagal";
        header("location:keluar.php");
    };
};

if(isset($_POST['Submitorder'])){
    $pilihgudangkus = mysqli_real_escape_string($sambung, $_POST['Gudangku']);
    $pilihprodukkus = mysqli_real_escape_string($sambung, $_POST['barangku']);
    $order = mysqli_real_escape_string($sambung, $_POST['qtyorder']);
    $ticketx = mysqli_real_escape_string($sambung, $_POST['ticketkel']);
    
    //ambil data ke db menggunakan query AND karena 2 syarat yaitu idgudang dan idproduk
    $cekstock = mysqli_query($sambung,"SELECT * FROM `store` WHERE idgudang='$pilihgudangkus' AND idstock='$pilihprodukkus'");
    $ambildatanya=mysqli_fetch_array($cekstock);
    
    //ambil kolom spesifik yaitu nilai stock pada case ini lalu buat query masuk yaitu jumlah
    $stoksekarang = $ambildatanya['stock'];
    $kurangistokdenganqty=$stoksekarang-$order;
    //masukan data perpindahan ke dalam db sendiri

    if($kurangistokdenganqty < 1){
        header("location:outside.php");
    }else{
        //masukan data perpindahan ke dalam db sendiri
        $masukdb = mysqli_query($sambung,"INSERT INTO `pesan` (`idpesan`, `idgudang`, `idstock`, `qty`, `status`, `tampil`, `idticket`) VALUES (NULL, $pilihgudangkus, $pilihprodukkus, $order, DEFAULT, DEFAULT, $ticketx);");
        header("location:outside.php");
    };
};

if(isset($_POST['Submitrevokeuser'])){
    $idloginr = mysqli_real_escape_string($sambung, $_POST['idl']);
    $hapusdatauser = mysqli_query($sambung,"DELETE FROM login WHERE `login`.`iduser` = $idloginr");

    //cek apakah berhasil
    if($hapusdatauser){
        header("location:management_user.php");
    }else{
        echo "gagal";
        header("location:management_user.php");
    };
};

if(isset($_POST['Submitlogin'])){
    $namaemail = mysqli_real_escape_string($sambung, $_POST['email']);
    $passwordku = mysqli_real_escape_string($sambung, $_POST['password']);

    $masukankedb = mysqli_query($sambung,"INSERT INTO `login` (`iduser`, `email`, `password`, `role`) VALUES (NULL, '$namaemail', '$passwordku', DEFAULT)");
    if($indb){
        header("location:management_user.php");
    }else{
        echo "gagal";
        header("location:management_user.php");
    };
};

//update User
if(isset($_POST['updateuser'])){
    $idlog = mysqli_real_escape_string($sambung, $_POST['idl']);
    $passwordus = mysqli_real_escape_string($sambung, $_POST['password']);
    $roleplay = mysqli_real_escape_string($sambung, $_POST['role']);
    
    $updateproduk = mysqli_query($sambung,"UPDATE `login` SET `password` = '$passwordus', `role` = '$roleplay' WHERE `login`.`iduser` = $idlog");

    //cek apakah berhasil
    if($updateproduk){
        header("location:management_user.php");
    }else{
        echo "gagal";
        header("location:management_user.php");
    };
};

// tampilkan di admin
if(isset($_POST['kirim'])){
    $idtixa = mysqli_real_escape_string($sambung, $_POST['idtix']);
    
    $updateter = mysqli_query($sambung, "UPDATE `ticket` SET `tersedia` = 'tidak' WHERE `ticket`.`idticket` = $idtixa");
    $updatekan = mysqli_query($sambung,"UPDATE `pesan` SET `tampil` = 'ya' WHERE `pesan`.`idticket` = $idtixa");

    //cek apakah berhasil
    if($updatekan){
        header("location:outside.php");
    }else{
        echo "gagal";
        header("location:outside.php");
    };
};

// approve admin
if(isset($_POST['approve'])){
    $idpesanku = mysqli_real_escape_string($sambung, $_POST['idp']);
    $jumlahpesanku = mysqli_real_escape_string($sambung, $_POST['jumlahpesan']);
    $idgudangku = mysqli_real_escape_string($sambung, $_POST['namasimpananku']);
    $idbarangku = mysqli_real_escape_string($sambung, $_POST['namabarangku']);

    $ambildata = mysqli_query($sambung, "SELECT * FROM `store` WHERE idgudang='$idgudangku' AND idstock='$idbarangku'");
    $siman  = mysqli_fetch_array($ambildata);
    $jumlahsedia = $siman['stock'];
    $hasilke= $jumlahsedia-$jumlahpesanku;
    $ambildatalengkap=mysqli_query($sambung,"SELECT * FROM pesan p JOIN warehouse w ON w.idgudang = p.idgudang JOIN stock t ON t.idstock = p.idstock JOIN ticket i ON i.idticket = p.idticket WHERE p.idpesan = '$idpesanku'");
    $simkan= mysqli_fetch_array($ambildatalengkap);
    $tiketnumber= $simkan['no_order'];

    $updatepesan = mysqli_query($sambung,"UPDATE `pesan` SET `status` = 'approve' WHERE `pesan`.`idpesan` = '$idpesanku'");
    $updagud = mysqli_query($sambung, "UPDATE `store` SET `stock`='$hasilke' WHERE `idgudang`='$idgudangku' AND `idstock`='$idbarangku'");
    $masukdbdariapp = mysqli_query($sambung,"INSERT into keluar (ticket,idgudang,idstock,qty) values('$tiketnumber','$idgudangku','$idbarangku','$jumlahpesanku')");

    //cek apakah berhasil
    if($updatepesan){
        header("location:order.php");
    }else{
        echo "gagal";
        header("location:order.php");
    };
};

// ditolak admin
if(isset($_POST['reject'])){
    $idpesanan = mysqli_real_escape_string($sambung, $_POST['idp']);
    
    $updatepesanan = mysqli_query($sambung, "UPDATE `pesan` SET `status` = 'reject' WHERE `pesan`.`idpesan` = $idpesanan");

    //cek apakah berhasil
    if($updatepesanan){
        header("location:order.php");
    }else{
        echo "gagal";
        header("location:order.php");
    };
};

?>
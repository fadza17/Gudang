<?php
require "function.php";

//cek login terdaftar atau tidak
if(isset($_POST["login"])){
    $email = $_POST["email"];
    $password = $_POST["password"];

    // proses pencocokan dengan database
    $cekdb = mysqli_query($sambung, "SELECT * FROM login where email='$email' and password='$password'");
    // cek hasil data
    $ceking = mysqli_num_rows($cekdb);
    $user=mysqli_fetch_array($cekdb);

    if($ceking>0){
        //membuat session agar dapat masuk jika sudah login sekali
        $_SESSION['log'] = 'True';
        //simpan nama email yang diinputkan untuk data login as
        $_SESSION["user_email"] = $email;
        $_SESSION["role"] = $user['role'];
        if($user['role']=='admin'){
            header("location:index.php");
        }else{
            header("location:outside.php");
        };
    } else {
        //redirect atau mengaarahkan ke halaman login lagi jika data tidak cocok dan dapat langsung login ulang sampai benar
        header("location:login.php");
    };
};

// untuk mengecek apakah sudah login atau belum karena jika sudah login dapat langsung ke dashboard
if(!isset($_SESSION['log'])){

}else{
    header('location:index.php');
};
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Page Title - SB Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                        <form method="post">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input class="form-control py-4" name="email" id="inputEmailAddress" type="email" placeholder="Enter email address" />
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input class="form-control py-4" name="password" id="inputPassword" type="password" placeholder="Enter password" />
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button class="btn btn-primary"  name="login">Login</button>
                                            </div>
                                        </form>
                                    </div>
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
    </body>
</html>

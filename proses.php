
<?php
require_once("./koneksi.php");

/*
========================================================================
			PROSES LOGIN
========================================================================
*/

$username = $_POST['username'];
$password = md5($_POST['password']);


if ($password == '' and $username == '') {
	echo "<script language='javascript'> alert('Username dan password harus diisi!');history.go(-1); </script>";
} else if ($password == '') {
	echo "<script language='javascript'> alert('Password harus diisi!');history.go(-1); </script>";
} else if ($username == '') {
	echo "<script language='javascript'> alert('Username harus diisi!');history.go(-1); </script>";
} else {
	$query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
	$sql = mysqli_query($koneksi, $query);

	//$query_login = mysqli_query($koneksi, "SELECT * FROM admin WHERE username = '$username' && password = '$password'");
	// $cek_data = mysqli_num_rows($query_login);
	if (mysqli_num_rows($sql) > 0) {
		session_start();
		// $data = mysqli_fetch_assoc($query_login);
		$data = mysqli_fetch_array($sql);

		$_SESSION['username'] = $data['username'];
		$_SESSION['password'] = $data['password'];

		// $_SESSION['id_user'] = $data['username'];
		// $_SESSION['nama'] = $data['username'];
		// $_SESSION['password'] = $data['password'];
		// $_SESSION['email'] = $data['email'];
		// $_SESSION['jk'] = $data['jk'];
		// $_SESSION['alamat'] = $data['alamat'];
		// $_SESSION['tgl_lahir'] = $data['tgl_lahir'];
		// $_SESSION['telepon'] = $data['telepon'];
		// $_SESSION['level'] = "admin";
		// alihkan ke halaman login kembali
		header("location: ./admin");

		// if ($data['level'] == "user") {
		// 	// cek jika user login sebagai user
		// 	$_SESSION['id_user'] = $data['id_user'];
		// 	$_SESSION['nama'] = $data['nama'];
		// 	$_SESSION['password'] = $data['password'];
		// 	$_SESSION['email'] = $data['email'];
		// 	$_SESSION['jk'] = $data['jk'];
		// 	$_SESSION['alamat'] = $data['alamat'];
		// 	$_SESSION['tgl_lahir'] = $data['tgl_lahir'];
		// 	$_SESSION['telepon'] = $data['telepon'];
		// 	$_SESSION['level'] = "user";
		// 	// alihkan ke halaman login kembali
		// 	header("location: ./user");
		// } else if ($data['level'] == "admin") {
		// 	$_SESSION['id_user'] = $data['id_user'];
		// 	$_SESSION['nama'] = $data['nama'];
		// 	$_SESSION['password'] = $data['password'];
		// 	$_SESSION['email'] = $data['email'];
		// 	$_SESSION['jk'] = $data['jk'];
		// 	$_SESSION['alamat'] = $data['alamat'];
		// 	$_SESSION['tgl_lahir'] = $data['tgl_lahir'];
		// 	$_SESSION['telepon'] = $data['telepon'];
		// 	$_SESSION['level'] = "admin";
		// 	// alihkan ke halaman login kembali
		// 	header("location: ./admin");
		// }
	} else {
		echo "<script language='javascript'> alert('Username atau password salah!');history.go(-1); </script>";
	}
}
?>
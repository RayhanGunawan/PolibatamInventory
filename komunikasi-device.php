<?php
// include("connect.php");
require_once("./koneksi.php");
?>

<?php
date_default_timezone_set('Asia/Jakarta');
$datetime = date('Y-m-d H:i:s');


if (isset($_POST['mdd']) && isset($_POST['tagid'])) {
   $mode = $_POST['mdd'];
   $tags = $_POST['tagid'];

   if ($mode == "inb") {
      // Update ID ke db
      $result = mysqli_query($koneksi, "SELECT * FROM rfid WHERE no='1'");
      $row = mysqli_fetch_array($result);
      $cek = $row["no"];
      //$mode = $row["mode"];

      if ($cek == "") $sql = "INSERT into `rfid` (no, id, mode) VALUES (1, '$tags', '$mode')";
      else $sql = "UPDATE rfid SET no=1, id='$tags', mode='$mode' WHERE no='1'";
      if (!mysqli_query($koneksi, $sql)) echo "Error: " . $sql . "<br>" . mysqli_error($koneksi);

      $cekID = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE id='$tags'");
      $row = mysqli_fetch_array($cekID);
      $barang = $row["barang"];
      $already;
      if (mysqli_num_rows($cekID) > 0) $already = true;
      else $already = false;

      if ($already) echo "#idsudah|" . $mode . "|" . $barang . "|";
      else          echo "#idbelum|" . $mode . "|" . $barang . "|";
   } else if ($mode == "out") {
      $checkID = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE id= '$tags' AND status='inb'");
      $row = mysqli_fetch_array($checkID);

      $checkID = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE id='$tags' AND status='out'");
      $already;
      if (mysqli_num_rows($checkID) > 0) $already = true;
      else $already = false;

      if ($row > 0) {
         $barang = $row["barang"];
         $lokasi = $row["lokasi"];
         $out_date = $row["out_date"];

         //check stock dan kurangi stock
         $query_stok = mysqli_query($koneksi, "SELECT count(*) as Jumlah FROM tb_barang WHERE barang='$barang' AND status='inb'");
         $jumlah_stok = mysqli_fetch_array($query_stok);
         if ($jumlah_stok['Jumlah'] != 0) {
            $current_stok = $jumlah_stok['Jumlah'] - 1;
         } else  $current_stok = $jumlah_stok['Jumlah'];

         // input data ke database
         $query = "INSERT INTO `tb_outbound` (`id`, `barang`, `lokasi`, `datetime`) VALUES ('$tags', '$barang', '$lokasi', CURRENT_TIMESTAMP())";
         if (mysqli_query($koneksi, $query)) {
            mysqli_query($koneksi, "UPDATE kategori SET stock='$current_stok' WHERE barang='$barang'");
            mysqli_query($koneksi, "UPDATE tb_barang SET status='out', out_date=CURRENT_TIMESTAMP() WHERE id='$tags'");
            mysqli_query($koneksi, "UPDATE rfid SET id='' WHERE no='1'");
         }
      } else {
         // check barang
         $cek_barang = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE id='$tags'");
         $row = mysqli_fetch_array($cek_barang);
         if ($row > 0) {
            $barang = $row["barang"];
            $lokasi = $row["lokasi"];
            $current_stok = "N/A";
            $out_date = $row["out_date"];
         } else {
            $barang = "Not Found";
            $lokasi = "N/A";
            $current_stok = "N/A";
            $out_date = "N/A";
         }
      }

      if ($already) echo "#idsudah|" . $mode . "|" . $barang . "|" . $current_stok . "|" . $lokasi . "|" . $out_date . "|";
      else          echo "#idbelum|" . $mode . "|" . $barang . "|" . $current_stok . "|" . $lokasi . "|";
   } else if ($mode == "chk") {
      $checkID = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE id= '$tags' AND status='inb'");
      $row = mysqli_fetch_array($checkID);

      $checkID = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE id='$tags' AND status='out'");
      $already;
      if (mysqli_num_rows($checkID) > 0) $already = true;
      else $already = false;

      if ($row > 0) {
         $barang = $row["barang"];
         $lokasi = $row["lokasi"];
         $out_date = $row["out_date"];

         //check stock
         $query_stok = mysqli_query($koneksi, "SELECT count(*) as Jumlah FROM tb_barang WHERE barang='$barang' AND status='inb'");
         $jumlah_stok = mysqli_fetch_array($query_stok);
         $current_stok = $jumlah_stok['Jumlah'];
      } else {
         // check barang
         $cek_barang = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE id='$tags'");
         $row = mysqli_fetch_array($cek_barang);
         if ($row > 0) {
            $barang = $row["barang"];
            $lokasi = $row["lokasi"];
            $current_stok = "N/A";
            $out_date = $row["out_date"];
         } else {
            $barang = "Not Found";
            $lokasi = "N/A";
            $current_stok = "N/A";
            $out_date = "N/A";
         }
      }

      // $cekID = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE id='$tags'");
      // $row = mysqli_fetch_array($cekID);
      // $already;
      // if (mysqli_num_rows($cekID) > 0) $already = true;
      // else $already = false;

      if ($already) echo "#idsudah|" . $mode . "|" . $barang . "|" . $current_stok . "|" . $lokasi . "|" . $out_date . "|";
      else          echo "#idbelum|" . $mode . "|" . $barang . "|" . $current_stok . "|" . $lokasi . "|";
   }
}
?>
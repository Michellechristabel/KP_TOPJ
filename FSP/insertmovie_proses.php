<?php
$mysqli = new mysqli("localhost", "root", "", "movie");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
// Memastikan data dari form sudah ada
// $judul = $_POST['judul'];
// $rilis = $_POST['rilis'];
// $rilis = date("Y-m-d", strtotime($rilis));
// $skor = $_POST['skor'];
// // $sinopsis = $_POST['sinopsis'];
// $sinopsis = isset($_POST['sinopsis']) ? $_POST['sinopsis'] : '';
// // $serial = ($_POST['serial']);
// $serial = isset($_POST['serial']) ? $_POST['serial'] : 0;
// $genre = $_POST['genre'];
// $poster =$_FILES['poster'];
// $ext = pathinfo($poster['name'], PATHINFO_EXTENSION);

// $idmovie = isset($row['idmovie']) ? $row['idmovie'] : 0;
// $judul = isset($_POST['judul']) ? $_POST['judul'] : '';
// $rilis = isset($_POST['rilis']) ? $_POST['rilis'] : '';
// $rilis = date("Y-m-d", strtotime($rilis)); // Format tanggal
// $skor = isset($_POST['skor']) ? $_POST['skor'] : 0;
// $sinopsis = isset($_POST['sinopsis']) ? $_POST['sinopsis'] : ''; // ✅ Pastikan sinopsis ada
// $serial = isset($_POST['serial']) ? 1 : 0; // ✅ Simpan sebagai 1 (ya) atau 0 (tidak)
// $arr_genre = isset($_POST['genre']) ? $_POST['genre'] : []; // ✅ Pastikan genre sebagai array
// $poster = $_FILES['poster'];
// $ext = pathinfo($poster['name'], PATHINFO_EXTENSION);

// Ambil data dari form
$judul = isset($_POST['judul']) ? $_POST['judul'] : '';
$rilis = isset($_POST['rilis']) ? date("Y-m-d", strtotime($_POST['rilis'])) : null;
$skor = isset($_POST['skor']) ? floatval($_POST['skor']) : 0;
$sinopsis = isset($_POST['sinopsis']) ? $_POST['sinopsis'] : '';
$serial = isset($_POST['serial']) ? 1 : 0;
$arr_genre = isset($_POST['genre']) ? $_POST['genre'] : array();
$arr_pemain = isset($_POST['pemain']) ? $_POST['pemain'] : array();
$arr_peran = isset($_POST['peran']) ? $_POST['peran'] : array();
$poster = $_FILES['poster'];

//$ext = pathinfo($poster['name'], PATHINFO_EXTENSION);


// Query SQL yang benar dengan tanda kurung penutup
// $stmt = $mysqli->prepare($sql);
// Binding parameter
// $stmt->bind_param("ssdsis", $judul, $rilis, $skor, $sinopsis, $serial, $ext);
// Eksekusi query
// $stmt->execute();

// $jumlah_yang_dieksekusi = $stmt->affected_rows;
// $last_id = $stmt->insert_id;
// $mysqli->close();

$sql = "INSERT INTO movie (judul, rilis, skor, sinopsis, serial) VALUES (?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ssdsi", $judul, $rilis, $skor, $sinopsis, $serial);
$stmt->execute();
$idmovie = $stmt->insert_id;

$jumlah_yang_dieksekusi = $stmt->affected_rows;
$idmovie = $stmt->insert_id;

//if($jumlah_yang_dieksekusi > 0) {
if($idmovie) {
    //insertnya sukses
    //insert pemain
    foreach($arr_pemain as $idx => $idpemain) {
        
    }
    //insert genre
    $sql = "Insert into genre_movie Values (?, ?)";
    $stmt = $mysqli->prepare($sql);
    foreach($arr_genre as $idgenre) {
        $stmt->bind_param("ii", $idmovie, $idgenre);
        $stmt->execute();
    }
    // move_uploaded_file($poster['tmp_name'], "img/".$last_id.".".$ext);
    // header("location: insertmovie.php?err=0");

    foreach ($poster['tmp_name'] as $idx => $tmp_name) {
        if (empty($tmp_name)) continue; //kalau kosong dilewati

        //kalau ga kosong
        $sql = "Insert into gambar (idmovie) Values (?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $idmovie);
        $stmt->execute();
        $idgambar = $mysqli->insert_id; //dapatkan dulu id gambar

        $ext = pathinfo($poster['name'][$idx], PATHINFO_EXTENSION);
        move_uploaded_file($tmp_name, "img/".$idgambar.".".$ext);

        $sql = "Update gambar Set extention=? Where idgambar=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("si", $ext, $idgambar);
        $stmt->execute();
    }





    // if (move_uploaded_file($_FILES["poster"]["tmp_name"], $newname)) {
    //     //update extension
    //     $stmt= $mysqli->prepare("UPDATE movie SET extention=? WHERE idmovie=?");
    //     $stmt->bind_param("si", $imageFileType, $last_id);
    //     $stmt->execute();
    // }

// if ($last_id) {
//     // **Simpan genre ke dalam tabel genre_movie**
//     if (!empty($arr_genre)) {
//         $sql = "INSERT INTO genre_movie (idmovie, idgenre) VALUES (?, ?)";
//         $stmt = $mysqli->prepare($sql);

//         foreach ($arr_genre as $idgenre) {
//             $stmt->bind_param("ii", $last_id, $idgenre);
//             $stmt->execute();
//         }
//     }
    
//     $upload_path = "img/" . $last_id . "." . $ext;
//     if (move_uploaded_file($poster['tmp_name'], $upload_path)) {
//         header("location: insertmovie.php?err=0"); 
//     } else {
//         echo "Gagal mengunggah gambar.";
//     }
// } else {
//     echo "Gagal menambahkan film.";
// }
}
?>
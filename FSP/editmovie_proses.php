<?php
$mysqli = new mysqli("localhost", "root", "", "movie");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Tangkap data dari form
$id_movie = $_POST['id_movie'];
$judul = $_POST['judul'];
$rilis = $_POST['rilis'];
$skor = $_POST['skor'];
$sinopsis = $_POST['sinopsis'];
$serial = $_POST['serial'];
$genre = $_POST['genre'];

// Query UPDATE
$query = $mysqli->prepare("UPDATE movies SET judul=?, rilis=?, skor=?, sinopsis=?, serial=?, genre=? WHERE id=?");
$query->bind_param("ssdsssi", $judul, $rilis, $skor, $sinopsis, $serial, $genre, $id_movie);

if ($query->execute()) {
    echo "Data berhasil diperbarui! <a href='index.php'>Kembali</a>";
} else {
    echo "Terjadi kesalahan: " . $query->error;
}
?>
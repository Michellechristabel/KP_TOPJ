<?php
$mysqli = new mysqli("localhost", "root", "", "movie");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Tangkap ID movie dari query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID movie tidak ditemukan!";
    exit();
}

$id_movie = $_GET['id'];

// Query SELECT untuk mendapatkan data movie berdasarkan ID
$query = $mysqli->prepare("SELECT * FROM movies WHERE id = ?");
$query->bind_param("i", $id_movie);
$query->execute();
$result = $query->get_result();

// Periksa apakah data ditemukan
if ($result->num_rows === 0) {
    echo "Movie tidak ditemukan!";
    exit();
}

$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie</title>
</head>
<body>
<form method="post" action="editmovie_proses.php">
    <input type="hidden" name="id_movie" value="<?= htmlspecialchars($row['id']) ?>">

    <div><label>Judul</label> <input type="text" name="judul" value="<?= htmlspecialchars($row['judul']) ?>"></div>
    <div><label>Tgl Rilis</label> <input type="date" name="rilis" value="<?= htmlspecialchars($row['rilis']) ?>"></div>
    <div><label>Skor</label> <input type="text" name="skor" step="any" min="0" max="10" value="<?= htmlspecialchars($row['skor']) ?>"></div>
    <div><label>Sinopsis</label> <textarea name="sinopsis"><?= htmlspecialchars($row['sinopsis']) ?></textarea></div>
    <div><label>Serial</label> 
        <input type="radio" name="serial" value="1" id="ya" <?= ($row['serial'] == 1) ? "checked" : "" ?>><label for="ya">Ya</label>
        <input type="radio" name="serial" value="0" id="tidak" <?= ($row['serial'] == 0) ? "checked" : "" ?>><label for="tidak">Tidak</label>
    </div>
    <div><label>Genre</label> <input type="text" name="genre" value="<?= htmlspecialchars($row['genre']) ?>"></div>

    <br>
    <input type="submit" name="simpan" value="Simpan">
</form>
</body>
</html>

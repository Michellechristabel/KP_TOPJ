<?php
$mysqli = new mysqli("localhost", "root", "", "movie");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$sql = "SELECT m.id, m.judul, DATE_FORMAT(m.rilis, '%d-%m-%Y') as rilis, 
               m.skor, m.sinopsis, m.serial, m.extention, 
               IFNULL(GROUP_CONCAT(g.nama SEPARATOR ', '), 'Genre tidak tersedia') AS genre 
        FROM movie m
        LEFT JOIN genre_movie gm ON m.id = gm.idmovie
        LEFT JOIN genre g ON gm.idgenre = g.idgenre
        GROUP BY m.id";

$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <style>
            .textmerah {
                color: red;
            }
    </style>
</head>
<body>
<div>
<form method="GET">
    <label>Masukkan Judul</label> <input type="text" name="cari"> <input type="submit" name="search" value="Search">
</form>
<?php  
    if(isset($_GET['cari'])) {
        echo "<p><i>Hasil pencarian untuk kata '".$_GET['cari']."'</i></p>";
    }    
?>
</div>

<?php
$cari = isset($_GET['cari']) ? $_GET['cari'] : "";
$cari_persen = "%".$cari."%";
$stmt = $mysqli->prepare("SELECT * FROM movie where judul Like ?");
$stmt->bind_param("s", $cari_persen);
$stmt->execute();
$res = $stmt->get_result(); 
echo "<table border='1'>
<tr>
    <th></th>
    <th>Judul</th>
    <th>Tgl. Rilis</th>
    <th>Skor</th>
    <th>Sinopsis</th>
    <th>Serial</th>
    <th>Extention</th>
    <th>Genre</th>
    <th>Aksi</th>
</tr>";

// while($row = $res->fetch_assoc()) {
//     // Pastikan idmovie ada sebelum digunakan
//     $idmovie = isset($row['idmovie']) ? $row['idmovie'] : 'N/A';

//     // format tanggal rilis
//     $formattedDate = date("d-m-Y", strtotime($row['rilis']));
    
//     // Menampilkan "Ya" atau "Tidak" untuk kolom serial
//     $serial = $row['serial'] == 1 ? 'Ya' : 'Tidak';

//     $rowClass = $row['skor'] < 5 ? 'textmerah' : '';

//     echo "<tr>";
//     // echo "<tr class='$rowClass'>";
//     echo "<td><img src='img/".$idmovie.".".$row['extention']."' alt='Poster' width='100'></td>";    
//     echo "<td>".$row['judul']."</td>";
//     echo "<td>".$formattedDate."</td>";
//     // echo "<td>".$row['rilis']."</td>";
//     echo "<td>".$row['skor']."</td>";
//     echo "<td>".$row['sinopsis']."</td>";
//     echo "<td>".$serial."</td>";
//     echo "<td>".$row['extention']."</td>";
//     echo "<td>".$row['genre']."</td>";
    
//     //ambil genre
//     $sql = "Select genre.nama From genre inner join genre_movie on genre.idgenre=genre_movie.idgenre where genre_movie.idmovie=?";
//     $stmt = $mysqli->prepare($sql);
//     $stmt->bind_param("i", $idmovie);
//     $stmt->execute();
//     $res2 = $stmt->get_result();
//     echo "<td><ul>";
//     // while($genre=$res->fetch_assoc()) {
//     //     echo "<li>".$genre['nama']."</li>";  
//     // }
//     if ($res2->num_rows > 0) { // Cek apakah ada hasil
//         while ($genre = $res2->fetch_assoc()) {
//             echo "<li>".$genre['nama']."</li>";
//         }
//     } else {
//         echo "<li>Genre tidak tersedia</li>"; // Tambahkan placeholder jika tidak ada genre
//     }

//     // Kolom Aksi: Link untuk mengedit data
//     echo "<td><a href='editmovie.php?idmovie=".$idmovie."'>Ubah Data</a></td>";
//     echo "</tr>";
// };
// echo "</table>";

// $stmt->close();

while ($row = $result->fetch_assoc()) {
    $idmovie = $row['id'];
    $formattedDate = $row['rilis'];
    $serial = $row['serial'] == 1 ? 'Ya' : 'Tidak';
    echo "<tr>";
    echo "<td><img src='img/" . htmlspecialchars($idmovie) . "." . htmlspecialchars($row['extention']) . "' alt='Poster' width='100'></td>";
    echo "<td>" . htmlspecialchars($row['judul']) . "</td>";
    echo "<td>" . htmlspecialchars($formattedDate) . "</td>";
    echo "<td class='" . ($row['skor'] < 5 ? 'textmerah' : '') . "'>" . htmlspecialchars($row['skor']) . "</td>";
    echo "<td>" . htmlspecialchars($row['sinopsis']) . "</td>";
    echo "<td>" . $serial . "</td>";
    echo "<td>" . htmlspecialchars($row['extention']) . "</td>";
    echo "<td>" . htmlspecialchars($row['genre']) . "</td>";
    echo "<td><a href='editmovie.php?idmovie=" . urlencode($idmovie) . "'>Ubah Data</a></td>";
    echo "</tr>";
}
$stmt->close();
?>
</body>
</html>
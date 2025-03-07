<?php
$mysqli = new mysqli("localhost", "root", "", "movie");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert movie</title>
    <script src="js/jquery-3.7.1.js"></script>
</head>
<body>
<form method="post" action="insertmovie_proses.php" enctype="multipart/form-data">
    <div><label>Judul</label> <input type="text" name="judul"></div>
    <div><label>Tgl Rilis</label> <input  type="date" name="rilis"></div>
    <div><label>Skor</label> <input  type="text" name="skor" step="any" min="0" max="10"></div>
    <div><label>Sinopsis</label> <textarea name="Sinopsis"></textarea></div>
    <div><label>Serial</label> 
        <input type="radio" name="serial" value="1" id="ya"><label for="ya">Ya</label>
        <input type="radio" name="serial" value="0" id="tidak"><label for="tidak">Tidak</label>
    </div>
    <div><label>Genre</label> 
    <?php 
        $sql = "Select * from genre";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        $res = $stmt-> get_result();
        while($row = $res->fetch_assoc()) {
            echo "<input type='checkbox' name='genre[]' value='".$row['idgenre']."'> <label>".$row['nama']."</label> ";
        }
    ?>
    <div><label>Poster</label> 
        <div id='upload'>
        <input type="file" name="poster[]" accept="img/jpeg, img/png>">
        </div>
        <input type="button" id="btnTambahPoster" value="Tambah Poster">
    </div>
    <br>
    <div><label>Pemain</label>
        <div>
            <select id='pemain'>
                <option value=''>-- Pilih Pemain --</option>
                <?php
                    $sql = "Select * From pemain Order by nama";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while($row=$res->fetch_asscoc()) {
                        echo "<option value='".$row['idpemain']."'>".$row['nama']."</option>";
                    }
                ?>
            </select>
            <select id = 'peran'>
                <option Value = 'Utama'>Utama</option>
                <option Value = 'Pembantu'>Pembantu</option>
                <option Value = 'Cameo'>Cameo</option>
            </select>
            <input type="button" id="btnTambahPemain" value="Tambah Pemain">
        </div>
        <div>
            <table border='1' width='100%'>
                <thead>
                    <tr><th>Pemain</th><th>Peran</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody id ='daftarpemain'>
                    <tr>
                        <td>Brad Pitt</td><td>Utama</td><td><input type="button" class="hapusPemain" value="Hapus"></td>
                    </tr>
                </tbody>

<br>
<input type="submit" name="simpan" value="Simpan">

<?php
    if(isset($_GET['err'])) {
        if($_GET['err']==='0') {
            echo "<p>Data Berhasil Disimpan</p>";
        }
    }
    ?>
</form>
<script type="text/javascript">
    $('body').on('click', '#btnTambahPemain', function() {
        var nama = $('#pemain option:selected').text();
        var peran = $('#peran').val();
        var temp = "<tr><td>"+nama+"</td><td>"+peran+"</td><td><input type="hidden" name='pemain[]' value='"+idpemain+"'></td>";
        temp += "<td>"+peran+"<input type='hidden' name='peran[]' value='"+peran+"'></td>";
        temp += "<td><input type='button'cclass='hapusPemain' value='Hapus'></td></tr>";
        $('#daftarpemain').append(temp);
    })
    $('#btnTambahPoster').click(function() {
        var temp ="<div><input type='file' name='poster[]' accept='image/jpeg, image/png'><input type='button' class='hapus' value='Hapus'></div>";
        $('#upload').append(temp);
    });

    $('body').on('click','.hapusPemain', function() {
        $(this).parent().parent().remove()
    })

    //$('.hapus').click(function() {
    $('body').on('click', '.hapus', function() {
        $(this).parent().remove();
    });
</script>
</body>
</html>
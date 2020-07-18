<?php 
    $conn = mysqli_connect("localhost", "root", "", "phpdasar");


    function query($query) {
        global $conn;
        $result = mysqli_query($conn, $query);
        $rows = [];
        while( $row = mysqli_fetch_assoc($result) ) {
            $rows[] = $row;
        }
        return $rows;
    }

    function tambah($data) {
        global $conn;

        $nrp = htmlspecialchars($data["nrp"]);
        $nama = htmlspecialchars($data["nama"]);
        $email = htmlspecialchars($data["email"]);
        $jurusan = htmlspecialchars($data["jurusan"]);
        
        // upload gambar
        $gambar = upload(); 
        if ( !$gambar ) {
            return false;
        }

        $query = "INSERT INTO mahasiswa 
                    VALUES
                    ('','$nrp','$nama','$email','$jurusan','$gambar')
                    ";
        mysqli_query($conn, $query);

        return mysqli_affected_rows($conn);
    }

    function upload() {

        $namaFile = $_FILES["gambar"]["name"];
        $ukuranFile = $_FILES["gambar"]["size"];
        $error = $_FILES["gambar"]["error"];
        $tmp_name = $_FILES["gambar"]["tmp_name"];

        // cek apakah tidak ada gambar yang di upload
        if ( $error === 4 ) {
            echo "
                <script>
                    alert('Pilih Gambar Terlebih Dahulu!');
                </script>
                ";
            return false;
        }

        // cek apakah yang di upload adalah gambar
        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = explode('.', $namaFile);
        $ekstensiGambar = strtolower(end($ekstensiGambar));
        if ( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
            echo "
                <script>
                    alert('Yang anda upload bukan gambar!');
                </script>
                ";
            return false;
        }

        // cek jika ukurannya terlalu besar
        if ( $ukuranFile > 2000000 ) {
            echo "
                <script>
                    alert('Ukuran gambar terlalu besar!');
                </script>
                ";
            return false;
        }

        // lolos pengecekan, gambar siap diupload
        // Generate nama gambar random
        $namaFileBaru = uniqid();
        $namaFileBaru .= '.';
        $namaFileBaru .= $ekstensiGambar;

        move_uploaded_file($tmp_name, 'img/' . $namaFileBaru);

        return $namaFileBaru;
    }

    function hapus($id) {
        global $conn;

        mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id");
        return mysqli_affected_rows($conn);
    }

    function ubah($data) {
        global $conn;

        $id = $data["id"];
        $nrp = htmlspecialchars($data["nrp"]);
        $nama = htmlspecialchars($data["nama"]);
        $email = htmlspecialchars($data["email"]);
        $jurusan = htmlspecialchars($data["jurusan"]);
        $gambarLama = htmlspecialchars($data["gambarLama"]);

        // cek apakah user pilih gambar baru atau tidak
        if ( $_FILES["gambar"]["error"] === 4 ) {
            $gambar = $gambarLama;
        } else {
            $gambar = upload();
        }

        $query = "UPDATE mahasiswa SET
                    nama = '$nama',
                    nrp = '$nrp',
                    email = '$email',
                    jurusan = '$jurusan',
                    gambar = '$gambar'
                    WHERE id = $id
                ";
        mysqli_query($conn, $query);

        return mysqli_affected_rows($conn);
    }

    function cari($keyword) {
        $query = "SELECT * FROM mahasiswa
                        WHERE
                    nama LIKE '%$keyword%' OR
                    nrp LIKE '%$keyword%' OR
                    email LIKE '%$keyword%' OR
                    jurusan LIKE '%$keyword%'
                ";
        return query($query);
    }
?>
<?php
class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "laundry_fezz";
    private $koneksi;

    public function __construct()
    {
        $this->koneksi = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        if (mysqli_connect_errno()) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }
    }
    public function login($username)
    {
        $username = mysqli_real_escape_string($this->koneksi, $username);
        $query = "SELECT * FROM user WHERE username = '$username'";
        $data = mysqli_query($this->koneksi, $query);
        
        if (mysqli_num_rows($data) == 0) {
            echo "<b>Data user tidak ada</b>";
            header("location: login.php");
            return [];
        } else {
            $hasil = [];
            while ($row = mysqli_fetch_array($data)) {
                $hasil[] = $row;
            }
            return $hasil;
        }
    }
    public function tampil_data()
    {
        $hasil = [];
        $query = "SELECT a.*, b.* FROM user a 
                  INNER JOIN jenis_kelamin b ON b.kode_jk = a.jenis_kelamin";
        $data = mysqli_query($this->koneksi, $query);
        
        while ($row = mysqli_fetch_array($data)) {
            $hasil[] = $row;
        }
        return $hasil;
    }

    public function tampil_data_jenis_kelamin()
    {
        $hasil_jenis_kelamin = [];
        $query = "SELECT * FROM jenis_kelamin";
        $data_jenis_kelamin = mysqli_query($this->koneksi, $query);
        
        while ($row_jenis_kelamin = mysqli_fetch_array($data_jenis_kelamin)) {
            $hasil_jenis_kelamin[] = $row_jenis_kelamin;
        }
        return $hasil_jenis_kelamin;
    }
    public function tambah_data_user($username, $password, $akses_id, $kode_pelanggan, $nama_pelanggan, $jenis_kelamin, $alamat, $email, $no_hp)
    {
        $username = mysqli_real_escape_string($this->koneksi, $username);
        $password = mysqli_real_escape_string($this->koneksi, $password);
        $akses_id = mysqli_real_escape_string($this->koneksi, $akses_id);
        $kode_pelanggan = mysqli_real_escape_string($this->koneksi, $kode_pelanggan);
        $nama_pelanggan = mysqli_real_escape_string($this->koneksi, $nama_pelanggan);
        $jenis_kelamin = mysqli_real_escape_string($this->koneksi, $jenis_kelamin);
        $alamat = mysqli_real_escape_string($this->koneksi, $alamat);
        $email = mysqli_real_escape_string($this->koneksi, $email);
        $no_hp = mysqli_real_escape_string($this->koneksi, $no_hp);
        
        $query = "INSERT INTO user (username, password, akses_id, kode_pelanggan, nama_pelanggan, jenis_kelamin, alamat, email, no_hp) 
                  VALUES ('$username', '$password', '$akses_id', '$kode_pelanggan', '$nama_pelanggan', '$jenis_kelamin', '$alamat', '$email', '$no_hp')";
        
        return mysqli_query($this->koneksi, $query);
    }
    public function tampil_transaksi()
    {
        $hasil = [];
        $query = "SELECT a.kode_transaksi, a.kode_pelanggan, a.kode_paket, a.harga, a.kilo, a.total, 
                         a.tanggal_masuk, a.tanggal_keluar, a.status, b.paket, b.harga_paket AS harga, 
                         c.nama_pelanggan
                  FROM transaksi a
                  LEFT JOIN paket b ON b.kode_paket = a.kode_paket
                  LEFT JOIN user c ON c.kode_pelanggan = a.kode_pelanggan
                  ORDER BY a.tanggal_masuk DESC";
        
        $data = mysqli_query($this->koneksi, $query);
        while ($row = mysqli_fetch_array($data)) {
            $hasil[] = $row;
        }
        return $hasil;
    }
    public function tambah_transaksi($kode_transaksi, $kode_pelanggan, $kode_paket, $harga, $kilo, $total, $tanggal_masuk, $tanggal_keluar, $status)
    {
        $kode_transaksi = mysqli_real_escape_string($this->koneksi, $kode_transaksi);
        $kode_pelanggan = mysqli_real_escape_string($this->koneksi, $kode_pelanggan);
        $kode_paket = mysqli_real_escape_string($this->koneksi, $kode_paket);
        $harga = mysqli_real_escape_string($this->koneksi, $harga);
        $kilo = mysqli_real_escape_string($this->koneksi, $kilo);
        $total = mysqli_real_escape_string($this->koneksi, $total);
        $tanggal_masuk = mysqli_real_escape_string($this->koneksi, $tanggal_masuk);
        $tanggal_keluar = mysqli_real_escape_string($this->koneksi, $tanggal_keluar);
        $status = mysqli_real_escape_string($this->koneksi, $status);
        
        $query = "INSERT INTO transaksi (kode_transaksi, kode_pelanggan, kode_paket, harga, kilo, total, tanggal_masuk, tanggal_keluar, status) 
                  VALUES ('$kode_transaksi', '$kode_pelanggan', '$kode_paket', '$harga', '$kilo', '$total', '$tanggal_masuk', '$tanggal_keluar', '$status')";
        
        return mysqli_query($this->koneksi, $query);
    }
    public function tambah_data_paket($kode_paket, $paket, $harga_paket, $deskripsi)
    {
        $kode_paket = mysqli_real_escape_string($this->koneksi, $kode_paket);
        $paket = mysqli_real_escape_string($this->koneksi, $paket);
        $harga_paket = mysqli_real_escape_string($this->koneksi, $harga_paket);
        $deskripsi = mysqli_real_escape_string($this->koneksi, $deskripsi);
        
        $query = "INSERT INTO paket VALUES ('', '$kode_paket', '$paket', '$harga_paket', '$deskripsi')";
        return mysqli_query($this->koneksi, $query);
    }

    public function tampil_data_paket()
    {
        $hasil = [];
        $query = "SELECT * FROM paket";
        $data = mysqli_query($this->koneksi, $query);
        
        while ($row = mysqli_fetch_array($data)) {
            $hasil[] = $row;
        }
        return $hasil;
    }
    public function username($username)
    {
        $username = mysqli_real_escape_string($this->koneksi, $username);
        $hasil_pelanggan = [];
        
        $query = "SELECT a.*, b.* FROM user a
                  INNER JOIN jenis_kelamin b ON b.kode_jk = a.jenis_kelamin
                  WHERE a.username = '$username'";
        
        $data_pelanggan = mysqli_query($this->koneksi, $query);
        while ($row_pelanggan = mysqli_fetch_assoc($data_pelanggan)) {
            $hasil_pelanggan[] = $row_pelanggan;
        }
        return $hasil_pelanggan;
    }
    public function edit_data_pelanggan($kode_pelanggan, $nama_pelanggan, $jenis_kelamin, $alamat, $email, $no_hp)
    {
        $kode_pelanggan = mysqli_real_escape_string($this->koneksi, $kode_pelanggan);
        $nama_pelanggan = mysqli_real_escape_string($this->koneksi, $nama_pelanggan);
        $jenis_kelamin = mysqli_real_escape_string($this->koneksi, $jenis_kelamin);
        $alamat = mysqli_real_escape_string($this->koneksi, $alamat);
        $email = mysqli_real_escape_string($this->koneksi, $email);
        $no_hp = mysqli_real_escape_string($this->koneksi, $no_hp);
        
        $query = "UPDATE user SET 
                    kode_pelanggan = '$kode_pelanggan', 
                    nama_pelanggan = '$nama_pelanggan', 
                    jenis_kelamin = '$jenis_kelamin',
                    alamat = '$alamat', 
                    email = '$email', 
                    no_hp = '$no_hp' 
                  WHERE kode_pelanggan = '$kode_pelanggan'";
        
        return mysqli_query($this->koneksi, $query);
    }
    public function hapus_data_pelanggan($username)
    {
        $username = mysqli_real_escape_string($this->koneksi, $username);
        $query = "DELETE FROM user WHERE username = '$username'";
        return mysqli_query($this->koneksi, $query);
    }

    public function edit_data_user($username, $password, $akses_id)
    {
        $username = mysqli_real_escape_string($this->koneksi, $username);
        $password = mysqli_real_escape_string($this->koneksi, $password);
        $akses_id = mysqli_real_escape_string($this->koneksi, $akses_id);
        
        $query = "UPDATE user SET password = '$password', akses_id = '$akses_id' WHERE username = '$username'";
        return mysqli_query($this->koneksi, $query);
    }

    public function hapus_data_user($username)
    {
        $username = mysqli_real_escape_string($this->koneksi, $username);
        $query = "DELETE FROM user WHERE username = '$username'";
        return mysqli_query($this->koneksi, $query);
    }
    public function edit_data_paket($kode_paket, $paket, $harga_paket, $deskripsi)
    {
        $kode_paket = mysqli_real_escape_string($this->koneksi, $kode_paket);
        $paket = mysqli_real_escape_string($this->koneksi, $paket);
        $harga_paket = mysqli_real_escape_string($this->koneksi, $harga_paket);
        $deskripsi = mysqli_real_escape_string($this->koneksi, $deskripsi);
        
        $query = "UPDATE paket SET 
                    paket = '$paket', 
                    harga_paket = '$harga_paket', 
                    deskripsi = '$deskripsi' 
                  WHERE kode_paket = '$kode_paket'";
        
        return mysqli_query($this->koneksi, $query);
    }

    public function hapus_data_paket($kode_paket)
    {
        $kode_paket = mysqli_real_escape_string($this->koneksi, $kode_paket);
        $query = "DELETE FROM paket WHERE kode_paket = '$kode_paket'";
        return mysqli_query($this->koneksi, $query);
    }
    public function kode_paket($kode_paket)
    {
        $kode_paket = mysqli_real_escape_string($this->koneksi, $kode_paket);
        $hasil_paket = [];
        
        $query = "SELECT * FROM paket WHERE kode_paket = '$kode_paket'";
        $data_paket = mysqli_query($this->koneksi, $query);
        
        while ($row_paket = mysqli_fetch_assoc($data_paket)) {
            $hasil_paket[] = $row_paket;
        }
        return $hasil_paket;
    }
    public function tampil_transaksi_pelanggan($username)
    {
        $username = mysqli_real_escape_string($this->koneksi, $username);
        $hasil = [];
        
        $query = "SELECT a.*, b.*, c.*, c.kode_pelanggan, d.*
                  FROM transaksi a
                  INNER JOIN paket b ON b.kode_paket = a.kode_paket
                  INNER JOIN user c ON c.kode_pelanggan = a.kode_pelanggan
                  INNER JOIN paket d ON d.harga_paket = a.harga
                  WHERE a.kode_pelanggan = '$username'
                  ORDER BY a.tanggal_masuk ASC";
        
        $data = mysqli_query($this->koneksi, $query);
        while ($row = mysqli_fetch_array($data)) {
            $hasil[] = $row;
        }
        return $hasil;
    }
    public function kode_transaksi($kode_transaksi)
    {
        $kode_transaksi = mysqli_real_escape_string($this->koneksi, $kode_transaksi);
        $hasil_transaksi = [];
        
        $query = "SELECT * FROM transaksi WHERE kode_transaksi = '$kode_transaksi'";
        $data_transaksi = mysqli_query($this->koneksi, $query);
        
        while ($row_transaksi = mysqli_fetch_assoc($data_transaksi)) {
            $hasil_transaksi[] = $row_transaksi;
        }
        return $hasil_transaksi;
    }
    public function edit_data_transaksi($kode_transaksi, $kode_pelanggan, $kode_paket, $harga, $kilo, $total, $tanggal_masuk, $tanggal_keluar, $status)
    {
        $kode_transaksi = mysqli_real_escape_string($this->koneksi, $kode_transaksi);
        $kode_pelanggan = mysqli_real_escape_string($this->koneksi, $kode_pelanggan);
        $kode_paket = mysqli_real_escape_string($this->koneksi, $kode_paket);
        $harga = mysqli_real_escape_string($this->koneksi, $harga);
        $kilo = mysqli_real_escape_string($this->koneksi, $kilo);
        $total = mysqli_real_escape_string($this->koneksi, $total);
        $tanggal_masuk = mysqli_real_escape_string($this->koneksi, $tanggal_masuk);
        $tanggal_keluar = mysqli_real_escape_string($this->koneksi, $tanggal_keluar);
        $status = mysqli_real_escape_string($this->koneksi, $status);
        
        $query = "UPDATE transaksi SET 
                    kode_pelanggan = '$kode_pelanggan', 
                    kode_paket = '$kode_paket', 
                    harga = '$harga', 
                    kilo = '$kilo',
                    total = '$total', 
                    tanggal_masuk = '$tanggal_masuk', 
                    tanggal_keluar = '$tanggal_keluar', 
                    status = '$status' 
                  WHERE kode_transaksi = '$kode_transaksi'";
        
        return mysqli_query($this->koneksi, $query);
    }
    public function hapus_data_transaksi($kode_transaksi)
    {
        $kode_transaksi = mysqli_real_escape_string($this->koneksi, $kode_transaksi);
        $query = "DELETE FROM transaksi WHERE kode_transaksi = '$kode_transaksi'";
        return mysqli_query($this->koneksi, $query);
    }

    public function ambil_data_pelanggan()
    {
        $hasil_data_pelanggan = [];
        $query = "SELECT * FROM user";
        $data_pelanggan = mysqli_query($this->koneksi, $query);
        
        while ($row_data_pelanggan = mysqli_fetch_array($data_pelanggan)) {
            $hasil_data_pelanggan[] = $row_data_pelanggan;
        }
        return $hasil_data_pelanggan;
    }

    public function ambil_data_paket()
    {
        $hasil_data_paket = [];
        $query = "SELECT * FROM paket";
        $data_paket = mysqli_query($this->koneksi, $query);
        
        while ($row_data_paket = mysqli_fetch_array($data_paket)) {
            $hasil_data_paket[] = $row_data_paket;
        }
        return $hasil_data_paket;
    }
    public function update_total_transaksi($kode_transaksi, $total)
    {
        $kode_transaksi = mysqli_real_escape_string($this->koneksi, $kode_transaksi);
        $total = mysqli_real_escape_string($this->koneksi, $total);
        
        $query = "UPDATE transaksi SET total = '$total' WHERE kode_transaksi = '$kode_transaksi'";
        return mysqli_query($this->koneksi, $query);
    }
}
?>
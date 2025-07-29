<?php
/**
 * Database Setup Script for Laundry Fezz
 * Run this file once to initialize the database with sample data
 */

include '../routes/config.php';

try {
    $db = new Database();
    echo "✅ Database connection successful!\n\n";
    
    // Check if tables exist
    $tables = ['jenis_kelamin', 'user', 'paket', 'transaksi'];
    $tablesExist = true;
    
    foreach ($tables as $table) {
        $result = mysqli_query($db->koneksi, "SHOW TABLES LIKE '$table'");
        if (mysqli_num_rows($result) == 0) {
            $tablesExist = false;
            echo "❌ Table '$table' does not exist!\n";
        } else {
            echo "✅ Table '$table' exists\n";
        }
    }
    
    if (!$tablesExist) {
        echo "\n📋 Please run the SQL schema file first:\n";
        echo "mysql -u root -p laundry_fezz < database/schema.sql\n\n";
        exit;
    }
    
    echo "\n🔍 Checking existing data...\n";
    
    // Check and insert sample data if needed
    
    // 1. Check jenis_kelamin
    $result = mysqli_query($db->koneksi, "SELECT COUNT(*) as count FROM jenis_kelamin");
    $row = mysqli_fetch_assoc($result);
    if ($row['count'] == 0) {
        echo "📝 Inserting jenis_kelamin data...\n";
        mysqli_query($db->koneksi, "INSERT INTO jenis_kelamin (kode_jk, jenis_kelamin) VALUES ('L', 'Laki-laki'), ('P', 'Perempuan')");
    }
    
    // 2. Check admin user
    $result = mysqli_query($db->koneksi, "SELECT COUNT(*) as count FROM user WHERE username = 'admin'");
    $row = mysqli_fetch_assoc($result);
    if ($row['count'] == 0) {
        echo "👤 Creating admin user...\n";
        $db->tambah_data_user('admin', 'admin123', 1, 'ADM001', 'Administrator', 'L', 'Jl. Admin No. 1', 'admin@laundryfezz.com', '081234567890');
    }
    
    // 3. Check paket data
    $result = mysqli_query($db->koneksi, "SELECT COUNT(*) as count FROM paket");
    $row = mysqli_fetch_assoc($result);
    if ($row['count'] == 0) {
        echo "📦 Creating sample packages...\n";
        $db->tambah_data_paket('PKT001', 'Cuci Kering', 5000, 'Layanan cuci dan kering biasa');
        $db->tambah_data_paket('PKT002', 'Cuci Setrika', 7000, 'Layanan cuci, kering, dan setrika');
        $db->tambah_data_paket('PKT003', 'Cuci Express', 10000, 'Layanan cuci cepat selesai dalam 24 jam');
        $db->tambah_data_paket('PKT004', 'Dry Clean', 15000, 'Layanan dry cleaning untuk pakaian khusus');
    }
    
    // 4. Create sample pelanggan
    $result = mysqli_query($db->koneksi, "SELECT COUNT(*) as count FROM user WHERE akses_id = 2");
    $row = mysqli_fetch_assoc($result);
    if ($row['count'] == 0) {
        echo "👥 Creating sample customers...\n";
        $db->tambah_data_user('customer1', 'customer123', 2, 'PLG001', 'John Doe', 'L', 'Jl. Pelanggan No. 1', 'john@email.com', '081234567891');
        $db->tambah_data_user('customer2', 'customer123', 2, 'PLG002', 'Jane Smith', 'P', 'Jl. Pelanggan No. 2', 'jane@email.com', '081234567892');
    }
    
    // 5. Create sample transactions
    $result = mysqli_query($db->koneksi, "SELECT COUNT(*) as count FROM transaksi");
    $row = mysqli_fetch_assoc($result);
    if ($row['count'] == 0) {
        echo "💰 Creating sample transactions...\n";
        $db->tambah_transaksi('TRX20250130001', 'PLG001', 'PKT001', 5000, 2.5, 12500, '2025-01-30', null, 'Proses');
        $db->tambah_transaksi('TRX20250130002', 'PLG002', 'PKT002', 7000, 3.0, 21000, '2025-01-29', '2025-01-30', 'Selesai');
        $db->tambah_transaksi('TRX20250130003', 'PLG001', 'PKT003', 10000, 1.5, 15000, '2025-01-28', '2025-01-29', 'Selesai');
    }
    
    echo "\n🎉 Database initialization completed successfully!\n\n";
    echo "📊 Database Summary:\n";
    
    // Show summary
    $result = mysqli_query($db->koneksi, "SELECT COUNT(*) as count FROM user WHERE akses_id = 1");
    $admin_count = mysqli_fetch_assoc($result)['count'];
    echo "   👑 Admins: $admin_count\n";
    
    $result = mysqli_query($db->koneksi, "SELECT COUNT(*) as count FROM user WHERE akses_id = 2");
    $customer_count = mysqli_fetch_assoc($result)['count'];
    echo "   👥 Customers: $customer_count\n";
    
    $result = mysqli_query($db->koneksi, "SELECT COUNT(*) as count FROM paket");
    $package_count = mysqli_fetch_assoc($result)['count'];
    echo "   📦 Packages: $package_count\n";
    
    $result = mysqli_query($db->koneksi, "SELECT COUNT(*) as count FROM transaksi");
    $transaction_count = mysqli_fetch_assoc($result)['count'];
    echo "   💰 Transactions: $transaction_count\n";
    
    echo "\n🔑 Default Login Credentials:\n";
    echo "   Admin - Username: admin, Password: admin123\n";
    echo "   Customer - Username: customer1, Password: customer123\n";
    echo "   Customer - Username: customer2, Password: customer123\n";
    
    echo "\n🌐 You can now access the application at: http://localhost/laundry-fezz/\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>

<?php

class CurlCookieHandler {
    private $cookieFile;
    private $cookieJar;
    
    public function __construct() {
        // Buat temporary file untuk menyimpan cookie
        $this->cookieFile = tempnam(sys_get_temp_dir(), 'cookie_');
        $this->cookieJar = [];
    }
    
    // Fungsi untuk login dan mendapatkan cookie
    public function loginAndGetCookie($loginUrl, $postData) {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $loginUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_COOKIEJAR => $this->cookieFile,  // Simpan cookie ke file
            CURLOPT_COOKIEFILE => $this->cookieFile, // Baca cookie dari file
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            throw new Exception("Login Error: " . $error);
        }
        
        return $response;
    }
    
    // Fungsi untuk request dengan cookie yang sudah ada
    public function requestWithCookie($url, $method = 'GET', $postData = null) {
        $ch = curl_init();
        
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEFILE => $this->cookieFile, // Gunakan cookie yang sudah disimpan
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true
        ];
        
        if ($method === 'POST') {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = http_build_query($postData);
        }
        
        curl_setopt_array($ch, $options);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            throw new Exception("Request Error: " . $error);
        }
        
        return $response;
    }
    
    // Fungsi untuk membaca cookie yang tersimpan
    public function getCookies() {
        if (file_exists($this->cookieFile)) {
            return file_get_contents($this->cookieFile);
        }
        return null;
    }
    
    // Hapus file cookie saat objek dihancurkan
    public function __destruct() {
        if (file_exists($this->cookieFile)) {
            unlink($this->cookieFile);
        }
    }
}

// Contoh penggunaan
try {
    // Inisialisasi handler
    $cookieHandler = new CurlCookieHandler();
    
    // Data login
    $loginData = [
        'email' => 'deeniedoank@gmail.com',
        'password' => 'deenie88'
    ];
    
    // Login dan dapatkan cookie
    $loginResponse = $cookieHandler->loginAndGetCookie(
        'https://btcspinner.io/login',
        $loginData
    );
    
    // Lakukan request ke halaman yang membutuhkan authentication
    $protectedPageResponse = $cookieHandler->requestWithCookie(
        'https://btcspinner.io/spinner'
    );
    
    // Contoh POST request dengan cookie
    $postData = [
        'key' => 'value'
    ];
    $postResponse = $cookieHandler->requestWithCookie(
        'https://btcspinner.io/spinner',
        'POST',
        $postData
    );
    
    // Lihat cookie yang tersimpan
    echo "Stored Cookies:\n";
    echo $cookieHandler->getCookies();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

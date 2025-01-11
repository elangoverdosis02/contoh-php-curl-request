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
    public function requestWithCookie($url, $method = 'GET', $postData = null, $headers2) {
        $ch = curl_init();
        
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEFILE => $this->cookieFile, // Gunakan cookie yang sudah disimpan
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true
        ];
        
        if ($method === 'PATCH') {
        
        $options[CURLOPT_FOLLOWLOCATION] = true;
        $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
        $options[CURLOPT_CUSTOMREQUEST] = 'PATCH';
        $options[CURLOPT_POSTFIELDS] = json_encode($postData);
        $options[CURLOPT_HTTPHEADER] = $headers2;
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

      $headers2 = [
     'upgrade-insecure-requests: 1',
      'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36',
       'accept: application/json, text/javascript, */*; q=0.01',
       'x-requested-with: mark.via.gp',
       'sec-ch-ua: "Android WebView";v="131", "Chromium";v="131", "Not_A ',
       'Brand";v="24"',
       'content-type: application/x-www-form-urlencoded; charset=UTF-8',
       'sec-ch-ua-mobile: ?1',
       'origin: https://btcspinner.io',
       'sec-fetch-site: same-origin',
       'sec-fetch-mode: cors',
       'sec-fetch-dest: empty',
       'referer: https://btcspinner.io/spinner',
       'accept-encoding: gzip, deflate, br, zstd',
       'accept-language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7'
    
];
    
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
    
    // Contoh PATCH request dengan cookie
    $postData = [
        '_token' => 'cHUwzzXkYR1Dsl7WX2CrVPRstpvUj0uQXpTEHLAw',
        'token' => 'user_f1c95c9afd4aeffea4be05608042b8ab',
        'coins' => '9.074291481960144'
    ];
    $postResponse = $cookieHandler->requestWithCookie(
        'https://btcspinner.io/spinner',
        'PATCH',
        $postData,
         $headers2
    );
    echo "Login Response: " . $loginResponse . "\n\n";
    echo "PATCH Response: " . $postResponse . "\n\n";
    // Lihat cookie yang tersimpan
    echo "Stored Cookies:\n";
    echo $cookieHandler->getCookies();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

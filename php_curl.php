<?php

// Fungsi untuk melakukan simple GET request
function makeGetRequest($url, $headers = []) {
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => $headers
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        return "Error: " . $err;
    }
    return $response;
}

// Fungsi untuk melakukan POST request dengan JSON data
function makePostRequest($url, $data, $headers = []) {
    $curl = curl_init();
    
    // Tambahkan header content-type JSON jika belum ada
    $headers[] = 'Content-Type: application/json';
    
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => $headers
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        return "Error: " . $err;
    }
    return $response;
}

// Contoh penggunaan
$headers = [
    'Authorization: Bearer YOUR_TOKEN_HERE',
    'Accept: application/json'
];

// Contoh GET request
$getResponse = makeGetRequest(
    'https://api.example.com/data',
    $headers
);
echo "GET Response: " . $getResponse . "\n";

// Contoh POST request
$postData = [
    'name' => 'John Doe',
    'email' => 'john@example.com'
];

$postResponse = makePostRequest(
    'https://api.example.com/users',
    $postData,
    $headers
);
echo "POST Response: " . $postResponse . "\n";
?>

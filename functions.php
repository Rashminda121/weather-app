<?php
function getWeatherData($api_key, $city) {
    // Check if city is empty
    if (empty($city)) {
        return ['error' => ['message' => 'Please enter a city name']];
    }

    $url = "http://api.weatherapi.com/v1/current.json?key={$api_key}&q={$city}&aqi=no";
    
    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    // Execute the request
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Close cURL session
    curl_close($ch);
    
    // Check for errors
    if ($http_code !== 200) {
        $error_data = json_decode($response, true);
        return ['error' => [
            'message' => $error_data['error']['message'] ?? 'Failed to fetch weather data'
        ]];
    }
    
    // Return decoded JSON data
    return json_decode($response, true);
}
?>
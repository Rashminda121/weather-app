<?php
require_once 'functions.php';
require_once 'load_env.php';
loadEnv(__DIR__ . '/.env');

session_start();

// Set default theme mode if not set
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light'; // Default to light mode
}

// Toggle theme if requested
if (isset($_GET['toggle_theme'])) {
    $_SESSION['theme'] = ($_SESSION['theme'] === 'light') ? 'dark' : 'light';
}

$api_key = $_ENV['WEATHER_API'];
$default_city = 'Colombo';
$city = isset($_GET['city']) ? $_GET['city'] : $default_city;

// Fetch weather data
$weather_data = getWeatherData($api_key, $city);
$is_day = isset($weather_data['current']['is_day']) ? $weather_data['current']['is_day'] : 1;

// Calculate sunrise/sunset times if available
$sun_info = getSunInfo($api_key, $city);
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $_SESSION['theme']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container <?php echo $is_day ? 'day' : 'night'; ?>">
        <div class="header-container">
            <h1 class="app-title">Weather Dashboard</h1>
            <div class="theme-toggle">
                <a href="?toggle_theme=1&city=<?php echo urlencode($city); ?>" class="theme-btn">
                    <i class="fas fa-<?php echo $_SESSION['theme'] === 'dark' ? 'sun' : 'moon'; ?>"></i>
                    <span><?php echo ucfirst($_SESSION['theme']); ?> Mode</span>
                </a>
            </div>
        </div>
        
        <div class="search-container">
            <form action="" method="GET">
                <input type="text" name="city" placeholder="Search for a city..." value="<?php echo htmlspecialchars($city); ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
                <input type="hidden" name="toggle_theme" value="<?php echo $_GET['toggle_theme'] ?? ''; ?>">
            </form>
        </div>
        
        <?php if (isset($weather_data['error'])): ?>
            <div class="error-message">
                <p><?php echo $weather_data['error']['message']; ?></p>
            </div>
        <?php elseif ($weather_data): ?>
            <!-- Main Weather Card -->
            <div class="weather-card">
                <div class="location-time">
                    <h2><?php echo $weather_data['location']['name']; ?>, <?php echo $weather_data['location']['country']; ?></h2>
                    <p class="date"><?php echo date('l, F j, Y', strtotime($weather_data['location']['localtime'])); ?></p>
                    <p class="time"><?php echo date('h:i A', strtotime($weather_data['location']['localtime'])); ?></p>
                </div>
                
                <div class="current-weather">
                    <div class="temperature">
                        <img src="<?php echo $weather_data['current']['condition']['icon']; ?>" alt="<?php echo $weather_data['current']['condition']['text']; ?>">
                        <div>
                            <span class="main-temp"><?php echo round($weather_data['current']['temp_c']); ?>°C</span>
                            <span class="feels-like">Feels like <?php echo round($weather_data['current']['feelslike_c']); ?>°C</span>
                        </div>
                    </div>
                    <div class="condition">
                        <p class="weather-condition"><?php echo $weather_data['current']['condition']['text']; ?></p>
                        <div class="temp-range">
                            <span><i class="fas fa-temperature-high"></i> High: <?php echo round($weather_data['forecast']['forecastday'][0]['day']['maxtemp_c']); ?>°C</span>
                            <span><i class="fas fa-temperature-low"></i> Low: <?php echo round($weather_data['forecast']['forecastday'][0]['day']['mintemp_c']); ?>°C</span>
                        </div>
                    </div>
                </div>
                
                <!-- Primary Weather Metrics -->
                <div class="weather-metrics">
                    <div class="metric">
                        <i class="fas fa-tint"></i>
                        <div>
                            <span class="metric-label">Humidity</span>
                            <span class="metric-value"><?php echo $weather_data['current']['humidity']; ?>%</span>
                            <span class="metric-desc"><?php echo getHumidityDescription($weather_data['current']['humidity']); ?></span>
                        </div>
                    </div>
                    <div class="metric">
                        <i class="fas fa-wind"></i>
                        <div>
                            <span class="metric-label">Wind</span>
                            <span class="metric-value"><?php echo round($weather_data['current']['wind_kph']); ?> km/h</span>
                            <span class="metric-desc"><?php echo $weather_data['current']['wind_dir']; ?></span>
                        </div>
                    </div>
                    <div class="metric">
                        <i class="fas fa-sun"></i>
                        <div>
                            <span class="metric-label">UV Index</span>
                            <span class="metric-value"><?php echo $weather_data['current']['uv']; ?></span>
                            <span class="metric-desc"><?php echo getUVIndexDescription($weather_data['current']['uv']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Widgets Row -->
            <div class="widgets-row">
                <!-- Sunrise/Sunset Widget -->
                <div class="widget sun-times">
                    <h3><i class="fas fa-sun"></i> Sun Times</h3>
                    <div class="sun-data">
                        <div class="sun-event">
                            <span>Sunrise</span>
                            <span><?php echo $sun_info['sunrise']; ?></span>
                        </div>
                        <div class="sun-event">
                            <span>Sunset</span>
                            <span><?php echo $sun_info['sunset']; ?></span>
                        </div>
                        <div class="daylight">
                            <span>Daylight</span>
                            <span><?php echo $sun_info['daylight']; ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Air Quality Widget -->
                <div class="widget air-quality">
                    <h3><i class="fas fa-wind"></i> Air Quality</h3>
                    <div class="aqi-container">
                        <div 
                            class="aqi-index" 
                            style="<?php 
                                $aqiIndex = $weather_data['current']['air_quality']['us-epa-index'] ?? null;
                                $colors = $aqiIndex ? getAQIColors($aqiIndex) : ['bg' => '#fff', 'text' => '#000'];
                                echo "background: {$colors['bg']}; color: {$colors['text']};";
                            ?>"
                        >
                            <?= $aqiIndex ? getAQIDescription($aqiIndex) : 'N/A'; ?>
                        </div>
                        <div class="pollutants">
                            <div>
                                <span>CO</span>
                                <span><?= round($weather_data['current']['air_quality']['co'], 1) ?> µg/m³</span>
                            </div>
                            <div>
                                <span>NO₂</span>
                                <span><?= round($weather_data['current']['air_quality']['no2'], 1) ?> µg/m³</span>
                            </div>
                            <div>
                                <span>O₃</span>
                                <span><?= round($weather_data['current']['air_quality']['o3'], 1) ?> µg/m³</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Hourly Forecast Widget -->
                <div class="widget hourly-forecast">
                    <h3><i class="fas fa-clock"></i> Hourly Forecast</h3>
                    <div class="hourly-container">
                        <?php 
                        $hours = array_slice($weather_data['forecast']['forecastday'][0]['hour'], date('G'), 6);
                        foreach ($hours as $hour): 
                        ?>
                        <div class="hour">
                            <span><?php echo date('H:i', strtotime($hour['time'])); ?></span>
                            <img src="<?php echo $hour['condition']['icon']; ?>" alt="<?php echo $hour['condition']['text']; ?>">
                            <span><?php echo round($hour['temp_c']); ?>°C</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Secondary Metrics -->
            <div class="secondary-metrics">
                <div class="metric-box">
                    <i class="fas fa-cloud-rain"></i>
                    <div>
                        <span>Precipitation</span>
                        <span><?php echo $weather_data['current']['precip_mm']; ?> mm</span>
                    </div>
                </div>
                <div class="metric-box">
                    <i class="fas fa-eye"></i>
                    <div>
                        <span>Visibility</span>
                        <span><?php echo round($weather_data['current']['vis_km']); ?> km</span>
                    </div>
                </div>
                <div class="metric-box">
                    <i class="fas fa-cloud"></i>
                    <div>
                        <span>Cloud Cover</span>
                        <span><?php echo $weather_data['current']['cloud']; ?>%</span>
                    </div>
                </div>
                <div class="metric-box">
                    <i class="fas fa-temperature-high"></i>
                    <div>
                        <span>Pressure</span>
                        <span><?php echo $weather_data['current']['pressure_mb']; ?> mb</span>
                    </div>
                </div>
                <div class="metric-box">
                    <i class="fas fa-moon"></i>
                    <div>
                        <span>Moon Phase</span>
                        <span><?php echo $weather_data['forecast']['forecastday'][0]['astro']['moon_phase']; ?></span>
                    </div>
                </div>
                <div class="metric-box">
                    <i class="fas fa-percentage"></i>
                    <div>
                        <span>Chance of Rain</span>
                        <span><?php echo $weather_data['forecast']['forecastday'][0]['day']['daily_chance_of_rain']; ?>%</span>
                    </div>
                </div>
            </div>
            
            <!-- 3-Day Forecast -->
            <div class="forecast-card">
                <h3><i class="fas fa-calendar-alt"></i> 3-Day Forecast</h3>
                <div class="forecast-days">
                    <?php 
                    $days = array_slice($weather_data['forecast']['forecastday'], 0, 3);
                    foreach ($days as $day): 
                        $date = new DateTime($day['date']);
                    ?>
                    <div class="forecast-day">
                        <span class="day-name"><?php echo $date->format('D'); ?></span>
                        <span class="day-date"><?php echo $date->format('M j'); ?></span>
                        <img src="<?php echo $day['day']['condition']['icon']; ?>" alt="<?php echo $day['day']['condition']['text']; ?>">
                        <div class="day-temps">
                            <span class="day-high"><?php echo round($day['day']['maxtemp_c']); ?>°</span>
                            <span class="day-low"><?php echo round($day['day']['mintemp_c']); ?>°</span>
                        </div>
                        <span class="day-condition"><?php echo $day['day']['condition']['text']; ?></span>
                        <div class="day-rain">
                            <i class="fas fa-cloud-rain"></i>
                            <span><?php echo $day['day']['daily_chance_of_rain']; ?>%</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
        <?php else: ?>
            <div class="loading">
                <div class="spinner"></div>
                <p>Loading weather data...</p>
            </div>
        <?php endif; ?>
        
        <footer>
            <p>Weather data provided by <a href="https://www.weatherapi.com/" target="_blank">WeatherAPI.com</a></p>
            <p class="last-updated">Last updated: <?php echo date('h:i A'); ?></p>
        </footer>
    </div>
    
    <script>
        // Auto-refresh every 15 minutes
        setTimeout(function(){
            window.location.reload();
        }, 900000);
        
        // Smooth theme transition
        document.documentElement.style.transition = 'background-color 0.5s ease';
    </script>
</body>
</html>
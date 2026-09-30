<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safeway";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// 1) Crime Stats: Fetch area, incident_count, crime_index, last_updated
$crimeStats = [];
$result = $conn->query("SELECT area, incident_count, crime_index, last_updated FROM crime_stats");
while ($row = $result->fetch_assoc()) {
    $crimeStats[] = $row;
}

// 2) Safety Distribution: Fetch category, label, value, weightage_percent
$safetyData = [];
$result = $conn->query("SELECT category, label, value, weightage_percent FROM safety_distribution");
while ($row = $result->fetch_assoc()) {
    $safetyData[] = $row;
}

// 3) Safety Checks: Fetch user_id, check_day, check_time, check_type, location, status
$safetyChecks = [];
$result = $conn->query("SELECT user_id, check_day, check_time, check_type, location, status FROM safety_checks");
while ($row = $result->fetch_assoc()) {
    $safetyChecks[] = $row;
}

// 4) Feature Usage: Fetch feature, usage_count, user_type, last_used_at
$featureUsage = [];
$result = $conn->query("SELECT feature, usage_count, user_type, last_used_at FROM feature_usage");
while ($row = $result->fetch_assoc()) {
    $featureUsage[] = $row;
}

// 5) Crime Type Trends: Weekly trends for all crime types, ordered by week asc
$labels = [];
$datasets = [
    ['label' => 'Theft',       'data' => [], 'borderColor' => '#e74c3c', 'fill' => false],
    ['label' => 'Harassment',  'data' => [], 'borderColor' => '#f39c12', 'fill' => false],
    ['label' => 'Assault',     'data' => [], 'borderColor' => '#8e44ad', 'fill' => false],
    ['label' => 'Robbery',     'data' => [], 'borderColor' => '#3498db', 'fill' => false],
    ['label' => 'Vandalism',   'data' => [], 'borderColor' => '#2ecc71', 'fill' => false],
    ['label' => 'Kidnapping',  'data' => [], 'borderColor' => '#e67e22', 'fill' => false]
];
$result = $conn->query("SELECT week, theft, harassment, assault, robbery, vandalism, kidnapping FROM crime_type_trends ORDER BY STR_TO_DATE(week, '%Y-%u')");
while ($row = $result->fetch_assoc()) {
    $labels[] = $row['week'];
    $datasets[0]['data'][] = (int)$row['theft'];
    $datasets[1]['data'][] = (int)$row['harassment'];
    $datasets[2]['data'][] = (int)$row['assault'];
    $datasets[3]['data'][] = (int)$row['robbery'];
    $datasets[4]['data'][] = (int)$row['vandalism'];
    $datasets[5]['data'][] = (int)$row['kidnapping'];
}
$crimeTypes = ['labels' => $labels, 'datasets' => $datasets];

// 6) Emergency Response: Fetch area, service_type, average_response_time_min, response_rating
$responseData = [];
$result = $conn->query("SELECT area, service_type, average_response_time_min, response_rating, last_reported FROM emergency_response");
while ($row = $result->fetch_assoc()) {
    $responseData[] = $row;
}

// 7) Notification Engagement: Labels by type, with viewed, clicked, dismissed counts
$notifEngage = ['labels' => [], 'datasets' => [
    ['label' => 'Viewed',    'data' => [], 'backgroundColor' => '#28a745'],
    ['label' => 'Clicked',   'data' => [], 'backgroundColor' => '#17a2b8'],
    ['label' => 'Dismissed', 'data' => [], 'backgroundColor' => '#dc3545']
]];
$result = $conn->query("SELECT type, viewed, clicked, dismissed, sent_at FROM notification_engagement");
while ($row = $result->fetch_assoc()) {
    $notifEngage['labels'][] = $row['type'];
    $notifEngage['datasets'][0]['data'][] = (int)$row['viewed'];
    $notifEngage['datasets'][1]['data'][] = (int)$row['clicked'];
    $notifEngage['datasets'][2]['data'][] = (int)$row['dismissed'];
}

// 8) Feedback Bubble: Fetch label, feedback_positive, feedback_negative, safety_score, traffic_density
$bubbleData = [];
$result = $conn->query("SELECT label, feedback_positive, feedback_negative, safety_score, traffic_density, submitted_at FROM feedback_bubble");
while ($row = $result->fetch_assoc()) {
    $bubbleData[] = [
        'label' => $row['label'],
        'data' => [[
            'x' => (int)$row['feedback_positive'],
            'y' => (float)$row['safety_score'],
            'r' => max(5, (float)$row['traffic_density'] * 10) // scale bubble size (min size 5)
        ]],
        // Use consistent color for better UX, but random is okay if you want variety
        'backgroundColor' => 'rgba(' . rand(50,200) . ',' . rand(50,200) . ',' . rand(50,200) . ',0.6)'
    ];
}

// Output all data as JSON
echo json_encode([
    'crimeStats'    => $crimeStats,
    'safetyData'    => $safetyData,
    'safetyChecks'  => $safetyChecks,
    'featureUsage'  => $featureUsage,
    'crimeTypes'    => $crimeTypes,
    'responseData'  => $responseData,
    'notifEngage'   => $notifEngage,
    'bubbleData'    => $bubbleData
]);
?>

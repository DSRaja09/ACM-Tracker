<?php
// Database configuration
$host = 'localhost';
$db = 'user_registration';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Handle connection error
    die('Connection failed: ' . $e->getMessage());
}

// Fetch participants
$participants = $pdo->query('SELECT name, cf_handles FROM participants')->fetchAll();

// Fetch contest link
$contest = $pdo->query('SELECT contest_link_4 FROM tfc_contests LIMIT 1')->fetch();
$contest_link = $contest['contest_link_4'];

// Parse contest ID from contest link (assuming the format is like 'https://codeforces.com/contest/{contestId}')
preg_match('/contest\/(\d+)/', $contest_link, $matches);
$contest_id = $matches[1];

// Fetch ranks from Codeforces API
$ranks = [];
if ($contest_id) {
    foreach ($participants as $participant) {
        $handle = $participant['cf_handles'];
        $api_url = "https://codeforces.com/api/contest.standings?contestId={$contest_id}&handles={$handle}&showUnofficial=true";
        
        $api_response = file_get_contents($api_url);
        $result = json_decode($api_response, true);
        
        if ($result && $result['status'] === 'OK') {
            $rank = $result['result']['rows'][0]['rank'];
            $penalty = $result['result']['rows'][0]['penalty'];
            $ranks[] = [
                'name' => $participant['name'],
                'rank' => $rank,
                'penalty' => $penalty
            ];
        }
    }
}

// Sort ranks array by rank in ascending order
usort($ranks, function($a, $b) {
    return $a['rank'] <=> $b['rank'];
});

// Divide participants into teams
$teams = [];
foreach ($ranks as $index => $rank) {
    $team_index = floor($index / 3);
    $teams[$team_index][] = $rank;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rankings & Teams</title>
  <link rel="stylesheet" href="Dashboard.css">
</head>
<body>
  <header> <h1> Board </h1> </header>
  <h2>Ranking</h2>
  <table>
    <thead class="green-bg">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Rank</th>
        <th>Penalty</th>
      </tr>
    </thead>
    <tbody class="bodyclass">
      <?php foreach ($ranks as $index => $rank): ?>
      <tr>
        <td><?php echo $index + 1; ?></td>
        <td><?php echo htmlspecialchars($rank['name']); ?></td>
        <td><?php echo htmlspecialchars($rank['rank']); ?></td>
        <td><?php echo htmlspecialchars($rank['penalty']); ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h2>Teams</h2>
  <table>
    <thead>
      <tr>
        <th>Team</th>
        <th>Players</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($teams as $team_index => $team_members): ?>
      <tr>
        <td>Team <?php echo $team_index + 1; ?></td>
        <td>
          <?php 
          $player_names = array_map(function($member) {
              return htmlspecialchars($member['name']);
          }, $team_members);
          echo implode(', ', $player_names);
          ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>

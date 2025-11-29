<?php
$pageTitle = "Historique des Quiz - Administration";
include __DIR__ . '/../header.php';
require_once __DIR__ . '/../../db_conn.php';
$connection = getConnection();

// ✅ Récupération des filtres
$userFilter  = isset($_GET['user']) ? trim($_GET['user']) : '';
$courseFilter = isset($_GET['course']) ? trim($_GET['course']) : '';
$dateFilter  = isset($_GET['date']) ? trim($_GET['date']) : '';

// ✅ Construction de la requête avec filtres dynamiques
$query = "
    SELECT qr.score, qr.completed_at, r.nom, r.prenom, c.title AS course_title
    FROM quiz_results qr
    JOIN registerform r ON qr.user_id = r.id
    JOIN courses c ON qr.quiz_id = c.id
    WHERE 1=1
";

if ($userFilter !== '') {
    $query .= " AND (r.nom LIKE '%" . mysqli_real_escape_string($connection, $userFilter) . "%' 
                 OR r.prenom LIKE '%" . mysqli_real_escape_string($connection, $userFilter) . "%')";
}
if ($courseFilter !== '') {
    $query .= " AND c.title LIKE '%" . mysqli_real_escape_string($connection, $courseFilter) . "%'";
}
if ($dateFilter !== '') {
    $query .= " AND DATE(qr.completed_at) = '" . mysqli_real_escape_string($connection, $dateFilter) . "'";
}

$query .= " ORDER BY qr.completed_at DESC";
$quizHistory = mysqli_query($connection, $query);

// ✅ Préparer les données pour le graphique (score moyen par cours)
$chartQuery = "
    SELECT c.title AS course_title, AVG(qr.score) AS avg_score
    FROM quiz_results qr
    JOIN courses c ON qr.quiz_id = c.id
    GROUP BY c.title
    ORDER BY c.title
";
$chartResult = mysqli_query($connection, $chartQuery);

$labels = [];
$dataScores = [];
while ($row = mysqli_fetch_assoc($chartResult)) {
    $labels[] = $row['course_title'];
    $dataScores[] = round($row['avg_score'], 2);
}
?>

<h1 class="mb-4"><span class="highlight">🕒 Historique des Quiz</span></h1>

<!-- ✅ Formulaire de filtres -->
<form method="get" class="row mb-4">
    <div class="col-md-3">
        <input type="text" name="user" class="form-control" placeholder="Filtrer par utilisateur" value="<?= htmlspecialchars($userFilter) ?>">
    </div>
    <div class="col-md-3">
        <input type="text" name="course" class="form-control" placeholder="Filtrer par cours" value="<?= htmlspecialchars($courseFilter) ?>">
    </div>
    <div class="col-md-3">
        <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($dateFilter) ?>">
    </div>
    <div class="col-md-3 d-flex">
        <button type="submit" class="btn btn-primary flex-fill me-2">🔍 Filtrer</button>
        <a href="?export=csv&user=<?= urlencode($userFilter) ?>&course=<?= urlencode($courseFilter) ?>&date=<?= urlencode($dateFilter) ?>" class="btn btn-success flex-fill">⬇️ Export CSV</a>
    </div>
</form>

<!-- ✅ Tableau des résultats -->
<?php if (mysqli_num_rows($quizHistory) > 0): ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Utilisateur</th>
                    <th>Cours</th>
                    <th>Score</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($h = mysqli_fetch_assoc($quizHistory)): ?>
                    <tr>
                        <td><?= htmlspecialchars($h['nom'] . " " . $h['prenom']) ?></td>
                        <td><?= htmlspecialchars($h['course_title']) ?></td>
                        <td><?= $h['score'] ?>%</td>
                        <td><?= date("d/m/Y H:i", strtotime($h['completed_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-muted">Aucun quiz trouvé pour ces critères.</p>
<?php endif; ?>

<!-- ✅ Graphique des scores moyens par cours -->
<h2 class="mt-5">📊 Scores moyens par cours</h2>
<canvas id="quizChart" height="100"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('quizChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Score moyen (%)',
            data: <?= json_encode($dataScores) ?>,
            backgroundColor: '#2196f3'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});
</script>
<?php
// ✅ Préparer les données pour le graphique temporel (nombre de quiz réalisés par jour)
$timeQuery = "
    SELECT DATE(qr.completed_at) AS quiz_date, COUNT(*) AS total_quiz
    FROM quiz_results qr
    GROUP BY DATE(qr.completed_at)
    ORDER BY quiz_date ASC
";
$timeResult = mysqli_query($connection, $timeQuery);

$timeLabels = [];
$timeData = [];
while ($row = mysqli_fetch_assoc($timeResult)) {
    $timeLabels[] = $row['quiz_date'];
    $timeData[] = $row['total_quiz'];
}
?>

<h2 class="mt-5">📈 Évolution des quiz réalisés dans le temps</h2>
<canvas id="quizTimelineChart" height="100"></canvas>
<script>
new Chart(document.getElementById('quizTimelineChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($timeLabels) ?>,
        datasets: [{
            label: 'Quiz réalisés par jour',
            data: <?= json_encode($timeData) ?>,
            borderColor: '#4caf50',
            backgroundColor: 'rgba(76, 175, 80, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
<?php
// ✅ Préparer les données pour le graphique mensuel (nombre de quiz réalisés par mois)
$monthlyQuery = "
    SELECT DATE_FORMAT(qr.completed_at, '%Y-%m') AS quiz_month, COUNT(*) AS total_quiz
    FROM quiz_results qr
    GROUP BY quiz_month
    ORDER BY quiz_month ASC
";
$monthlyResult = mysqli_query($connection, $monthlyQuery);

$monthLabels = [];
$monthData = [];
while ($row = mysqli_fetch_assoc($monthlyResult)) {
    $monthLabels[] = $row['quiz_month'];
    $monthData[] = $row['total_quiz'];
}
?>

<h2 class="mt-5">📈 Évolution mensuelle des quiz réalisés</h2>
<canvas id="quizMonthlyChart" height="100"></canvas>
<script>
new Chart(document.getElementById('quizMonthlyChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($monthLabels) ?>,
        datasets: [{
            label: 'Quiz réalisés par mois',
            data: <?= json_encode($monthData) ?>,
            borderColor: '#ff9800',
            backgroundColor: 'rgba(255, 152, 0, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
<?php
// ✅ Préparer les données pour le graphique par utilisateur (nombre de quiz réalisés par utilisateur)
$userActivityQuery = "
    SELECT CONCAT(r.nom, ' ', r.prenom) AS full_name, COUNT(*) AS total_quiz
    FROM quiz_results qr
    JOIN registerform r ON qr.user_id = r.id
    GROUP BY full_name
    ORDER BY total_quiz DESC
";
$userActivityResult = mysqli_query($connection, $userActivityQuery);

$userLabels = [];
$userData = [];
while ($row = mysqli_fetch_assoc($userActivityResult)) {
    $userLabels[] = $row['full_name'];
    $userData[] = $row['total_quiz'];
}
?>

<h2 class="mt-5">👥 Comparaison par utilisateur</h2>
<canvas id="userActivityChart" height="100"></canvas>
<script>
new Chart(document.getElementById('userActivityChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($userLabels) ?>,
        datasets: [{
            label: 'Nombre de quiz réalisés',
            data: <?= json_encode($userData) ?>,
            backgroundColor: '#673ab7'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<a href="../admin.php" class="btn btn-secondary mt-3">⬅️ Retour au tableau de bord</a>

<?php include __DIR__ . '/../footer.php'; ?>

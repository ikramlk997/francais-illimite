<?php
$pageTitle = "Tableau de Bord - Administration";
include __DIR__ . '/header.php';
require_once __DIR__ . '/../db_conn.php';
$connection = getConnection();

// ✅ Statistiques globales
$totalUsers   = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) AS total FROM registerform"))['total'] ?? 0;
$totalCourses = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) AS total FROM courses"))['total'] ?? 0;
$totalQuizzes = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) AS total FROM quizzes"))['total'] ?? 0;
$totalQuizDone= mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) AS total FROM quiz_results"))['total'] ?? 0;

// ✅ Pagination
$limit  = 20;
$page   = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$countRes   = mysqli_query($connection, "SELECT COUNT(*) AS total FROM quiz_results");
$totalRows  = mysqli_fetch_assoc($countRes)['total'];
$totalPages = ceil($totalRows / $limit);

// ✅ Historique des quiz réalisés
$quizHistory = mysqli_query($connection, "
    SELECT qr.score, qr.completed_at, r.nom, r.prenom, c.title AS course_title
    FROM quiz_results qr
    JOIN registerform r ON qr.user_id = r.id
    JOIN courses c ON qr.quiz_id = c.id
    ORDER BY qr.completed_at DESC
    LIMIT $limit OFFSET $offset
");
?>

<h1 class="mb-4">⚙️ Tableau de Bord Administration</h1>

<!-- ✅ Dashboard en cartes -->
<div class="dashboard">
    <div class="card"><h2>👥 Utilisateurs</h2><p><?= $totalUsers ?></p></div>
    <div class="card"><h2>📘 Cours</h2><p><?= $totalCourses ?></p></div>
    <div class="card"><h2>🎯 Quiz créés</h2><p><?= $totalQuizzes ?></p></div>
    <div class="card"><h2>📝 Quiz réalisés</h2><p><?= $totalQuizDone ?></p></div>
</div>

<!-- ✅ Graphique global -->
<h2 class="mt-5">📊 Statistiques globales</h2>
<canvas id="adminStatsChart" height="100"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('adminStatsChart'), {
    type: 'bar',
    data: {
        labels: ['Utilisateurs', 'Cours', 'Quiz créés', 'Quiz réalisés'],
        datasets: [{
            label: 'Statistiques Admin',
            data: [<?= $totalUsers ?>, <?= $totalCourses ?>, <?= $totalQuizzes ?>, <?= $totalQuizDone ?>],
            backgroundColor: ['#2196f3','#4caf50','#ff9800','#9c27b0']
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        animation: { duration: 1200, easing: 'easeOutBounce' }
    }
});
</script>

<!-- ✅ Historique des quiz -->
<h2 class="mt-5">🕒 Historique des quiz réalisés</h2>
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

    <!-- ✅ Pagination -->
    <nav aria-label="Pagination">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php else: ?>
    <p class="text-muted">Aucun quiz réalisé pour le moment.</p>
<?php endif; ?>

<a href="../home.php" class="btn btn-secondary mt-3">⬅️ Retour à l'accueil</a>

<?php include __DIR__ . '/footer.php'; ?>

<?php
$pageTitle = "Mon Profil - Français Illimité";
include 'header.php';

require_once 'db_conn.php';
$connection = getConnection();

if (!isset($_SESSION['idu'])) {
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['idu']);
$nom      = $_SESSION['nomu'];
$prenom   = $_SESSION['prenomu'];
$email    = $_SESSION['emailu'];

// ✅ Récupération des cours suivis
$coursesQuery = mysqli_query($connection, "
    SELECT c.title, c.level, uc.progress 
    FROM user_courses uc
    JOIN courses c ON uc.course_id = c.id
    WHERE uc.user_id = $user_id
");

// ✅ Récupération des résultats quiz détaillés (question par question)
$quizQuery = mysqli_query($connection, "
    SELECT q.question, up.answer, up.correct, c.title AS course_title
    FROM user_progress up
    JOIN quizzes q ON up.quiz_id = q.id
    JOIN courses c ON up.course_id = c.id
    WHERE up.user_id = $user_id
");

// ✅ Statistiques globales de l'utilisateur (quiz_results)
$totalCourses = mysqli_num_rows($coursesQuery);

// Nombre de quiz réalisés
$res = mysqli_query($connection, "SELECT COUNT(*) AS total FROM quiz_results WHERE user_id = $user_id");
$data = mysqli_fetch_assoc($res);
$totalQuiz = $data['total'] ?? 0;

// Score moyen
$res = mysqli_query($connection, "SELECT AVG(score) AS avg_score FROM quiz_results WHERE user_id = $user_id");
$data = mysqli_fetch_assoc($res);
$avgScore = $data['avg_score'] ? round($data['avg_score'], 2) : 0;

// ✅ Historique des quiz réalisés
$quizHistory = mysqli_query($connection, "
    SELECT qr.score, qr.completed_at, c.title AS course_title
    FROM quiz_results qr
    JOIN courses c ON qr.quiz_id = c.id
    WHERE qr.user_id = $user_id
    ORDER BY qr.completed_at DESC
");
?>
<div class="profile-card">
    <div class="profile-header text-center">
        <img src="css/images/logo.png" alt="Avatar" class="avatar">
        <h2><?= htmlspecialchars($nom) . " " . htmlspecialchars($prenom) ?></h2>
        <p><?= htmlspecialchars($email) ?></p>
    </div>

    <!-- ✅ Statistiques globales -->
    <div class="profile-section">
        <h3>📊 Mes statistiques</h3>
        <p>Cours suivis : <strong><?= $totalCourses ?></strong></p>
        <p>Quiz réalisés : <strong><?= $totalQuiz ?></strong></p>
        <p>Score moyen : <strong><?= $avgScore ?>%</strong></p>

        <canvas id="userStatsChart" height="100"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        new Chart(document.getElementById('userStatsChart'), {
            type: 'bar',
            data: {
                labels: ['Cours suivis', 'Quiz réalisés', 'Score moyen'],
                datasets: [{
                    label: 'Progression',
                    data: [<?= $totalCourses ?>, <?= $totalQuiz ?>, <?= $avgScore ?>],
                    backgroundColor: ['#2196f3','#4caf50','#ff9800']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });
        </script>
    </div>

    <!-- ✅ Liste des cours suivis -->
    <div class="profile-section">
        <h3>📘 Mes cours suivis</h3>
        <?php if ($totalCourses > 0): ?>
            <ul>
                <?php while ($c = mysqli_fetch_assoc($coursesQuery)): ?>
                    <li>
                        <?= htmlspecialchars($c['title']) ?> (<?= htmlspecialchars($c['level']) ?>)  
                        - Progression : <?= $c['progress'] ?>%
                        <div class="progress-bar"><span style="width:<?= $c['progress'] ?>%"></span></div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Aucun cours suivi pour le moment.</p>
        <?php endif; ?>
    </div>

    <!-- ✅ Résultats quiz détaillés -->
    <div class="profile-section">
        <h3>🎯 Mes résultats quiz (détail)</h3>
        <?php if (mysqli_num_rows($quizQuery) > 0): ?>
            <ul>
                <?php while ($q = mysqli_fetch_assoc($quizQuery)): ?>
                    <li>
                        <strong><?= htmlspecialchars($q['course_title']) ?> :</strong>  
                        <?= htmlspecialchars($q['question']) ?><br>
                        Votre réponse : <?= htmlspecialchars($q['answer']) ?>  
                        <?= $q['correct'] ? "✅ Correct" : "❌ Incorrect" ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Aucun quiz détaillé pour le moment.</p>
        <?php endif; ?>
    </div>

    <!-- ✅ Historique des quiz réalisés -->
    <div class="profile-section">
        <h3>🕒 Historique de mes quiz</h3>
        <?php if (mysqli_num_rows($quizHistory) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Cours</th>
                        <th>Score</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($h = mysqli_fetch_assoc($quizHistory)): ?>
                        <tr>
                            <td><?= htmlspecialchars($h['course_title']) ?></td>
                            <td><?= $h['score'] ?>%</td>
                            <td><?= date("d/m/Y H:i", strtotime($h['completed_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun quiz réalisé pour le moment.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

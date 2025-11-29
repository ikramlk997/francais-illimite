<?php
$pageTitle = "Gestion des Utilisateurs - Administration";
include __DIR__ . '/../header.php';
require_once __DIR__ . '/../../db_conn.php';
$connection = getConnection();

$result = mysqli_query($connection, "SELECT id, nom, prenom, email, is_admin FROM registerform");
?>

<h1 class="mb-4"><span class="highlight">👥 Liste des utilisateurs</span></h1>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Admin ?</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($user = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['prenom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= $user['is_admin'] ? "<span class='badge bg-success'>✅ Oui</span>" : "<span class='badge bg-secondary'>❌ Non</span>" ?></td>
                        <td>
                            <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">✏️ Modifier</a>
                            <a href="delete.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">🗑️ Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center text-muted">Aucun utilisateur trouvé.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<a href="../admin.php" class="btn btn-secondary mt-3">⬅️ Retour au tableau de bord</a>

<?php include __DIR__ . '/../footer.php'; ?>

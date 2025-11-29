    </main>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; <?= date("Y") ?> Français Illimité. Tous droits réservés.</p>
        <p class="small">Développé avec ❤️ par Ikram</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const ctx = document.getElementById('statsChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Cours', 'Quiz', 'Utilisateurs'],
                datasets: [{
                    label: 'Statistiques',
                    data: [<?= mysqli_num_rows($courses) ?>, 5, 20], // valeurs dynamiques à remplacer
                    backgroundColor: ['#2196f3','#4caf50','#ff9800']
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

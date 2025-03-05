<?php
// Configuration de la base de données
$host = 'localhost';
$user = 'root';
$password = 'Kookies7*';
$database = 'company_db';

// Connexion MySQLi
$conn = mysqli_connect($host, $user, $password, $database);

// Vérification de la connexion
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Récupération des données
$result = mysqli_query($conn, "SELECT * FROM employees");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>DataTable Server-Side</title>
    <link href="https://cdn.datatables.net/2.2.2/css/dataTables.css" rel="stylesheet">
    <style>
        .dataTables_wrapper {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        th { background-color: #f8f9fa !important; }
    </style>
</head>
<body>
    <table id="example" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Poste</th>
                <th>Bureau</th>
                <th>Date d'embauche</th>
                <th>Salaire</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['first_name']) ?></td>
                    <td><?= htmlspecialchars($row['last_name']) ?></td>
                    <td><?= htmlspecialchars($row['position']) ?></td>
                    <td><?= htmlspecialchars($row['office']) ?></td>
                    <td><?= date('d/m/Y', strtotime($row['start_date'])) ?></td>
                    <td><?= number_format($row['salary'], 2, ',', ' ') ?> €</td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    
    <script>
    $(document).ready(function() {
        new DataTable('#example', {
            lengthMenu: [
                [10, 25, 2, -1],
                [10, 25, 2, 'Tous']
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/2.2.2/i18n/fr-FR.json'
            },
            columnDefs: [
                { targets: 5, type: 'date-eu' }, // Format date européenne
                { targets: 6, type: 'num-fmt' }  // Format numérique
            ]
        });
    });
    </script>

    <?php
    // Fermer la connexion
    mysqli_free_result($result);
    mysqli_close($conn);
    ?>
</body>
</html>
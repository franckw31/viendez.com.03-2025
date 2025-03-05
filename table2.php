<?php
// Configuration sécurisée (à déplacer dans un fichier séparé en production)
// require_once 'config.php'; // Déplacer les infos sensibles ici
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'Kookies7*');
define('DB_NAME', 'company_db');
// Connexion MySQLi avec gestion d'erreur améliorée
try {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if (mysqli_connect_errno()) {
        throw new Exception("Échec de la connexion MySQL : " . mysqli_connect_error());
    }
    
    // Requête préparée pour plus de sécurité
    $stmt = mysqli_prepare($conn, "SELECT id, first_name, last_name, position, office, start_date, salary FROM employees");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

} catch (Exception $e) {
    die("Erreur système : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employés - Tableau de gestion</title>
    
    <!-- DataTables Style + Extensions -->
    <link href="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-2.0.7/b-3.0.2/b-html5-3.0.2/r-3.0.2/datatables.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --hover-color: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 2rem 1rem;
        }

        .dataTables_wrapper {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            padding: 1.5rem;
        }

        table.dataTable {
            border-collapse: collapse;
            margin: 0 !important;
        }

        th {
            background-color: var(--primary-color) !important;
            color: white !important;
            padding: 1rem 1.5rem !important;
            font-weight: 600 !important;
            border-bottom: none !important;
        }

        td {
            padding: 0.8rem 1.5rem !important;
            vertical-align: middle !important;
        }

        tr:hover td {
            background-color: var(--hover-color) !important;
        }

        .dt-length,
        .dt-search,
        .dt-paging {
            margin: 1rem 0;
        }

        /* Style personnalisé pour les boutons */
        .dt-buttons .dt-button {
            background: var(--primary-color) !important;
            border-radius: 5px !important;
            color: white !important;
            transition: all 0.3s ease;
        }

        .dt-buttons .dt-button:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <table id="employeeTable" class="display nowrap" style="width:100%">
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
                    <td><?= htmlspecialchars(ucwords($row['first_name'])) ?></td>
                    <td><?= htmlspecialchars(strtoupper($row['last_name'])) ?></td>
                    <td><?= htmlspecialchars($row['position']) ?></td>
                    <td><?= htmlspecialchars($row['office']) ?></td>
                    <td data-order="<?= $row['start_date'] ?>">
                        <?= date('d/m/Y', strtotime($row['start_date'])) ?>
                    </td>
                    <td data-order="<?= $row['salary'] ?>">
                        <?= number_format($row['salary'], 2, ',', ' ') ?> €
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Scripts DataTables + Extensions -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-2.0.7/b-3.0.2/b-html5-3.0.2/r-3.0.2/datatables.min.js"></script>

    <script>
    $(document).ready(function() {
        const table = new DataTable('#employeeTable', {
            responsive: true,
            dom: 'Blfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5', 
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    title: 'Liste des employés'
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/2.0.7/i18n/fr-FR.json'
            },
            columnDefs: [
                { 
                    targets: 5,
                    render: DataTable.render.date('DD/MM/YYYY'),
                    type: 'date-eu'
                },
                {
                    targets: 6,
                    className: 'dt-body-right',
                    render: (data) => new Intl.NumberFormat('fr-FR', {
                        style: 'currency',
                        currency: 'EUR'
                    }).format(data)
                }
            ],
            initComplete: function() {
                $('.dt-button').removeClass('dt-button');
            }
        });
    });
    </script>

    <?php
    // Nettoyage des ressources
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    ?>
</body>
</html>
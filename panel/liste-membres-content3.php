<?php
// Inclure le fichier de configuration
include('/panel/include/config.php');

// Configuration de la base de données
define('DB_CONFIG', [
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'Kookies7*',
    'name' => 'company_db',
    'charset' => 'utf8mb4'
]);

// Fonction pour obtenir la connexion à la base de données
function getDBConnection() {
    static $conn = null;
    if ($conn === null) {
        $conn = mysqli_connect(DB_CONFIG['host'], DB_CONFIG['user'], DB_CONFIG['password'], DB_CONFIG['name']);
        if (!$conn) die('Erreur de connexion : ' . mysqli_connect_error());
        mysqli_set_charset($conn, DB_CONFIG['charset']);
    }
    return $conn;
}

// Fonction pour récupérer les employés
function fetchEmployees() {
    $conn = getDBConnection();
    $result = mysqli_query($conn, "SELECT * FROM employees");
    return mysqli_num_rows($result) > 0 ? $result : [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des employés</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="main-content">
        <div class="wrap-content container" id="container">
            <div class="container-fluid container-fullw bg-white">
                <div class="col-md-12">
                    <div class="row margin-top-30">
                        <div class="panel-white">
                            <div class="panel-body">
                                <main class="container">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-white">
                                            <h2 class="h5 mb-0"><i class="bi bi-people me-2"></i>Liste des employés</h2>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="table-responsive">
                                                <table id="employeeTable" class="table table-hover w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Prénom</th>
                                                            <th>Nom</th>
                                                            <th>Poste</th>
                                                            <th>Bureau</th>
                                                            <th>Date embauche</th>
                                                            <th>Salaire</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach(fetchEmployees() as $row): ?>
                                                        <tr class="clickable-row" data-id="<?= $row['id'] ?>">
                                                            <td><?= $row['id'] ?></td>
                                                            <td><?= ucwords(strtolower($row['first_name'])) ?></td>
                                                            <td><?= strtoupper($row['last_name']) ?></td>
                                                            <td><?= $row['position'] ?></td>
                                                            <td><?= $row['office'] ?></td>
                                                            <td data-order="<?= strtotime($row['start_date']) ?>">
                                                                <?= date('d/m/Y', strtotime($row['start_date'])) ?>
                                                            </td>
                                                            <td class="salary-cell" data-order="<?= $row['salary'] ?>">
                                                                <?= number_format($row['salary'], 2, ',', ' ') ?> €
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </main>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <script>
    $(document).ready(function() {
        const table = $('#employeeTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },
            dom: '<"row"<"col"B><"col"f>>rt<"row"<"col"i><"col"p>>',
            buttons: ['copy', 'excel', 'pdf', 'print'],
            pageLength: 3,
            order: [[0, 'asc']],
            columnDefs: [
                { targets: 5, type: 'date-eu' },
                { targets: 6, className: 'salary-cell' }
            ],
            responsive: true
        });

        $('#employeeTable').on('click', 'tr.clickable-row', function() {
            window.location.href = 'employee-details.php?id=' + $(this).data('id');
        });
    });
    </script>
</body>
</html>

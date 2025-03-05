<?php
// config.php
define('DB_CONFIG', [
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'Kookies7*',
    'name' => 'company_db',
    'charset' => 'utf8mb4'
]);

function getDBConnection() {
    static $conn = null;
    if ($conn === null) {
        $conn = mysqli_connect(DB_CONFIG['host'], DB_CONFIG['user'], DB_CONFIG['password'], DB_CONFIG['name']);
        if (!$conn) die('Erreur de connexion : ' . mysqli_connect_error());
        mysqli_set_charset($conn, DB_CONFIG['charset']);
    }
    return $conn;
}

function fetchEmployees() {
    $conn = getDBConnection();
    $result = mysqli_query($conn, "SELECT * FROM employees");
    return mysqli_num_rows($result) > 0 ? $result : [];
}

function updateEmployee($id, $first_name, $last_name, $position, $office, $start_date, $salary) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("UPDATE employees SET first_name = ?, last_name = ?, position = ?, office = ?, start_date = ?, salary = ? WHERE id = ?");
    $stmt->bind_param("sssssdi", $first_name, $last_name, $position, $office, $start_date, $salary, $id);
    return $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_employee'])) {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $position = $_POST['position'];
    $office = $_POST['office'];
    $start_date = $_POST['start_date'];
    $salary = $_POST['salary'];

    if (updateEmployee($id, $first_name, $last_name, $position, $office, $start_date, $salary)) {
        echo "<div class='alert alert-success'>Employé mis à jour avec succès!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la mise à jour de l'employé.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Employés</title>

    <!-- CSS combiné -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        .clickable-row { cursor: pointer; transition: background 0.2s; position: relative; }
        .clickable-row:hover { background: rgba(52, 152, 219, 0.1)!important; }
        .salary-cell { font-weight: 500; text-align: right; }
        .dataTables_wrapper .dataTables_filter input { border-radius: 5px; }
        .save-icon {
            cursor: pointer;
            color: #007bff;
        }
        .save-icon:hover {
            color: #0056b3;
        }
    </style>
</head>
<body class="bg-light">
    <header class="bg-dark text-white p-3 mb-4">
        <h1 class="h4 mb-0">Gestion des Employés</h1>
        <p class="mb-0 small opacity-75">Tableau de bord des ressources humaines</p>
    </header>

    <main class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h2 class="h5 mb-0"><i class="bi bi-people me-2"></i>Liste des employés</h2>
            </div>

            <div class="card-body p-2">
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach(fetchEmployees() as $row): ?>
                        <tr class="clickable-row" data-id="<?= $row['id'] ?>">
                            <td data-order="<?= $row['id'] ?>"><?= $row['id'] ?></td>
                            <td data-order="<?= strtolower($row['first_name']) ?>">
                                <input type="text" class="form-control form-control-sm" value="<?= ucwords(strtolower($row['first_name'])) ?>" name="first_name[<?= $row['id'] ?>]">
                            </td>
                            <td data-order="<?= strtoupper($row['last_name']) ?>">
                                <input type="text" class="form-control form-control-sm" value="<?= strtoupper($row['last_name']) ?>" name="last_name[<?= $row['id'] ?>]">
                            </td>
                            <td data-order="<?= $row['position'] ?>">
                                <input type="text" class="form-control form-control-sm" value="<?= $row['position'] ?>" name="position[<?= $row['id'] ?>]">
                            </td>
                            <td data-order="<?= $row['office'] ?>">
                                <input type="text" class="form-control form-control-sm" value="<?= $row['office'] ?>" name="office[<?= $row['id'] ?>]">
                            </td>
                            <td data-order="<?= strtotime($row['start_date']) ?>">
                                <input type="date" class="form-control form-control-sm" value="<?= $row['start_date'] ?>" name="start_date[<?= $row['id'] ?>]">
                            </td>
                            <td data-order="<?= $row['salary'] ?>">
                                <input type="number" step="0.01" class="form-control form-control-sm salary-cell" value="<?= $row['salary'] ?>" name="salary[<?= $row['id'] ?>]">
                            </td>
                            <td>
                                <i class="bi bi-save save-icon" data-id="<?= $row['id'] ?>"></i>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Scripts combinés -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
    $(document).ready(function() {
        const table = $('#employeeTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },
            dom: '<"row"<"col"B><"col"f>>rt<"row"<"col"i><"col"p>>',
            buttons: ['copy', 'excel', 'pdf', 'print'],
            pageLength: 5,
            order: [[0, 'asc']],
            columnDefs: [
                { targets: 5, type: 'date-eu' },
                { targets: 6, className: 'salary-cell' }
            ]
        });

        $('#employeeTable').on('click', '.save-icon', function() {
            const row = $(this).closest('tr');
            const id = row.data('id');
            const first_name = row.find('input[name="first_name[' + id + ']"]').val();
            const last_name = row.find('input[name="last_name[' + id + ']"]').val();
            const position = row.find('input[name="position[' + id + ']"]').val();
            const office = row.find('input[name="office[' + id + ']"]').val();
            const start_date = row.find('input[name="start_date[' + id + ']"]').val();
            const salary = row.find('input[name="salary[' + id + ']"]').val();

            $.ajax({
                url: window.location.href,
                method: 'POST',
                data: {
                    update_employee: true,
                    id: id,
                    first_name: first_name,
                    last_name: last_name,
                    position: position,
                    office: office,
                    start_date: start_date,
                    salary: salary
                },
                success: function(response) {
                    alert('Employé mis à jour avec succès!');
                },
                error: function() {
                    alert('Erreur lors de la mise à jour de l\'employé.');
                }
            });
        });
    });
    </script>
</body>
</html>

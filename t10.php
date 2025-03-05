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

function fetchEmployeeById($id) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
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

if (isset($_GET['fetch_employee'])) {
    $id = $_GET['fetch_employee'];
    $employee = fetchEmployeeById($id);
    header('Content-Type: application/json');
    echo json_encode($employee);
    exit();
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
        .edit-icon {
            display: none;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #007bff;
        }
        .clickable-row:hover .edit-icon {
            display: block;
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
                            <td>
                                <i class="bi bi-pencil-square edit-icon" data-id="<?= $row['id'] ?>"></i>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal de modification -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Modifier l'employé</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" method="POST">
                            <input type="hidden" name="id" id="editId">
                            <div class="mb-3">
                                <label for="editFirstName" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="editFirstName" name="first_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="editLastName" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="editLastName" name="last_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="editPosition" class="form-label">Poste</label>
                                <input type="text" class="form-control" id="editPosition" name="position" required>
                            </div>
                            <div class="mb-3">
                                <label for="editOffice" class="form-label">Bureau</label>
                                <input type="text" class="form-control" id="editOffice" name="office" required>
                            </div>
                            <div class="mb-3">
                                <label for="editStartDate" class="form-label">Date embauche</label>
                                <input type="date" class="form-control" id="editStartDate" name="start_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="editSalary" class="form-label">Salaire</label>
                                <input type="number" step="0.01" class="form-control" id="editSalary" name="salary" required>
                            </div>
                            <button type="submit" name="update_employee" class="btn btn-primary">Sauvegarder</button>
                        </form>
                    </div>
                </div>
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
            pageLength: 3,
            order: [[0, 'asc']],
            columnDefs: [
                { targets: 5, type: 'date-eu' },
                { targets: 6, className: 'salary-cell' }
            ]
        });

        $('#employeeTable').on('click', 'tr.clickable-row', function() {
            window.location.href = 'employee-details.php?id=' + $(this).data('id');
        });

        $('#employeeTable').on('click', '.edit-icon', function(event) {
            event.stopPropagation();
            const id = $(this).data('id');
            $.ajax({
                url: window.location.href,
                method: 'GET',
                data: { fetch_employee: id },
                success: function(data) {
                    console.log(data); // Ajouté pour vérifier les données reçues
                    $('#editId').val(data.id);
                    $('#editFirstName').val(data.first_name);
                    $('#editLastName').val(data.last_name);
                    $('#editPosition').val(data.position);
                    $('#editOffice').val(data.office);
                    $('#editStartDate').val(data.start_date);
                    $('#editSalary').val(data.salary);
                    $('#editModal').modal('show');
                },
                error: function() {
                    alert('Erreur lors de la récupération des données de l\'employé.');
                }
            });
        });
    });
    </script>
</body>
</html>

<?php
session_start();
error_reporting(0);
include('include/config.php');

if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
} else {
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Liste des membres</title>
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
</head>
<body>
    <div id="app">
        <?php include('include/sidebar.php'); ?>
        <div class="app-content">
            <?php include('include/header.php'); ?>
            <div class="main-content">
                <div class="wrap-content container" id="container">
                    <section id="page-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h1 class="mainTitle">Admin | Liste des membres</h1>
                            </div>
                            <ol class="breadcrumb"></ol>
                        </div>
                    </section>
                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row margin-top-30">
                                    <div class="col-lg-8 col-md-12">
                                        <div class="panel panel-white">
                                            <div class="panel-body">
                                                <div id="layoutSidenav_content">
                                                    <main>
                                                        <div class="container-fluid px-4">
                                                            <h1 class="mt-4">Liste des membres</h1>
                                                            <ol class="breadcrumb mb-4">
                                                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                                                <li class="breadcrumb-item active">Liste des membres</li>
                                                            </ol>
                                                            <div class="card mb-4">
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
                                                        </div>
                                                    </main>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="panel panel-white"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('include/footer.php'); ?>
        </div>
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
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
<?php } ?>

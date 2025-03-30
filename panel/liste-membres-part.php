<?php
session_start();
error_reporting(0);
include('include/config.php');

if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
} else {
    // config.php
    define('DB_CONFIG', [
        'host'     => 'localhost',
        'user'     => 'root', 
        'password' => 'Kookies7*',
        'name'     => 'dbs9616600',
        'charset'  => 'utf8mb4'
    ]);
    
    $qui = $_SESSION['id'];

    function getDBConnection() {
        static $conn = null;
        if ($conn === null) {
            $conn = mysqli_connect(DB_CONFIG['host'], DB_CONFIG['user'], DB_CONFIG['password'], DB_CONFIG['name']);
            if (!$conn) die('Erreur de connexion : ' . mysqli_connect_error());
            mysqli_set_charset($conn, DB_CONFIG['charset']);
        }
        return $conn;
    }

    function fetchParticipants($activite_id = null) {
        $conn = getDBConnection();
        
        if (!$activite_id) {
            $sql = "SELECT `id-activite`, ville FROM activite ORDER BY date_depart DESC LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $activite_id = $row['id-activite'];
        }
        
        $query = "SELECT 
                    m.`id-membre`,
                    m.pseudo,
                    a.buyin,
                    a.bounty,
                    p.rake,  -- Utiliser p.rake au lieu de a.rake
                    p.recave,
                    p.classement,
                    p.tf,
                    p.points,
                    p.gain as cagnotte,
                    p.`id-participation`,
                    p.`id-activite`
                FROM participation p
                JOIN membres m ON p.`id-membre` = m.`id-membre`
                JOIN activite a ON p.`id-activite` = a.`id-activite`
                WHERE p.`id-activite` = ?
                ORDER BY p.classement ASC, m.pseudo ASC";
                
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $activite_id);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    $selected_activite = isset($_POST['activite_id']) ? $_POST['activite_id'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
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
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/modernizr/modernizr.js"></script>
    <script src="vendor/jquery-cookie/jquery.cookie.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="vendor/switchery/switchery.min.js"></script>
    <script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
    <script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="vendor/autosize/autosize.min.js"></script>
    <script src="vendor/selectFx/classie.js"></script>
    <script src="vendor/selectFx/selectFx.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/form-elements.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            FormElements.init();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<head>
    <title>Admin | Liste des participants</title>
    <style>
        .editable {
            cursor: pointer;
            padding: 5px !important;
        }
        .editable:hover {
            background-color: #f0f0f0;
        }
        .editable input {
            width: 100%;
            padding: 2px;
            box-sizing: border-box;
        }
        .save-btn {
            display: none;
            margin-top: 10px;
        }
        .edit-mode .save-btn {
            display: inline-block;
        }
        .edit-cell {
            cursor: pointer;
            padding: 5px !important;
        }
        .edit-cell:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }
        
        /* Styles du tableau */
        .table {
            width: 100%;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            border-radius: 4px;
        }
        
        .table thead th {
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            border-bottom: 2px solid #dee2e6;
            padding: 12px 8px;
        }
        
        .table tbody td {
            padding: 12px 8px;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
            font-size: 0.95rem;
        }
        
        .table tbody tr:hover {
            background-color: rgba(0,123,255,0.05);
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .money {
            font-family: monospace;
            font-weight: 600;
        }
        
        .badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge-success {
            background: #28a745;
            color: white;
        }

        .editing-controls {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .validate-btn {
            padding: 2px 5px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .cancel-btn {
            padding: 2px 5px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div id="app">
        <?php include('include/sidebar.php'); ?>
        <div class="app-content">
            <?php include('include/header.php'); ?>
            <div class="main-content">
                <div class="wrap-content container" id="container">
                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="mt-4">Liste des Participants</h1>
                                
                                <form method="post" class="mb-4">
                                    <div class="d-flex align-items-center justify-content-start" style="gap: 10px;">
                                        <select name="activite_id" class="form-select" style="width: 300px;">
                                            <?php 
                                            $sql = "SELECT `id-activite`, date_depart, ville 
                                                   FROM activite 
                                                   ORDER BY date_depart DESC";
                                            $result = mysqli_query(getDBConnection(), $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $selected = ($selected_activite == $row['id-activite']) ? 'selected' : '';
                                                echo "<option value='{$row['id-activite']}' $selected>" . 
                                                     date('d/m/Y', strtotime($row['date_depart'])) . " - {$row['ville']}</option>";
                                            }
                                            ?>
                                        </select>
                                        <button type="submit" class="btn btn-primary">Filtrer</button>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table id="participantsTable" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="5%">#</th>
                                                <th width="20%">Pseudo</th>
                                                <th class="text-right" width="10%">Buyin</th>
                                                <th class="text-right" width="10%">Bounty</th>
                                                <th class="text-right money edit-cell" data-field="rake" width="10%">Rake</th>
                                                <th class="text-right" width="10%">Coût-In</th>
                                                <th class="text-center" width="7%">Recave</th>
                                                <th class="text-center" width="8%">Classement</th>
                                                <th class="text-center" width="5%">TF</th>
                                                <th class="text-center" width="7%">Points</th>
                                                <th class="text-right" width="8%">Cagnotte</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $result = fetchParticipants($selected_activite);
                                            $i = 1;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $cout_in = $row['buyin'] + $row['bounty'] + $row['rake'];
                                                echo "<tr data-id='{$row['id-participation']}'>";
                                                echo "<td class='text-center'>$i</td>";
                                                echo "<td>{$row['pseudo']}</td>";
                                                echo "<td class='text-right money'>{$row['buyin']} €</td>";
                                                echo "<td class='text-right money'>{$row['bounty']} €</td>";
                                                echo "<td class='text-right money edit-cell' data-field='rake' data-original='{$row['rake']}'>{$row['rake']} €</td>";
                                                echo "<td class='text-right money'>$cout_in €</td>";
                                                echo "<td class='text-center edit-cell' data-field='recave' data-original='{$row['recave']}'>{$row['recave']}</td>";
                                                echo "<td class='text-center edit-cell' data-field='classement' data-original='{$row['classement']}'><span class='badge badge-success'>{$row['classement']}</span></td>";
                                                echo "<td class='text-center edit-cell' data-field='tf' data-original='{$row['tf']}'>{$row['tf']}</td>";
                                                echo "<td class='text-center edit-cell' data-field='points' data-original='{$row['points']}'>{$row['points']}</td>";
                                                echo "<td class='text-right money edit-cell' data-field='cagnotte' data-original='{$row['cagnotte']}'>{$row['cagnotte']} €</td>";
                                                echo "</tr>";
                                                $i++;
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5" class="text-right">Total:</th>
                                                <th class="text-right money"></th>
                                                <th class="text-center"></th>
                                                <th class="text-center"></th>
                                                <th class="text-center"></th>
                                                <th class="text-center"></th>
                                                <th class="text-right money"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('include/footer.php'); ?>
    </div>

    <script>
        $(document).ready(function() {
            const table = $('#participantsTable').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },
                dom: '<"row"<"col"B><"col"f>>rt<"row"<"col"i><"col"p>>',
                buttons: ['copy', 'excel', 'pdf', 'print'],
                pageLength: 25,
                order: [[7, 'asc']], // Tri par classement
                columnDefs: [
                    { className: 'text-right money', targets: [2,3,4,5,10] },
                    { className: 'text-center', targets: [0,6,7,8,9] },
                    { className: 'edit-cell', targets: [4,6,7,8,9,10] } // Ajouter le RAKE (colonne 4)
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    
                    // Calcul des totaux pour chaque colonne
                    [5,6,7,8,9,10].forEach(function(colIndex) {
                        var total = api.column(colIndex, {search:'applied'})
                            .data()
                            .reduce(function(a, b) {
                                return parseInt(a) + parseInt(b.replace(/[€\s]/g, '') || 0);
                            }, 0);
                        $(api.column(colIndex).footer()).html(total + (colIndex === 5 || colIndex === 10 ? ' €' : ''));
                    });
                }
            });

            // Edition des cellules
            $('.edit-cell').on('click', function() {
                if (!$(this).hasClass('editing')) {
                    const value = $(this).text().replace(/[€\s]/g, '');
                    $(this).data('original-value', value)
                           .addClass('editing')
                           .html(`
                                <div class="editing-controls">
                                    <input type="number" value="${value}" style="width:60px;text-align:center">
                                    <button class="validate-btn" title="Valider"><i class="fa fa-check"></i></button>
                                    <button class="cancel-btn" title="Annuler"><i class="fa fa-times"></i></button>
                                </div>
                           `)
                           .find('input').focus().select();
                }
            });

            // Gestion de la validation
            $(document).on('click', '.validate-btn', function() {
                const cell = $(this).closest('.edit-cell');
                const value = cell.find('input').val();
                updateCell(cell, value);
            });

            // Gestion de l'annulation
            $(document).on('click', '.cancel-btn', function() {
                const cell = $(this).closest('.edit-cell');
                const originalValue = cell.data('original-value');
                restoreCell(cell, originalValue);
            });

            // Fonction de mise à jour
            function updateCell(cell, value) {
                const field = cell.data('field');
                const id = cell.parent('tr').data('id');
                const originalValue = cell.data('original-value');

                $.ajax({
                    url: 'update_participation.php',
                    method: 'POST',
                    data: { id: id, field: field, value: value },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            const displayValue = field === 'cagnotte' || field === 'rake' 
                                ? `${value} €` 
                                : field === 'classement'
                                    ? `<span class="badge badge-success">${value}</span>`
                                    : value;
                            cell.removeClass('editing').html(displayValue);
                            table.draw(false);
                        } else {
                            alert('Erreur: ' + response.message);
                            restoreCell(cell, originalValue);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Erreur lors de la mise à jour';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            errorMsg = response.message || errorMsg;
                        } catch(e) {}
                        alert(errorMsg);
                        restoreCell(cell, originalValue);
                    }
                });
            }

            // Fonction de restauration
            function restoreCell(cell, value) {
                const field = cell.data('field');
                const displayValue = field === 'cagnotte' || field === 'rake'
                    ? `${value} €`
                    : field === 'classement'
                        ? `<span class="badge badge-success">${value}</span>`
                        : value;
                cell.removeClass('editing').html(displayValue);
            }

            // Validation avec Entrée
            $(document).on('keypress', '.edit-cell input', function(e) {
                if (e.which === 13) {
                    $(this).closest('.edit-cell').find('.validate-btn').click();
                }
            });
        });
    </script>
</body>
</html>
<?php } ?>

<?php
// Configuration de la base de données (à déplacer dans un fichier séparé en production)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'Kookies7*');
define('DB_NAME', 'company_db');

// Fonction pour gérer les erreurs de connexion
function handleDatabaseError($error) {
    die('<div class="error-container">
        <h2>Erreur de connexion</h2>
        <p>' . htmlspecialchars($error) . '</p>
        <p>Veuillez contacter l\'administrateur système.</p>
    </div>');
}

// Connexion à la base de données avec gestion d'erreur
try {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if (mysqli_connect_errno()) {
        throw new Exception("Échec de la connexion MySQL : " . mysqli_connect_error());
    }
    
    // Définir l'encodage des caractères
    mysqli_set_charset($conn, 'utf8mb4');
    
    // Requête préparée pour récupérer les données des employés
    $stmt = mysqli_prepare($conn, "SELECT id, first_name, last_name, position, office, start_date, salary FROM employees");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

} catch (Exception $e) {
    handleDatabaseError($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="Latin-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Gestion des Employés - Tableau de bord</title>
    
    <!-- DataTables CSS + Extensions -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            padding: 0;
            margin: 0;
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            color: white;
            padding: 1.5rem 2rem;
        }
        
        .header h1 {
            font-size: 1.8rem;
            margin: 0;
            font-weight: 600;
        }
        
        .header p {
            margin: 0.5rem 0 0;
            opacity: 0.9;
        }
        
        .content-wrapper {
            padding: 2rem;
        }
        
        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            border: none;
            margin-bottom: 2rem;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.08);
            padding: 1.25rem 1.5rem;
        }
        
        .card-title {
            font-size: 1.25rem;
            margin: 0;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* DataTables customization */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1.5rem;
        }
        
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1.5rem;
        }
        
        table.dataTable {
            border-collapse: collapse !important;
            margin: 0 !important;
            width: 100% !important;
        }
        
        table.dataTable thead th {
            background-color: var(--light-bg) !important;
            color: var(--primary-color) !important;
            padding: 1rem !important;
            font-weight: 600 !important;
            border-bottom: 2px solid var(--primary-color) !important;
            position: relative;
            cursor: pointer;
        }
        
        table.dataTable thead th:after,
        table.dataTable thead th:before {
            opacity: 0.4;
        }
        
        table.dataTable thead th.sorting_asc:after,
        table.dataTable thead th.sorting_desc:after {
            opacity: 1 !important;
        }
        
        table.dataTable tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        table.dataTable tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.04) !important;
        }
        
        table.dataTable tbody td {
            padding: 0.75rem 1rem !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            vertical-align: middle !important;
        }
        
        /* Custom column styling */
        .salary-cell {
            font-weight: 500;
            text-align: right;
        }
        
        .date-cell {
            white-space: nowrap;
        }
        
        /* Buttons styling */
        .dt-buttons .btn {
            margin-right: 0.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            padding: 0.375rem 0.75rem;
            font-weight: 500;
        }
        
        .btn-export {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
        
        .btn-export:hover {
            background-color: #1a2530;
            color: white;
            transform: translateY(-2px);
        }
        
        /* Search box styling */
        .dataTables_filter input {
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            padding: 0.375rem 0.75rem;
            margin-left: 0.5rem;
            transition: var(--transition);
        }
        
        .dataTables_filter input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            outline: none;
        }
        
        /* Pagination styling */
        .page-link {
            color: var(--primary-color);
            border-radius: var(--border-radius) !important;
            margin: 0 0.2rem;
        }
        
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        /* Status indicators */
        .status-badge {
            display: inline-block;
            border-radius: 30px;
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .content-wrapper {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .header {
                padding: 1rem;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
        }
        
        /* Error styling */
        .error-container {
            max-width: 600px;
            margin: 5rem auto;
            background: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            text-align: center;
        }
        
        .error-container h2 {
            color: var(--accent-color);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Gestion des Employés</h1>
        <p>Tableau de bord pour la gestion des données du personnel</p>
    </div>
    
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title">
                    <i class="bi bi-people me-2"></i>Liste des Employés
                </h2>
            </div>
            
            <div class="card-body">
                <table id="employeeTable" class="table table-hover">
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
                        <?php 
                        if (isset($result) && mysqli_num_rows($result) > 0):
                            while($row = mysqli_fetch_assoc($result)): 
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars(ucwords(strtolower($row['first_name']))) ?></td>
                                <td><?= htmlspecialchars(strtoupper($row['last_name'])) ?></td>
                                <td><?= htmlspecialchars($row['position']) ?></td>
                                <td><?= htmlspecialchars($row['office']) ?></td>
                                <td class="date-cell" data-order="<?= strtotime($row['start_date']) ?>">
                                    <?= date('d/m/Y', strtotime($row['start_date'])) ?>
                                </td>
                                <td class="salary-cell" data-order="<?= $row['salary'] ?>">
                                    <?= number_format($row['salary'], 2, ',', ' ') ?> €
                                </td>
                            </tr>
                        <?php 
                            endwhile; 
                        else: 
                        ?>
                            <tr>
                                <td colspan="7" class="text-center">Aucun employé trouvé dans la base de données.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts - MODIFIÉS POUR CORRIGER L'EXPORT PDF -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables et ses extensions -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <!-- Extensions d'export -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

    <script>
    $(document).ready(function() {
        
        // Initialiser DataTables avec options avancées
        var table = $('#employeeTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
            },
            // Configuration des boutons d'export
            dom: '<"row mb-3"<"col-md-6"B><"col-md-6"f>>rt<"row"<"col-md-6"l><"col-md-6"p>>i',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="bi bi-clipboard me-1"></i>Copier',
                    className: 'btn btn-export',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="bi bi-file-excel me-1"></i>Excel',
                    className: 'btn btn-export',
                    title: 'Liste des employés',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-pdf me-1"></i>PDF',
                    className: 'btn btn-export',
                    title: 'Liste des employés',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    },
                    customize: function(doc) {
                        // Personnalisation du document PDF
                        doc.defaultStyle.fontSize = 10;
                        doc.styles.tableHeader.fontSize = 11;
                        doc.styles.tableHeader.alignment = 'left';
                        
                        // Définir les en-têtes de colonne
                        doc.content[1].table.body[0].forEach(function(header) {
                            header.fillColor = '#2c3e50';
                            header.color = '#ffffff';
                        });
                        
                        // Ajouter un titre plus visible
                        doc.content.splice(0, 1, {
                            text: 'Liste des employés',
                            style: {
                                fontSize: 14,
                                bold: true,
                                alignment: 'center',
                                margin: [0, 0, 0, 15]
                            }
                        });
                        
                        // Ajouter des informations de pied de page
                        doc.footer = function(currentPage, pageCount) {
                            return {
                                text: 'Page ' + currentPage.toString() + ' sur ' + pageCount.toString(),
                                alignment: 'center',
                                fontSize: 8
                            };
                        };
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer me-1"></i>Imprimer',
                    className: 'btn btn-export',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }
            ],
            // Définition des colonnes pour formatage spécifique
            columnDefs: [
                { 
                    targets: 5, // Date d'embauche
                    type: 'date-eu' 
                },
                {
                    targets: 6, // Salaire
                    className: 'salary-cell',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            // Extraire la valeur numérique et formater pour l'affichage
                            const value = parseFloat(data.replace(/[^\d,]/g, '').replace(',', '.'));
                            return new Intl.NumberFormat('fr-FR', {
                                style: 'currency',
                                currency: 'EUR'
                            }).format(value);
                        }
                        return data;
                    }
                }
            ],
            // Options de tri par défaut
            order: [[0, 'asc']],
            // Pagination
            pagingType: 'full_numbers',
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Tous']],
            // Recherche globale
            search: {
                return: true,
                smart: true
            },
            // Rendre toutes les colonnes triables
            ordering: true,
            // Activer le stateSave pour sauvegarder l'état du tableau
            stateSave: true,
            // Activer le tri par plusieurs colonnes
            orderMulti: true
        });
        
        // Ajouter une classe pour styliser les boutons
        $('.dt-buttons .dt-button').addClass('btn');
        
        // Log pour débogage
        console.log('DataTable initialisé avec succès');
    });
    </script>

    <?php
    // Nettoyage des ressources
    if (isset($result)) mysqli_free_result($result);
    if (isset($stmt)) mysqli_stmt_close($stmt);
    if (isset($conn)) mysqli_close($conn);
    ?>
</body>
</html>
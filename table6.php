<?php
// Start session to store employee data
session_start();

// Initial employee data (used if no saved data exists)
$initialEmployeeData = [
    ['id' => 1, 'first_name' => 'Jean', 'last_name' => 'DUPONT', 'position' => 'Développeur', 'office' => 'Paris', 'start_date' => '2020-05-12', 'salary' => 45000],
    ['id' => 2, 'first_name' => 'Marie', 'last_name' => 'MARTIN', 'position' => 'Designer', 'office' => 'Lyon', 'start_date' => '2019-03-15', 'salary' => 42000],
    ['id' => 3, 'first_name' => 'Pierre', 'last_name' => 'BERNARD', 'position' => 'Chef de Projet', 'office' => 'Paris', 'start_date' => '2018-11-01', 'salary' => 55000],
    ['id' => 4, 'first_name' => 'Sophie', 'last_name' => 'PETIT', 'position' => 'Marketing', 'office' => 'Marseille', 'start_date' => '2021-01-20', 'salary' => 38000],
    ['id' => 5, 'first_name' => 'Thomas', 'last_name' => 'ROBERT', 'position' => 'Comptable', 'office' => 'Bordeaux', 'start_date' => '2017-07-05', 'salary' => 41000],
];

// Load employee data from session or use initial data
if (!isset($_SESSION['employeeData'])) {
    $_SESSION['employeeData'] = $initialEmployeeData;
}

// Handle save all changes
if (isset($_POST['save_all'])) {
    // In a real application, this would save to a database
    // For this example, we just keep the data in the session
    $_SESSION['employeeData'] = json_decode($_POST['employee_data'], true);
    $saveMessage = "Toutes les modifications ont été enregistrées";
}

// Handle cell edit
if (isset($_POST['save_edit'])) {
    $id = intval($_POST['employee_id']);
    $field = $_POST['field'];
    $value = $_POST['value'];
    
    // Validate and convert data based on field type
    if ($field === 'salary') {
        $value = floatval(str_replace([' ', ','], ['', '.'], $value));
    } elseif ($field === 'start_date') {
        // Convert from DD/MM/YYYY to YYYY-MM-DD if needed
        if (strpos($value, '/') !== false) {
            $dateParts = explode('/', $value);
            if (count($dateParts) === 3) {
                $value = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
            }
        }
    }
    
    // Update the employee data
    foreach ($_SESSION['employeeData'] as $key => $employee) {
        if ($employee['id'] === $id) {
            $_SESSION['employeeData'][$key][$field] = $value;
            break;
        }
    }
    
    // Return JSON response for AJAX requests
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        echo json_encode(['success' => true]);
        exit;
    }
}

// Format date from YYYY-MM-DD to DD/MM/YYYY
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('d/m/Y');
}

// Format salary with Euro symbol
function formatSalary($salary) {
    return number_format($salary, 2, ',', ' ') . ' €';
}

// Search functionality
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$filteredEmployees = $_SESSION['employeeData'];

if (!empty($searchTerm)) {
    $filteredEmployees = array_filter($filteredEmployees, function($employee) use ($searchTerm) {
        $searchString = strtolower($searchTerm);
        return (
            stripos($employee['first_name'], $searchString) !== false ||
            stripos($employee['last_name'], $searchString) !== false ||
            stripos($employee['position'], $searchString) !== false ||
            stripos($employee['office'], $searchString) !== false
        );
    });
}

// Sorting functionality
$sortField = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sortDirection = isset($_GET['direction']) ? $_GET['direction'] : 'asc';

usort($filteredEmployees, function($a, $b) use ($sortField, $sortDirection) {
    if ($a[$sortField] == $b[$sortField]) {
        return 0;
    }
    
    if ($sortDirection === 'asc') {
        return $a[$sortField] > $b[$sortField] ? 1 : -1;
    } else {
        return $a[$sortField] < $b[$sortField] ? 1 : -1;
    }
});
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Employés</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-icons@0.344.0/font/lucide.min.css">
    <style>
        .edit-button {
            visibility: hidden;
        }
        tr:hover .edit-button {
            visibility: visible;
        }
        .save-message {
            animation: fadeOut 3s forwards;
            animation-delay: 2s;
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="min-h-screen bg-gray-100">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#2c3e50] to-[#34495e] text-white py-6 px-8">
            <h1 class="text-2xl font-semibold">Gestion des Employés</h1>
            <p class="mt-1 opacity-90">Tableau de bord pour la gestion des données du personnel</p>
        </div>
        
        <!-- Content -->
        <div class="p-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-[#2c3e50] flex items-center">
                        <i class="lucide-table mr-2 h-5 w-5"></i>
                        Liste des Employés
                    </h2>
                    <form method="post" id="saveAllForm">
                        <input type="hidden" name="employee_data" id="employeeDataInput" value="">
                        <button type="submit" name="save_all" class="bg-green-600 text-white px-4 py-2 rounded-md flex items-center text-sm hover:bg-green-700 transition-all">
                            <i class="lucide-save mr-2 h-4 w-4"></i>
                            Sauvegarder les modifications
                        </button>
                    </form>
                </div>
                
                <!-- Save Message -->
                <?php if (isset($saveMessage)): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 save-message">
                    <p><?php echo $saveMessage; ?></p>
                </div>
                <?php endif; ?>
                
                <!-- Card Body -->
                <div class="p-6">
                    <!-- Search and Export Controls -->
                    <div class="flex flex-wrap justify-between mb-6">
                        <div class="flex items-center">
                            <button onclick="exportData('copy')" class="bg-[#2c3e50] text-white px-3 py-2 rounded-md mr-2 flex items-center text-sm hover:bg-[#1a2530] transition-all">
                                <i class="lucide-copy mr-1 h-4 w-4"></i>
                                Copier
                            </button>
                            <button onclick="exportData('excel')" class="bg-[#2c3e50] text-white px-3 py-2 rounded-md mr-2 flex items-center text-sm hover:bg-[#1a2530] transition-all">
                                <i class="lucide-file-spreadsheet mr-1 h-4 w-4"></i>
                                Excel
                            </button>
                            <button onclick="exportData('pdf')" class="bg-[#2c3e50] text-white px-3 py-2 rounded-md mr-2 flex items-center text-sm hover:bg-[#1a2530] transition-all">
                                <i class="lucide-file mr-1 h-4 w-4"></i>
                                PDF
                            </button>
                            <button onclick="exportData('print')" class="bg-[#2c3e50] text-white px-3 py-2 rounded-md flex items-center text-sm hover:bg-[#1a2530] transition-all">
                                <i class="lucide-printer mr-1 h-4 w-4"></i>
                                Imprimer
                            </button>
                        </div>
                        
                        <div class="flex items-center mt-4 sm:mt-0">
                            <form method="get" class="flex items-center">
                                <label for="search" class="mr-2 text-gray-700">Rechercher:</label>
                                <div class="relative">
                                    <i class="lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400"></i>
                                    <input
                                        id="search"
                                        name="search"
                                        type="text"
                                        class="border border-gray-300 rounded-md pl-10 pr-4 py-2 focus:border-[#3498db] focus:ring focus:ring-[#3498db] focus:ring-opacity-25 transition-all"
                                        placeholder="Rechercher..."
                                        value="<?php echo htmlspecialchars($searchTerm); ?>"
                                    >
                                    <button type="submit" class="ml-2 bg-[#3498db] text-white px-3 py-2 rounded-md">Rechercher</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse" id="employeeTable">
                            <thead>
                                <tr>
                                    <th class="bg-gray-50 text-[#2c3e50] px-4 py-3 text-left font-semibold border-b-2 border-[#2c3e50] cursor-pointer hover:bg-gray-100">
                                        <a href="?sort=id&direction=<?php echo $sortField === 'id' && $sortDirection === 'asc' ? 'desc' : 'asc'; ?>&search=<?php echo urlencode($searchTerm); ?>">
                                            ID
                                        </a>
                                    </th>
                                    <th class="bg-gray-50 text-[#2c3e50] px-4 py-3 text-left font-semibold border-b-2 border-[#2c3e50] cursor-pointer hover:bg-gray-100">
                                        <a href="?sort=first_name&direction=<?php echo $sortField === 'first_name' && $sortDirection === 'asc' ? 'desc' : 'asc'; ?>&search=<?php echo urlencode($searchTerm); ?>">
                                            Prénom
                                        </a>
                                    </th>
                                    <th class="bg-gray-50 text-[#2c3e50] px-4 py-3 text-left font-semibold border-b-2 border-[#2c3e50] cursor-pointer hover:bg-gray-100">
                                        <a href="?sort=last_name&direction=<?php echo $sortField === 'last_name' && $sortDirection === 'asc' ? 'desc' : 'asc'; ?>&search=<?php echo urlencode($searchTerm); ?>">
                                            Nom
                                        </a>
                                    </th>
                                    <th class="bg-gray-50 text-[#2c3e50] px-4 py-3 text-left font-semibold border-b-2 border-[#2c3e50] cursor-pointer hover:bg-gray-100">
                                        <a href="?sort=position&direction=<?php echo $sortField === 'position' && $sortDirection === 'asc' ? 'desc' : 'asc'; ?>&search=<?php echo urlencode($searchTerm); ?>">
                                            Poste
                                        </a>
                                    </th>
                                    <th class="bg-gray-50 text-[#2c3e50] px-4 py-3 text-left font-semibold border-b-2 border-[#2c3e50] cursor-pointer hover:bg-gray-100">
                                        <a href="?sort=office&direction=<?php echo $sortField === 'office' && $sortDirection === 'asc' ? 'desc' : 'asc'; ?>&search=<?php echo urlencode($searchTerm); ?>">
                                            Bureau
                                        </a>
                                    </th>
                                    <th class="bg-gray-50 text-[#2c3e50] px-4 py-3 text-left font-semibold border-b-2 border-[#2c3e50] cursor-pointer hover:bg-gray-100">
                                        <a href="?sort=start_date&direction=<?php echo $sortField === 'start_date' && $sortDirection === 'asc' ? 'desc' : 'asc'; ?>&search=<?php echo urlencode($searchTerm); ?>">
                                            Date d'embauche
                                        </a>
                                    </th>
                                    <th class="bg-gray-50 text-[#2c3e50] px-4 py-3 text-left font-semibold border-b-2 border-[#2c3e50] cursor-pointer hover:bg-gray-100">
                                        <a href="?sort=salary&direction=<?php echo $sortField === 'salary' && $sortDirection === 'asc' ? 'desc' : 'asc'; ?>&search=<?php echo urlencode($searchTerm); ?>">
                                            Salaire
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($filteredEmployees) > 0): ?>
                                    <?php foreach ($filteredEmployees as $index => $employee): ?>
                                        <tr class="hover:bg-blue-50 transition-colors <?php echo $index % 2 === 0 ? 'bg-gray-50' : 'bg-white'; ?>" 
                                            data-id="<?php echo $employee['id']; ?>">
                                            <td class="px-4 py-3 border-b border-gray-100"><?php echo $employee['id']; ?></td>
                                            
                                            <td class="px-4 py-3 border-b border-gray-100">
                                                <div class="flex items-center justify-between group">
                                                    <span id="display-first_name-<?php echo $employee['id']; ?>">
                                                        <?php echo ucfirst(strtolower($employee['first_name'])); ?>
                                                    </span>
                                                    <button 
                                                        onclick="startEditing(<?php echo $employee['id']; ?>, 'first_name', '<?php echo htmlspecialchars($employee['first_name']); ?>')"
                                                        class="edit-button p-1 text-gray-500 hover:text-blue-600"
                                                        title="Modifier">
                                                        <i class="lucide-edit-2 h-4 w-4"></i>
                                                    </button>
                                                </div>
                                                <div id="edit-first_name-<?php echo $employee['id']; ?>" class="hidden">
                                                    <div class="flex items-center">
                                                        <input 
                                                            type="text" 
                                                            class="w-full px-2 py-1 border border-blue-400 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            value="<?php echo htmlspecialchars($employee['first_name']); ?>"
                                                            id="input-first_name-<?php echo $employee['id']; ?>"
                                                        >
                                                        <button 
                                                            onclick="saveEdit(<?php echo $employee['id']; ?>, 'first_name')"
                                                            class="ml-1 p-1 text-green-600 hover:text-green-800"
                                                            title="Sauvegarder">
                                                            <i class="lucide-save h-4 w-4"></i>
                                                        </button>
                                                        <button 
                                                            onclick="cancelEditing(<?php echo $employee['id']; ?>, 'first_name')"
                                                            class="ml-1 p-1 text-red-600 hover:text-red-800"
                                                            title="Annuler">
                                                            <i class="lucide-x h-4 w-4"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3 border-b border-gray-100">
                                                <div class="flex items-center justify-between group">
                                                    <span id="display-last_name-<?php echo $employee['id']; ?>">
                                                        <?php echo strtoupper($employee['last_name']); ?>
                                                    </span>
                                                    <button 
                                                        onclick="startEditing(<?php echo $employee['id']; ?>, 'last_name', '<?php echo htmlspecialchars($employee['last_name']); ?>')"
                                                        class="edit-button p-1 text-gray-500 hover:text-blue-600"
                                                        title="Modifier">
                                                        <i class="lucide-edit-2 h-4 w-4"></i>
                                                    </button>
                                                </div>
                                                <div id="edit-last_name-<?php echo $employee['id']; ?>" class="hidden">
                                                    <div class="flex items-center">
                                                        <input 
                                                            type="text" 
                                                            class="w-full px-2 py-1 border border-blue-400 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            value="<?php echo htmlspecialchars($employee['last_name']); ?>"
                                                            id="input-last_name-<?php echo $employee['id']; ?>"
                                                        >
                                                        <button 
                                                            onclick="saveEdit(<?php echo $employee['id']; ?>, 'last_name')"
                                                            class="ml-1 p-1 text-green-600 hover:text-green-800"
                                                            title="Sauvegarder">
                                                            <i class="lucide-save h-4 w-4"></i>
                                                        </button>
                                                        <button 
                                                            onclick="cancelEditing(<?php echo $employee['id']; ?>, 'last_name')"
                                                            class="ml-1 p-1 text-red-600 hover:text-red-800"
                                                            title="Annuler">
                                                            <i class="lucide-x h-4 w-4"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3 border-b border-gray-100">
                                                <div class="flex items-center justify-between group">
                                                    <span id="display-position-<?php echo $employee['id']; ?>">
                                                        <?php echo htmlspecialchars($employee['position']); ?>
                                                    </span>
                                                    <button 
                                                        onclick="startEditing(<?php echo $employee['id']; ?>, 'position', '<?php echo htmlspecialchars($employee['position']); ?>')"
                                                        class="edit-button p-1 text-gray-500 hover:text-blue-600"
                                                        title="Modifier">
                                                        <i class="lucide-edit-2 h-4 w-4"></i>
                                                    </button>
                                                </div>
                                                <div id="edit-position-<?php echo $employee['id']; ?>" class="hidden">
                                                    <div class="flex items-center">
                                                        <input 
                                                            type="text" 
                                                            class="w-full px-2 py-1 border border-blue-400 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            value="<?php echo htmlspecialchars($employee['position']); ?>"
                                                            id="input-position-<?php echo $employee['id']; ?>"
                                                        >
                                                        <button 
                                                            onclick="saveEdit(<?php echo $employee['id']; ?>, 'position')"
                                                            class="ml-1 p-1 text-green-600 hover:text-green-800"
                                                            title="Sauvegarder">
                                                            <i class="lucide-save h-4 w-4"></i>
                                                        </button>
                                                        <button 
                                                            onclick="cancelEditing(<?php echo $employee['id']; ?>, 'position')"
                                                            class="ml-1 p-1 text-red-600 hover:text-red-800"
                                                            title="Annuler">
                                                            <i class="lucide-x h-4 w-4"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3 border-b border-gray-100">
                                                <div class="flex items-center justify-between group">
                                                    <span id="display-office-<?php echo $employee['id']; ?>">
                                                        <?php echo htmlspecialchars($employee['office']); ?>
                                                    </span>
                                                    <button 
                                                        onclick="startEditing(<?php echo $employee['id']; ?>, 'office', '<?php echo htmlspecialchars($employee['office']); ?>')"
                                                        class="edit-button p-1 text-gray-500 hover:text-blue-600"
                                                        title="Modifier">
                                                        <i class="lucide-edit-2 h-4 w-4"></i>
                                                    </button>
                                                </div>
                                                <div id="edit-office-<?php echo $employee['id']; ?>" class="hidden">
                                                    <div class="flex items-center">
                                                        <input 
                                                            type="text" 
                                                            class="w-full px-2 py-1 border border-blue-400 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            value="<?php echo htmlspecialchars($employee['office']); ?>"
                                                            id="input-office-<?php echo $employee['id']; ?>"
                                                        >
                                                        <button 
                                                            onclick="saveEdit(<?php echo $employee['id']; ?>, 'office')"
                                                            class="ml-1 p-1 text-green-600 hover:text-green-800"
                                                            title="Sauvegarder">
                                                            <i class="lucide-save h-4 w-4"></i>
                                                        </button>
                                                        <button 
                                                            onclick="cancelEditing(<?php echo $employee['id']; ?>, 'office')"
                                                            class="ml-1 p-1 text-red-600 hover:text-red-800"
                                                            title="Annuler">
                                                            <i class="lucide-x h-4 w-4"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3 border-b border-gray-100 whitespace-nowrap">
                                                <div class="flex items-center justify-between group">
                                                    <span id="display-start_date-<?php echo $employee['id']; ?>">
                                                        <?php echo formatDate($employee['start_date']); ?>
                                                    </span>
                                                    <button 
                                                        onclick="startEditing(<?php echo $employee['id']; ?>, 'start_date', '<?php echo formatDate($employee['start_date']); ?>')"
                                                        class="edit-button p-1 text-gray-500 hover:text-blue-600"
                                                        title="Modifier">
                                                        <i class="lucide-edit-2 h-4 w-4"></i>
                                                    </button>
                                                </div>
                                                <div id="edit-start_date-<?php echo $employee['id']; ?>" class="hidden">
                                                    <div class="flex items-center">
                                                        <input 
                                                            type="text" 
                                                            class="w-full px-2 py-1 border border-blue-400 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            value="<?php echo formatDate($employee['start_date']); ?>"
                                                            id="input-start_date-<?php echo $employee['id']; ?>"
                                                            placeholder="JJ/MM/AAAA"
                                                        >
                                                        <button 
                                                            onclick="saveEdit(<?php echo $employee['id']; ?>, 'start_date')"
                                                            class="ml-1 p-1 text-green-600 hover:text-green-800"
                                                            title="Sauvegarder">
                                                            <i class="lucide-save h-4 w-4"></i>
                                                        </button>
                                                        <button 
                                                            onclick="cancelEditing(<?php echo $employee['id']; ?>, 'start_date')"
                                                            class="ml-1 p-1 text-red-600 hover:text-red-800"
                                                            title="Annuler">
                                                            <i class="lucide-x h-4 w-4"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <td class="px-4 py-3 border-b border-gray-100 text-right font-medium">
                                                <div class="flex items-center justify-between group">
                                                    <span id="display-salary-<?php echo $employee['id']; ?>">
                                                        <?php echo formatSalary($employee['salary']); ?>
                                                    </span>
                                                    <button 
                                                        onclick="startEditing(<?php echo $employee['id']; ?>, 'salary', '<?php echo $employee['salary']; ?>')"
                                                        class="edit-button p-1 text-gray-500 hover:text-blue-600"
                                                        title="Modifier">
                                                        <i class="lucide-edit-2 h-4 w-4"></i>
                                                    </button>
                                                </div>
                                                <div id="edit-salary-<?php echo $employee['id']; ?>" class="hidden">
                                                    <div class="flex items-center">
                                                        <input 
                                                            type="number" 
                                                            class="w-full px-2 py-1 border border-blue-400 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            value="<?php echo $employee['salary']; ?>"
                                                            id="input-salary-<?php echo $employee['id']; ?>"
                                                        >
                                                        <button 
                                                            onclick="saveEdit(<?php echo $employee['id']; ?>, 'salary')"
                                                            class="ml-1 p-1 text-green-600 hover:text-green-800"
                                                            title="Sauvegarder">
                                                            <i class="lucide-save h-4 w-4"></i>
                                                        </button>
                                                        <button 
                                                            onclick="cancelEditing(<?php echo $employee['id']; ?>, 'salary')"
                                                            class="ml-1 p-1 text-red-600 hover:text-red-800"
                                                            title="Annuler">
                                                            <i class="lucide-x h-4 w-4"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="px-4 py-3 text-center">Aucun employé trouvé dans la base de données.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-6 flex justify-between items-center">
                        <div>
                            <span class="text-sm text-gray-600">Affichage de 1 à <?php echo count($filteredEmployees); ?> sur <?php echo count($filteredEmployees); ?> entrées</span>
                        </div>
                        <div class="flex">
                            <button class="px-3 py-1 mx-1 rounded-md border border-gray-300 text-[#2c3e50] hover:bg-gray-50">Précédent</button>
                            <button class="px-3 py-1 mx-1 rounded-md bg-[#2c3e50] text-white">1</button>
                            <button class="px-3 py-1 mx-1 rounded-md border border-gray-300 text-[#2c3e50] hover:bg-gray-50">Suivant</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Store original employee data for tracking changes
        let employeeData = <?php echo json_encode($_SESSION['employeeData']); ?>;
        
        // Start editing a cell
        function startEditing(id, field, value) {
            // Hide display element and show edit element
            document.getElementById(`display-${field}-${id}`).parentElement.classList.add('hidden');
            document.getElementById(`edit-${field}-${id}`).classList.remove('hidden');
            
            // Focus the input
            const input = document.getElementById(`input-${field}-${id}`);
            input.focus();
            
            // Add key event listeners
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    saveEdit(id, field);
                } else if (e.key === 'Escape') {
                    cancelEditing(id, field);
                }
            });
        }
        
        // Cancel editing
        function cancelEditing(id, field) {
            // Hide edit element and show display element
            document.getElementById(`display-${field}-${id}`).parentElement.classList.remove('hidden');
            document.getElementById(`edit-${field}-${id}`).classList.add('hidden');
        }
        
        // Save edited value
        function saveEdit(id, field) {
            const input = document.getElementById(`input-${field}-${id}`);
            const value = input.value;
            
            // Validate input
            if (field === 'salary' && isNaN(parseFloat(value))) {
                alert('Veuillez entrer un nombre valide pour le salaire');
                return;
            } else if (field === 'start_date') {
                // Simple date validation
                const datePattern = /^(\d{2})\/(\d{2})\/(\d{4})$/;
                if (!datePattern.test(value) && !value.match(/^\d{4}-\d{2}-\d{2}$/)) {
                    alert('Veuillez entrer une date valide au format JJ/MM/AAAA');
                    return;
                }
            }
            
            // Send AJAX request to save the change
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'index.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Update the display value
                            let displayValue = value;
                            
                            // Format display value based on field type
                            if (field === 'first_name') {
                                displayValue = value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
                            } else if (field === 'last_name') {
                                displayValue = value.toUpperCase();
                            } else if (field === 'salary') {
                                // Format as currency
                                displayValue = new Intl.NumberFormat('fr-FR', {
                                    style: 'currency',
                                    currency: 'EUR'
                                }).format(parseFloat(value));
                            }
                            
                            document.getElementById(`display-${field}-${id}`).textContent = displayValue;
                            
                            // Update the employee data in our local copy
                            employeeData.forEach(function(employee, index) {
                                if (employee.id === id) {
                                    employeeData[index][field] = value;
                                }
                            });
                            
                            // Update the hidden input for saving all changes
                            document.getElementById('employeeDataInput').value = JSON.stringify(employeeData);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                }
                
                // Hide edit element and show display element
                cancelEditing(id, field);
            };
            
            xhr.send(`save_edit=1&employee_id=${id}&field=${field}&value=${encodeURIComponent(value)}`);
        }
        
        // Export data functions
        function exportData(type) {
            alert(`Export as ${type} would happen here in a real application`);
        }
        
        // Initialize the form data on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('employeeDataInput').value = JSON.stringify(employeeData);
            
            // Add row click handler for viewing details
            const rows = document.querySelectorAll('#employeeTable tbody tr');
            rows.forEach(row => {
                row.addEventListener('click', function(e) {
                    // Don't trigger if clicking on a button or input
                    if (e.target.tagName === 'BUTTON' || e.target.tagName === 'INPUT' || 
                        e.target.closest('button') || e.target.closest('input')) {
                        return;
                    }
                    
                    const id = this.getAttribute('data-id');
                    if (id) {
                        alert(`Viewing details for employee ID: ${id}`);
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php
session_start();
error_reporting(0);
include('panel/include/config.php'); 

$gid_part = intval($_GET['part']); // get value
$gid_acti = intval($_GET['acti']);
$gid_tabl = intval($_GET['tabl']);
$gid_sieg = intval($_GET['sieg']);
$source = $_GET['sour'];
$actu = strtotime(date("Y-m-d H:i:s"));
$actu2 = date("Y-m-d");

$rech = mysqli_query($con, "SELECT `id-activite`,`titre-activite`,`date_depart` FROM `activite` WHERE `id-membre` = 2 ORDER BY `id-activite` DESC");
$trouv = mysqli_fetch_assoc($rech);
$dateact = $trouv["date_depart"];
$dateact2 = strtotime($dateact);

if (isset($_POST['submitchoixact'])) {
    $acti = $_POST['acti'];
    echo "Activité selectionnée : " . $acti;
}

if (isset($_POST['submitcreaj'])) {
    $pseudo = $_POST['pseudo'];
    $fname = $_POST['fname'];
    echo "Pseudo Créé = " . $pseudo . ", Prénom = " . $fname . "-";
    $sql2 = mysqli_query($con, "INSERT INTO `membres` (`pseudo`, `fname`) VALUES ('$pseudo', '$fname')");
}

if (isset($_POST['submit'])) {
    $membre = $_POST['membre'];
    $acti = $_POST['acti'];
    $tabl = $_POST['table'];
    $sieg = $_POST['siege'];
    echo "Joueur Ins = " . $membre . ", Activité = " . $acti . ", Table = " . $tabl . ", Siege = " . $sieg;
    $sql2 = mysqli_query($con, "INSERT INTO `participation` (`id-membre`, `id-activite`, `id-table`, `id-siege`) VALUES ('$membre', '$acti', '$tabl', '$sieg')");
}

if (isset($_POST['submitsupc'])) {
    $membre = $_POST['membresup'];
    $acti = $_POST['actisup'];
    echo "Joueur Sup = " . $membre . ", Activité = " . $acti;
    $sql2 = mysqli_query($con, "DELETE FROM `participation` WHERE `id-membre` = $membre AND `id-activite` = $acti");
}
?>

<style>
:root {
    --primary-color: #ff6b2b;
    --primary-hover: #ff8651;
    --background: #f5f7fa;
    --card-bg: #ffffff;
    --text-color: #2d3748;
    --border-color: #e2e8f0;
}

body {
    background: var(--background);
    font-family: 'Segoe UI', system-ui, sans-serif;
    color: var(--text-color);
    line-height: 1.5;
}

.container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.card {
    background: var(--card-bg);
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 
                0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    margin: 0.5rem 0;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    font-size: 1rem;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(255, 107, 43, 0.1);
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-primary-orange2 {
    background: var(--primary-color);
    color: white;
    box-shadow: 0 2px 4px rgba(255, 107, 43, 0.2);
}

.btn-primary-orange2:hover {
    background: var(--primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 107, 43, 0.3);
}

.btn-block {
    width: 100%;
}

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 1.5rem;
}

th, td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

th {
    font-weight: 600;
    color: var(--text-color);
    background: rgba(0, 0, 0, 0.02);
}

select {
    appearance: none;
    background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23444' viewBox='0 0 16 16'%3E%3Cpath d='M8 12L2 6h12z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 16px;
    padding-right: 2.5rem;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.card {
    animation: fadeIn 0.3s ease-out;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card {
        padding: 1rem;
    }
    
    th, td {
        padding: 0.75rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
    }
}

/* Update the btn-container styles */
.btn-container {
    display: flex;
    justify-content: flex-end; /* Change from center to flex-end */
    align-items: center;
    padding: 0.5rem;
    min-width: 200px;
    margin-left: auto; /* Add this to push container to right */
}

.btn-primary-orange2 {
    min-width: 180px;
    text-align: center;
    margin: 0 auto;
}

@media (max-width: 768px) {
    .btn-container {
        min-width: 150px;
    }
    
    .btn-primary-orange2 {
        min-width: 140px;
    }
}

/* Add these new styles inside the existing <style> block */
.data-table {
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.data-table th {
    background: var(--primary-color);
    color: white;
    font-weight: 600;
}

.data-table tr:nth-child(even) {
    background: rgba(0, 0, 0, 0.02);
}

.data-table tr:hover {
    background: rgba(255, 107, 43, 0.05);
}
</style>

<div class="container">
    <div class="card">
        <form method="post">
            <table>
                <tr> 
                    <th>Activité</th>
                    <td>
                        <?php
                        $acti = mysqli_query($con, "SELECT `id-activite`,`titre-activite`,`date_depart` FROM `activite` WHERE (`id-membre` = 2 AND `date_depart` >= '$actu2') ORDER BY `id-activite` ASC");
                        echo "<select name='acti' class='form-control'><option value='-Anonyme-'>Sélectionner une activité</option>";
                        while ($choix = mysqli_fetch_assoc($acti)) {		  
                            $liste = $choix['titre-activite']; 
                            echo "<option value='{$choix["id-activite"]}'>{$choix["titre-activite"]}</option>";   
                        }
                        echo "</select>";
                        ?>
                    </td>
                    <td class="btn-container">
                        <button type="submit" class="btn btn-primary-orange2" name="submitchoixact">
                            Choix Activité
                        </button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div class="card">
        <form method="post">
            <table>
                <tr> 
                    <th>Pseudo</th>
                    <td>
                        <input class="form-control" id="pseudo" name="pseudo" type="text" value="<?php echo $row['pseudo']; ?>">
                    </td>
                    <th>Prénom</th>
                    <td>
                        <input class="form-control" id="fname" name="fname" type="text" value="<?php echo $row['fname']; ?>">
                    </td>
                    <td class="btn-container">
                        <button type="submit" class="btn btn-primary-orange2" name="submitcreaj">
                            Création Rapide du joueur
                        </button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div class="card">
        <form method="post">
            <table>
                <tr> 
                    <th>Joueur</th>
                    <td>
                        <?php
                        $membres = mysqli_query($con, "SELECT `id-membre`,`pseudo` FROM `membres` ORDER BY `pseudo` ASC");
                        echo "<select name='membre' class='form-control'><option value='-Anonyme-'>Sélectionner un joueur</option>";
                        while ($choix = mysqli_fetch_assoc($membres)) {		  
                            $listepseudo = $choix['pseudo']; 
                            echo "<option value='{$choix["id-membre"]}'>{$choix["pseudo"]}</option>";   
                        }
                        echo "</select>";
                        ?>
                    </td>
                    <th>Activité</th>
                    <td>
                        <?php
                        $acti = mysqli_query($con, "SELECT `id-activite`,`titre-activite`,`date_depart` FROM `activite` WHERE (`id-membre` = 2 AND `date_depart` >= '$actu2') ORDER BY `id-activite` ASC");
                        echo "<select name='acti' class='form-control'><option value='-Anonyme-'>Sélectionner une activité</option>";
                        while ($choix = mysqli_fetch_assoc($acti)) {		  
                            $liste = $choix['titre-activite']; 
                            echo "<option value='{$choix["id-activite"]}'>{$choix["titre-activite"]}</option>";   
                        }
                        echo "</select>";
                        ?>
                    </td>
                    <th>Table</th>
                    <td>
                        <select name="table" id="table" class="form-control" type="text">
                            <option value='1' selected>Table 1</option>
                            <option value='2'>Table 2</option> 
                            <option value='3'>Table 3</option> 
                            <option value='4'>Table 4</option> 
                            <option value='5'>Table 5</option> 
                        </select>
                    </td>
                    <th>Siege</th>
                    <td>
                        <select name="siege" id="siege" class="form-control" type="text">
                            <option value='1'>1</option>
                            <option value='2'>2</option> 
                            <option value='3'>3</option> 
                            <option value='4'>4</option> 
                            <option value='5'>5</option>
                            <option value='6'>6</option> 
                            <option value='7'>7</option> 
                            <option value='8'>8</option> 
                            <option value='9'>9</option> 
                            <option value='10'>10</option> 
                        </select>
                    </td>
                    <td colspan="2" class="btn-container">
                        <button type="submit" class="btn btn-primary-orange2 btn-block" name="submit">Inscription Rapide Cash</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div class="card">
        <form method="post">
            <table>
                <tr> 
                    <th>Joueur</th>
                    <td>
                        <?php
                        $membres = mysqli_query($con, "SELECT `id-membre`,`pseudo` FROM `membres` ORDER BY `pseudo` ASC");
                        echo "<select name='membresup' class='form-control'><option value='-Anonyme-'>Sélectionner un joueur</option>";
                        while ($choix = mysqli_fetch_assoc($membres)) {		  
                            $listepseudo = $choix['membres.pseudo']; 
                            echo "<option value='{$choix["id-membre"]}'>{$choix["pseudo"]}</option>";   
                        }
                        echo "</select>";
                        ?>
                    </td>
                    <th>Activité</th>
                    <td>
                        <?php
                        $acti = mysqli_query($con, "SELECT `id-activite`,`titre-activite`,`date_depart` FROM `activite` WHERE (`id-membre` = 2 AND `date_depart` >= '$actu2') ORDER BY `id-activite` ASC");
                        echo "<select name='actisup' class='form-control'><option value='-Anonyme-'>Sélectionner une activité</option>";
                        while ($choix = mysqli_fetch_assoc($acti)) {		  
                            $liste = $choix['titre-activite']; 
                            echo "<option value='{$choix["id-activite"]}'>{$choix["titre-activite"]}</option>";   
                        }
                        echo "</select>";
                        ?>
                    </td>
                    <td colspan="2" class="btn-container">
                        <button type="submit" class="btn btn-primary-orange2 btn-block" name="submitsupc">Suppression Rapide Cash</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <!-- Add this new card for the data view -->
    <div class="card">
        <h2 style="margin-bottom: 1rem; color: var(--text-color);">Liste des Participations</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Joueur</th>
                    <th>Activité</th>
                    <th>Table</th>
                    <th>Siège</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT m.pseudo, a.titre-activite, p.id-table, p.id-siege, a.date_depart 
                         FROM participation p 
                         JOIN membres m ON p.id-membre = m.id-membre 
                         JOIN activite a ON p.id-activite = a.id-activite 
                         ORDER BY a.date_depart DESC";
                $result = mysqli_query($con, $query);
                
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['pseudo']}</td>";
                    echo "<td>{$row['titre-activite']}</td>";
                    echo "<td>Table {$row['id-table']}</td>";
                    echo "<td>Siège {$row['id-siege']}</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['date_depart'])) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
?>
<!-- <script type="text/javascript">window.location.replace("<?php echo $source.$id_activite; ?>")</script>   -->
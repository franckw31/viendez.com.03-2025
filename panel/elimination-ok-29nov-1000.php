<?php
session_start();
error_reporting(0);
include('include/config.php'); 
$id_participation = intval($_GET['id']); // get value
$id_activite = intval($_GET['ac']);
$source= $_GET['source'];
$req = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-participation` = '$id_participation' ");            
while ($res = mysqli_fetch_array($req)) 
    { 
    $siegelibre=$res['id-siege'];$tablelibre=$res['id-table'];
    $modif = mysqli_query($con, "UPDATE `participation` SET `option` = 'Elimine', `id-siege` = '0',`id-table` = '0' WHERE `id-participation` = '$id_participation'");
    };
; 
?>
<!-- <script type="text/javascript">window.location.replace("<?php echo $source.$id_activite; ?>");</script> ;  -->
<?php
$sql0 = mysqli_query($con, "SELECT * FROM `participation` WHERE ( (`id-activite` = '$id_activite' AND `option` NOT LIKE  'Annule') AND (`id-activite` = '$id_activite' AND `option` NOT LIKE  'Elimine') ) " ) ;
$nb_joueurs = mysqli_num_rows($sql0);
$sqleli = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = '$id_activite' AND `option` LIKE  'Elimine') " ) ;
$nb_joueurseli = mysqli_num_rows($sqleli);
$sqldep = mysqli_query($con, "SELECT * FROM `participation` WHERE ( (`id-activite` = '$id_activite' AND `option` NOT LIKE  'Annule') AND (`id-activite` = '$id_activite' AND `option` NOT LIKE  'Attente') ) " ) ;
$nb_joueursdep = mysqli_num_rows($sqldep);
$classement = $nb_joueursdep - $nb_joueurseli+1;
$modif = mysqli_query($con, "UPDATE `participation` SET `classement` = '$classement' WHERE `id-participation` = '$id_participation'");
$sql = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` = $id_activite ");
while ($res = mysqli_fetch_array($sql)) 
    { 
    $nb_tables = $res["nb-tables"];
    };    
(int)$table = 1;
while ((int)$table <= (int)$nb_tables)
    {
    $sql2 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id_activite AND `id-table` = $table) ");
    $cpttable[$table] = mysqli_num_rows($sql2);
    (int)$table = (int)$table + 1;
    };
echo $cpttable[1].$cpttable[2].$cpttable[3].$cpttable[4];
$moy=$nb_joueurs/$nb_tables;$moy = (round($moy,0));
echo "-".$moy."-";
echo "{".$siegelibre."-".$tablelibre."}";
$table = 1;
while ((int)$table <= (int)$nb_tables)
    {
    // $sql3 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id_activite AND `id-table` = $table) ");
    if ($cpttable[$table] > $moy)
        {
        $tabledepart = $table;
        };
    (int)$table = (int)$table + 1;
    };
    // echo "sortie while".$id_activite.$tabledepart;   
if ($tabledepart > 0) // donc deplacement
    {
    $sql3 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id_activite AND `id-table` = $tabledepart) ORDER BY `id-siege`");
    // echo "sql3 av while";
    while  ($res3 = mysqli_fetch_array($sql3))
        { 
        $id_membre = $res3['id-membre'];echo "+".$id_membre."+";
        };
    // echo $id_membre;
    $modif = mysqli_query($con, "UPDATE `participation` SET `id-table` = '$tablelibre' , `id-siege` = '$siegelibre'  WHERE (`id-activite` = $id_activite AND `id-membre` = $id_membre ) ");
    
    // on casse ?
    // si 4t et nbj=19
    if ($nb_tables>3 AND $nb_joueurs=19) // on casse 4e table
    {
        // on cherche la table a sup (4j)
        $cpt=1;
        while ((int)$cpt <= (int)$nb_tables)
        {   
            $req1 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id_activite AND `id-table` = $cpt AND `id-siege` > 0) ");
            $nb_joueursatable = mysqli_num_rows($req1);
            if ($nb_joueursatable = 4) 
            {
                $tableacasser = $cpt;
                // on cherche premier des 4 joueurs à deplacer
                $nb_joueurs_a_dep = $nb_joueursatable;
                while  ($res1 = mysqli_fetch_array($req1) AND $nb_joueurs_a_dep > 0)
                {
                    $id_part = $res1['id-participation']; // premier des 4 a dep
                    $id_membre = $res1['id-membre']; // premier des 4 a dep
                    $modif = mysqli_query($con, "UPDATE `participation` SET `id-siege` = '0',`id-table` = '0' WHERE `id-participation` = '$id_part'"); // on libere le siege
                    // on cherche 1ere table receveuse
                    $table_recev_scrut=1;
                    while ((int)$table_recev_scrut <= (int)$nb_tables)
                    {
                        if ($table_recev_scrut <> $tableacasser)
                        {
                            // table receveuse = cpt2
                            // on cherche 1er siege libre
                            $req2 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id_activite AND `id-table` = $table_recev_scrut AND `id-siege` = 0) ");
                            $res2 = mysqli_fetch_array($req2);
                            $id_siegelibre = $res2['id-siege'];
                            $id_tablelibre = $res2['id-table'];
                            // on place 1er des 4 joueurs
                            $nb_joueurs_a_dep = $nb_joueurs_a_dep - 1;
                            $modif = mysqli_query($con, "UPDATE `participation` SET `id-siege` = '$id_siegelibre',`id-table` = '$id_tablelibre' WHERE `id-participation` = '$id_part'");
                            $table_recev_scrut = $table_recev_scrut + 1 ;if ($table_recev_scrut > $nb_tables) $table_recev_scrut = 0;
                        };
                        $table_recev_scrut = $table_recev_scrut + 1 ;
                    }
                }
            }
            $cpt = $cpt + 1;
        } 
        $req1 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id_activite AND `id-table` = $tabledepart) ORDER BY `id-siege`");
    };


    // on casse ?
    // si 3t et nbj=14
    if ($nb_tables=3 AND $nb_joueurs=14) // on casse 2e table
    {
        // on cherche la table a sup (4j)
        $cpt=1;
        while ((int)$cpt <= (int)$nb_tables)
        {   
            $req1 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id_activite AND `id-table` = $cpt AND `id-siege` > 0) ");
            $nb_joueursatable = mysqli_num_rows($req1);
            if ($nb_joueursatable = 4) 
            {
                $tableacasser = $cpt;
                // on cherche premier des 4 joueurs à deplacer
                $nb_joueurs_a_dep = $nb_joueursatable;
                while  ($res1 = mysqli_fetch_array($req1) AND $nb_joueurs_a_dep > 0)
                {
                    $id_part = $res1['id-participation']; // premier des 4 a dep
                    $id_membre = $res1['id-membre']; // premier des 4 a dep
                    $modif = mysqli_query($con, "UPDATE `participation` SET `id-siege` = '0',`id-table` = '0' WHERE `id-participation` = '$id_part'"); // on libere le siege
                    // on cherche 1ere table receveuse
                    $table_recev_scrut=1;
                    while ((int)$table_recev_scrut <= (int)$nb_tables)
                    {
                        if ($table_recev_scrut <> $tableacasser)
                        {
                            // table receveuse = cpt2
                            // on cherche 1er siege libre
                            $req2 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id_activite AND `id-table` = $table_recev_scrut AND `id-siege` = 0) ");
                            $res2 = mysqli_fetch_array($req2);
                            $id_siegelibre = $res2['id-siege'];
                            $id_tablelibre = $res2['id-table'];
                            // on place 1er des 4 joueurs
                            $nb_joueurs_a_dep = $nb_joueurs_a_dep - 1;
                            $modif = mysqli_query($con, "UPDATE `participation` SET `id-siege` = '$id_siegelibre',`id-table` = '$id_tablelibre' WHERE `id-participation` = '$id_part'");
                            $table_recev_scrut = $table_recev_scrut + 1 ;if ($table_recev_scrut > $nb_tables) $table_recev_scrut = 0;
                        }
                        else 
                        {
                            $table_recev_scrut = $table_recev_scrut + 1 ;if ($table_recev_scrut > $nb_tables) $table_recev_scrut = 0;
                        }
                    }
                }
            }
            $cpt = $cpt + 1;
        } 
        // $req1 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id_activite AND `id-table` = $tabledepart) ORDER BY `id-siege`");
    }






   };  
?>
<script type="text/javascript">window.location.replace("<?php echo $source.$id_activite; ?>");</script> ;   
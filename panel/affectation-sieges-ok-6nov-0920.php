
        <?php
            include_once('include/config.php');
            $ret = mysqli_query($con, "SELECT * FROM `activite` WHERE 1 ");
            while ($row = mysqli_fetch_array($ret)) 
                { 
                $pointeur = $row["id-activite"];
                $pointeur_position = 0;
                $ret2 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = '$pointeur' AND `option` LIKE 'Inscrit') OR (`id-activite` = '$pointeur' AND `option` LIKE 'Option')
                 OR (`id-activite` = '$pointeur' AND `option` LIKE 'Reservation') OR (`id-activite` = '$pointeur' AND `option` LIKE 'Inscrit') OR (`id-activite` = '$pointeur' AND `option` LIKE 'Inscrit') ORDER BY RAND()" ) ;
                while ($row2 = mysqli_fetch_array($ret2)) 
                    { 
                    $id = $row2['id-participation'];
                    $pointeur_position = $pointeur_position + 1;
                    $modif = mysqli_query($con, "UPDATE `participation` SET `position` = '$pointeur_position' WHERE `id-participation` = '$id'");
                } ;
            };
            echo "Ok"
        ?>
        <!-- INSERT INTO `activite` (SELECT NULL, `id-structure-buyin`, `id-membre`, `titre-activite`, `date_depart`, `heure_depart`, `ville`, `rue`, `lng`, `lat`, `icon`, `ico-siz`, `photo`, `lien`, `lien-id`, `lien-texte`, `lien-texte-fin`, `places`, `reserves`, `options`, `libre`, `commentaire`, `buyin`, `rake`, `bounty`, `jetons`, `recave`, `addon`, `ante`, `bonus`, `nb-tables`, `taille-table1`, `id-table1`, `taille-table2`, `id-table2`, `taille-table3`, `id-table3`, `taille-table4`, `id-table4`, `taille-table5`, `id-table5`, `taille-table6`, `id-table6` FROM `activite` WHERE `id-activite` = 30) -->
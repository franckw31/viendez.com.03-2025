<table>
<tr>
<th>Identifiant</th>
<th>Nom </th>
<th>Commentaire</th>
</tr>
<tr>
<td>
<?php echo "1"; ?>
</td>
<td>
<?php echo "2"; ?>
</td>
<td>
<?php echo "3"; ?>
</td>                                                                                        
</tr>
</table>
var html = "";
var feature = features[0];
    html += '<table id="popup_content" class="mdl-data-table"><center><h5>Accident Details</h5></center>' +
          '<td><strong> Year of Crash </strong></td>' +
          '<td>' + feature.properties.CRASH_YR + '</td></tr>' +
          '<tr><td><strong> Road </strong></td>' + '<td>' +
          feature.properties.ODPS_LOC_ROAD_NME + ' ' +
          feature.properties.ODPS_LOC_ROAD_SUFFIX_CD + ' ' +
          feature.properties.ODPS_LOC_ROUTE_PREFIX_CD + ' ' +
          feature.properties.ODPS_LOC_ROUTE_ID + '</td></tr>' +
          '<tr><td><strong> Light Conditions </strong></td>' + '<td>' +
          feature.properties.LIGHT_COND_PRIMARY_CD + '</td></tr>' +
          '<tr><td><strong> Crash Type </strong></td>' + '<td>' +
          feature.properties.CRASH_TYPE_CD + '</td></tr>' +
          '<tr><td><strong> Weather </strong></td>' + '<td>' +
          feature.properties.WEATHER_COND_CD + '</td></tr>' +
          '<tr><td><strong> Road Conditions </strong></td>' + '<td>' +
          feature.properties.ROAD_COND_PRIMARY_CD + ' ' +
          feature.properties.ROAD_COND_SECONDARY_CD + '</td></tr>' +
          '<tr><td><strong> Factors </strong></td>' + '<td>' +
          feature.properties.U1_CONT_CIR_PRIMARY_CD + '</td></tr>' +
          '<tr><td><strong> Vehicle Type </strong></td>' + '<td>' +
          feature.properties.U1_TYPE_OF_UNIT_CD + '</td></tr>' +
          '<tr><td><strong> Road Contour </strong></td>' + '<td>' +
          feature.properties.ROAD_CONTOUR_CD + '</td></tr>' +
          '<tr><td><strong> Serious Injuries </strong></td>' + '<td>' +
          feature.properties.ODPS_TOTAL_FATALITIES_NBR + '</td></tr>' +
          '<tr><td><strong> Fatalities </strong></td>' + '<td>' +
          feature.properties.INCAPAC_INJURIES_NBR + '</td></tr>';
      
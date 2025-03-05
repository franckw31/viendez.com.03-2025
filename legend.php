<html>
<head>
<meta charset=utf-8 />
<title>Custom legend</title>
<meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
<script src='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.js'></script>
<link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
<style>
  body { margin:0; padding:0; }
  #map { position:absolute; top:0; bottom:0; width:100%; }
</style>
</head>
<body>
<style>
.legend label,
.legend span {
  display:block;
  float:left;
  height:15px;
  width:20%;
  text-align:center;
  font-size:9px;
  color:#808080;
  }
</style>

<!-- Set the display of this container to none so we can
     add it programmatically to `legendControl` -->
<div id='legend' style='display:none;'>
  <strong><a href="/index.php">Name of source</a>The Title or Explanation of your Map</strong>
  <nav class='legend clearfix'>
    <span style='background:#F1EEF6;'></span>
    <span style='background:#BDC9E1;'></span>
    <span style='background:#74A9CF;'></span>
    <span style='background:#2B8CBE;'></span>
    <span style='background:#045A8D;'></span>
    <label>0 - 20%</label>
    <label>40%</label>
    <label>60%</label>
    <label>80%</label>
    <label>100%</label>
    <small>Source: <a href="/index.php">Name of source</a></small>
</div>
<div id='map'></div>

<script>
L.mapbox.accessToken = 'pk.eyJ1IjoiZnJhbmNrdzMxIiwiYSI6ImNsbmJqemU5cjA0MDYya3RkczNrMHdqb2wifQ.6NLEMz-lShL80j9QuGW9cA';
  var map = L.mapbox.map('map')
    .setView([52.5049, 13.3937], 4)
    .addLayer(L.mapbox.styleLayer('mapbox://styles/mapbox/dark-v10'));

  map.legendControl.addLegend(document.getElementById('legend').innerHTML);
</script>
</body>
</html>
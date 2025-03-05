<html>
<head>
<meta charset=utf-8 />
<title>Query the Geocoder control results in code</title>
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
  pre.ui-output {
    display:block;
    position:absolute;
    bottom:10px;
    left:10px;
    padding:5px 10px;
    background:rgba(0,0,0,0.5);
    color:#fff;
    font-size:11px;
    line-height:18px;
    border-radius:3px;
    max-height:50%;
    max-width:25%;
    overflow:auto;
    word-wrap: break-word;
    white-space:pre-wrap;
    }
    pre.ui-output:empty { padding:0; }
</style>
<div id='map'></div>
<pre id='output' class='ui-output'></pre>
<script>
L.mapbox.accessToken = 'pk.eyJ1IjoiZnJhbmNrdzMxIiwiYSI6ImNsbmJqemU5cjA0MDYya3RkczNrMHdqb2wifQ.6NLEMz-lShL80j9QuGW9cA';
var output = document.getElementById('output');
var map = L.mapbox.map('map')
    .setView([43.6, 1.5],12)
    .addLayer(L.mapbox.styleLayer('mapbox://styles/mapbox/streets-v11'));

// Initialize the geocoder control and add it to the map.
var geocoderControl = L.mapbox.geocoderControl('mapbox.places',{autocomplete:true});
geocoderControl.addTo(map);

// Listen for the `found` result and display the first result
// in the output container. For all available events, see
// https://www.mapbox.com/mapbox.js/api/v3.3.1/l-mapbox-geocodercontrol/#section-geocodercontrol-on
geocoderControl.on('found', function(res) {
    output.innerHTML = JSON.stringify(res.results.features[0]);
});
</script>
</body>
</html>
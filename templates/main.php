<?php
script('gpxedit', 'leaflet');
script('gpxedit', 'L.Control.MousePosition');
script('gpxedit', 'Control.Geocoder');
script('gpxedit', 'ActiveLayers');
script('gpxedit', 'Control.MiniMap');
script('gpxedit', 'L.Control.Locate.min');
script('gpxedit', 'leaflet-sidebar.min');
script('gpxedit', 'jquery-ui.min');
script('gpxedit', 'jquery.mousewheel');
script('gpxedit', 'detect_timezone');
script('gpxedit', 'jquery.detect_timezone');
script('gpxedit', 'moment-timezone-with-data.min');
script('gpxedit', 'leaflet.draw-src');
//script('gpxedit', 'leaflet.geometryutil');
script('gpxedit', 'leaflet.snap');
script('gpxedit', 'Control.Draw.Plus');
script('gpxedit', 'Leaflet.LinearMeasurement');
script('gpxedit', 'gpxedit');

style('gpxedit', 'style');
style('gpxedit', 'leaflet');
style('gpxedit', 'L.Control.MousePosition');
style('gpxedit', 'Control.Geocoder');
style('gpxedit', 'leaflet-sidebar');
style('gpxedit', 'Control.MiniMap');
style('gpxedit', 'jquery-ui.min');
style('gpxedit', 'font-awesome.min');
style('gpxedit', 'gpxedit');
style('gpxedit', 'L.Control.Locate.min');
style('gpxedit', 'leaflet.draw');
style('gpxedit', 'Leaflet.LinearMeasurement');

?>

<div id="app">
    <div id="app-content">
        <?php print_unescaped($this->inc('gpxcontent')); ?>
    </div>
</div>

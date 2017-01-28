(function ($, OC) {
'use strict';

var gpxedit = {
    map: {},
    baseLayers: null,
    drawControl: null,
    id: 0,
    // indexed by gpxedit_id
    layersData: {},
    currentAjax : null
};

var symbolSelectClasses = {
    'unknown': 'unknown-select',
    'marker': 'marker-select',
    'Dot, White': 'dot-select',
    'Pin, Blue': 'pin-blue-select',
    'Pin, Green': 'pin-green-select',
    'Pin, Red': 'pin-red-select',
    'Flag, Green': 'flag-green-select',
    'Flag, Red': 'flag-red-select',
    'Flag, Blue': 'flag-blue-select',
    'Block, Blue': 'block-blue-select',
    'Block, Green': 'block-green-select',
    'Block, Red': 'block-red-select',
    'Blue Diamond': 'diamond-blue-select',
    'Green Diamond': 'diamond-green-select',
    'Red Diamond': 'diamond-red-select',
    'Residence': 'residence-select',
    'Drinking Water': 'drinking-water-select',
    'Trail Head': 'hike-select',
    'Bike Trail': 'bike-trail-select',
    'Campground': 'campground-select',
    'Bar': 'bar-select',
    'Skull and Crossbones': 'skullcross-select',
    'Geocache': 'geocache-select',
    'Geocache Found': 'geocache-open-select',
    'Medical Facility': 'medical-select',
    'Contact, Alien': 'contact-alien-select',
    'Contact, Big Ears': 'contact-bigears-select',
    'Contact, Female3': 'contact-female3-select',
    'Contact, Cat': 'contact-cat-select',
    'Contact, Dog': 'contact-dog-select',
}

var symbolIcons = {
    'marker': L.divIcon({
            className: 'leaflet-marker-blue',
            iconAnchor: [12, 41]
    }),
    'Dot, White': L.divIcon({
            iconSize:L.point(7,7),
    }),
    'Pin, Blue': L.divIcon({
        className: 'pin-blue',
        iconAnchor: [5, 30]
    }),
    'Pin, Green': L.divIcon({
        className: 'pin-green',
        iconAnchor: [5, 30]
    }),
    'Pin, Red': L.divIcon({
        className: 'pin-red',
        iconAnchor: [5, 30]
    }),
    'Flag, Green': L.divIcon({
        className: 'flag-green',
        iconAnchor: [1, 25]
    }),
    'Flag, Red': L.divIcon({
        className: 'flag-red',
        iconAnchor: [1, 25]
    }),
    'Flag, Blue': L.divIcon({
        className: 'flag-blue',
        iconAnchor: [1, 25]
    }),
    'Block, Blue': L.divIcon({
        className: 'block-blue',
        iconAnchor: [8, 8]
    }),
    'Block, Green': L.divIcon({
        className: 'block-green',
        iconAnchor: [8, 8]
    }),
    'Block, Red': L.divIcon({
        className: 'block-red',
        iconAnchor: [8, 8]
    }),
    'Blue Diamond': L.divIcon({
        className: 'diamond-blue',
        iconAnchor: [9, 9]
    }),
    'Green Diamond': L.divIcon({
        className: 'diamond-green',
        iconAnchor: [9, 9]
    }),
    'Red Diamond': L.divIcon({
        className: 'diamond-red',
        iconAnchor: [9, 9]
    }),
    'Residence': L.divIcon({
        className: 'residence',
        iconAnchor: [12, 12]
    }),
    'Drinking Water': L.divIcon({
        className: 'drinking-water',
        iconAnchor: [12, 12]
    }),
    'Trail Head': L.divIcon({
        className: 'hike',
        iconAnchor: [12, 12]
    }),
    'Bike Trail': L.divIcon({
        className: 'bike-trail',
        iconAnchor: [12, 12]
    }),
    'Campground': L.divIcon({
        className: 'campground',
        iconAnchor: [12, 12]
    }),
    'Bar': L.divIcon({
        className: 'bar',
        iconAnchor: [10, 12]
    }),
    'Skull and Crossbones': L.divIcon({
        className: 'skullcross',
        iconAnchor: [12, 12]
    }),
    'Geocache': L.divIcon({
        className: 'geocache',
        iconAnchor: [11, 10]
    }),
    'Geocache Found': L.divIcon({
        className: 'geocache-open',
        iconAnchor: [11, 10]
    }),
    'Medical Facility': L.divIcon({
        className: 'medical',
        iconAnchor: [13, 11]
    }),
    'Contact, Alien': L.divIcon({
        className: 'contact-alien',
        iconAnchor: [12, 12]
    }),
    'Contact, Big Ears': L.divIcon({
        className: 'contact-bigears',
        iconAnchor: [12, 12]
    }),
    'Contact, Female3': L.divIcon({
        className: 'contact-female3',
        iconAnchor: [12, 12]
    }),
    'Contact, Cat': L.divIcon({
        className: 'contact-cat',
        iconAnchor: [12, 12]
    }),
    'Contact, Dog': L.divIcon({
        className: 'contact-dog',
        iconAnchor: [12, 12]
    }),
}

var hoverStyle = {
    weight: 12,
    opacity: 0.7,
    color: 'black'
};
var defaultStyle = {
    opacity: 0.9,
    color: '#f357a1',
    weight: 7
};

function endsWith(str, suffix) {
    return str.indexOf(suffix, str.length - suffix.length) !== -1;
}

function load_map() {
  // change meta to send referrer
  // usefull for IGN tiles authentication !
  $('meta[name=referrer]').attr('content', 'origin');

  var layer = getUrlParameter('layer');
  console.log('layer '+layer);
  var default_layer = 'OpenStreetMap';
  if (typeof layer !== 'undefined'){
      default_layer = decodeURI(layer);
  }

  // get url from key and layer type
  function geopUrl (key, layer, format)
  { return 'http://wxs.ign.fr/'+ key + '/wmts?LAYER=' + layer
      +'&EXCEPTIONS=text/xml&FORMAT='+(format?format:'image/jpeg')
          +'&SERVICE=WMTS&VERSION=1.0.0&REQUEST=GetTile&STYLE=normal'
          +'&TILEMATRIXSET=PM&TILEMATRIX={z}&TILECOL={x}&TILEROW={y}' ;
  }
  // change it if you deploy GPXEDIT
  var API_KEY = 'ljthe66m795pr2v2g8p7faxt';
  var ign = new L.tileLayer ( geopUrl(API_KEY,'GEOGRAPHICALGRIDSYSTEMS.MAPS'),
          { attribution:'&copy; <a href="http://www.ign.fr/">IGN-France</a>',
              maxZoom:18
          });

  var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
  var osmAttribution = 'Map data &copy; 2013 <a href="http://openstreetmap'+
                       '.org">OpenStreetMap</a> contributors';
  var osm = new L.TileLayer(osmUrl, {maxZoom: 18, attribution: osmAttribution});

  var osmfrUrl = 'http://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png';
  var osmfr = new L.TileLayer(osmfrUrl,
              {maxZoom: 20, attribution: osmAttribution});
  var osmfr2 = new L.TileLayer(osmfrUrl,
               {minZoom: 0, maxZoom: 13, attribution: osmAttribution});

  var openmapsurferUrl = 'http://openmapsurfer.uni-hd.de/tiles/roads/'+
                         'x={x}&y={y}&z={z}';
  var openmapsurferAttribution = 'Imagery from <a href="http://giscience.uni'+
  '-hd.de/">GIScience Research Group @ University of Heidelberg</a> &mdash; '+
  'Map data &copy; <a href="http://www.openstreetmap.org/copyright">'+
  'OpenStreetMap</a>';
  var openmapsurfer = new L.TileLayer(openmapsurferUrl,
                      {maxZoom: 18, attribution: openmapsurferAttribution});

  var transportUrl = 'http://a.tile2.opencyclemap.org/transport/{z}/{x}/{y}.'+
                     'png';
  var transport = new L.TileLayer(transportUrl,
                  {maxZoom: 18, attribution: osmAttribution});

  var pisteUrl = 'http://tiles.openpistemap.org/nocontours/{z}/{x}/{y}.png';
  var piste = new L.TileLayer(pisteUrl,
              {maxZoom: 18, attribution: osmAttribution});

  var hikebikeUrl = 'http://toolserver.org/tiles/hikebike/{z}/{x}/{y}.png';
  var hikebike = new L.TileLayer(hikebikeUrl,
                 {maxZoom: 18, attribution: osmAttribution});

  var osmCycleUrl = 'http://{s}.tile.opencyclemap.org/cycle/{z}/{x}/{y}.png';
  var osmCycleAttrib = '&copy; <a href="http://www.opencyclemap.org">'+
  'OpenCycleMap</a>, &copy; <a href="http://www.openstreetmap.org/copyright">'+
  'OpenStreetMap</a>';
  var osmCycle = new L.TileLayer(osmCycleUrl,
                 {maxZoom: 18, attribution: osmCycleAttrib});

  var darkUrl = 'http://a.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png';
  var darkAttrib = '&copy; Map tiles by CartoDB, under CC BY 3.0. Data by'+
                   ' OpenStreetMap, under ODbL.';
  var dark = new L.TileLayer(darkUrl, {maxZoom: 18, attribution: darkAttrib});

  var esriTopoUrl = 'https://server.arcgisonline.com/ArcGIS/rest/services/World'+
                    '_Topo_Map/MapServer/tile/{z}/{y}/{x}';
  var esriTopoAttrib = 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ, '+
  'TomTom, Intermap, iPC, USGS, FAO, NPS, NRCAN, GeoBase, Kadaster NL, Ord'+
  'nance Survey, Esri Japan, METI, Esri China (Hong Kong), and the GIS User'+
  ' Community';
  var esriTopo = new L.TileLayer(esriTopoUrl,
                 {maxZoom: 18, attribution: esriTopoAttrib});

  var esriAerialUrl = 'https://server.arcgisonline.com/ArcGIS/rest/services'+
                      '/World_Imagery/MapServer/tile/{z}/{y}/{x}';
  var esriAerialAttrib = 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, '+
  'USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the'+
  ' GIS User Community';
  var esriAerial = new L.TileLayer(esriAerialUrl,
                   {maxZoom: 18, attribution: esriAerialAttrib});

  var tonerUrl = 'http://{s}.tile.stamen.com/toner/{z}/{x}/{y}.jpg';
  var stamenAttribution = '<a href="http://leafletjs.com" title="A JS library'+
  ' for interactive maps">Leaflet</a> | © Map tiles by <a href="http://stamen'+
  '.com">Stamen Design</a>, under <a href="http://creativecommons.org/license'+
  's/by/3.0">CC BY 3.0</a>, Data by <a href="http://openstreetmap.org">OpenSt'+
  'reetMap</a>, under <a href="http://creativecommons.org/licenses/by-sa/3.0"'+
  '>CC BY SA</a>.';
  var toner = new L.TileLayer(tonerUrl,
              {maxZoom: 18, attribution: stamenAttribution});

  var watercolorUrl = 'http://{s}.tile.stamen.com/watercolor/{z}/{x}/{y}.jpg';
  var watercolor = new L.TileLayer(watercolorUrl,
                   {maxZoom: 18, attribution: stamenAttribution});

  var routeUrl = 'http://{s}.tile.openstreetmap.fr/route500/{z}/{x}/{y}.png';
  var routeAttrib = '&copy, Tiles © <a href="http://www.openstreetmap.fr">O'+
  'penStreetMap France</a>';
  var route = new L.TileLayer(routeUrl,
              {minZoom: 1, maxZoom: 20, attribution: routeAttrib});

  var baseLayers = {
        'OpenStreetMap': osm,
        'OpenCycleMap': osmCycle,
        'IGN France': ign,
        'OpenMapSurfer Roads': openmapsurfer,
        'Hike & bike': hikebike,
        'OSM Transport': transport,
        'ESRI Aerial': esriAerial,
        'ESRI Topo with relief': esriTopo,
        'Dark' : dark,
        'Toner' : toner,
        'Watercolor' : watercolor,
        'OpenStreetMap France': osmfr
  };
  // add custom layers
  $('#tileserverlist li').each(function(){
      var sname = $(this).attr('name');
      var surl = $(this).attr('title');
      baseLayers[sname] = new L.TileLayer(surl,
              {maxZoom: 18, attribution: 'custom tile server'});
  });
  gpxedit.baseLayers = baseLayers;
  var baseOverlays = {
      'OsmFr Route500': route,
      'OpenPisteMap Relief':
        L.tileLayer('http://tiles2.openpistemap.org/landshaded/{z}/{x}/{y}.png',
                    {
                    attribution: '&copy, Tiles © <a href="http://www.o'+
                    'penstreetmap.fr">OpenStreetMap France</a>',
                    minZoom: 1,
                    maxZoom: 15
                    }
        ),
      'OpenPisteMap pistes' : piste
  };

  var layerlist = [];

  gpxedit.map = new L.Map('map', {
      zoomControl: true,
      layers: layerlist,
  });

  L.control.scale({metric: true, imperial: true, position:'topleft'})
  .addTo(gpxedit.map);

  L.control.mousePosition().addTo(gpxedit.map);
  gpxedit.searchControl = L.Control.geocoder({position:'topleft'});
  gpxedit.searchControl.addTo(gpxedit.map);
  gpxedit.locateControl = L.control.locate({follow:true});
  gpxedit.locateControl.addTo(gpxedit.map);
  L.Control.measureControl().addTo(gpxedit.map);
  L.control.sidebar('sidebar').addTo(gpxedit.map);

  gpxedit.map.setView(new L.LatLng(27, 5), 3);

  if (! baseLayers.hasOwnProperty(default_layer)){
      default_layer = 'OpenStreetMap';
  }
  gpxedit.map.addLayer(baseLayers[default_layer]);

  gpxedit.activeLayers = L.control.activeLayers(baseLayers, baseOverlays);
  gpxedit.activeLayers.addTo(gpxedit.map);

  gpxedit.minimapControl = new L.Control.MiniMap(
          osmfr2,
          { toggleDisplay: true, position:'bottomright' }
  ).addTo(gpxedit.map);
  gpxedit.minimapControl._toggleDisplayButtonClicked();

  gpxedit.editableLayers = new L.FeatureGroup();
  gpxedit.map.addLayer(gpxedit.editableLayers);

  var options = {
      position: 'bottomleft',
      draw: {
          polyline: {
              shapeOptions: {
                  color: '#f357a1',
                  weight: 7
              }
          },
          polygon:false,
          circle: false,
          rectangle:false,
          marker: {
              icon: symbolIcons['marker']
          }
      },
      edit: {
          featureGroup: gpxedit.editableLayers, //REQUIRED!!
      }
  };

  L.drawLocal.draw.toolbar.buttons.polyline = t('gpxedit','Draw a track');
  L.drawLocal.draw.toolbar.buttons.marker = t('gpxedit','Add a waypoint');
  L.drawLocal.edit.toolbar.buttons.edit = t('gpxedit','Edit');
  L.drawLocal.edit.toolbar.buttons.editDisabled = t('gpxedit','Nothing to edit');
  L.drawLocal.edit.toolbar.buttons.remove = t('gpxedit','Delete');
  L.drawLocal.edit.toolbar.buttons.removeDisabled = t('gpxedit','Nothing to delete');
  L.drawLocal.edit.toolbar.actions.save.title = t('gpxedit','Validate changes');
  L.drawLocal.edit.toolbar.actions.save.text = t('gpxedit','Ok');
  L.drawLocal.edit.toolbar.actions.cancel.title = t('gpxedit','Discard all changes');
  L.drawLocal.edit.toolbar.actions.cancel.text = t('gpxedit','Cancel');
  L.drawLocal.edit.handlers.edit.tooltip.text = t('gpxedit','Drag to move elements,<br/>click to remove a point');
  L.drawLocal.edit.handlers.edit.tooltip.subtext = t('gpxedit','Click cancel to undo changes');
  L.drawLocal.edit.handlers.remove.tooltip.text = t('gpxedit','Click on an element to delete it');
  L.drawLocal.draw.handlers.marker.tooltip.start = t('gpxedit','Click map to add waypoint');
  L.drawLocal.draw.handlers.polyline.tooltip.start = t('gpxedit','Click to start drawing track');
  L.drawLocal.draw.handlers.polyline.tooltip.cont = t('gpxedit','Click to continue drawing track');
  L.drawLocal.draw.handlers.polyline.tooltip.end = t('gpxedit','Click last point to finish track');
  L.drawLocal.draw.toolbar.actions.text = t('gpxedit','Cancel');
  L.drawLocal.draw.toolbar.actions.title = t('gpxedit','Cancel drawing');
  L.drawLocal.draw.toolbar.finish.text = t('gpxedit','Finish');
  L.drawLocal.draw.toolbar.finish.title = t('gpxedit','Finish drawing');
  L.drawLocal.draw.toolbar.undo.text = t('gpxedit','Delete last point');
  L.drawLocal.draw.toolbar.undo.title = t('gpxedit','Delete last point drawn');
  var drawControl = new L.Control.Draw(options);
  gpxedit.drawControl = drawControl;
  gpxedit.map.addControl(drawControl);

  // when something is created, we generate popup content
  // and initialize layer data
  gpxedit.map.on(L.Draw.Event.CREATED, function (e) {
      onCreated(e.layerType, e.layer);
  });
  // not used for the moment
  gpxedit.map.on('draw:edited', function (e) {
	  var layers = e.layers;
	  layers.eachLayer(function (layer) {
      });
  });
  // remove data associated with the deleted layer
  gpxedit.map.on('draw:deleted', function (e) {
	  var layers = e.layers;
	  layers.eachLayer(function (layer) {
          delete gpxedit.layersData[layer.gpxedit_id];
      });
  });

  // load data into popup when it opens
  // this is needed because popup content is created each time we open one
  // so, the content is lost when it's closed
  gpxedit.map.on('popupopen', function(e){
      var id = e.popup._source.gpxedit_id;
      //var id = parseInt(e.popup.getContent().match(/layerid="(\d+)"/)[1]);
      var buttonParent = $('button.popupOkButton[layerid='+id+']').parent();
      buttonParent.find('input.layerName').val(gpxedit.layersData[id].name);
      buttonParent.find('textarea.layerDesc').val(gpxedit.layersData[id].description);
      buttonParent.find('textarea.layerCmt').val(gpxedit.layersData[id].comment);
      if (gpxedit.layersData[id].layer.type === 'marker'){
          if (symbolIcons.hasOwnProperty(gpxedit.layersData[id].symbol)){
              buttonParent.find('select[role=symbol]').val(gpxedit.layersData[id].symbol);
          }
          else if(gpxedit.layersData[id].symbol === ''){
              buttonParent.find('select[role=symbol]').val('');
          }
          else{
              buttonParent.find('select[role=symbol]').val('unknown');
          }
          buttonParent.find('select[role=symbol]').change();
          var latlng = gpxedit.layersData[id].layer.getLatLng();
          buttonParent.find('input.layerLat').val(latlng.lat.toFixed(6));
          buttonParent.find('input.layerLon').val(latlng.lng.toFixed(6));
      }
  });

}

// called when something is drawn by hand or when a gpx is loaded
// it generates the popup content and initializes the layer's data
// it returns the layer in case we want to set the layer's data manually (when loading a gpx)
function onCreated(type, layer){
    var popupTitle;
    var layerType;
    if (type === 'polyline' || type === 'track'){
        popupTitle = t('gpxedit', 'Track');
        layerType = 'track';
    }
    else if (type === 'route') {
        popupTitle = t('gpxedit', 'Route');
        layerType = 'route';
    }
    else if (type === 'marker') {
        popupTitle = t('gpxedit', 'Waypoint');
        layerType = 'marker';
    }

    var popupTxt = '<h2 class="popupTitle">'+popupTitle+'</h2><table class="popupdatatable">'+
        '<tr><td>'+t('gpxedit', 'Name')+'</td><td><input class="layerName"></input></td></tr>'+
        '<tr><td>'+t('gpxedit', 'Description')+'</td><td><textarea class="layerDesc"></textarea></td></tr>'+
        '<tr><td>'+t('gpxedit', 'Comment')+'</td><td><textarea class="layerCmt"></textarea></td></tr>';
    if (type === 'marker') {
        popupTxt = popupTxt + '<tr><td>'+t('gpxedit', 'Lat')+'</td><td><input class="layerLat"></input></td></tr>';
        popupTxt = popupTxt + '<tr><td>'+t('gpxedit', 'Lon')+'</td><td><input class="layerLon"></input></td></tr>';
        popupTxt = popupTxt + '<tr><td>'+t('gpxedit', 'Symbol')+'</td><td><select role="symbol">';
        popupTxt = popupTxt + '<option value="">'+t('gpxedit', 'No symbol')+'</option>';
        popupTxt = popupTxt + '<option value="unknown">'+t('gpxedit', 'Unknown symbol')+'</option>';
        for (var cl in symbolIcons){
            if (cl !== 'marker'){
                popupTxt = popupTxt + '<option value="'+cl+'">'+cl+'</option>';
            }
        }
        popupTxt = popupTxt + '</select></td></tr>';
    }
    popupTxt = popupTxt + '</table>';
    popupTxt = popupTxt + '<button class="popupOkButton" layerid="'+gpxedit.id+'">OK</button>';

    layer.bindPopup(popupTxt);
    if (type !== 'marker') {
        layer.on('mouseover', function(){
            layer.setStyle(hoverStyle);
        });
        layer.on('mouseout', function(){
            layer.setStyle(defaultStyle);
        });
    }

    layer.gpxedit_id = gpxedit.id;
    layer.type = layerType;
    gpxedit.layersData[gpxedit.id] = {
        name:'',
        description:'',
        comment:'',
        symbol:'',
        time: '',
        layer: layer
    };
    gpxedit.editableLayers.addLayer(layer);
    gpxedit.id++;
    return layer;
}

function getUrlParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] === sParam)
        {
            return sParameterName[1];
        }
    }
}

// generate gpx text from current map elements
function generateGpx(){
    var gpxText = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>\n';
    var now = new Date();
    var now_utc_str = now.getUTCFullYear()+'-'+
            ("0" + now.getUTCMonth()).slice(-2)+'-'+
            ("0" + now.getUTCDate()).slice(-2)+'T'+
            ("0" + now.getUTCHours()).slice(-2)+':'+
            ("0" + now.getUTCMinutes()).slice(-2)+':'+
            ("0" + now.getUTCSeconds()).slice(-2)+'Z';
    gpxText = gpxText + '<gpx xmlns="http://www.topografix.com/GPX/1/1"'+
       ' xmlns:gpxx="http://www.garmin.com/xmlschemas/GpxExtensions/v3"'+
       ' xmlns:wptx1="http://www.garmin.com/xmlschemas/WaypointExtension/v1"'+
       ' xmlns:gpxtpx="http://www.garmin.com/xmlschemas/TrackPointExtension/v1"'+
       ' creator="GpxEdit Owncloud/Nextcloud app '+
       $('#versionnumber').text().replace(/\s+/g,'')+'" version="1.1"'+
       ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'+
       ' xsi:schemaLocation="http://www.topografix.com/GPX/1/1'+
       ' http://www.topografix.com/GPX/1/1/gpx.xsd'+
       ' http://www.garmin.com/xmlschemas/GpxExtensions/v3'+
       ' http://www8.garmin.com/xmlschemas/GpxExtensionsv3.xsd'+
       ' http://www.garmin.com/xmlschemas/WaypointExtension/v1'+
       ' http://www8.garmin.com/xmlschemas/WaypointExtensionv1.xsd'+
       ' http://www.garmin.com/xmlschemas/TrackPointExtension/v1'+
       ' http://www.garmin.com/xmlschemas/TrackPointExtensionv1.xsd">\n';
    gpxText = gpxText + '<metadata>\n <time>'+now_utc_str+'</time>\n';
    var fileDesc = $('#desctext').val();
    if (fileDesc){
        gpxText = gpxText + ' <desc>'+fileDesc+'</desc>\n';
    }
    gpxText = gpxText + '</metadata>\n';

    gpxedit.editableLayers.eachLayer(function(layer){
        var id = layer.gpxedit_id;
        var name = gpxedit.layersData[id].name;
        var comment = gpxedit.layersData[id].comment;
        var description = gpxedit.layersData[id].description;
        var time = gpxedit.layersData[id].time;
        if (layer.type === 'marker'){
            var symbol = gpxedit.layersData[id].symbol;
            var lat = layer._latlng.lat;
            var lng = layer._latlng.lng;
            var alt = layer._latlng.alt;
            gpxText = gpxText + ' <wpt lat="'+lat+'" lon="'+lng+'">\n';
            if (name){
                gpxText = gpxText + '  <name>'+name+'</name>\n';
            }
            else{
                gpxText = gpxText + '  <name>unnamed</name>\n';
            }
            if (alt !== undefined){
                gpxText = gpxText + '  <ele>'+alt+'</ele>\n';
            }
            if (comment){
                gpxText = gpxText + '  <cmt>'+comment+'</cmt>\n';
            }
            if (symbol){
                gpxText = gpxText + '  <sym>'+symbol+'</sym>\n';
            }
            if (description){
                gpxText = gpxText + '  <desc>'+description+'</desc>\n';
            }
            if (time){
                gpxText = gpxText + '  <time>'+time+'</time>\n';
            }
            gpxText = gpxText + ' </wpt>\n';
        }
        else if(layer.type === 'track'){
            gpxText = gpxText + ' <trk>\n';
            if (name){
                gpxText = gpxText + '  <name>'+name+'</name>\n';
            }
            else{
                gpxText = gpxText + '  <name>unnamed</name>\n';
            }
            if (comment){
                gpxText = gpxText + '  <cmt>'+comment+'</cmt>\n';
            }
            if (description){
                gpxText = gpxText + '  <desc>'+description+'</desc>\n';
            }
            gpxText = gpxText + '  <trkseg>\n';
            for (var i=0; i<layer._latlngs.length; i++){
                var lat = layer._latlngs[i].lat;
                var lng = layer._latlngs[i].lng;
                var alt = layer._latlngs[i].alt;
                var time = layer._latlngs[i].time;
                gpxText = gpxText + '   <trkpt lat="'+lat+'" lon="'+lng+'">\n';
                if (time){
                    gpxText = gpxText + '    <time>'+time+'</time>\n';
                }
                if (alt !== undefined){
                    gpxText = gpxText + '    <ele>'+alt+'</ele>\n';
                }
                gpxText = gpxText + '   </trkpt>\n';
            }
            gpxText = gpxText + '  </trkseg>\n </trk>\n';
        }
        else if(layer.type === 'route'){
            gpxText = gpxText + ' <rte>\n';
            if (name){
                gpxText = gpxText + '  <name>'+name+'</name>\n';
            }
            else{
                gpxText = gpxText + '  <name>unnamed</name>\n';
            }
            if (comment){
                gpxText = gpxText + '  <cmt>'+comment+'</cmt>\n';
            }
            if (description){
                gpxText = gpxText + '  <desc>'+description+'</desc>\n';
            }
            for (var i=0; i<layer._latlngs.length; i++){
                var lat = layer._latlngs[i].lat;
                var lng = layer._latlngs[i].lng;
                var alt = layer._latlngs[i].alt;
                var time = layer._latlngs[i].time;
                gpxText = gpxText + '  <rtept lat="'+lat+'" lon="'+lng+'">\n';
                if (time !== undefined){
                    gpxText = gpxText + '   <time>'+time+'</time>\n';
                }
                if (alt !== undefined){
                    gpxText = gpxText + '   <ele>'+alt+'</ele>\n';
                }
                gpxText = gpxText + '  </rtept>\n';
            }
            gpxText = gpxText + ' </rte>\n';
        }
    });
    gpxText = gpxText + ' <extensions/>\n</gpx>';
    return gpxText;
}

// adds a marker and initialize its data
function drawMarker(latlng, name, desc, cmt, sym, time){
    var wst = $('#markerstyleselect').val();
    var tst = $('#tooltipstyleselect').val();
    var symboo = $('#symboloverwrite').is(':checked');
    var m = L.marker(latlng);
    if (symboo && sym !== '' && symbolIcons.hasOwnProperty(sym)){
        m.setIcon(symbolIcons[sym]);
    }
    else if(symboo && sym !== ''){
        m.setIcon(L.divIcon({
            className: 'unknown',
            iconAnchor: [12, 12]
        }));
    }
    else{
        m.setIcon(symbolIcons[wst]);
    }
    var layer = onCreated('marker', m);
    if (name !== ''){
        if (tst === 'p'){
            m.bindTooltip(name, {permanent:true});
        }
        else{
            m.bindTooltip(name, {sticky:true});
        }
    }
    gpxedit.layersData[layer.gpxedit_id].name = name;
    gpxedit.layersData[layer.gpxedit_id].comment = cmt;
    gpxedit.layersData[layer.gpxedit_id].description = desc;
    gpxedit.layersData[layer.gpxedit_id].symbol = sym;
    gpxedit.layersData[layer.gpxedit_id].time = time;
}

// adds a polyline and initialize its data
function drawLine(latlngs, name, desc, cmt, gpxtype, times){
    var wst = $('#markerstyleselect').val();
    var tst = $('#tooltipstyleselect').val();
    var p = L.polyline(latlngs, {
                  color: '#f357a1',
                  weight: 7
    });
    if (times.length === p._latlngs.length){
        for (var i=0; i<times.length; i++){
            if (times[i]){
                p._latlngs[i].time = times[i];
            }
        }
    }
    var layer = onCreated(gpxtype, p);
    if (name !== ''){
        if (tst === 'p'){
            p.bindTooltip(name, {permanent:true});
        }
        else{
            p.bindTooltip(name, {sticky:true});
        }
    }
    gpxedit.layersData[layer.gpxedit_id].name = name;
    gpxedit.layersData[layer.gpxedit_id].comment = cmt;
    gpxedit.layersData[layer.gpxedit_id].description = desc;
}

// parse gpx xml text to draw it on the map
function parseGpx(xml){
    //var dom = $.parseXML(xml);
    var dom = $(xml);
    var fileDesc = dom.find('>metadata>desc').text();
    $('#desctext').val(fileDesc);
    dom.find('wpt').each(function(){
        var lat = $(this).attr('lat');
        var lon = $(this).attr('lon');
        var name = $(this).find('name').text();
        var cmt = $(this).find('cmt').text();
        var desc = $(this).find('desc').text();
        var sym = $(this).find('sym').text();
        var ele = $(this).find('ele').text();
        var time = $(this).find('time').text();
        if (ele !== ''){
            drawMarker([lat, lon, ele], name, desc, cmt, sym, time);
        }
        else{
            drawMarker([lat, lon], name, desc, cmt, sym, time);
        }
    });
    dom.find('trk').each(function(){
        var latlngs = [];
        var name = $(this).find('>name').text();
        var cmt = $(this).find('>cmt').text();
        var desc = $(this).find('>desc').text();
        var times = [];
        $(this).find('trkseg').each(function(){
            $(this).find('trkpt').each(function(){
                var lat = $(this).attr('lat');
                var lon = $(this).attr('lon');
                var ele = $(this).find('ele').text();
                var time = $(this).find('time').text();
                times.push(time);
                if (ele !== ''){
                    latlngs.push([lat,lon,ele]);
                }
                else{
                    latlngs.push([lat,lon]);
                }
            });
        });
        drawLine(latlngs, name, desc, cmt, 'track', times);
    });
    dom.find('rte').each(function(){
        var latlngs = [];
        var name = $(this).find('>name').text();
        var cmt = $(this).find('>cmt').text();
        var desc = $(this).find('>desc').text();
        var times = [];
        $(this).find('rtept').each(function(){
            var lat = $(this).attr('lat');
            var lon = $(this).attr('lon');
            var ele = $(this).find('ele').text();
            var time = $(this).find('time').text();
            times.push(time);
            if (ele !== ''){
                latlngs.push([lat,lon,ele]);
            }
            else{
                latlngs.push([lat,lon]);
            }
        });
        drawLine(latlngs, name, desc, cmt, 'route', times);
    });
}

// remove layers from map and delete all layers data
function clear(){
    var layersToRemove = [];
    gpxedit.editableLayers.eachLayer(function (layer) {
          layer.unbindTooltip();
          delete gpxedit.layersData[layer.gpxedit_id];
          layersToRemove.push(layer);
    });

    for(var i=0; i<layersToRemove.length; i++){
        gpxedit.editableLayers.removeLayer(layersToRemove[i]);
    }
}

/*
 * get key events
 */
function checkKey(e){
    e = e || window.event;
    var kc = e.keyCode;
    console.log(kc);

    if (kc === 161 || kc === 223){
        e.preventDefault();
        gpxedit.minimapControl._toggleDisplayButtonClicked();
    }
    if (kc === 60 || kc === 220){
        e.preventDefault();
        $('#sidebar').toggleClass('collapsed');
    }
}

function showSaveFailAnimation(path, message){
    $('#failed').find('b#content').html(t('gpxedit', 'Failed to save file')+' '+path+'<br/>'+message);
    $('#failed').fadeIn();
    setTimeout(hideFailedAnimation, 4000);
}

function showFailAnimation(message){
    $('#failed').find('b#content').html(message);
    $('#failed').fadeIn();
    setTimeout(hideFailedAnimation, 4000);
}

function hideFailedAnimation(){
    $('#failed').fadeOut();
}

function showSaveSuccessAnimation(path){
    $('#saved').find('b#content').html(t('gpxedit', 'File successfully saved as')+'<br/>'+path);
    $('#saved').fadeIn();
    setTimeout(hideSaveSuccessAnimation, 4000);
}

function hideSaveSuccessAnimation(){
    $('#saved').fadeOut();
}

function showLoadingAnimation(){
    $('#loading').show();
}

function hideLoadingAnimation(){
    $('#loading').hide();
}

function showExportingAnimation(){
    $('#exporting').show();
}

function hideExportingAnimation(){
    $('#exporting').hide();
}

function showSavingAnimation(){
    $('#saving').show();
}

function hideSavingAnimation(){
    $('#saving').hide();
}



function loadAction(file){
    if (!endsWith(file, '.gpx') &&
            !endsWith(file, '.kml') &&
            !endsWith(file, '.jpg') &&
            !endsWith(file, '.csv')){
        OC.dialogs.alert(t('gpxedit', 'Impossible to load this file. ')+
            t('gpxedit', 'Supported formats are gpx, kml, csv (unicsv) and jpg.'),t('gpxedit', 'Load error'));
        return;
    }

    loadFile(file);
    // set save name
    var spl = file.split('/');
    var basename = spl[spl.length-1];
    $('input#saveName').val(
        basename.replace(/\.jpg$/, '.gpx').replace(/\.kml$/, '.gpx').replace(/\.csv$/, '.gpx')
    );
}

function loadFile(file){
    var req = {
        path : file
    }
    var url = OC.generateUrl('/apps/gpxedit/getgpx');
    $('#loadingpc').text('0 %');
    showLoadingAnimation();
    gpxedit.currentAjax = $.ajax({
        type: "POST",
        async: true,
        url: url,
        data: req,
        xhr: function(){
            var xhr = new window.XMLHttpRequest();
            xhr.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total * 100;
                    $('#loadingpc').text('('+parseInt(percentComplete)+' %)');
                }
            }, false);

            return xhr;
        }
        }).done(function (response) {
        if ($('#clearbeforeload').is(':checked')){
            clear();
        }
        if (response.gpx === ''){
            alert('The file does not exist or it is not a gpx');
        }
        else{
            parseGpx(response.gpx);
            var bounds = gpxedit.editableLayers.getBounds();
            gpxedit.map.fitBounds(bounds,
                    {animate:true, paddingTopLeft: [parseInt($('#sidebar').css('width')), 0]}
                    );
            hideLoadingAnimation();
        }
    });
}

function deleteTileServer(li){
    var sname = li.attr('name');
    var req = {
        servername : sname
    }
    var url = OC.generateUrl('/apps/gpxedit/deleteTileServer');
    $.ajax({
        type:'POST',
        url:url,
        data:req,
        async:true
    }).done(function (response) {
        //alert(response.done);
        if (response.done){
            li.remove();
            var activeLayerName = gpxedit.activeLayers.getActiveBaseLayer().name;
            // if we delete the active layer, first select another
            if (activeLayerName === sname){
                $('input.leaflet-control-layers-selector').first().click();
            }
            gpxedit.activeLayers.removeLayer(gpxedit.baseLayers[sname]);
            delete gpxedit.baseLayers[sname];
        }
    }).always(function(){
    });
}

function addTileServer(){
    var sname = $('#tileservername').val();
    var surl = $('#tileserverurl').val();
    if (sname === '' || surl === ''){
        alert('Server name or server url should not be empty');
        return;
    }
    $('#tileservername').val('');
    $('#tileserverurl').val('');

    var req = {
        servername : sname,
        serverurl : surl
    }
    var url = OC.generateUrl('/apps/gpxedit/addTileServer');
    $.ajax({
        type:'POST',
        url:url,
        data:req,
        async:true
    }).done(function (response) {
        //alert(response.done);
        if (response.done){
            $('#tileserverlist ul').prepend(
                '<li name="'+sname+'" title="'+surl+'">'+sname+' <button>'+
                '<i class="fa fa-trash" aria-hidden="true" style="color:red;"></i> '+
                t('gpxedit','Delete')+'</button></li>'
            );
            // add tile server in leaflet control
            var newlayer = new L.TileLayer(surl,
                    {maxZoom: 18, attribution: 'custom tile server'});
            gpxedit.activeLayers.addBaseLayer(newlayer, sname);
            gpxedit.baseLayers[sname] = newlayer;
        }
    }).always(function(){
    });
}

// affects future markers and also existing ones
function updateLeafletDrawMarkerStyle(){
    var wst = $('#markerstyleselect').val();
    var theclass = symbolSelectClasses[wst];
    $('#markerstyleselect').removeClass($('#markerstyleselect').attr('class'));
    $('#markerstyleselect').attr('style','');
    if (theclass){
        $('#markerstyleselect').addClass(theclass);
    }
    else if (wst !== ''){
        var url = OC.generateUrl('/apps/gpxedit/getExtraSymbol?');
        var fullurl = url+'name='+encodeURI(wst+'.png');
        $('#markerstyleselect').attr('style',
            'background: url(\''+fullurl+'\') no-repeat '+
            'right 8px center rgba(240, 240, 240, 0.90);'+
            'background-size: contain;');
    }
    var tst = $('#tooltipstyleselect').val();
    var theicon = symbolIcons[wst];

    gpxedit.drawControl.setDrawingOptions({
        marker: {
            icon: theicon
        }
    });

    var symboo = $('#symboloverwrite').is(':checked');
    gpxedit.editableLayers.eachLayer(function(layer){
        var id = layer.gpxedit_id;
        var name = gpxedit.layersData[id].name;
        var symbol = gpxedit.layersData[id].symbol;
        if (layer.type === 'marker'){
            if (symboo && symbol !== '' && symbolIcons.hasOwnProperty(symbol)){
                layer.setIcon(symbolIcons[symbol]);
            }
            else{
                layer.setIcon(theicon);
            }
        }
        if (name !== ''){
            layer.unbindTooltip();
            if (tst === 'p'){
                layer.bindTooltip(name, {permanent:true});
            }
            else{
                layer.bindTooltip(name, {sticky:true});
            }
        }
    });
}

function restoreOptions(){
    var url = OC.generateUrl('/apps/gpxedit/getOptionsValues');
    var req = {
    }
    var optionsValues = '{}';
    $.ajax({
        type: 'POST',
        url: url,
        data: req,
        async: false
    }).done(function (response) {
        optionsValues = response.values;
        //alert('option values : '+optionsValues);
    }).fail(function(){
        alert('failed to restore options values');
    });
    optionsValues = $.parseJSON(optionsValues);
    if (optionsValues.markerstyle !== undefined &&
            symbolIcons.hasOwnProperty(optionsValues.markerstyle)){
        $('#markerstyleselect').val(optionsValues.markerstyle);
    }
    if (optionsValues.tooltipstyle !== undefined){
        $('#tooltipstyleselect').val(optionsValues.tooltipstyle);
    }
	if (optionsValues.clearbeforeload !== undefined){
        $('#clearbeforeload').prop('checked', optionsValues.clearbeforeload);
    }
	if (optionsValues.symboloverwrite !== undefined){
        $('#symboloverwrite').prop('checked', optionsValues.symboloverwrite);
    }
}

function saveOptions(){
    var optionsValues = {};
    optionsValues.markerstyle = $('#markerstyleselect').val();
    optionsValues.tooltipstyle = $('#tooltipstyleselect').val();
	optionsValues.clearbeforeload = $('#clearbeforeload').is(':checked');
	optionsValues.symboloverwrite = $('#symboloverwrite').is(':checked');
    //alert('to save : '+JSON.stringify(optionsValues));

    var req = {
        optionsValues : JSON.stringify(optionsValues),
    }
    var url = OC.generateUrl('/apps/gpxedit/saveOptionsValues');
    $.ajax({
        type: 'POST',
        url: url,
        data: req,
        async: true
    }).done(function (response) {
        //alert(response);
    }).fail(function(){
        alert('failed to save options values');
    });
}

function fillWaypointStyles(){
    for (var st in symbolIcons){
        $('select#markerstyleselect').append('<option value="'+st+'">'+st+'</option>');
    }
    $('select#markerstyleselect').val('marker');
}

function addExtraSymbols(){
    var url = OC.generateUrl('/apps/gpxedit/getExtraSymbol?');
    $('ul#extrasymbols li').each(function(){
        var name = $(this).attr('name');
        var smallname = $(this).html();
        var fullurl = url+'name='+encodeURI(name);
        var d = L.icon({
            iconUrl: fullurl,
            iconSize: L.point(24, 24),
            iconAnchor: [12, 12]
        });
        symbolIcons[smallname] = d;
    });
}

function saveAction(targetPath){
    showExportingAnimation();
    var saveFilePath = targetPath+'/'+$('input#saveName').val();
    var gpxText = generateGpx();
    hideExportingAnimation();
    $('#savingpc').text('0 %');
    showSavingAnimation();
    var req = {
        path: saveFilePath,
        content: gpxText 
    }
    var url = OC.generateUrl('/apps/gpxedit/savegpx');
    $.ajax({
        type: 'POST',
        async: true,
        url: url,
        data: req,
        xhr: function(){
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total * 100;
                    //Do something with upload progress here
                    $('#savingpc').text('('+parseInt(percentComplete)+' %)');
                }
            }, false);

            return xhr;
        }
    }).done(function (response) {
        hideSavingAnimation();
        if (response.status === 'fiw'){
            showSaveFailAnimation(saveFilePath, t('gpxedit', 'Impossible to write file')+' : '+t('gpxedit', 'write access denied'));
        }
        else if (response.status === 'fu'){
            showSaveFailAnimation(saveFilePath, t('gpxedit', 'Impossible to write file')+' : '+t('gpxedit', 'folder does not exist'));
        }
        else if (response.status === 'fw'){
            showSaveFailAnimation(saveFilePath, t('gpxedit', 'Impossible to write file')+' : '+t('gpxedit', 'folder write access denied'));
        }
        else if (response.status === 'bfn'){
            showSaveFailAnimation(saveFilePath, t('gpxedit', 'Bad file name, must end with ".gpx"'));
        }
        else{
            showSaveSuccessAnimation(saveFilePath);
        }
    });
}

$(document).ready(function(){
    gpxedit.username = $('p#username').html();
    load_map();
	document.onkeydown = checkKey;
    addExtraSymbols();
    fillWaypointStyles();
    restoreOptions();

    $('select#markerstyleselect').change(function(e){
        updateLeafletDrawMarkerStyle();
		saveOptions();
    });
    $('select#tooltipstyleselect').change(function(e){
        updateLeafletDrawMarkerStyle();
		saveOptions();
    });
    $('body').on('change','#symboloverwrite', function() {
        updateLeafletDrawMarkerStyle();
		saveOptions();
    });
    // to set the draw style
    updateLeafletDrawMarkerStyle();
	$('body').on('change','#clearbeforeload', function() {
		saveOptions();
    });
    $('body').on('click','button.popupOkButton', function(e) {
        var id = parseInt($(this).attr('layerid'));
        var name = $(this).parent().find('.layerName').val();
        var description = $(this).parent().find('.layerDesc').val();
        var comment = $(this).parent().find('.layerCmt').val();
        var symbol = $(this).parent().find('select[role=symbol]').val();
        var wst = $('#markerstyleselect').val();
        var tst = $('#tooltipstyleselect').val();
        var symboo = $('#symboloverwrite').is(':checked');
        var type = gpxedit.layersData[id].layer.type;

        gpxedit.layersData[id].name = name;
        gpxedit.layersData[id].description = description;
        gpxedit.layersData[id].comment = comment;
        if (symbol !== 'unknown'){
            gpxedit.layersData[id].symbol = symbol;
        }
        gpxedit.layersData[id].layer.unbindTooltip();
        if (type === 'marker'){
            if (symbol === 'unknown'){
                // pass
            }
            else if (symboo && symbol !== '' && symbolIcons.hasOwnProperty(symbol)){
                gpxedit.layersData[id].layer.setIcon(symbolIcons[symbol])
            }
            else{
                var theicon = symbolIcons[wst];
                gpxedit.layersData[id].layer.setIcon(theicon);
            }
            var lat = $(this).parent().find('.layerLat').val();
            var lon = $(this).parent().find('.layerLon').val();
            var latlng = L.latLng(lat, lon);
            gpxedit.layersData[id].layer.setLatLng(latlng);
        }
        if (name !== ''){
            if (tst === 'p'){
                gpxedit.layersData[id].layer.bindTooltip(name, {permanent:true});
            }
            else{
                gpxedit.layersData[id].layer.bindTooltip(name, {sticky:true});
            }
        }

        gpxedit.map.closePopup();
    });

    $('button#clearButton').click(function(e){
        clear();
    });
    $('button#loadButton').click(function(e){
        if (gpxedit.currentAjax !== null){
            gpxedit.currentAjax.abort();
            hideLoadingAnimation();
        }
        OC.dialogs.filepicker(t('gpxedit', 'Load file (gpx, kml, csv, png)'), function(targetPath) {
            loadAction(targetPath);
        }, false, null, true);
    });

    $('button#saveButton').click(function(e){
        if (gpxedit.editableLayers.getLayers().length === 0){
            showFailAnimation(t('gpxedit', 'There is nothing to save'));
        }
        else{
            var filename = $('#saveName').val();
            OC.dialogs.filepicker(t('gpxedit', 'Where to save')+' <b>'+filename+'</b>', function(targetPath) {
                saveAction(targetPath);
            }, false, "httpd/unix-directory", true);
        }
    });

    // Custom tile server management
    $('body').on('click','#tileserverlist button', function(e) {
        deleteTileServer($(this).parent());
    });
    $('#addtileserver').click(function(){
        addTileServer();
    });

	$('body').on('change', 'select[role=symbol]', function() {
        $(this).removeClass($(this).attr('class'));
        $(this).attr('style','');
        if (symbolSelectClasses.hasOwnProperty($(this).val())){
            $(this).addClass(symbolSelectClasses[$(this).val()]);
        }
        else if ($(this).val() !== ''){
            var url = OC.generateUrl('/apps/gpxedit/getExtraSymbol?');
            var fullurl = url+'name='+encodeURI($(this).val()+'.png');
            $(this).attr('style',
                'background: url(\''+fullurl+'\') no-repeat '+
                'right 8px center rgba(240, 240, 240, 0.90);'+
                'background-size: contain;');
        }
    });

    // load a file if 'file' GET url parameter was given
    var urlfileparam = getUrlParameter('file');
    var fileparam = decodeURI(urlfileparam);
    if (urlfileparam && urlfileparam !== undefined){
        loadAction(fileparam);
    }

});

})(jQuery, OC);

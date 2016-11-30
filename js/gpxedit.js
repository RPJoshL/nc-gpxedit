(function ($, OC) {
'use strict';

var gpxedit = {
    map: {},
    baseLayers: null,
    id: 0,
    // indexed by gpxedit_id
    layersData: {}
};

function load_map() {
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
  // change it if you deploy GPXPOD
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
          { toggleDisplay: true, position:'bottomleft' }
  ).addTo(gpxedit.map);
  gpxedit.minimapControl._toggleDisplayButtonClicked();

  //gpxedit.map.on('moveend',updateTrackListFromBounds);
  //gpxedit.map.on('zoomend',updateTrackListFromBounds);
  //gpxedit.map.on('baselayerchange',updateTrackListFromBounds);

  var editableLayers = new L.FeatureGroup();
  gpxedit.map.addLayer(editableLayers);

  var MyCustomMarker = L.Icon.extend({
      options: {
          shadowUrl: null,
          //iconAnchor: new L.Point(12, 12),
          //iconSize: new L.Point(24, 24),
          //iconUrl: 'link/to/image.png'
          icon: L.divIcon({
              className: 'leaflet-div-icon2',
              iconAnchor: [5, 30]
          })

      }
  });

  var options = {
      position: 'topright',
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
              icon: L.divIcon({
                  className: 'leaflet-div-icon2',
                  iconAnchor: [5, 30]
              })
          }
      },
      edit: {
          featureGroup: editableLayers, //REQUIRED!!
      }
  };

  var drawControl = new L.Control.Draw(options);
  gpxedit.map.addControl(drawControl);

  gpxedit.map.on(L.Draw.Event.CREATED, function (e) {
      var type = e.layerType,
      layer = e.layer;
      var popupTitle = 'Line';
      if (type === 'marker') {
          popupTitle = 'Waypoint';
      }

      layer.bindPopup('<h2>'+popupTitle+'</h2>Name : <input class="layerName"></input><br/>'+
              'Description : <textarea class="layerDesc"></textarea><br/>'+
              'Comment : <textarea class="layerCmt"></textarea><br/>'+
              '<button class="popupOkButton" layerid="'+gpxedit.id+'">OK</button>');

      layer.gpxedit_id = gpxedit.id;
      layer.type = type;
      gpxedit.layersData[gpxedit.id] = {name:'', description:'', comment:'', layer: layer};
      editableLayers.addLayer(layer);
      gpxedit.id++;
  });
  gpxedit.map.on('draw:edited', function (e) {
	  var layers = e.layers;
	  layers.eachLayer(function (layer) {
		  //do whatever you want; most likely save back to db
          //alert('edited : '+Object.keys(layer));
      });
      editableLayers.eachLayer(function (layer) {
          //alert('edited : '+Object.keys(layer));
          alert('edited : '+Object.keys(layer._leaflet_id));
      });
  });
  gpxedit.map.on('draw:deleted', function (e) {
	  var layers = e.layers;
	  layers.eachLayer(function (layer) {
          delete gpxedit.layersData[layer.gpxedit_id];
      });
      editableLayers.eachLayer(function (layer) {
          alert(layer.gpxedit_id);
      });
  });

  gpxedit.map.on('popupopen', function(e){
      var id = parseInt(e.popup.getContent().match(/layerid="(\d+)"/)[1]);
      var buttonParent = $('button.popupOkButton[layerid='+id+']').parent();
      buttonParent.find('input.layerName').val(gpxedit.layersData[id].name);
      buttonParent.find('textarea.layerDesc').val(gpxedit.layersData[id].description);
      buttonParent.find('textarea.layerCmt').val(gpxedit.layersData[id].comment);
  });

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

$(document).ready(function(){
    gpxedit.username = $('p#username').html();
    load_map();

    $('body').on('click','button.popupOkButton', function(e) {
        var id = parseInt($(this).attr('layerid'));
        var name = $(this).parent().find('.layerName').val();
        var description = $(this).parent().find('.layerDesc').val();
        var comment = $(this).parent().find('.layerCmt').val();

        gpxedit.layersData[id].name = name;
        gpxedit.layersData[id].description = description;
        gpxedit.layersData[id].comment = comment;
        gpxedit.layersData[id].layer.bindTooltip(name, {sticky:true});

        gpxedit.map.closePopup();
    });
});

})(jQuery, OC);

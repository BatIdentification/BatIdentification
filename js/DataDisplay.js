//HEADER: Definitions

var map;

var iconStyle = new ol.style.Style({
  image: new ol.style.Icon(/** @type {module:ol/style/Icon~Options} */ ({
    anchor: [0.5, 46],
    anchorXUnits: 'fraction',
    anchorYUnits: 'pixels',
    src: 'images/marker.png'
  }))
});

//HEADER: Helper functions

function createLayer(features){
  var vectorSource = new ol.source.Vector({
    features: features
  });

  var vectorLayer = new ol.layer.Vector({
    source: vectorSource
  });

  return vectorLayer;
}

function setupMap(layers){

  map = new ol.Map({
    layers: layers,
    target: document.getElementById('map'),
    view: new ol.View({
      center: ol.proj.fromLonLat([-6.2539820, 53.3405900]),
      zoom: 8,
      extent: [-13037508.342789244, -9037508.342789244, 13037508.342789244, 9037508.342789244],
      minZoom: 3
    })
  });

  var element = document.getElementById('ol-popup');

  var popup = new ol.Overlay({
    element: element,
    positioning: 'bottom-center',
    stopEvent: false,
    offset: [0, -50]
  });
  map.addOverlay(popup);

  //SUB: Display popup on click
  map.on('click', function(evt) {
    var feature = map.forEachFeatureAtPixel(evt.pixel,
      function(feature) {
        return feature;
      });
    if (feature) {
      var coordinates = feature.getGeometry().getCoordinates();
      popup.setPosition(coordinates);
      content = "Specie: " + feature.get('specie') + "<br>Address: " + feature.get('address');
      content += feature.get('specie') != "Not identified" ? "<br><a href='call/" + feature.get('id') + "'>More info</a>" : "";
      $(element).popover({
        placement: 'top',
        html: true,
        content: content,
      });
      $(element).popover('show');
    } else {
      $(element).popover('destroy');
    }
  });

  // change mouse cursor when over marker
  map.on('pointermove', function(e) {
    if (e.dragging) {
      $(element).popover('destroy');
      return;
    }
    var pixel = map.getEventPixel(e.originalEvent);
    var hit = map.hasFeatureAtPixel(pixel);
    map.getTarget().style.cursor = hit ? 'pointer' : '';
  });

}

function getFeatures(url, params, callback){

  var vectorFeatures = [];

  $.post(url, params, function(response){

    for(var i = 0; i < response['calls'].length; i++){

      var batCall = response['calls'][i]

      var loc = [parseFloat(batCall['lng']), parseFloat(batCall['lat'])]

      var iconFeature = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.fromLonLat(loc)),
        name: batCall['lng'] + batCall['lat'],
        specie: batCall['species'],
        address: batCall['address'],
        id: batCall['id'],
      });

      iconFeature.setStyle(iconStyle);

      vectorFeatures.push(iconFeature);
    }

    callback(vectorFeatures);

  });

}

//HE: Make points for all the bat call


function setupPage(vectorFeatures){

  var customLayer = createLayer(vectorFeatures);

  setupMap([
    new ol.layer.Tile({
      source: new ol.source.OSM()
    }),
    customLayer
  ]);

}

$(document).ready(function(){

  var apiURL = "https://api." + window.location.hostname + "/api/";

  getFeatures(apiURL + "calls", {}, setupPage);

  $("#submit-btn").click(function(){

    var formData = {};

    $("input:checked").each(function(index){

        formData[`bat_species[${index}]`] = $(this).attr("name");

    });

    formData['range'] = $("#date_range").val();

    getFeatures(apiURL + "calls", formData, function(vectorFeatures){

      var vectorSource = new ol.source.Vector({
        features: vectorFeatures
      });

      layer = map.getLayers().getArray()[1];

      layer.setSource(vectorSource);

    })

  })

})

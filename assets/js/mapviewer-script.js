$(document).ready(function() {
  var map = null;
  var myMarker;
  var myLatlng;
  var layerGroup = null;
  
  function initializeGMap(lat, lng) {
    myLatlng = new L.LatLng(lat, lng);			
	var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
			var 	osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
			var 	osm = new L.TileLayer(osmUrl, {maxZoom: 100, attribution: osmAttrib});
				
				if(map == null){
						map = new L.Map('map_canvas', {layers: [osm], center: myLatlng, zoom: 14 });
						map.on("zoomstart", function (e) { 
						 
						});
						map.on("zoomend", function (e) { 
							//console.log("ZOOMEND", e);
							var currZoom = map.getZoom();							
							map.setView(myLatlng, currZoom);
						});
						layerGroup = new L.LayerGroup()
						layerGroup.addTo(map);
					}else{
						// var layers = L.layerGroup().addTo(map);
						// layers.clearLayers();
						/*$.each(layers, function(key, value) {
							 map.removeLayer(layer);
							console.log(key);
						});
					map.eachLayer(function(layer){
						  map.removeLayer(layer);
						});*/
						
					//layerGroup = L.layerGroup().addTo(map);
					
					// remove all the markers in one go
					layerGroup.clearLayers();
					osm.addTo(map);
					}

					// create markers
					// L.marker().addTo(layerGroup);

					
					 var marker = L.marker( [lat, lng] ).addTo(layerGroup);
					// var marker = L.marker( [lat, lng] ).addTo(map);
                  
  }
 
  $('#myModalLeaflet').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    initializeGMap(button.data('lat'), button.data('lng'));
    $("#location-map").css("width", "100%");
    $("#map_canvas").css("width", "100%");
  });

  $('#myModalLeaflet').on('shown.bs.modal', function() {
	map.invalidateSize();
	map.setView(myLatlng, 14);
  });
});
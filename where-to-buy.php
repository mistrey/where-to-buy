<?php
mysql_select_db($database_db_connect, $db_connect);
$sql = "SELECT id, company, latlong FROM dealers WHERE active = 1 AND www IS NOT NULL";
$partners = mysql_query($sql, $db_connect) or die(mysql_error());
$partner = mysql_fetch_array($partners);

print('var reprData = {');
do {
    printf("'%s': {name: '%s', latLng: %s},\n", $partner[0], $partner[1], $partner[2]);
}  while ($partner = mysql_fetch_array($partners));
print("};\n\n");

mysql_free_result($partners);

mysql_select_db($database_db_connect, $db_connect);
$sql = "SELECT country, idx FROM areas, country_area WHERE country_area.area = areas.area";
$countries = mysql_query($sql, $db_connect) or die(mysql_error());
$country = mysql_fetch_array($countries);

print('var countries = {');
do {
    printf("'%s': {idx: %s},\n", $country[0], $country[1]);
}  while ($country = mysql_fetch_array($countries));
print("};\n");

mysql_free_result($countries);
?>

$(function(){
  $('#world-map').vectorMap({
      map: 'world_mill',
      scaleColors: ['#C8EEFF', '#0071A4'],
      normalizeFunction: 'polynomial',
      hoverOpacity: 0.7,
      hoverColor: false,
      markerStyle: {
              initial: {
                      fill: '#F8E23B',
                      stroke: '#383f47'
              }
      },
      backgroundColor: '#383f47',
      markers: reprData,
      onRegionClick: function(e, code){
	    $("#accordion").accordion("option", "active", countries[code].idx);
      },
      onMarkerTipShow: function(e, el, code){
            el.html('Go to ' + reprData[code].name + '</br>' + document.getElementById(code).href);
      },
      onMarkerClick: function(e, code){
            window.location = document.getElementById(code).href;
      }
  });
});

$(function() {
 $("#accordion").accordion({
  heightStyle: "content",
  collapsible: true,
  active: false
 });
});

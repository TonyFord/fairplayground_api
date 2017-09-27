<?php

if( $_POST["JSN"] != null ){
  $fp=fopen("../rawdata/map_entries/MB_".date("Y_m_d_h_i",time()).".json","w+");
  fwrite($fp,$_POST["JSN"]);
  fclose($fp);

  $fp=fopen("../rawdata/MB_map_data.json","w+");
  fwrite($fp,$_POST["JSN"]);
  fclose($fp);

  $fp=fopen("../rawdata/MB_map.json","w+");
  fwrite($fp,"{ \"type\": \"FeatureCollection\", \"features\": [".$_POST["JSN"]."] }");
  fclose($fp);

}

 ?>
 <html lang="en">
   <head>
     <title>FairCoin / FairCoop - playground
     </title>
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description" content="">
     <meta name="author" content="">
     <meta name="robots" content="noindex, nofollow">
     <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet">
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
     <script
          src="https://code.jquery.com/jquery-1.11.2.min.js"
          integrity="sha256-Ls0pXSlb7AYs7evhd+VLnWsZ/AqEHcXBeMZUycz/CcA="
          crossorigin="anonymous"></script>
   </head>
   <body>
  <form method="POST">
    <input type="hidden" id="JSN" name="JSN" value="">
    <div class="container">
      <div class="row">
        <div class="col">
          <h2>FairCoop Map Data Editor for Members</h2>
        </div>
      </div>
      <div class="form-group row">
        <label for="entry_id" class="col-sm-2 col-form-label">id ( select for edit )</label>
        <div class="col-sm-10">
          <select id="entry_id" name="entry_id" onchange="get_entry(this.value)" class="form-control">
          </select>
        </div>
      </div>

      <div class="form-group row">
        <label for="e_id" class="col-sm-2 col-form-label">id*</label>
        <div class="col-sm-10">
          <input id="e_id" name="e_id" type="text" class="form-control" placeholder="localnode_nickname">
        </div>
      </div>

      <div class="form-group row">
        <label for="e_name" class="col-sm-2 col-form-label">name*</label>
        <div class="col-sm-10">
          <input id="e_name" name="e_name" type="text" class="form-control" placeholder="Member - mymapnickname">
        </div>
      </div>

      <div class="form-group row">
        <label for="e_description" class="col-sm-2 col-form-label">description (short)</label>
        <div class="col-sm-10">
          <input id="e_description" name="e_description" type="text" class="form-control" placeholder="">
        </div>
      </div>

      <div class="form-group row">
        <label for="e_contact" class="col-sm-2 col-form-label">contact*</label>
        <div class="col-sm-10">
          <input id="e_contact" name="e_contact" type="text" class="form-control" placeholder="@telegramnickname">
        </div>
      </div>

      <div class="form-group row">
        <label for="e_coordinate_lat" class="col-sm-2 col-form-label">Coordinate Latitude*</label>
        <div class="col-sm-10">
          <input id="e_coordinate_lat" name="e_coordinate_lat" type="text" class="form-control" placeholder="23.72909545898438"><br>
          <a href="https://www.latlong.net/" target="longlat">https://www.latlong.net/</a>
        </div>
      </div>

      <div class="form-group row">
        <label for="e_coordinate_lon" class="col-sm-2 col-form-label">Coordinate Longitude*</label>
        <div class="col-sm-10">
          <input id="e_coordinate_lon" name="e_coordinate_lon" type="text" class="form-control" placeholder="37.98100996893789">
        </div>
      </div>

      <div class="form-group row">
        <label for="e_color" class="col-sm-2 col-form-label">Map sign color</label>
        <div class="col-sm-10">
          <input id="e_color" name="e_icon" type="text" class="form-control"><br>
          ( no entry = default color )<br>use color names <a href="http://htmlcolorcodes.com/color-names/" target="colors">http://htmlcolorcodes.com/color-names/</a>
        </div>
      </div>

      <div class="form-group row">
        <label for="e_fairnetwork_profile" class="col-sm-2 col-form-label">FairNetwork Profile</label>
        <div class="col-sm-10">
          <input id="e_fairnetwork_profile" name="e_fairnetwork_profile" type="text" class="form-control">
        </div>
      </div>

      <div class="form-group row">
        <label for="e_OCP_project_profile" class="col-sm-2 col-form-label">OCP Project Profile</label>
        <div class="col-sm-10">
          <input id="e_OCP_project_profile" name="e_OCP_project_profile" type="text" class="form-control">
        </div>
      </div>

      <div class="form-group row">
        <label for="e_member_since" class="col-sm-2 col-form-label">member_since</label>
        <div class="col-sm-10">
          <input id="e_member_since" name="e_member_since" type="text" class="form-control" placeholder="YYYY-MM-DD">
        </div>
      </div>

      <div class="form-group row">
        <label for="e_involved" class="col-sm-2 col-form-label">Involved in ( use tags )</label>
        <div class="col-sm-10">
          <input id="e_involved" name="e_involved" type="text" class="form-control" placeholder="#Exchange_Office #FairPay #FairMarket #FreedomCoop #Stargate #Fairspot #NPO">
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-2">
          Existing tags
        </div>
        <div class="col-sm-10">
          <span id="involved_tags"></span>
        </div>
      </div>

      <div class="form-group row">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-10">
          <button type="button" class="btn btn-primary" onclick="get_entry( $('#entry_id').val() )">RELOAD</button>
          <button type="button" class="btn btn-primary" onclick="save_entry( $('#entry_id').val() )">SAVE</button>
        </div>
      </div>


    </div>
  </form>




    <script>
    $( document ).ready(function() {

      get_entries(true);

    });

    var JSN=[];

    function get_entries(load){
      if( load == true ) JSN=json_load("../rawdata/MB_map_data.json");
      var L=[];
      var tags=[];
      JSN.forEach(
        function( v,i ){
          L.push(v.properties.id);
          var T=v.properties.involved_tags.split(/\#/g);
          T.shift();
          T.forEach(
            function( w, j){
              if( tags.indexOf( w.trim() ) == -1 ) tags.push( w.trim() );
            }
          );
        }
      );

      tags.forEach(
        function( w,i ){
          $("#involved_tags").append( "#" + w + " " );
        }
      );

      $("#entry_id").html("<option value='NEW'>add new</option>");
      L.sort();
      L.forEach(
        function( v,i ){
          $("#entry_id").append( "<option value='" + v + "'>" + v + "</option>");
        }
      )

    }


    function get_entry(entry_id){

      $("#e_id").val("");
      $("#e_name").val("");
      $("#e_description").val("");
      $("#e_contact").val("");
      $("#e_coordinate_lon").val("");
      $("#e_coordinate_lat").val("");
      $("#e_color").val("");
      $("#e_fairnetwork_profile").val("");
      $("#e_OCP_project_profile").val("");
      $("#e_member_since").val("");
      $("#e_involved").val("");

      if( entry_id == "NEW"){

      } else {
        JSN.forEach(
          function( v,i ){
            if( v.properties.id == entry_id ){
              $("#e_id").val( v.properties.id );
              $("#e_name").val( v.properties.name );
              $("#e_description").val( v.properties.description );
              $("#e_contact").val( v.properties.contact );
              $("#e_coordinate_lon").val( v.geometry.coordinates[0] );
              $("#e_coordinate_lat").val( v.geometry.coordinates[1] );
              $("#e_color").val( v.properties._storage_options.color );
              $("#e_fairnetwork_profile").val( v.properties.fairnetwork_profile );
              $("#e_OCP_project_profile").val( v.properties.OCP_project_profile );
              $("#e_member_since").val( v.properties.member_since );
              $("#e_involved").val( v.properties.involved_tags );
            }
          }
        );
      }
    }

    function save_entry(entry_id){

      // check id if its unique
      if( entry_id != $("#e_id").val() || entry_id == "NEW" ){
        JSN.forEach(
          function( v,i ){
            if( v.properties.id == $("#e_id").val() ){
              alert("ID not unique! Please enter another ID!");
              return;
            }
          }
        );
      }

      // check if new dataset and add
      if( entry_id == "NEW"){
        var J={"type":"Feature","properties":{"_storage_options":{"color":""},"id":"NEW","name":"","description":"","contact":"","fairnetwork_profile":"","OCP_project_profile":"","member_since":"","involved_tags":""},"geometry":{"type":"Point","coordinates":[0,0]}}
        JSN.push(J);
        JSN[JSN.length-1].properties.id=$("#e_id").val();
        entry_id = $("#e_id").val();

        get_entries(false);
        $("#entry_id").val( $("#e_id").val() );
      }

      // edit json data
      JSN.forEach(
        function( v,i ){
          if( v.properties.id == entry_id ){
            JSN[i].properties.id = $("#e_id").val();
            JSN[i].properties.name = $("#e_name").val();
            JSN[i].properties.description = $("#e_description").val();
            JSN[i].properties.contact  = $("#e_contact").val();
            JSN[i].geometry.coordinates[0] = parseFloat( $("#e_coordinate_lon").val() );
            JSN[i].geometry.coordinates[1] = parseFloat( $("#e_coordinate_lat").val() );
            JSN[i].properties._storage_options.color = $("#e_color").val();
            JSN[i].properties.fairnetwork_profile = $("#e_fairnetwork_profile").val();
            JSN[i].properties.OCP_project_profile = $("#e_OCP_project_profile").val();
            JSN[i].properties.member_since = $("#e_member_since").val();
            JSN[i].properties.involved_tags = $("#e_involved").val();
          }
        }
      );
      $("#JSN").val(JSON.stringify(JSN));
      $("FORM")[0].submit();
    }


    function json_load( url ){
      var json = null;
      $.ajax({
          'async': false,
          'global': false,
          'cache': false,
          'url': url,
          'dataType': "json",
          'success': function (data) {
              json = data;
          }
      });
      return json;
    }

    </script>

   </body>
</html>

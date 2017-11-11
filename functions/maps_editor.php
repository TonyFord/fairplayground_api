<?php

include("admin.php");

if( $_POST["J"] != null ){

  $J=json_decode($_POST["J"],true);

  $fp=fopen("../rawdata/map_entries/changes/".date("Y_m_d_h_i",time())."_".$J["properties"]["id"].".json","w+");
  fwrite($fp,$_POST["J"]);
  fclose($fp);

} else if( $_POST["approve"] != null && $admin == true ) {

  $fn="../rawdata/map_entries/changes/".$_POST["approve"];
  $fp=fopen($fn,"r");
  $j=fread( $fp, filesize( $fn ));
  $J=json_decode( $j,true);
  fclose($fp);

  $fn="../rawdata/map_entries/FCLN/".$J["properties"]["id"].".json";
  $fp=fopen($fn,"w+");
  fwrite($fp,$j);
  fclose($fp);

  unlink( "../rawdata/map_entries/changes/".$_POST["approve"]);

  include("get_localnodes.php");

} else if( $_POST["cancel"] != null ) {
  unlink( "../rawdata/map_entries/changes/".$_POST["cancel"]);
}

$F = glob("../rawdata/map_entries/changes/*.json");

$fl="";

foreach( $F as $f ){
  $ff=preg_split("/\//",$f);
  $fl.="<p><a href='".$f."' target='request'>".$ff[4]."</a> <button type='button' class='btn btn-sm btn-info' onclick='changes(this)'>&nbsp;changes&nbsp;</button> <button type='button' class='btn btn-sm btn-success' onclick='approve_it(this)'>&nbsp;approve&nbsp;</button> <button type='button' class='btn btn-sm btn-danger' onclick='cancel_it(this)'>&nbsp;cancel&nbsp;</button></p>";
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
          <style>
          .openrequests {
            font-size:0.8em;
          }
          .openrequests P {
            margin:0.1em !important;
          }
          .openrequests BUTTON {
            padding:0em !important;
          }
          </style>
   </head>
   <body>
  <form method="POST">
    <input type="hidden" id="J" name="J" value="">
    <input type="hidden" id="approve" name="approve" value="">
    <input type="hidden" id="cancel" name="cancel" value="">
    <div class="container">
      <div class="row">
        <label for="entry_id" class="col-sm-2 col-form-label col-form-label-sm">open requests</label>
        <div class="col openrequests">
          <?php echo $fl; ?>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <h2>FairCoop Map Data Editor</h2>
        </div>
      </div>

      <div class="form-group row">
        <label for="entry_id" class="col-sm-2 col-form-label col-form-label-sm">id ( select for edit )</label>
        <div class="col-sm-10">
          <select id="entry_id" name="entry_id" onchange="get_entry(this.value)" class="form-control form-control-sm">
          </select>
        </div>
      </div>

      <div class="form-group row">
        <label for="e_id" class="col-sm-2 col-form-label col-form-label-sm">id*</label>
        <div class="col-sm-10">
          <input id="e_id" name="e_id" type="text" class="form-control foradd form-control-sm" placeholder="FCLN.MYID" readonly>
        </div>
      </div>

      <div class="form-group row">
        <label for="e_name" class="col-sm-2 col-form-label col-form-label-sm">name*</label>
        <div class="col-sm-10">
          <input id="e_name" name="e_name" type="text" class="form-control form-control-sm" placeholder="Athens">
        </div>
      </div>

      <div class="form-group row foredit">
        <label for="e_description" class="col-sm-2 col-form-label col-form-label-sm">description (short)</label>
        <div class="col-sm-10">
          <input id="e_description" name="e_description" type="text" class="form-control form-control-sm" placeholder="">
        </div>
      </div>

      <div class="form-group row foredit">
        <label for="e_contact" class="col-sm-2 col-form-label col-form-label-sm">contact*</label>
        <div class="col-sm-10">
          <input id="e_contact" name="e_contact" type="text" class="form-control form-control-sm" placeholder="athens@getfaircoin.net">
        </div>
      </div>

      <div class="form-group row foredit">
        <label for="e_coordinate_lat" class="col-sm-2 col-form-label col-form-label-sm">Coordinate Latitude*</label>
        <div class="col-sm-10">
          <input id="e_coordinate_lat" name="e_coordinate_lat" type="text" class="form-control form-control-sm" placeholder="23.72909545898438"><br>
          <a href="https://www.latlong.net/" target="longlat">https://www.latlong.net/</a>
        </div>
      </div>

      <div class="form-group row foredit">
        <label for="e_coordinate_lon" class="col-sm-2 col-form-label col-form-label-sm">Coordinate Longitude*</label>
        <div class="col-sm-10">
          <input id="e_coordinate_lon" name="e_coordinate_lon" type="text" class="form-control form-control-sm" placeholder="37.98100996893789">
        </div>
      </div>

      <div class="form-group row foredit">
        <label for="e_icon" class="col-sm-2 col-form-label col-form-label-sm">Map Icon ( URL )</label>
        <div class="col-sm-10">
          <input id="e_icon" name="e_icon" type="text" class="form-control form-control-sm" placeholder="https://upload.wikimedia.org/wikipedia/commons/5/5c/Flag_of_Greece.svg">
        </div>
      </div>

      <div class="form-group row foredit">
        <label for="e_color" class="col-sm-2 col-form-label col-form-label-sm">Map sign color</label>
        <div class="col-sm-10">
          <input id="e_color" name="e_icon" type="text" class="form-control form-control-sm"><br>
          ( no entry = default color )<br>use color names <a href="http://htmlcolorcodes.com/color-names/" target="colors">http://htmlcolorcodes.com/color-names/</a>
        </div>
      </div>

      <div class="form-group row foredit">
        <label for="e_fairnetwork_profile" class="col-sm-2 col-form-label col-form-label-sm">FairNetwork Profile</label>
        <div class="col-sm-10">
          <input id="e_fairnetwork_profile" name="e_fairnetwork_profile" type="text" class="form-control form-control-sm">
        </div>
      </div>

      <div class="form-group row foredit">
        <label for="e_OCP_project_profile" class="col-sm-2 col-form-label col-form-label-sm">OCP Project Profile</label>
        <div class="col-sm-10">
          <input id="e_OCP_project_profile" name="e_OCP_project_profile" type="text" class="form-control form-control-sm">
        </div>
      </div>

      <div class="form-group row foredit">
        <label for="e_participants" class="col-sm-2 col-form-label col-form-label-sm">Participants</label>
        <div class="col-sm-10">
          <input id="e_participants" name="e_participants" type="text" class="form-control form-control-sm">
        </div>
      </div>

      <div class="form-group row foredit">
        <label for="e_establishing_assembly" class="col-sm-2 col-form-label col-form-label-sm">Establishing Assembly</label>
        <div class="col-sm-10">
          <input id="e_establishing_assembly" name="e_establishing_assembly" type="text" class="form-control form-control-sm" placeholder="YYYY-MM-DD">
        </div>
      </div>

      <div class="form-group row foredit">
        <label for="e_assembly_notes" class="col-sm-2 col-form-label col-form-label-sm">Assembly Notes</label>
        <div class="col-sm-10">
          <input id="e_assembly_notes" name="e_assembly_notes" type="text" class="form-control form-control-sm">
        </div>
      </div>

      <div class="form-group row foredit">
        <label for="e_involved" class="col-sm-2 col-form-label col-form-label-sm">Involved in projects ( use tags )</label>
        <div class="col-sm-10">
          <input id="e_involved" name="e_involved" type="text" class="form-control form-control-sm" placeholder="#Exchange_Office #FairPay #FairMarket #FreedomCoop #Stargate #Fairspot #NPO">
        </div>
      </div>
      <div class="form-group row foredit">
        <div class="col-sm-2">
          Existing tags
        </div>
        <div class="col-sm-10">
          <span id="projects_involved"></span>
        </div>
      </div>

      <div class="form-group row">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-10">
          <button type="button" class="btn btn-primary requestbtn" onclick="save_entry( $('#entry_id').val() )">SAVE</button>
        </div>
      </div>


    </div>
  </form>




    <script>
    $( document ).ready(function() {

      get_entries(true);
      $(".foredit").toggleClass("d-none",true);
      $(".foradd").attr("readonly",false);
      $(".requestbtn").text("REQUEST NEW ID");

    });

    var JSN=[];

    function changes(obj){
      var J=json_load("../rawdata/map_entries/changes/" + $( $(obj).parent().children()[0] ).text() );
      var t="";
      JSN.features[0].forEach(
        function( v,i ){
          if( v.properties.id == J.properties.id ){
            $.each(J.properties, function(index, value) {
              if( v.properties[index] != value && typeof(value) != "object" ){
                t+="[" + index + "]\nOLD: " + v.properties[index] + "\nNEW: " + value + "\n\n";
              }
            });
            $.each(J.properties._storage_options, function(index, value) {
              if( v.properties._storage_options[index] != value && typeof(value) != "object" ){
                t+="[" + index + "]\nOLD: " + v.properties._storage_options[index] + "\nNEW: " + value + "\n\n";
              }
            });
          }
        }
      );
      if( t != "" ){
        alert( t );
      }

    }

    function approve_it(obj){
      if( <? if($admin){ echo "true"; } else { echo "false"; } ?> ){
        var y = confirm( $( $(obj).parent().children()[0] ).text() + "\n\nAre you sure to APPROVE this item?" );
        if( y == true ){
          $("#approve").val( $( $(obj).parent().children()[0] ).text() );
          $("FORM")[0].submit();
        }
      } else {
        alert("Contact map admin @TonyFord");
      }
    }

    function cancel_it(obj){
      var y = confirm( $( $(obj).parent().children()[0] ).text() + "\n\nAre you sure to CANCEL this item?" );
      if( y == true ){
        $("#cancel").val( $( $(obj).parent().children()[0] ).text() );
        $("FORM")[0].submit();
      }
    }

    function get_entries(load){
      if( load == true ) JSN=json_load("../rawdata/FCLN.geo.json");
      var L=[];
      var tags=[];
      JSN.features[0].forEach(
        function( v,i ){
          L.push(v.properties.id);
          var T=v.properties.projects_involved.split(/\#/g);
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
          $("#projects_involved").append( "#" + w + " " );
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
      $("#e_icon").val("");
      $("#e_color").val("");
      $("#e_fairnetwork_profile").val("");
      $("#e_OCP_project_profile").val("");
      $("#e_participants").val("");
      $("#e_establishing_assembly").val("");
      $("#e_assembly_notes").val("");
      $("#e_involved").val("");

      if( entry_id == "NEW"){
        $(".foredit").toggleClass("d-none",true);
        $(".foradd").attr("readonly",false);
        $(".requestbtn").text("REQUEST NEW ID");
      } else {
        JSN.features[0].forEach(
          function( v,i ){
            if( v.properties.id == entry_id ){
              $(".foredit").toggleClass("d-none",false);
              $(".foradd").attr("readonly",true);
              $(".requestbtn").text("REQUEST CHANGES");
              $("#e_id").val( v.properties.id );
              $("#e_name").val( v.properties.name );
              $("#e_description").val( v.properties.description );
              $("#e_contact").val( v.properties.contact );
              $("#e_coordinate_lon").val( v.geometry.coordinates[0] );
              $("#e_coordinate_lat").val( v.geometry.coordinates[1] );
              $("#e_icon").val( v.properties._storage_options.iconUrl );
              $("#e_color").val( v.properties._storage_options.color );
              $("#e_fairnetwork_profile").val( v.properties.fairnetwork_profile );
              $("#e_OCP_project_profile").val( v.properties.OCP_project_profile );
              $("#e_participants").val( v.properties.participants );
              $("#e_establishing_assembly").val( v.properties.establishing_assembly );
              $("#e_assembly_notes").val( v.properties.assembly_notes );
              $("#e_involved").val( v.properties.projects_involved );
            }
          }
        );
      }
    }

    function save_entry(entry_id){

      var flag=true;

      // check id if its unique
      if( entry_id != $("#e_id").val() || entry_id == "NEW" ){
        JSN.features[0].forEach(
          function( v,i ){
            if( v.properties.id == $("#e_id").val() ){
              alert("ID not unique! Please enter another ID!");
              flag=false;
            }
          }
        );
      }


      // add or edit json data

      if( flag == true ){

        var J={"type":"Feature","properties":{"_storage_options":{"iconUrl":"","color":""},"id":"","name":"","description":"","contact":"","fairnetwork_profile":"","OCP_project_profile":"","participants":"","establishing_assembly":"","assembly_notes":"","projects_involved":"","icon":""},"geometry":{"type":"Point","coordinates":[0,0]}}

        if( $(".foradd").attr("readonly") == undefined ){
          J.properties.id = $("#e_id").val();
          J.properties.name = $("#e_name").val();
          J.properties.description = "New Local Node, further details coming soon!";
        } else {
          J.properties.id = $("#e_id").val();
          J.properties.name = $("#e_name").val();
          J.properties.description = $("#e_description").val();
          J.properties.contact  = $("#e_contact").val();
          J.geometry.coordinates[0] = parseFloat( $("#e_coordinate_lon").val() );
          J.geometry.coordinates[1] = parseFloat( $("#e_coordinate_lat").val() );
          J.properties._storage_options.iconUrl = $("#e_icon").val();
          J.properties._storage_options.color = $("#e_color").val();
          J.properties.fairnetwork_profile = $("#e_fairnetwork_profile").val();
          J.properties.OCP_project_profile = $("#e_OCP_project_profile").val();
          J.properties.participants = $("#e_participants").val();
          J.properties.establishing_assembly = $("#e_establishing_assembly").val();
          J.properties.assembly_notes = $("#e_assembly_notes").val();
          J.properties.projects_involved = $("#e_involved").val();
          J.properties.icon = $("#e_icon").val();
        }

        $("#J").val(JSON.stringify(J));
        $("FORM")[0].submit();

      }

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

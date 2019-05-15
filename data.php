<?php

  session_name("batidentification");
  session_set_cookie_params(0, '/', '.batidentification.com');
  session_start();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>BatIdentification</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/ol.css" type="text/css">
    <link rel="stylesheet" href="css/ol.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="js/ol.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <?php
      include("includes/navigation.php");
    ?>
    <div class="content container-fluid data-container">

      <div class="row">

        <div class="col-md-9">

          <div id="map" class="map">
            <div id="ol-popup"></div>
          </div>
          <script src="js/DataDisplay.js"></script>

        </div>

        <div class="col-md-3 side-bar">

            <h3> Filter Bat Calls </h3>

            <h4> Species </h4>

            <div id="bat-species">

              <script>

                var species = ["common_pipistrelle", "nathusius_pipistrelle", "soprano_pipistrelle", "myotis", "leislers_bat", "brown_long_eared", "lesser_Horseshoe", "Unknown"];

                function toTitleCase(str) {
                    return str.replace(
                        /\w\S*/g,
                        function(txt) {
                            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                        }
                    );
                }

                $(document).ready(function(){

                    $.each(species, function(index, value){

                      if(index % 2 == 0){
                        $("#bat-species > table").append("<tr></tr>");
                      }

                      var name = toTitleCase(value.replace(new RegExp("_", 'g'), " "));
                      var trIndex = Math.floor(index/2) + 1;

                      $(`#bat-species > table tr:nth-of-type(${trIndex})`).append(`
                        <td><input checked type="checkbox" id="${value}" name="${value}"></td>
                        <td><label for="${value}">${name}</label></td>
                      `)

                    })

                });

              </script>

              <table></table>

            </div>

            <div id="date">

              <h4> Date </h4>

              <input type="text" class="form-control" id="date_range" name="date_range">

              <script type="text/javascript">

                $(document).ready(function() {

                  $('#date_range').daterangepicker({
                    opens: 'left',
                    startDate: "2018-07-18",
                    endDate: new Date(),
                    locale: {
                        "format": "YYYY-MM-DD",
                        "cancelLabel": "Reset"
                    }
                  });

                  $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                      $(this).data('daterangepicker').setStartDate("2018-07-18");
                      $(this).data('daterangepicker').setEndDate(new Date());
                  });

                });


              </script>

            </div>

            <button class="btn" id="submit-btn">Filter</button>

        </div>

      </div>

    </div>
    <?php
      include("includes/footer.php");
    ?>
  </body>
</html>

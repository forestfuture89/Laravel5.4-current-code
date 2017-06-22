jQuery(document).ready(function ($) {
    $(document).click(function () {
        $(".notification-block").removeClass("show");
    })
    $("#toggle_notification").click(function (e) {
        e.stopPropagation();
        $(".notification-block").toggleClass("show");
        return false;
    });
    $(".datepicker").pickadate({
      onSet: function(context) {
        if (!('clear' in context) && !('highlight' in context) ) {
          var invoker = $(this.component)[0].$node;
          $(this)[0].close();
          $(invoker).parent().find('.timepicker').on('click', function(){
            $(this).parent().find('.clockpicker-button').off('click.addTime');
            $(this).parent().find('.clockpicker-button').on('click.addTime', function(){
              var datefield = $(this).closest('.md-form').find('.datepicker').val();
              var timefield = $(this).closest('.md-form').find('.timepicker').val();
              var compose = datefield+' '+timefield;
              $(this).closest('.md-form').find('.datepicker').val(compose);
              $(this).off('click.addTime')
            })
          })
          $(invoker).parent().find('.timepicker').click();
        }
      },
    });
    $('.timepicker').pickatime({
        twelvehour: true
    });
});

window.onload = function () {

    // secret accessToken: 'sk.eyJ1Ijoid2ViZGV2c21hcnQiLCJhIjoiY2l6eWdrdW1hMDA0ZjMybmx0bXFyZGNqaSJ9.KAKx5GTs8hY-RpXOVkRFVw'
    $('.mdb-select').material_select();
    // only on charter page
    if ($("#charter_map").length) {

        // public accessToken
        mapboxgl.accessToken = 'pk.eyJ1Ijoid2ViZGV2c21hcnQiLCJhIjoiY2l6eWdoaXI5MDJyaDJ3azcxd2plam9pZCJ9.Xsk1jJoKVXrq8YTAbgOiXQ';
        var center = [-90.0659, 25.3043];
        var zoom = 4.8;
        var map = new mapboxgl.Map({
            container: 'charter_map',
            style: 'mapbox://styles/mapbox/light-v9',
            center: center,
            zoom: zoom
        });
        map.addControl(new mapboxgl.NavigationControl());
        var geojson = {
            "type": "FeatureCollection",
            "features": [
                {
                    "type": "Feature",
                    "properties": {
                        "photo": "../../resources/assets/images/vessel_1.jpg",
                        "type": "PSV",
                        "weight": "500 LT",
                        "deck": "1,000 ft<sup>2</sup>",
                        "pax": "12",
                        "price": "$/unit",
                        "iconSize": [46, 47]
                    },
                    "geometry": {
                        "type": "Point",
                        "coordinates": [
                            -91.43633,
                            29.31166
                        ]
                    }
                },
                {
                    "type": "Feature",
                    "properties": {
                        "photo": "../../resources/assets/images/vessel_2.jpg",
                        "type": "PSV",
                        "weight": "1,000 LT",
                        "deck": "5,000 ft<sup>2</sup>",
                        "pax": "10",
                        "price": "$/unit",
                        "iconSize": [46, 47]
                    },
                    "geometry": {
                        "type": "Point",
                        "coordinates": [
                            -93.68117,
                            29.42767
                        ]
                    }
                },
                {
                    "type": "Feature",
                    "properties": {
                        "photo": "../../resources/assets/images/vessel_3.jpg",
                        "type": "FSV",
                        "weight": "300 LT",
                        "deck": "400 ft<sup>2</sup>",
                        "pax": "25",
                        "price": "$/unit",
                        "iconSize": [46, 47]
                    },
                    "geometry": {
                        "type": "Point",
                        "coordinates": [
                            -91.70039,
                            27.91334
                        ]
                    }
                },
                {
                    "type": "Feature",
                    "properties": {
                        "photo": "../../resources/assets/images/vessel_2.jpg",
                        "type": "PSV",
                        "weight": "1,000 LT",
                        "deck": "5,000 ft<sup>2</sup>",
                        "pax": "10",
                        "price": "$/unit",
                        "iconSize": [46, 47]
                    },
                    "geometry": {
                        "type": "Point",
                        "coordinates": [
                            -94.42178,
                            27.82363
                        ]
                    }
                },
                {
                    "type": "Feature",
                    "properties": {
                        "photo": "../../resources/assets/images/vessel_2.jpg",
                        "type": "PSV",
                        "weight": "1,000 LT",
                        "deck": "5,000 ft<sup>2</sup>",
                        "pax": "10",
                        "price": "$/unit",
                        "iconSize": [46, 47]
                    },
                    "geometry": {
                        "type": "Point",
                        "coordinates": [
                            -90.20546,
                            29.14733
                        ]
                    }
                }
            ]
        };


        geojson.features.forEach(function (marker) {
            // create a DOM element for the marker
            var el = document.createElement('div');
            el.className = 'marker';
            el.style.backgroundImage = 'url(../../resources/assets/images/marker-vessel.png)';
            el.style.width = marker.properties.iconSize[0] + 'px';
            el.style.height = marker.properties.iconSize[1] + 'px';

            /**  charter popup Dom Start  **/
                // charter container
            var popupDiv = window.document.createElement('div');
            popupDiv.className = 'charter-detail';

            // charter container split top and bottom
            // -top: photoDiv
            var photoDiv = window.document.createElement('div');
            photoDiv.className = 'photo';


            // photoDiv contains charter image
            var ch_img = window.document.createElement('img');
            ch_img.src = marker.properties.photo;

            photoDiv.appendChild(ch_img);
            popupDiv.appendChild(photoDiv);

            // -bottom: specDiv
            var specDiv = window.document.createElement('div');
            specDiv.className = 'spec';

            // specDiv split multiple specSubDiv
            var specSubDiv_type = window.document.createElement('div');

            // --speed title
            var spec_t_type = window.document.createElement('span');
            spec_t_type.textContent = 'Type:';
            specSubDiv_type.appendChild(spec_t_type);
            // --type content
            var spec_c_type = window.document.createElement('span');
            spec_c_type.textContent = marker.properties.type;
            specSubDiv_type.appendChild(spec_c_type);
            specDiv.appendChild(specSubDiv_type);

            // specDiv split multiple specSubDiv
            var specSubDiv_weight = window.document.createElement('div');

            // --weight title
            var spec_t_weight = window.document.createElement('span');
            spec_t_weight.textContent = 'Weight:';
            specSubDiv_weight.appendChild(spec_t_weight);
            // --weight content
            var spec_c_weight = window.document.createElement('span');
            spec_c_weight.textContent = marker.properties.weight;
            specSubDiv_weight.appendChild(spec_c_weight);
            specDiv.appendChild(specSubDiv_weight);

            // specDiv split multiple specSubDiv
            var specSubDiv_deck = window.document.createElement('div');

            // --deck title
            var spec_t_deck = window.document.createElement('span');
            spec_t_deck.textContent = 'Deck Area:';
            specSubDiv_deck.appendChild(spec_t_deck);
            // --deck content
            var spec_c_deck = window.document.createElement('span');
            spec_c_deck.innerHTML = marker.properties.deck;
            specSubDiv_deck.appendChild(spec_c_deck);
            specDiv.appendChild(specSubDiv_deck);

            // specDiv split multiple specSubDiv
            var specSubDiv_pax = window.document.createElement('div');

            // --pax title
            var spec_t_pax = window.document.createElement('span');
            spec_t_pax.textContent = 'PAX:';
            specSubDiv_pax.appendChild(spec_t_pax);
            // --pax content
            var spec_c_pax = window.document.createElement('span');
            spec_c_pax.textContent = marker.properties.pax;
            specSubDiv_pax.appendChild(spec_c_pax);
            specDiv.appendChild(specSubDiv_pax);

            // specDiv split multiple specSubDiv
            var specSubDiv_price = window.document.createElement('div');

            // --price title
            var spec_t_price = window.document.createElement('span');
            spec_t_price.textContent = 'Price:';
            specSubDiv_price.appendChild(spec_t_price);
            // --price content
            var spec_c_price = window.document.createElement('span');
            spec_c_price.textContent = marker.properties.price;
            specSubDiv_price.appendChild(spec_c_price);
            specDiv.appendChild(specSubDiv_price);

            popupDiv.appendChild(specDiv);

            /**  charter popup Dom End  **/

            var popup = new mapboxgl.Popup()
                .setDOMContent(popupDiv)
                .setLngLat(marker.geometry.coordinates);

            // add marker to map
            new mapboxgl.Marker(el, {offset: [-marker.properties.iconSize[0] / 2, -marker.properties.iconSize[1] / 2]})
                .setLngLat(marker.geometry.coordinates)
                .setPopup(popup)
                .addTo(map);
        });


    }

}

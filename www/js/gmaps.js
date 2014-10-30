/*
 * Copyright 2010 Bohac Vaclav
 *
 * This library is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/*global google, document, $ */

/**
 * @preserve
 * @author    Vaclav Bohac <bohac.v@gmail.com>
 * @package   Gmaps Component
 * @license   GNU\GPLv3
 */


var GMAPS = (function () {
    'use strict';

    var has = function (obj, key) {
            return Object.prototype.hasOwnProperty.call(obj, key);
        },


        // Map helpers.

        helpers = {
            mapType: function (type) {
                if (!has(google.maps.MapTypeId, type)) {
                    throw { message: 'Unknown map type ' + type };
                }

                return google.maps.MapTypeId[type];
            },

            travelMode: function (name) {
                if (!has(google.maps.DirectionsTravelMode, name)) {
                    throw { message: 'Unknown travel mode ' + name };
                }

                return google.maps.DirectionsTravelMode[name];
            },

            createLatLng: function (coordinates) {
                return new google.maps.LatLng(coordinates.lat, coordinates.lng);
            },
			
			createMarkerImage: function(icon) {
				if(icon !== null) {
					return new google.maps.MarkerImage(icon.url,
						new google.maps.Size(icon.size.width, icon.size.height),
						new google.maps.Point(icon.origin.x, icon.origin.y),
						new google.maps.Point(icon.tip.x, icon.tip.y)
					);		
				} else {
					return undefined;
				}
			}
        },


        // Map services factory.

        factory = (function () {
            var memo = {};

            return {
                get: function (serviceName) {
                    if (has(memo, serviceName)) {
                        return memo[serviceName];
                    }

                    return (memo[serviceName] = new google.maps[serviceName]());
                }
            };
        }()),


        // Available services.

        services = {
            geocode: function (location, callback) {
                var request = { address: location },
                    geocoder = factory.get('Geocoder');

                geocoder.geocode(request, function (results, status) {

                    if (status === 'OK') {
                        callback(results);
                    }
                });
            }
        };


    return {
        init: function () {
            var self = this;

            this.markers = [];

            this.elem = document.getElementById('gmaps');

            this.$elem = $(this.elem);


            this.$elem.on('gmaps:ready', function () {
                self.prepareMarkers(self.addMarker);
            });

            this.prepareOptions(this.createMap);
        },

        prepareOptions: function (callback) {
            var self = this,

                options = this.$elem.data('options'),

                mapOptions = {
                    disableDefaultUI: !options.controls,
                    mapTypeId: helpers.mapType(options.type),
                    zoom: options.zoom
                },

                $elem = this.$elem;

            if (typeof options.center === 'string') {
                services.geocode(options.center, function (results) {
                    mapOptions.center = results[0].geometry.location;

                    callback.apply(self, [mapOptions]);
                });

            } else {
                mapOptions.center = helpers.createLatLng(options.center);

                callback.apply(self, [mapOptions]);
            }
        },

        createMap: function (options) {
            this.mapHandler = new google.maps.Map(this.elem, options);

            this.$elem.trigger('gmaps:ready');
        },

        prepareMarkers: function (callback) {
            var self = this, data = this.$elem.data('markers') || [];

            $.each(data, function (i, markerData) {
                var position = markerData.destination,
                    title = markerData.label,
					icon = markerData.icon;

                if (typeof position === 'string') {
                    services.geocode(position, function (results) {
                        var geocoded = results[0].geometry.location;

                        callback.apply(self, [{ position: geocoded, title: title, icon : helpers.createMarkerImage(icon) }]);
                    });

                } else {
                    callback.apply(self, [{ position: helpers.createLatLng(position), title: title, icon : helpers.createMarkerImage(icon) }]);
                }
            });
        },

        addMarker: function (options) {
            var marker = new google.maps.Marker(options);

            marker.setMap(this.mapHandler);

            this.markers.push(marker);
        }
    };
}());

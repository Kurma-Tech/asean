// map.addControl(new mapboxgl.FullscreenControl());
// Add the control to the map.
// map.addControl(
//   new MapboxGeocoder({
//     accessToken: mapboxgl.accessToken,
//     mapboxgl: mapboxgl
//   })
// );

map.addControl(new mapboxgl.NavigationControl());

// Create a default Marker and add it to the map.
const marker1 = new mapboxgl.Marker()
  .setLngLat([103.86672326058194, 1.3560165755331521])
  .addTo(map);

// Create a default Marker, colored black, rotated 45 degrees.
// const marker2 = new mapboxgl.Marker({ color: "black", rotation: 45 })
//   .setLngLat([106.84429415949182, -6.209365171242264])
//   .addTo(map);

// map.on("style.load", () => {
//   map.setFog({}); // Set the default atmosphere style
// });

// map.on('load', () => {
//   const layers = map.getStyle().layers;
//   // Find the index of the first symbol layer in the map style.
//   let firstSymbolId;
//   for (const layer of layers) {
//   if (layer.type === 'symbol') {
//   firstSymbolId = layer.id;
//   break;
//   }
//   }

//   map.addSource('urban-areas', {
//   'type': 'geojson',
//   'data': 'https://docs.mapbox.com/mapbox-gl-js/assets/ne_50m_urban_areas.geojson'
//   });
//   map.addLayer(
//   {
//   'id': 'urban-areas-fill',
//   'type': 'fill',
//   'source': 'urban-areas',
//   'layout': {},
//   'paint': {
//   'fill-color': '#f08',
//   'fill-opacity': 0.4
//   }
//   // This is the important part of this example: the addLayer
//   // method takes 2 arguments: the layer as an object, and a string
//   // representing another layer's name. If the other layer
//   // exists in the style already, the new layer will be positioned
//   // right before that layer in the stack, making it possible to put
//   // 'overlays' anywhere in the layer stack.
//   // Insert the layer beneath the first symbol layer.
//   },
//   firstSymbolId
//   );
//   });

// custom animated dot

const size = 150;

// This implements `StyleImageInterface`
// to draw a pulsing dot icon on the map.
const pulsingDot = {
  width: size,
  height: size,
  data: new Uint8Array(size * size * 4),

  // When the layer is added to the map,
  // get the rendering context for the map canvas.
  onAdd: function() {
    const canvas = document.createElement("canvas");
    canvas.width = this.width;
    canvas.height = this.height;
    this.context = canvas.getContext("2d");
  },

  // Call once before every frame where the icon will be used.
  render: function() {
    const duration = 1000;
    const t = performance.now() % duration / duration;

    const radius = size / 2 * 0.3;
    const outerRadius = size / 2 * 0.7 * t + radius;
    const context = this.context;

    // Draw the outer circle.
    context.clearRect(0, 0, this.width, this.height);
    context.beginPath();
    context.arc(this.width / 2, this.height / 2, outerRadius, 0, Math.PI * 2);
    context.fillStyle = `rgba(255, 200, 200, ${1 - t})`;
    context.fill();

    // Draw the inner circle.
    context.beginPath();
    context.arc(this.width / 2, this.height / 2, radius, 0, Math.PI * 2);
    context.fillStyle = "rgba(255, 100, 100, 1)";
    context.strokeStyle = "white";
    context.lineWidth = 2 + 4 * (1 - t);
    context.fill();
    context.stroke();

    // Update this image's data with data from the canvas.
    this.data = context.getImageData(0, 0, this.width, this.height).data;

    // Continuously repaint the map, resulting
    // in the smooth animation of the dot.
    map.triggerRepaint();

    // Return `true` to let the map know that the image was updated.
    return true;
  }
};

map.on("load", () => {
  map.addImage("pulsing-dot", pulsingDot, { pixelRatio: 2 });

  map.addSource("dot-point", {
    type: "geojson",
    data: {
      type: "FeatureCollection",
      features: [
        {
          type: "Feature",
          geometry: {
            type: "Point",
            coordinates: [124.43222167320181, 6.566942520282152] // icon position [lng, lat]
          }
        }
      ]
    }
  });
  map.addLayer({
    id: "layer-with-pulsing-dot",
    type: "symbol",
    source: "dot-point",
    layout: {
      "icon-image": "pulsing-dot"
    }
  });
});

// custom dot ends

// red dots

map.on("load", () => {
  map.addSource("national-park", {
    type: "geojson",
    data: {
      type: "FeatureCollection",
      features: [
        {
          type: "Feature",
          geometry: {
            type: "Polygon",
            coordinates: [
              [
                [94.90981183023646, 21.60153238269399],
                [95.50307347954842, 22.680188071998018],
                [96.4369112608728, 22.029911318275254],
                [96.4369112608728, 21.69343563661163],
                [95.7118136894915, 21.356172036795392],
                [95.11855204017955, 21.110400157503207]
              ]
            ]
          }
        },
        {
          type: "Feature",
          geometry: {
            type: "Point",
            coordinates: [95.15425760240666, 21.59195576473874]
          }
        },
        {
          type: "Feature",
          geometry: {
            type: "Point",
            coordinates: [95.34102515867151, 21.241657472531806]
          }
        },
        {
          type: "Feature",
          geometry: {
            type: "Point",
            coordinates: [95.73653292487948, 21.574077716347027]
          }
        }
      ]
    }
  });

  map.addLayer({
    id: "park-boundary",
    type: "fill",
    source: "national-park",
    paint: {
      "fill-color": "#888888",
      "fill-opacity": 0.4
    },
    filter: ["==", "$type", "Polygon"]
  });

  map.addLayer({
    id: "park-volcanoes",
    type: "circle",
    source: "national-park",
    paint: {
      "circle-radius": 6,
      "circle-color": "#B42222"
    },
    filter: ["==", "$type", "Point"]
  });
});

// red dots ends

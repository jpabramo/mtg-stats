var matchdata = {
  details: matchdetails,
  events: []
};

var num_players = matchdata.details.length;

layouts = {
  2: [
    () => {
      // Side by side
      seat0.classList.add("rotate_270");

      seat1.classList.add("rotate_270");
    },
    () => {
      // Opposite
      seat0.classList.add("rotate_180");
    }
  ],
  3: [() => {
    seat0.classList.add("rotate_270");
    seat0.style.gridRow = '1 / span 2';
    seat0.style.gridColumn = 1;

    seat1.classList.add("rotate_90");
    seat1.style.gridRow = '1 / span 2';
    seat1.style.gridColumn = 2;

    seat2.style.gridColumn = '1 / span 2';
    seat2.style.gridRow = 3;
  }],
  4: [
    () => {
      // Two sides
      seat0.classList.add("rotate_270");

      seat1.classList.add("rotate_270");

      seat2.style.gridColumn = 2;
      seat2.classList.add("rotate_90");

      seat3.style.gridColumn = 2;
      seat3.classList.add("rotate_90");
    },
    () => {
      // Four corners
      seat0.style.gridRow = '1 / span 2';
      seat0.classList.add("rotate_180");

      seat1.classList.add("rotate_270");
      seat1.style.gridRow = '2 / span 2';
      seat1.style.gridColumn = 1;

      seat2.classList.add("rotate_90");
      seat2.style.gridRow = '2 / span 2';
      seat2.style.gridColumn = 2;

      seat3.style.gridRow = '3 / span 2';
    }
  ],
  5: [
    () => {
      // Two and three
      seat0.classList.add("rotate_270");
      seat0.style.gridRow = '1 / span 3';
      seat0.style.gridColumn = 1;

      seat1.classList.add("rotate_270");
      seat1.style.gridRow = '4 / span 3';
      seat1.style.gridColumn = 1;
      
      seat2.classList.add("rotate_90");
      seat2.style.gridRow = '1 / span 2';
      seat2.style.gridColumn = 2;

      seat3.classList.add("rotate_90");
      seat3.style.gridRow = '3 / span 2';
      seat3.style.gridColumn = 2;

      seat4.classList.add("rotate_90");
      seat4.style.gridRow = '5 / span 2';
      seat4.style.gridColumn = 2;
    },
    () => {
      // Four and one
      seat0.classList.add("rotate_270");
      seat0.style.gridColumn = 1;
      seat0.style.gridRow = 1;

      seat1.classList.add("rotate_270");
      seat1.style.gridColumn = 1;
      seat1.style.gridRow = 2;

      seat2.classList.add("rotate_90");
      seat2.style.gridColumn = 2;
      seat2.style.gridRow = 1;

      seat3.classList.add("rotate_90");
      seat3.style.gridColumn = 2;
      seat3.style.gridRow = 2;

      seat4.style.gridRow = 3;
      seat4.style.gridColumn = '1 / span 2';
    }
  ]
};

function defaultLayout() {
  var seat;
  for(var t = 0; t < num_players; t++) {
    var gridx = t % 2 + 1;
    var gridy = t / 2 + 1;
    seat = $(`#seat${t}`);
    seat.style.gridRow = gridy;
    seat.style.gridColumn = gridx;
    
    if(t%2 == 0) seat.classList.add("rotate_270");
    else seat.classList.add("rotate_90");
  }

  if(num_players%2 == 1) {
    seat.classList.remove("rotate_270");
    seat.style.gridColumn = "1 / span 2";
  }
}

function applyLayout() {
  if(num_players > 5) {
    defaultLayout();
    return;
  }
  else if(layouts[num_players].length > 1) showLayoutPicker();
  else layouts[num_players][0]();
}

function showLayoutPicker() {
  playarea.classList.add('hidden');
  layout_picker.classList.remove('hidden');
}

function pickLayout(n) {
  layout_picker.classList.add('hidden');
  playarea.classList.remove('hidden');
  layouts[num_players][n]();
}

applyLayout();
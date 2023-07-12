var players = [];
var thumbnailBkp = [];

function choosePlayer(playerid) {
  players.push(playerid);

  var thumb = document.getElementById(`player_${playerid}`);
  thumb.classList.add("selected");
  thumb.onclick = () => removePlayer(playerid);

  go_btn.disabled = players.length < 2;
}

function removePlayer(playerid) {
  var ind = players.indexOf(playerid);
  players.splice(ind, 1);

  var thumb = document.getElementById(`player_${playerid}`);
  thumb.classList.remove("selected");
  thumb.onclick = () => choosePlayer(playerid);
  go_btn.disabled = players.length < 2;
}

function chooseTables() {
  location.search = `players=${players.join(',')}`;
}

function showDecklist(playerid) {
  currPlayer = playerid;
  
  oldScrollPos = [window.scrollX, window.scrollY];
  document.getElementById(`decklist_${playerid}`).classList.remove('hidden');
  table_menu.classList.add('hidden');

  callback = (e) => { if(e.code == 'Escape') hideDecklist(playerid) };
  document.addEventListener('keydown', callback);
}

function hideDecklist(playerid) {
  currPlayer = undefined;

  document.getElementById(`decklist_${playerid}`).classList.add('hidden');
  table_menu.classList.remove('hidden');

  document.removeEventListener('keydown', callback);

  window.scrollTo(oldScrollPos[0], oldScrollPos[1]);
}

function selectDeck(thumbnail, deckid) {
  var playerthumb = document.getElementById(`player_${currPlayer}`);
  playerthumb.classList.add("selected");
  playerthumb.onclick = () => { removeDeck(deckid) };
  var playertext = playerthumb.getElementsByTagName("p")[0];
  var playerimg = playerthumb.getElementsByTagName("img")[0];

  thumbnailBkp[deckid] = {
    name: playertext.innerText,
    src: playerimg.src,
    playerid: currPlayer
  };

  playertext.innerText = thumbnail.getElementsByTagName("p")[0].innerText;
  playerimg.src = thumbnail.getElementsByTagName("img")[0].src;

  var parent = playerthumb.parentElement.parentElement;
  var parentid = parent.id.replace("table_", "");
  matches[parentid].push(deckid);
  hideDecklist(currPlayer);

  var button = document.getElementById(`go_btn_${parentid}`);
  button.disabled = matches[parentid].length < match_sizes[parentid];
}

function removeDeck(deckid) {
  var parentid;
  var match;
  for(matchid in matches) {
    match = matches[matchid];
    if(match.indexOf(deckid) == -1) continue;
    parentid = matchid;
    break;
  }

  match.splice(match.indexOf(deckid), 1);

  var bkp = thumbnailBkp[deckid];
  var playerthumb = document.getElementById(`player_${bkp.playerid}`);
  var playertext = playerthumb.getElementsByTagName("p")[0];
  var playerimg = playerthumb.getElementsByTagName("img")[0];

  playerthumb.classList.remove("selected");
  playerthumb.onclick = () => { showDecklist(bkp.playerid) };
  
  playertext.innerText = bkp.name;
  playerimg.src = bkp.src;

  delete thumbnailBkp[deckid];
  
  var go_btn = document.getElementById(`go_btn_${parentid}`);
  go_btn.disabled = match.length < match_sizes[parentid];
}
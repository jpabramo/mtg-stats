var callback = undefined;
var currPlayer = undefined;
var thumbnailBkp = {};
var match = [];
var oldScrollPos = [window.scrollX, window.scrollY];

function showDecklist(playerid) {
  currPlayer = playerid;
  
  oldScrollPos = [window.scrollX, window.scrollY];
  document.getElementById(`decklist_${playerid}`).classList.remove('hidden');
  player_select.classList.add('hidden');

  callback = (e) => { if(e.code == 'Escape') hideDecklist(playerid); };
  document.addEventListener('keydown', callback);
}

function hideDecklist(playerid) {
  currPlayer = undefined;

  document.getElementById(`decklist_${playerid}`).classList.add('hidden');
  player_select.classList.remove('hidden');

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

  match.push(deckid);
  hideDecklist(currPlayer);

  if(window.go_btn != undefined) go_btn.disabled = match.length < 2;
  else if(window.tables_title != undefined) {

  }
}

function removeDeck(deckid) {
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
  
  go_btn.disabled = match.length < 2;
}

function startNewMatch() {
  if(match.length < 2) return;

  document.location = "/play/play.php?startMatch=" + match;
}
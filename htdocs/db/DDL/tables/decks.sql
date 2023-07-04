-- mtgstats.decks definition

CREATE TABLE `decks` (
  `deckid` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` int(11) NOT NULL,
  `commander` varchar(100) NOT NULL,
  `decklist` text DEFAULT NULL,
  PRIMARY KEY (`deckid`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4;
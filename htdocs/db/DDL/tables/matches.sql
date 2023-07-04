-- mtgstats.matches definition

CREATE TABLE `matches` (
  `matchid` int(11) NOT NULL,
  `deckid` int(11) NOT NULL,
  `matchtime` timestamp NOT NULL DEFAULT current_timestamp(),
  `winner` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`matchid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
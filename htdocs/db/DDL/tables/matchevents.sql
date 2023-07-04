-- mtgstats.matchevents definition

CREATE TABLE `matchevents` (
  `matchid` int(11) NOT NULL,
  `deckid` int(11) NOT NULL,
  `eventtime` timestamp NOT NULL DEFAULT current_timestamp(),
  `health` int(11) NOT NULL DEFAULT 40,
  `turn` int(11) NOT NULL,
  `eventtype` set('start','pass','loss','win') NOT NULL DEFAULT 'start',
  `monarch` tinyint(1) DEFAULT 0,
  `poison` int(10) unsigned DEFAULT 0,
  `experience` int(10) unsigned DEFAULT 0,
  `energy` int(10) unsigned DEFAULT 0,
  `initiative` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
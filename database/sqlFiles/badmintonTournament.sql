USE joutes;

-- ========================================== Data ==============================================

--
--  Insert Data in events
--

INSERT INTO joutes.events(NAME) VALUES ('joutes 2017');

--
--  Insert Data in sports
--

INSERT INTO sports(NAME, description) VALUES ('Badminton', 'En double');

--
--  Insert Data in courts
--

INSERT INTO courts(NAME, sport_id) VALUES ('Terrain A', 1),('Terrain B', 1),('Terrain C', 1),('Terrain D', 1);

--
--  Insert Data in tournaments
--

INSERT INTO tournaments(NAME, start_date, event_id, sport_id) VALUES ('Tournoi de Bad', '2017-06-11', 1, 1);

--
--  Insert Data in gameTypes
--

INSERT INTO gameTypes(gameTypeDescription) VALUES ('Modalités de jeu');

--
--  Insert Data in poolModes
--

INSERT INTO poolModes(modeDescription,planningAlgorithm) VALUES ('Matches simples',1),('Aller-retour',2),('Elimination directe',3);

--
--  Insert Data in participants
--

INSERT INTO participants(first_name,last_name) VALUES ("Ahmed","Casey"),("Chester","Day"),("Riley","Garrison"),("Duncan","Roy"),("Remedios","Black"),("Mark","Molina"),("Dana","Justice"),("Linus","Leon"),("Cairo","Farmer"),("Nyssa","Gallagher");
INSERT INTO participants(first_name,last_name) VALUES ("Allegra","Waller"),("Emery","Copeland"),("Illana","Mcgowan"),("Magee","Bauer"),("Patricia","Briggs"),("Samuel","Meyers"),("Nelle","Holcomb"),("Shay","David"),("Kai","Quinn"),("Brendan","Macdonald");
INSERT INTO participants(first_name,last_name) VALUES ("Justin","Jones"),("Erich","Shepherd"),("Joseph","Compton"),("Moses","Pope"),("Hedley","Thornton"),("Deborah","Wells"),("Kay","Ortega"),("Dorothy","Johnston"),("Irene","Alston"),("Doris","Baird");
INSERT INTO participants(first_name,last_name) VALUES ("Zorita","Ellis"),("Yen","Hale"),("Madison","Marshall"),("Angela","Perry"),("Michael","Woodard"),("Karyn","Riddle"),("Carol","Lang"),("Malik","Padilla"),("Maxine","Rowland"),("Halee","Larson");
INSERT INTO participants(first_name,last_name) VALUES ("Tatyana","Rosario"),("Latifah","Jenkins"),("Wynne","Rowland"),("Nola","Adkins"),("Nicole","Wilkerson"),("Sybil","Murray"),("Cadman","Evans"),("Xenos","Kramer"),("Illana","Riley"),("Evan","Logan");
INSERT INTO participants(first_name,last_name) VALUES ("Risa","Fuller"),("Jenette","Alvarado"),("Colorado","Moss"),("Bree","Velazquez"),("Madonna","Preston"),("Daria","Pearson"),("Uta","Hensley"),("Paul","Lambert"),("Declan","Ramirez"),("Davis","Mcleod");
INSERT INTO participants(first_name,last_name) VALUES ("Wanda","Sears"),("Melvin","Bowen"),("Lareina","Forbes"),("Dane","Holland"),("Norman","Mcleod"),("Blythe","Cruz"),("Jayme","Gill"),("Adele","Warren"),("Candace","Valenzuela"),("Judith","Blake");

--
--  Insert Data in teams
--

INSERT INTO teams(NAME,tournament_id) VALUES ('Badboys',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Super Nanas',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('CPVN Crew',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Magical Girls',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('OliverTwist',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Scarman',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Siomer',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Salsadi',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Monoster',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Picalo',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Dellit',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('SuperStar',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Masting',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Clafier',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Robert2Poche',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Alexandri',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('FanGirls',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Les Otakus',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Gamers',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Over2000',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Shinigami',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Rocketteurs',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Gilles & 2Sot-Vetage',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Maya Labeille',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Taupes ModL',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Les Pausés',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Absolute Frost',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Dark Side',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Btooom',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Stalgia',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Clattonia',1);
INSERT INTO teams(NAME,tournament_id) VALUES ('Warriors',1);

--
--  Insert Data in participant_team
--

INSERT INTO participant_team(participant_id, team_id, isCaptain) SELECT id, ROUND(id/2), (id%2) FROM participants LIMIT 64;

--
--  Insert Data in contenders
--

-- ================= stage 1 =====================

-- pools id 1-8
INSERT INTO pools (tournament_id, start_time, end_time, poolName, mode_id, gameType_id, poolSize, stage, isFinished)
VALUES
  (1, '08:00', '10:00', 'Badminton 1-1', 1, 1, 4, 1, 0), (1, '08:00', '10:00', 'Badminton 1-2', 1, 1, 4, 1, 0),
  (1, '08:00', '10:00', 'Badminton 1-3', 1, 1, 4, 1, 0), (1, '08:00', '10:00', 'Badminton 1-4', 1, 1, 4, 1, 0),
  (1, '08:00', '10:00', 'Badminton 1-5', 1, 1, 4, 1, 0), (1, '08:00', '10:00', 'Badminton 1-6', 1, 1, 4, 1, 0),
  (1, '08:00', '10:00', 'Badminton 1-7', 1, 1, 4, 1, 0), (1, '08:00', '10:00', 'Badminton 1-8', 1, 1, 4, 1, 0);

-- contenders are automatic: teams 1-4 -> pool 1, teams 5-8 -> pool 2, thus team X -> pool floor((X+3)/4)
INSERT INTO contenders(pool_id,team_id)
VALUES
  (1, 1),
  (1, 2),
  (1, 3),
  (1, 4),

  (2, 5),
  (2, 6),
  (2, 7),
  (2, 8),

  (3, 9),
  (3, 10),
  (3, 11),
  (3, 12),

  (4, 13),
  (4, 14),
  (4, 15),
  (4, 16),

  (5, 17),
  (5, 18),
  (5, 19),
  (5, 20),

  (6, 21),
  (6, 22),
  (6, 23),
  (6, 24),

  (7, 25),
  (7, 26),
  (7, 27),
  (7, 28),

  (8, 29),
  (8, 30),
  (8, 31),
  (8, 32);

-- ================= stage 2 =====================

-- pools id 9-16
INSERT INTO pools (tournament_id, start_time, end_time, poolName, mode_id, gameType_id, poolSize, stage, isFinished)
VALUES
  (1, '10:00', '12:00', 'Badminton 2-1', 1, 1, 4, 2, 0), (1, '10:00', '12:00', 'Badminton 2-2', 1, 1, 4, 2, 0),
  (1, '10:00', '12:00', 'Badminton 2-3', 1, 1, 4, 2, 0), (1, '10:00', '12:00', 'Badminton 2-4', 1, 1, 4, 2, 0),
  (1, '10:00', '12:00', 'Badminton 2-5', 1, 1, 4, 2, 0), (1, '10:00', '12:00', 'Badminton 2-6', 1, 1, 4, 2, 0),
  (1, '10:00', '12:00', 'Badminton 2-7', 1, 1, 4, 2, 0), (1, '10:00', '12:00', 'Badminton 2-8', 1, 1, 4, 2, 0);

INSERT INTO contenders (pool_id, rank_in_pool, pool_from_id)
VALUES
  (9, 1, 1),
  (9, 1, 2),
  (9, 1, 3),
  (9, 1, 4),

  (10, 1, 5),
  (10, 1, 6),
  (10, 1, 7),
  (10, 1, 8),

  (11, 2, 1),
  (11, 2, 2),
  (11, 2, 3),
  (11, 2, 4),

  (12, 2, 5),
  (12, 2, 6),
  (12, 2, 7),
  (12, 2, 8),

  (13, 3, 1),
  (13, 3, 2),
  (13, 3, 3),
  (13, 3, 4),

  (14, 3, 5),
  (14, 3, 6),
  (14, 3, 7),
  (14, 3, 8),

  (15, 4, 1),
  (15, 4, 2),
  (15, 4, 3),
  (15, 4, 4),

  (16, 4, 5),
  (16, 4, 6),
  (16, 4, 7),
  (16, 4, 8);

-- ================= stage 3 =====================

-- pools id 17-20
INSERT INTO pools (tournament_id, start_time, end_time, poolName, mode_id, gameType_id, poolSize, stage, isFinished)
VALUES
  (1, '13:30', '15:30', 'Badminton 3-1', 1, 1, 4, 3, 0), (1, '13:30', '15:30', 'Badminton 3-2', 1, 1, 4, 3, 0),
  (1, '13:30', '15:30', 'Badminton 3-3', 1, 1, 4, 3, 0), (1, '13:30', '15:30', 'Badminton 3-4', 1, 1, 4, 3, 0),
  (1, '13:30', '15:30', 'Badminton 3-5', 1, 1, 4, 3, 0), (1, '13:30', '15:30', 'Badminton 3-6', 1, 1, 4, 3, 0),
  (1, '13:30', '15:30', 'Badminton 3-7', 1, 1, 4, 3, 0), (1, '13:30', '15:30', 'Badminton 3-8', 1, 1, 4, 3, 0);

INSERT INTO contenders (pool_id, rank_in_pool, pool_from_id)
VALUES
  (17, 1, 9),
  (17, 1, 10),
  (17, 1, 11),
  (17, 1, 12),

  (18, 1, 13),
  (18, 1, 14),
  (18, 1, 15),
  (18, 1, 16),

  (19, 2, 9),
  (19, 2, 10),
  (19, 2, 11),
  (19, 2, 12),

  (20, 2, 13),
  (20, 2, 14),
  (20, 2, 15),
  (20, 2, 16),

  (21, 3, 9),
  (21, 3, 10),
  (21, 3, 11),
  (21, 3, 12),

  (22, 3, 13),
  (22, 3, 14),
  (22, 3, 15),
  (22, 3, 16),

  (23, 4, 9),
  (23, 4, 10),
  (23, 4, 11),
  (23, 4, 12),

  (24, 4, 13),
  (24, 4, 14),
  (24, 4, 15),
  (24, 4, 16);

-- ================= stage 4 (finals) =====================

-- pools id 21-24
INSERT INTO pools (tournament_id, start_time, end_time, poolName, mode_id, gameType_id, poolSize, stage, isFinished)
VALUES
  (1, '15:30', '16:30', 'Badminton 4-1', 1, 1, 2, 4, 0), (1, '15:30', '16:30', 'Badminton 4-2', 1, 1, 2, 4, 0),
  (1, '15:30', '16:30', 'Badminton 4-3', 1, 1, 2, 4, 0), (1, '15:30', '16:30', 'Badminton 4-4', 1, 1, 2, 4, 0),
  (1, '15:30', '16:30', 'Badminton 4-5', 1, 1, 2, 4, 0), (1, '15:30', '16:30', 'Badminton 4-6', 1, 1, 2, 4, 0),
  (1, '15:30', '16:30', 'Badminton 4-7', 1, 1, 2, 4, 0), (1, '15:30', '16:30', 'Badminton 4-8', 1, 1, 2, 4, 0);

INSERT INTO contenders (pool_id, rank_in_pool, pool_from_id)
VALUES

  (25, 1, 17),
  (25, 1, 18),
  (25, 1, 19),
  (25, 1, 20),

  (26, 1, 21),
  (26, 1, 22),
  (26, 1, 23),
  (26, 1, 24),

  (27, 2, 17),
  (27, 2, 18),
  (27, 2, 19),
  (27, 2, 20),

  (28, 2, 21),
  (28, 2, 22),
  (28, 2, 23),
  (28, 2, 24),

  (29, 3, 17),
  (29, 3, 18),
  (29, 3, 19),
  (29, 3, 20),

  (30, 3, 21),
  (30, 3, 22),
  (30, 3, 23),
  (30, 3, 24),

  (31, 4, 17),
  (31, 4, 18),
  (31, 4, 19),
  (31, 4, 20),

  (32, 4, 21),
  (32, 4, 22),
  (32, 4, 23),
  (24, 4, 24);

DELIMITER $$
-- XCL, 4.3.2107
-- A procedure that generates single games within a pool. !! Assumes the contender ids of the pool are contiguous !!
CREATE PROCEDURE generateGames(IN poolid INT)
BEGIN
  DECLARE c1 INT DEFAULT (SELECT id FROM contenders WHERE pool_id=poolid LIMIT 1);
  DECLARE c2 INT;
  DECLARE psize INT DEFAULT (SELECT poolSize FROM pools WHERE id=poolid);
  DECLARE pstart TIME DEFAULT (SELECT pools.start_time FROM pools WHERE id=poolid); -- pool start time
  DECLARE i,j,s1,s2 INT DEFAULT 0;
  DECLARE deltat INT;
  DECLARE gamestart TIME;
  WHILE i < psize-1 DO
    SET j=i+1;
    WHILE j < psize DO
      SET deltat = 15*(i+j-1); -- Assume 15 minutes per game
      SET gamestart = ADDTIME(pstart,MAKETIME(deltat DIV 60, deltat MOD 60, 0));
      IF gamestart < MAKETIME(10,30,0) THEN -- generate a fake result
    IF RAND() > 0.5 THEN -- contender 1 wins
      BEGIN
        SET s1 = 15;
        SET s2 = FLOOR(5+8*RAND());
      END;
    ELSE -- contender 2 wins
      BEGIN
        SET s2 = 15;
        SET s1 = FLOOR(5+8*RAND());
      END;
    END IF;
      ELSE
      BEGIN
        SET s1 = NULL;
        SET s2 = NULL;
      END;
    END IF;
      INSERT INTO games (contender1_id, contender2_id, DATE, start_time, court_id, score_contender1, score_contender2) VALUES (c1+i,c1+j,(SELECT start_date FROM pools INNER JOIN tournaments ON tournament_id = tournaments.id WHERE pools.id=poolid),gamestart,1,s1,s2);
      SET j = j + 1;
    END WHILE;
    SET i = i + 1;
  END WHILE;
END;
$$

DELIMITER $$
-- XCL, 4.3.2107
-- A procedure that generates all games !! Assumes the pool ids start at 1 and are contiguous !!
CREATE PROCEDURE generateAllGames()
BEGIN
  DECLARE n INT DEFAULT (SELECT COUNT(id) FROM pools);
  DECLARE i INT DEFAULT 1;
  WHILE i <= n DO
    CALL generateGames(i);
    SET i = i + 1;
  END WHILE;
END;
$$
DELIMITER ;

CALL generateAllGames();

DROP PROCEDURE generateGames; -- cleanup
DROP PROCEDURE generateAllGames; -- cleanup



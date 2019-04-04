USE joutes;

ALTER TABLE joutes.participant_team ADD isTournamentManager TINYINT(1);
CREATE TABLE news (
	id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	content varchar(600) NOT NULL,
	creation_datetime TIMESTAMP NOT NULL,
	isUrgent TINYINT(1) NOT NULL,
	tournament_id int(10) UNSIGNED NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (tournament_id) REFERENCES joutes.tournaments(id)
);

INSERT INTO joutes.news (content, creation_datetime, isUrgent, tournament_id) VALUES ("Ceci est une news de test", "2019-03-21 12:20:10","0","1");

ALTER TABLE joutes.participants ADD phone_number INT(10) UNSIGNED;
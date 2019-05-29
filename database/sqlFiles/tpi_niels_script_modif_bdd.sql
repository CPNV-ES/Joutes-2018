ALTER TABLE homestead.pools ADD COLUMN bestFinalRank INT null;
-- Add the value for bestFinalRank, only for the badminton tournament created by the Badminton seeder
-- the value of bestFinalRank must be set for every ending pools, for every tournaments, when creating them.
UPDATE homestead.pools SET bestFinalRank = '1' WHERE pools.id='21';
UPDATE homestead.pools SET bestFinalRank = '3' WHERE pools.id='22';
UPDATE homestead.pools SET bestFinalRank = '5' WHERE pools.id='23';
UPDATE homestead.pools SET bestFinalRank = '7' WHERE pools.id='24';
UPDATE homestead.pools SET bestFinalRank = '9' WHERE pools.id='19';
UPDATE homestead.pools SET bestFinalRank = '9' WHERE pools.id='20';
UPDATE homestead.pools SET bestFinalRank = '17' WHERE pools.id='13';
UPDATE homestead.pools SET bestFinalRank = '17' WHERE pools.id='14';
UPDATE homestead.pools SET bestFinalRank = '17' WHERE pools.id='15';
UPDATE homestead.pools SET bestFinalRank = '17' WHERE pools.id='16';

update homestead.games set score_contender1 = round(RAND()*10+5,0) where score_contender1 is null;
update homestead.games set score_contender2 = round(RAND()*10+5,0) where score_contender2 is null;
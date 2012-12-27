.bail on

DROP TABLE IF EXISTS word;

VACUUM; /* see to use the auto_vacuum pragma */

CREATE TABLE word (  /* leave columns in alpabetical order */
    ascii TEXT NOT NULL,
    line INTEGER NOT NULL,
    original TEXT NOT NULL
);

.import ../application/data/ghostwords/word.txt word

CREATE INDEX 'ascii' ON word (ascii ASC);

SELECT count(*) FROM word;

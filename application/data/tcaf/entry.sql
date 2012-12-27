.bail on

DROP TABLE IF EXISTS entry;

VACUUM;

/* leave columns in alpabetical order */
CREATE TABLE entry ( 
    ascii TEXT NOT NULL,
    conjugation TEXT NOT NULL,
    line INTEGER NOT NULL,
    original TEXT NOT NULL,
    tense TEXT NOT NULL
);

.import ../application/data/tcaf/entry.txt entry

CREATE INDEX 'entry_ascii' ON entry (ascii ASC);

SELECT count(*) FROM entry;

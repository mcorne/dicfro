.bail on

DROP TABLE IF EXISTS word;

VACUUM;

/* leave columns in alpabetical order */
CREATE TABLE word (
    ascii TEXT NOT NULL,
    composed INTEGER NOT NULL,
    infinitive TEXT NOT NULL,
    infinitive_ascii TEXT NOT NULL,
    line INTEGER NOT NULL,
    original TEXT NOT NULL,
    person TEXT NOT NULL,
    tense TEXT NOT NULL
);

.import ../application/data/tcaf/word.txt word

CREATE INDEX 'word_ascii' ON word (ascii ASC);
CREATE INDEX 'infinitive_ascii' ON word (infinitive_ascii ASC);

SELECT count(*) FROM word;
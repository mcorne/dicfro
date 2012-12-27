.bail on

DROP TABLE IF EXISTS word;

VACUUM; /* see to use the auto_vacuum pragma */

CREATE TABLE word (
    latin TEXT NOT NULL,
    line INTEGER NOT NULL,
    original TEXT NOT NULL,
    upper TEXT NOT NULL
);

.import ../application/data/whitaker/word.txt word

CREATE INDEX 'latin' ON word (latin ASC);
CREATE INDEX 'word_line' ON word (line ASC);

SELECT count(*) FROM word;

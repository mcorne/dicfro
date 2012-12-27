.bail on

DROP TABLE IF EXISTS entry;

VACUUM; /* see to use the auto_vacuum pragma */

CREATE TABLE entry (
    abbreviation INTEGER NOT NULL,
    age TEXT NOT NULL,
    area TEXT NOT NULL,
    auxiliary TEXT NULL,
    cases TEXT NULL, 
    comparison TEXT NULL, 
    conjugation TEXT NULL, 
    declension TEXT NULL, 
    definition TEXT NOT NULL,
    frequency TEXT NOT NULL,
    gender TEXT NULL, 
    geography TEXT NOT NULL,
    info TEXT NOT NULL,
    line INTEGER PRIMARY KEY NOT NULL,
    mood TEXT NULL,
    number TEXT NULL, 
    person TEXT NULL, 
    pof TEXT NOT NULL, 
    pronoun TEXT NULL,
    source TEXT NOT NULL,
    tense TEXT NULL, 
    undeclined INTEGER NOT NULL,
    variant TEXT NULL, 
    verb TEXT NULL, 
    voice TEXT NULL, 
    which TEXT NULL
);

CREATE INDEX 'entry_line' ON entry (line ASC);

.import ../application/data/whitaker/entry.txt entry

SELECT count(*) FROM entry;

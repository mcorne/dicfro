.bail on

DROP TABLE IF EXISTS entry;

VACUUM; /* see to use the auto_vacuum pragma */

CREATE TABLE entry ( /* leave columns in alpabetical order */
    info TEXT NOT NULL,
    lemma TEXT NOT NULL,
    line INTEGER PRIMARY KEY NOT NULL,
    main TEXT NULL,
    pof TEXT NULL,
    variants TEXT NULL
);

.import ../application/data/tobler/entry.txt entry

CREATE INDEX 'entry_line' ON entry (line ASC);

SELECT count(*) FROM entry;

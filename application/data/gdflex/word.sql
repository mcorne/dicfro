.bail on

DROP TABLE IF EXISTS word;

VACUUM; /* see to use the auto_vacuum pragma */

CREATE TABLE word (  /* leave columns in alpabetical order */
    ascii TEXT NOT NULL,
    errata TEXT NOT NULL,
    image TEXT NOT NULL UNIQUE,
    line INTEGER NOT NULL,
    original TEXT NOT NULL,
    previous TEXT NOT NULL
);

.separator ~
.import ../application/data/gdflex/word.txt word

CREATE INDEX 'ascii' ON word (ascii ASC);
CREATE INDEX 'image' ON word (image ASC);

SELECT count(*) FROM word;

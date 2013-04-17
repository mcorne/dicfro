.bail on

DROP TABLE IF EXISTS word;

VACUUM;

/* leave columns in alpabetical order */
CREATE TABLE word (  
    ascii    TEXT    NOT NULL,
    fix      TEXT    NOT NULL,
    image    TEXT    NOT NULL,
    line     INTEGER NOT NULL,
    original TEXT    NOT NULL,
    page     INTEGER NOT NULL,
    previous TEXT    NOT NULL,
    volume   INTEGER NOT NULL
);

.import %s word

CREATE INDEX 'ascii' ON word (ascii ASC);
CREATE INDEX 'image' ON word (image ASC);
CREATE INDEX 'page'  ON word (page ASC);

SELECT count(*) FROM word;

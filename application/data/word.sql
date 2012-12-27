.bail on

DROP TABLE IF EXISTS word;

VACUUM;

/* leave columns in alpabetical order */
CREATE TABLE word (  
    ascii TEXT NOT NULL,
    image TEXT NOT NULL UNIQUE,
    line INTEGER NOT NULL,
    original TEXT NOT NULL,
    previous TEXT NOT NULL
);

.import %s word

CREATE INDEX 'ascii' ON word (ascii ASC);
CREATE INDEX 'image' ON word (image ASC);

SELECT count(*) FROM word;

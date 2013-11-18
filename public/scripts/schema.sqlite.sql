-- scripts/schema.sqlite.sql
--
-- You will need load your database schema with this SQL.
 
CREATE TABLE news (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(255) NULL,
    youtube VARCHAR(255) NULL,
    comment TEXT NULL,
    created DATETIME NOT NULL
);
 
CREATE INDEX "id" ON "news" ("id");

CREATE TABLE tour (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    location VARCHAR(255) NOT NULL,
    when DATETIME NOT NULL
);

CREATE INDEX "id" ON "tour" ("id");
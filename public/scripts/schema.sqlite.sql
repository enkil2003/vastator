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
 
CREATE INDEX "news_id" ON "news" ("id");

CREATE TABLE tour (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    location VARCHAR(255) NULL,
    created DATETIME NOT NULL
);

CREATE INDEX "tour_id" ON "tour" ("id");

CREATE TABLE theband (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    image VARCHAR(255) NULL,
    history TEXT
);

CREATE INDEX "theband_id" ON "theband" ("id");

CREATE TABLE gallery (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    image VARCHAR(255) NULL,
    year DATE NOT NULL,
    description TEXT NULL
);

CREATE INDEX "gallery_id" ON "gallery" ("id");

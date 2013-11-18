-- scripts/schema.sqlite.sql
--
-- You will need load your database schema with this SQL.
 
CREATE TABLE IF NOT EXISTS news (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(255) NULL,
    youtube VARCHAR(255) NULL,
    comment TEXT NULL,
    created DATETIME NOT NULL
);
 
CREATE INDEX IF NOT EXISTS "news_id" ON "news" ("id");

CREATE TABLE IF NOT EXISTS tour (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    location VARCHAR(255) NULL,
    created DATETIME NOT NULL
);

CREATE INDEX IF NOT EXISTS "tour_id" ON "tour" ("id");

CREATE TABLE IF NOT EXISTS theband (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    image VARCHAR(255) NULL,
    history TEXT
);

CREATE INDEX IF NOT EXISTS "theband_id" ON "theband" ("id");

CREATE TABLE IF NOT EXISTS gallery (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    image VARCHAR(255) NULL,
    year DATE NOT NULL,
    description TEXT NULL
);

CREATE INDEX IF NOT EXISTS "gallery_id" ON "gallery" ("id");

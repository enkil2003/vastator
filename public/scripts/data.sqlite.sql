-- scripts/data.sqlite.sql
--
-- You can begin populating the database with the following SQL statements.
 
INSERT INTO news (title, image, comment, created) VALUES
    ('un titulo',
    'vast1.jpg',
    'el contenido de la noticia',
    DATETIME('NOW'));

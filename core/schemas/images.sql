DROP TABLE IF EXISTS "images";
CREATE TABLE "images"
(
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "filename" VARCHAR(256),
    "width" INTEGER,
    "height" INTEGER,
    "mod_time" TIMESTAMP
);
CREATE INDEX filename_index ON images( filename );
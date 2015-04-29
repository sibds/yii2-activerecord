PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE "post" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "content" TEXT,
    "create_at" INTEGER,
    "update_at" INTEGER,
    "create_by" INTEGER,
    "update_by" INTEGER
);
INSERT INTO "post" VALUES(1,'test1',NULL,NULL,NULL,NULL);
INSERT INTO "post" VALUES(2,'test2',NULL,NULL,NULL,NULL);
INSERT INTO "post" VALUES(3,'test3',NULL,NULL,NULL,NULL);
CREATE TABLE "user" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "login" TEXT
);
INSERT INTO "user" VALUES(1,'admin');
INSERT INTO "user" VALUES(2,'demo1');
INSERT INTO "user" VALUES(3,'demo2');
DELETE FROM sqlite_sequence;
INSERT INTO "sqlite_sequence" VALUES('user',3);
INSERT INTO "sqlite_sequence" VALUES('post',3);
COMMIT;

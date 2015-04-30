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

DELETE FROM sqlite_sequence;
INSERT INTO "sqlite_sequence" VALUES('post',3);
COMMIT;

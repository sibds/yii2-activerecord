PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE "post" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "content" TEXT,
    "created_at" INTEGER,
    "updated_at" INTEGER,
    "created_by" INTEGER,
    "updated_by" INTEGER,
    "status" INTEGER,
    "removed" INTEGER
);

DELETE FROM sqlite_sequence;
INSERT INTO "sqlite_sequence" VALUES('post',3);
COMMIT;

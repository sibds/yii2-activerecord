CREATE TABLE "post" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "content" TEXT,
    "created_at" INTEGER,
    "updated_at" INTEGER,
    "created_by" INTEGER,
    "updated_by" INTEGER,
    "locked" INTEGER,
    "removed" INTEGER
);
INSERT INTO "post" VALUES (1, 'test', 1454414533, 1454414533, 100, 100, 0, 0);

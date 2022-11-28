ALTER TABLE todos ADD completed BOOL DEFAULT false NOT NULL;
ALTER TABLE todos ADD completed_on DATETIME DEFAULT NULL NULL;
CREATE INDEX todos_completed_IDX ON todos (completed);

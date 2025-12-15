CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "first_name" varchar not null,
  "last_name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "two_factor_secret" text,
  "two_factor_recovery_codes" text,
  "two_factor_confirmed_at" datetime,
  "google_id" varchar,
  "google_token" text,
  "google_refresh_token" text,
  "google_token_expires_at" datetime,
  "is_admin" tinyint(1) not null default '0',
  "last_active_at" datetime
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "course_user"(
  "course_id" integer not null,
  "user_id" integer not null,
  "role" varchar not null,
  foreign key("course_id") references "courses"("id") on delete cascade,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "telescope_entries"(
  "sequence" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "batch_id" varchar not null,
  "family_hash" varchar,
  "should_display_on_index" tinyint(1) not null default '1',
  "type" varchar not null,
  "content" text not null,
  "created_at" datetime
);
CREATE UNIQUE INDEX "telescope_entries_uuid_unique" on "telescope_entries"(
  "uuid"
);
CREATE INDEX "telescope_entries_batch_id_index" on "telescope_entries"(
  "batch_id"
);
CREATE INDEX "telescope_entries_family_hash_index" on "telescope_entries"(
  "family_hash"
);
CREATE INDEX "telescope_entries_created_at_index" on "telescope_entries"(
  "created_at"
);
CREATE INDEX "telescope_entries_type_should_display_on_index_index" on "telescope_entries"(
  "type",
  "should_display_on_index"
);
CREATE TABLE IF NOT EXISTS "telescope_entries_tags"(
  "entry_uuid" varchar not null,
  "tag" varchar not null,
  foreign key("entry_uuid") references "telescope_entries"("uuid") on delete cascade,
  primary key("entry_uuid", "tag")
);
CREATE INDEX "telescope_entries_tags_tag_index" on "telescope_entries_tags"(
  "tag"
);
CREATE TABLE IF NOT EXISTS "telescope_monitoring"(
  "tag" varchar not null,
  primary key("tag")
);
CREATE TABLE IF NOT EXISTS "module_objectives"(
  "id" integer primary key autoincrement not null,
  "module_id" integer not null,
  "number" varchar not null,
  "objective" text not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("module_id") references "course_modules"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "course_assessments"(
  "id" integer primary key autoincrement not null,
  "module_id" integer,
  "title" varchar not null,
  "type" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("module_id") references "course_modules"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "course_instruction"(
  "id" integer primary key autoincrement not null,
  "module_id" integer,
  "title" varchar not null,
  "type" varchar not null,
  "content" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("module_id") references "course_modules"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "course_materials"(
  "id" integer primary key autoincrement not null,
  "course_id" integer not null,
  "module_id" integer,
  "module_objective_id" integer,
  "title" varchar not null,
  "type" varchar not null,
  "url" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("course_id") references "courses"("id") on delete cascade,
  foreign key("module_id") references "course_modules"("id") on delete cascade,
  foreign key("module_objective_id") references "module_objectives"("id") on delete set null
);
CREATE TABLE IF NOT EXISTS "course_objectives"(
  "id" integer primary key autoincrement not null,
  "course_id" integer not null,
  "number" varchar not null,
  "objective" text not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("course_id") references "courses"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "course_objective_module"(
  "id" integer primary key autoincrement not null,
  "course_objective_id" integer not null,
  "course_module_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("course_objective_id") references "course_objectives"("id") on delete cascade,
  foreign key("course_module_id") references "course_modules"("id") on delete cascade
);
CREATE UNIQUE INDEX "course_objective_module_course_objective_id_course_module_id_unique" on "course_objective_module"(
  "course_objective_id",
  "course_module_id"
);
CREATE TABLE IF NOT EXISTS "course_media_library_needs"(
  "id" integer primary key autoincrement not null,
  "module_id" integer not null,
  "name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("module_id") references "course_modules"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "module_items"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime,
  "course_module_id" integer not null,
  "itemable_type" varchar not null,
  "itemable_id" integer not null,
  "order_index" integer not null default '0',
  foreign key("course_module_id") references "course_modules"("id") on delete cascade
);
CREATE INDEX "module_items_itemable_type_itemable_id_index" on "module_items"(
  "itemable_type",
  "itemable_id"
);
CREATE UNIQUE INDEX "module_items_course_module_id_itemable_id_itemable_type_unique" on "module_items"(
  "course_module_id",
  "itemable_id",
  "itemable_type"
);
CREATE TABLE IF NOT EXISTS "course_modules"(
  "id" integer primary key autoincrement not null,
  "course_id" integer not null,
  "title" varchar not null,
  "module_objectives" text,
  "order_index" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  "course_objective_id" integer,
  "module_overview" text,
  "module_wrap_up" text,
  "module_items_id" integer,
  foreign key("course_objective_id") references course_objectives("id") on delete set null on update no action,
  foreign key("course_id") references courses("id") on delete cascade on update no action,
  foreign key("module_items_id") references "module_items"("id") on delete set null
);
CREATE TABLE IF NOT EXISTS "course_quizzes"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime,
  "title" varchar not null,
  "instructions" text,
  "settings" text,
  "questions" text
);
CREATE TABLE IF NOT EXISTS "course_pages"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime,
  "title" varchar not null,
  "content" text
);
CREATE TABLE IF NOT EXISTS "course_assignments"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime,
  "title" varchar not null,
  "purpose" text,
  "criteria" text,
  "settings" text
);
CREATE TABLE IF NOT EXISTS "course_discussions"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime,
  "title" varchar not null,
  "is_graded" tinyint(1) not null default '0',
  "settings" text,
  "prompt" text
);
CREATE TABLE IF NOT EXISTS "module_overviews"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime,
  "content" text not null
);
CREATE TABLE IF NOT EXISTS "profiles"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime,
  "user_id" integer not null,
  "bio" varchar,
  "is_admin" tinyint(1) not null default '0',
  "allowed_roles" text,
  "avatar" varchar,
  "title" varchar,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE UNIQUE INDEX "users_google_id_unique" on "users"("google_id");
CREATE TABLE IF NOT EXISTS "module_wrap_ups"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime,
  "content" text not null
);
CREATE TABLE IF NOT EXISTS "teams"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "team_user"(
  "id" integer primary key autoincrement not null,
  "team_id" integer not null,
  "user_id" integer not null,
  "role" varchar not null default 'member',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("team_id") references "teams"("id") on delete cascade,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "team_invitations"(
  "id" integer primary key autoincrement not null,
  "team_id" integer not null,
  "email" varchar not null,
  "token" varchar not null,
  "role" varchar not null default 'member',
  "expires_at" datetime not null,
  "accepted_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("team_id") references "teams"("id") on delete cascade
);
CREATE UNIQUE INDEX "team_invitations_token_unique" on "team_invitations"(
  "token"
);
CREATE TABLE IF NOT EXISTS "notifications"(
  "id" varchar not null,
  "type" varchar not null,
  "notifiable_type" varchar not null,
  "notifiable_id" integer not null,
  "data" text not null,
  "read_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  primary key("id")
);
CREATE INDEX "notifications_notifiable_type_notifiable_id_index" on "notifications"(
  "notifiable_type",
  "notifiable_id"
);
CREATE TABLE IF NOT EXISTS "conversations"(
  "id" integer primary key autoincrement not null,
  "subject" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  "type" varchar check("type" in('group', 'direct')) not null default 'direct'
);
CREATE TABLE IF NOT EXISTS "deliverables"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime,
  "name" varchar not null,
  "template_days_offset" integer not null
);
CREATE TABLE IF NOT EXISTS "development_cycles"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime,
  "name" varchar not null,
  "start_date" date not null,
  "end_date" date not null
);
CREATE TABLE IF NOT EXISTS "courses"(
  "id" integer primary key autoincrement not null,
  "prefix" varchar not null,
  "number" varchar not null,
  "title" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  "document_id" varchar,
  "development_cycle_id" integer,
  "status" varchar not null default 'pending',
  "completion_date" date,
  foreign key("development_cycle_id") references "development_cycles"("id") on delete set null
);
CREATE TABLE IF NOT EXISTS "course_deliverable"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime,
  "course_id" integer not null,
  "deliverable_id" integer not null,
  "due_date" date,
  "is_done" tinyint(1) not null default '0',
  "date_completed" date,
  "missed_due_date_count" integer not null default '0',
  foreign key("course_id") references "courses"("id") on delete cascade,
  foreign key("deliverable_id") references "deliverables"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "module_objective_alignments"(
  "id" integer primary key autoincrement not null,
  "module_objective_id" integer not null,
  "alignable_type" varchar not null,
  "alignable_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("module_objective_id") references "module_objectives"("id") on delete cascade
);
CREATE INDEX "module_objective_alignments_alignable_type_alignable_id_index" on "module_objective_alignments"(
  "alignable_type",
  "alignable_id"
);
CREATE UNIQUE INDEX "module_objective_alignments_module_objective_id_alignable_id_alignable_type_unique" on "module_objective_alignments"(
  "module_objective_id",
  "alignable_id",
  "alignable_type"
);
CREATE TABLE IF NOT EXISTS "activities"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "subject_type" varchar not null,
  "subject_id" integer not null,
  "action" varchar not null,
  "description" text,
  "metadata" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE INDEX "activities_subject_type_subject_id_index" on "activities"(
  "subject_type",
  "subject_id"
);
CREATE INDEX "activities_created_at_user_id_index" on "activities"(
  "created_at",
  "user_id"
);
CREATE TABLE IF NOT EXISTS "admin_settings"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime,
  "capacity" integer not null default '5'
);
CREATE TABLE IF NOT EXISTS "personal_access_tokens"(
  "id" integer primary key autoincrement not null,
  "tokenable_type" varchar not null,
  "tokenable_id" integer not null,
  "name" text not null,
  "token" varchar not null,
  "abilities" text,
  "last_used_at" datetime,
  "expires_at" datetime,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" on "personal_access_tokens"(
  "tokenable_type",
  "tokenable_id"
);
CREATE UNIQUE INDEX "personal_access_tokens_token_unique" on "personal_access_tokens"(
  "token"
);
CREATE INDEX "personal_access_tokens_expires_at_index" on "personal_access_tokens"(
  "expires_at"
);
CREATE TABLE IF NOT EXISTS "threads"(
  "id" integer primary key autoincrement not null,
  "subject" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE TABLE IF NOT EXISTS "participants"(
  "id" integer primary key autoincrement not null,
  "conversation_id" integer not null,
  "user_id" integer not null,
  "last_read" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE TABLE IF NOT EXISTS "appointments"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime,
  "host_id" integer not null,
  "subject" varchar not null,
  "start_time" datetime not null,
  "end_time" datetime not null,
  "notes" text,
  foreign key("host_id") references "users"("id")
);
CREATE TABLE IF NOT EXISTS "appointment_users"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime,
  "appointment_id" integer not null,
  "user_id" integer not null,
  "status" varchar not null default 'pending',
  foreign key("appointment_id") references "appointments"("id") on delete cascade,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "messages"(
  "id" integer primary key autoincrement not null,
  "conversation_id" integer not null,
  "user_id" integer not null,
  "body" text,
  "is_typing" tinyint(1) not null default '0',
  "attachments" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("conversation_id") references "conversations"("id") on delete cascade,
  foreign key("user_id") references "users"("id") on delete cascade
);

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_08_14_170933_add_two_factor_columns_to_users_table',1);
INSERT INTO migrations VALUES(5,'2025_10_02_031235_create_courses_table',1);
INSERT INTO migrations VALUES(6,'2025_10_02_032902_create_course_user_table',1);
INSERT INTO migrations VALUES(7,'2025_10_03_023737_create_telescope_entries_table',1);
INSERT INTO migrations VALUES(8,'2025_10_03_212000_create_course_components_tables',1);
INSERT INTO migrations VALUES(9,'2025_10_09_215242_create_course_objectives_table',1);
INSERT INTO migrations VALUES(10,'2025_10_11_040048_update_course_modules_table',1);
INSERT INTO migrations VALUES(11,'2025_10_12_001515_create_course_objective_module_table',1);
INSERT INTO migrations VALUES(12,'2025_10_16_012359_create_course_media_library_needs_table',1);
INSERT INTO migrations VALUES(13,'2025_10_19_064942_create_module_items_table',1);
INSERT INTO migrations VALUES(14,'2025_10_19_065000_update_course_modules_table',1);
INSERT INTO migrations VALUES(15,'2025_10_19_165840_create_course_quizzes_table',1);
INSERT INTO migrations VALUES(16,'2025_10_19_171747_create_course_pages_table',1);
INSERT INTO migrations VALUES(17,'2025_10_19_172025_create_course_assignments_table',1);
INSERT INTO migrations VALUES(18,'2025_10_19_190740_create_course_discussions_table',1);
INSERT INTO migrations VALUES(19,'2025_10_22_051801_create_module_overviews_table',1);
INSERT INTO migrations VALUES(20,'2025_10_26_043618_add_order_index_to_module_items_table',1);
INSERT INTO migrations VALUES(21,'2025_10_27_044912_create_profiles_table',1);
INSERT INTO migrations VALUES(22,'2025_10_28_082352_add_google_auth_to_users_table',1);
INSERT INTO migrations VALUES(23,'2025_10_29_235154_update_courses_table',1);
INSERT INTO migrations VALUES(24,'2025_11_02_155338_create_module_wrap_ups_table',1);
INSERT INTO migrations VALUES(25,'2025_11_04_024611_add_google_token_expires_at_to_users',1);
INSERT INTO migrations VALUES(26,'2025_11_12_051016_create_teams_table',1);
INSERT INTO migrations VALUES(27,'2025_11_12_051043_create_team_user_table',1);
INSERT INTO migrations VALUES(28,'2025_11_12_051106_create_team_invitations_table',1);
INSERT INTO migrations VALUES(29,'2025_11_14_013919_add_admin_to_user_table',1);
INSERT INTO migrations VALUES(30,'2025_11_22_020920_create_notifications_table',1);
INSERT INTO migrations VALUES(31,'2025_11_22_033658_create_conversations_table',1);
INSERT INTO migrations VALUES(32,'2025_11_22_033712_create_messages_table',1);
INSERT INTO migrations VALUES(33,'2025_11_22_033805_create_participants_table',1);
INSERT INTO migrations VALUES(34,'2025_11_22_212241_create_deliverables_table',1);
INSERT INTO migrations VALUES(35,'2025_11_25_022756_create_development_cycles_table',1);
INSERT INTO migrations VALUES(36,'2025_11_25_022757_add_development_cycle_to_courses',1);
INSERT INTO migrations VALUES(37,'2025_11_25_024333_create_course_deliverable_table',1);
INSERT INTO migrations VALUES(38,'2025_11_25_224113_update_profiles_table',1);
INSERT INTO migrations VALUES(39,'create_module_objective_alignments_table',1);
INSERT INTO migrations VALUES(40,'2025_11_28_001619_create_activities_table',2);
INSERT INTO migrations VALUES(41,'2025_11_28_032023_update_courses_table',3);
INSERT INTO migrations VALUES(42,'2025_12_01_020412_create_admin_settings_table',4);
INSERT INTO migrations VALUES(43,'2025_12_01_032945_create_personal_access_tokens_table',5);
INSERT INTO migrations VALUES(44,'2025_12_03_010629_update_courses_table',6);
INSERT INTO migrations VALUES(45,'2014_10_28_175635_create_threads_table',7);
INSERT INTO migrations VALUES(46,'2014_10_28_175710_create_messages_table',8);
INSERT INTO migrations VALUES(47,'2014_10_28_180224_create_participants_table',9);
INSERT INTO migrations VALUES(48,'2014_11_03_154831_add_soft_deletes_to_participants_table',9);
INSERT INTO migrations VALUES(49,'2014_12_04_124531_add_softdeletes_to_threads_table',9);
INSERT INTO migrations VALUES(50,'2017_03_30_152742_add_soft_deletes_to_messages_table',9);
INSERT INTO migrations VALUES(51,'2025_12_05_031204_create_appointments_table',10);
INSERT INTO migrations VALUES(52,'2025_12_05_031408_create_appointment_users_table',10);
INSERT INTO migrations VALUES(53,'2025_12_06_043652_update_appointment_users_table',11);
INSERT INTO migrations VALUES(54,'2025_12_07_000556_add_last_active_to_users_table',12);
INSERT INTO migrations VALUES(55,'2025_12_07_000943_add_type_to_conversations_table',13);
INSERT INTO migrations VALUES(56,'2025_12_07_002421_create_messages_table',14);

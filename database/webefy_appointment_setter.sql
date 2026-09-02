-- =====================================================================
--  Webefy Appointment Setter — database schema + demo data
-- ---------------------------------------------------------------------
--  Target : MySQL 5.7+ / MariaDB 10.4+ (XAMPP)
--  Import : phpMyAdmin -> pick the `appointment_db` schema -> Import
--           CLI:  mysql -u root appointment_db < database/webefy_appointment_setter.sql
--
--  Notes for review:
--   * users.role holds 'admin' (Webefy / Super Admin) or 'tenant'
--     (business owner / Tenant Admin). It drives the post-login
--     redirect and which sidebar/header partial renders. Kept as a
--     short VARCHAR so a 'tenant_staff' value can be added later.
--   * Single database, every tenant-owned table carries tenant_id.
--   * Demo rows are fully populated for tenant #1 (Sarah's HVAC); the
--     other six tenants are seeded shallow (no services/appointments).
--   * The three base Laravel migrations are recorded in `migrations`
--     so `php artisan migrate` stays happy after import.
-- =====================================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
--  Drop (child -> parent)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `invoices`;
DROP TABLE IF EXISTS `call_logs`;
DROP TABLE IF EXISTS `reminders`;
DROP TABLE IF EXISTS `appointments`;
DROP TABLE IF EXISTS `business_hours`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `tenants`;
DROP TABLE IF EXISTS `personal_access_tokens`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `migrations`;

-- =====================================================================
--  Laravel framework tables
-- =====================================================================

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_02_100909_create_personal_access_tokens_table', 1);

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Laravel Sanctum — API tokens (n8n service account). Issue with `php artisan n8n:token`.
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`, `tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
--  Application tables
-- =====================================================================

-- ---- tenants --------------------------------------------------------
CREATE TABLE `tenants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_name` varchar(255) NOT NULL,
  `booking_slug` varchar(255) NOT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'trial' COMMENT 'active | trial | paused',
  `location` varchar(255) DEFAULT NULL COMMENT 'City, State shown in the tenant sidebar',
  `timezone` varchar(64) NOT NULL DEFAULT 'America/Chicago',
  `contact_phone` varchar(32) DEFAULT NULL,
  `plan` varchar(50) DEFAULT NULL COMMENT 'Starter | Growth | Scale | Trial',
  `seats` smallint unsigned NOT NULL DEFAULT 1,
  `monthly_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `trial_ends_at` date DEFAULT NULL,
  `vapi_phone_number_id` varchar(255) DEFAULT NULL COMMENT 'NULL = use Webefy shared default',
  `vapi_assistant_id` varchar(255) DEFAULT NULL COMMENT 'NULL = use Webefy shared default',
  `whatsapp_phone_number_id` varchar(255) DEFAULT NULL COMMENT 'NULL = use Webefy shared default',
  `whatsapp_template_name` varchar(255) DEFAULT NULL COMMENT 'NULL = use Webefy shared default',
  `whatsapp_reminder_message` text COMMENT 'Editable reminder body, supports {{name}} {{service}} {{date}} {{time}} {{business}}',
  `confirmation_call_script` text COMMENT 'Read-only AI voice script (managed by Webefy)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenants_booking_slug_unique` (`booking_slug`),
  KEY `tenants_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---- users ---------------------------------------------------------
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'tenant' COMMENT 'admin | tenant | service (n8n API)',
  `tenant_id` bigint unsigned DEFAULT NULL COMMENT 'NULL for admin / service users',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`),
  KEY `users_tenant_id_index` (`tenant_id`),
  CONSTRAINT `users_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---- services (offered per tenant) --------------------------------
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `name` varchar(120) NOT NULL,
  `icon` varchar(40) DEFAULT NULL COMMENT 'Font Awesome 6 solid icon name, e.g. "snowflake"',
  `sort_order` smallint unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_tenant_name_unique` (`tenant_id`, `name`),
  KEY `services_tenant_id_index` (`tenant_id`),
  CONSTRAINT `services_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---- business_hours ---------------------------------------------
CREATE TABLE `business_hours` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `day_of_week` tinyint unsigned NOT NULL COMMENT '0=Sun ... 6=Sat',
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `opens_at` time DEFAULT NULL,
  `closes_at` time DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `business_hours_tenant_day_unique` (`tenant_id`, `day_of_week`),
  CONSTRAINT `business_hours_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---- appointments (the table n8n reads/writes) ------------------
CREATE TABLE `appointments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(32) NOT NULL,
  `service_name` varchar(120) DEFAULT NULL COMMENT 'denormalised label captured at booking time',
  `notes` text,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending' COMMENT 'pending | confirmed | declined | completed | no_show',
  `source` varchar(20) NOT NULL DEFAULT 'web' COMMENT 'web | embed | phone | manual',
  `confirmed_at` datetime DEFAULT NULL,
  `confirmation_method` varchar(20) DEFAULT NULL COMMENT 'whatsapp | voice | manual',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_tenant_id_index` (`tenant_id`),
  KEY `appointments_service_id_index` (`service_id`),
  KEY `appointments_status_index` (`status`),
  KEY `appointments_tenant_date_index` (`tenant_id`, `appointment_date`),
  KEY `appointments_status_date_index` (`status`, `appointment_date`),
  CONSTRAINT `appointments_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---- reminders (WhatsApp / voice nudges) -----------------------
CREATE TABLE `reminders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `appointment_id` bigint unsigned NOT NULL,
  `channel` varchar(20) NOT NULL COMMENT 'whatsapp | voice',
  `status` varchar(20) NOT NULL DEFAULT 'queued' COMMENT 'queued | sent | failed',
  `scheduled_for` datetime DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `outcome` varchar(20) DEFAULT NULL COMMENT 'confirmed | declined | no_reply',
  `provider_message_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reminders_tenant_id_index` (`tenant_id`),
  KEY `reminders_appointment_id_index` (`appointment_id`),
  KEY `reminders_status_scheduled_index` (`status`, `scheduled_for`),
  CONSTRAINT `reminders_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reminders_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---- call_logs (Vapi AI confirmation calls) -------------------
CREATE TABLE `call_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `appointment_id` bigint unsigned NOT NULL,
  `vapi_call_id` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'queued' COMMENT 'queued | ringing | in_progress | completed | failed | no_answer',
  `outcome` varchar(20) DEFAULT NULL COMMENT 'confirmed | reschedule | declined | no_response',
  `recording_url` varchar(2048) DEFAULT NULL,
  `duration_seconds` int unsigned DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `call_logs_tenant_id_index` (`tenant_id`),
  KEY `call_logs_appointment_id_index` (`appointment_id`),
  CONSTRAINT `call_logs_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `call_logs_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---- invoices (Super Admin > Billing) -------------------------
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `number` varchar(40) NOT NULL,
  `plan` varchar(50) DEFAULT NULL,
  `seats` smallint unsigned NOT NULL DEFAULT 1,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'open' COMMENT 'paid | past_due | trial | open',
  `period_start` date DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  `issued_on` date DEFAULT NULL,
  `due_on` date DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_number_unique` (`number`),
  KEY `invoices_tenant_id_index` (`tenant_id`),
  KEY `invoices_status_index` (`status`),
  CONSTRAINT `invoices_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---- settings (agency-wide, key/value) -----------------------
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
--  Demo data
--  Every password below is  "password"  (bcrypt).
-- =====================================================================

INSERT INTO `tenants`
(`id`,`business_name`,`booking_slug`,`industry`,`status`,`location`,`timezone`,`contact_phone`,`plan`,`seats`,`monthly_amount`,`trial_ends_at`,`whatsapp_reminder_message`,`confirmation_call_script`,`created_at`,`updated_at`) VALUES
(1,'Sarah\'s HVAC','sarahshvac','HVAC','active','Austin, TX','America/Chicago','(512) 555-0140','Growth',3,420.00,NULL,
 'Hi {{name}}, this is Sarah\'s HVAC confirming your {{service}} on {{date}} at {{time}}. Reply YES to confirm or CHANGE to pick a new time.',
 'Hi, this is the scheduling assistant for {{business}} calling about your {{service}} appointment on {{date}} at {{time}}. Press 1 or say confirm to keep it, or 2 to reschedule.',
 '2026-03-26 09:00:00','2026-09-01 09:00:00'),
(2,'Bright Smile Dental','brightsmile','Dental','active','Round Rock, TX','America/Chicago',NULL,'Growth',5,560.00,NULL,NULL,NULL,'2026-01-15 09:00:00','2026-09-01 09:00:00'),
(3,'Blue Wave Pool Care','bluewave','Pool Cleaning','trial','Phoenix, AZ','America/Phoenix',NULL,'Trial',2,0.00,'2026-09-24',NULL,NULL,'2026-08-10 09:00:00','2026-09-01 09:00:00'),
(4,'Luxe Hair Studio','luxehair','Salon','active','Dallas, TX','America/Chicago',NULL,'Scale',8,890.00,NULL,NULL,NULL,'2025-11-05 09:00:00','2026-09-01 09:00:00'),
(5,'Peak Roofing Co.','peakroofing','Roofing','paused','Denver, CO','America/Denver',NULL,'Starter',1,185.00,NULL,NULL,NULL,'2026-02-20 09:00:00','2026-09-01 09:00:00'),
(6,'Comfort Air Solutions','comfortair','HVAC','active','San Antonio, TX','America/Chicago',NULL,'Growth',3,420.00,NULL,NULL,NULL,'2026-04-12 09:00:00','2026-09-01 09:00:00'),
(7,'Vista Med Spa','vistamedspa','Med Spa','trial','Scottsdale, AZ','America/Phoenix',NULL,'Trial',2,0.00,'2026-10-02',NULL,NULL,'2026-08-18 09:00:00','2026-09-01 09:00:00');

INSERT INTO `users`
(`id`,`name`,`email`,`email_verified_at`,`password`,`role`,`tenant_id`,`created_at`,`updated_at`) VALUES
(1,'Webefy Ops','ops@webefytoday.com','2026-01-01 00:00:00','$2y$12$QDZbEidowLG3viPUoI0OZOXMGeFjV2GqXsW1wDJyj5B8pdnfbnAwG','admin',NULL,'2026-01-01 00:00:00','2026-01-01 00:00:00'),
(2,'Sarah Nguyen','sarah@sarahshvac.com','2026-03-26 09:00:00','$2y$12$QDZbEidowLG3viPUoI0OZOXMGeFjV2GqXsW1wDJyj5B8pdnfbnAwG','tenant',1,'2026-03-26 09:00:00','2026-03-26 09:00:00'),
(3,'n8n Automation','n8n@webefytoday.com',NULL,'$2y$12$QDZbEidowLG3viPUoI0OZOXMGeFjV2GqXsW1wDJyj5B8pdnfbnAwG','service',NULL,'2026-01-01 00:00:00','2026-01-01 00:00:00');

-- icon = Font Awesome 6 (free, solid) icon name; rendered as "fa-solid fa-<icon>"
INSERT INTO `services`
(`id`,`tenant_id`,`name`,`icon`,`sort_order`,`is_active`,`created_at`,`updated_at`) VALUES
(1,1,'AC Repair','snowflake',1,1,'2026-03-26 09:00:00','2026-03-26 09:00:00'),
(2,1,'Heating Issue','fire',2,1,'2026-03-26 09:00:00','2026-03-26 09:00:00'),
(3,1,'Tune-Up','screwdriver-wrench',3,1,'2026-03-26 09:00:00','2026-03-26 09:00:00'),
(4,1,'Duct Cleaning','wind',4,1,'2026-03-26 09:00:00','2026-03-26 09:00:00'),
(5,1,'Emergency Call-Out','phone-volume',5,1,'2026-03-26 09:00:00','2026-03-26 09:00:00'),
(6,1,'New Install / Quote','clipboard-list',6,1,'2026-03-26 09:00:00','2026-03-26 09:00:00');

INSERT INTO `business_hours`
(`tenant_id`,`day_of_week`,`is_closed`,`opens_at`,`closes_at`,`note`,`created_at`,`updated_at`) VALUES
(1,0,1,NULL,NULL,'Closed - emergency line only','2026-03-26 09:00:00','2026-03-26 09:00:00'),
(1,1,0,'07:00:00','18:00:00',NULL,'2026-03-26 09:00:00','2026-03-26 09:00:00'),
(1,2,0,'07:00:00','18:00:00',NULL,'2026-03-26 09:00:00','2026-03-26 09:00:00'),
(1,3,0,'07:00:00','18:00:00',NULL,'2026-03-26 09:00:00','2026-03-26 09:00:00'),
(1,4,0,'07:00:00','18:00:00',NULL,'2026-03-26 09:00:00','2026-03-26 09:00:00'),
(1,5,0,'07:00:00','18:00:00',NULL,'2026-03-26 09:00:00','2026-03-26 09:00:00'),
(1,6,0,'08:00:00','13:00:00',NULL,'2026-03-26 09:00:00','2026-03-26 09:00:00');

INSERT INTO `appointments`
(`id`,`tenant_id`,`service_id`,`customer_name`,`customer_email`,`customer_phone`,`service_name`,`notes`,`appointment_date`,`appointment_time`,`status`,`source`,`confirmed_at`,`confirmation_method`,`created_at`,`updated_at`) VALUES
(1,1,1,'Marcus Reed','marcus.reed@example.com','(512) 447-0192','AC Repair','Not cooling since yesterday.','2026-09-01','09:00:00','confirmed','web','2026-08-31 18:02:00','whatsapp','2026-08-30 14:10:00','2026-08-31 18:02:00'),
(2,1,2,'Dana Whitfield','dana.w@example.com','(512) 903-7741','Heating Issue',NULL,'2026-09-01','11:30:00','confirmed','phone','2026-08-31 18:05:00','voice','2026-08-30 15:40:00','2026-08-31 18:05:00'),
(3,1,3,'Ollie Nakamura',NULL,'(737) 220-5518','Tune-Up','Annual maintenance.','2026-09-02','08:00:00','pending','web',NULL,NULL,'2026-09-01 09:12:00','2026-09-01 09:12:00'),
(4,1,1,'Priya Raman','priya.raman@example.com','(512) 664-3390','AC Repair',NULL,'2026-09-02','13:15:00','confirmed','embed','2026-09-01 18:01:00','whatsapp','2026-08-31 10:05:00','2026-09-01 18:01:00'),
(5,1,4,'Greg Salazar',NULL,'(210) 458-1174','Duct Cleaning',NULL,'2026-09-03','10:00:00','declined','web',NULL,NULL,'2026-08-29 16:20:00','2026-09-02 18:03:00'),
(6,1,3,'Erin Cole','erin.cole@example.com','(512) 771-0286','Tune-Up',NULL,'2026-09-03','15:45:00','pending','web',NULL,NULL,'2026-09-01 11:30:00','2026-09-01 11:30:00'),
(7,1,5,'Tom Beaudry',NULL,'(512) 338-9027','Emergency Call-Out','No heat, house is cold.','2026-09-04','07:30:00','confirmed','phone','2026-09-03 19:15:00','whatsapp','2026-09-03 18:50:00','2026-09-03 19:15:00'),
(8,1,2,'Nina Alvarez','nina.alvarez@example.com','(737) 601-4488','Heating Issue',NULL,'2026-09-04','14:00:00','pending','web',NULL,NULL,'2026-09-02 09:05:00','2026-09-02 09:05:00');

INSERT INTO `reminders`
(`tenant_id`,`appointment_id`,`channel`,`status`,`scheduled_for`,`sent_at`,`outcome`,`created_at`,`updated_at`) VALUES
(1,1,'whatsapp','sent','2026-08-31 18:00:00','2026-08-31 18:00:00','confirmed','2026-08-31 06:00:00','2026-08-31 18:02:00'),
(1,2,'voice','sent','2026-08-31 18:00:00','2026-08-31 18:04:00','confirmed','2026-08-31 06:00:00','2026-08-31 18:05:00'),
(1,3,'whatsapp','sent','2026-09-01 18:00:00','2026-09-01 18:00:00','no_reply','2026-09-01 06:00:00','2026-09-01 18:00:00'),
(1,4,'whatsapp','sent','2026-09-01 18:00:00','2026-09-01 18:00:00','confirmed','2026-09-01 06:00:00','2026-09-01 18:01:00'),
(1,5,'voice','sent','2026-09-02 18:00:00','2026-09-02 18:02:00','declined','2026-09-02 06:00:00','2026-09-02 18:03:00'),
(1,6,'whatsapp','sent','2026-09-02 18:00:00','2026-09-02 18:00:00','no_reply','2026-09-02 06:00:00','2026-09-02 18:00:00'),
(1,8,'whatsapp','queued','2026-09-03 18:00:00',NULL,NULL,'2026-09-02 09:05:00','2026-09-02 09:05:00');

INSERT INTO `call_logs`
(`tenant_id`,`appointment_id`,`vapi_call_id`,`status`,`outcome`,`recording_url`,`duration_seconds`,`started_at`,`ended_at`,`created_at`,`updated_at`) VALUES
(1,2,'vapi_demo_0002','completed','confirmed',NULL,48,'2026-08-31 18:04:00','2026-08-31 18:04:48','2026-08-31 18:04:00','2026-08-31 18:05:00'),
(1,5,'vapi_demo_0005','completed','declined',NULL,33,'2026-09-02 18:02:00','2026-09-02 18:02:33','2026-09-02 18:02:00','2026-09-02 18:03:00');

INSERT INTO `invoices`
(`tenant_id`,`number`,`plan`,`seats`,`amount`,`status`,`period_start`,`period_end`,`issued_on`,`due_on`,`paid_at`,`created_at`,`updated_at`) VALUES
(1,'INV-2609-014','Growth',3,420.00,'paid','2026-09-01','2026-09-30','2026-09-01','2026-09-08','2026-09-01 10:12:00','2026-09-01 00:05:00','2026-09-01 10:12:00'),
(2,'INV-2609-015','Growth',5,560.00,'paid','2026-09-01','2026-09-30','2026-09-01','2026-09-08','2026-09-02 08:40:00','2026-09-01 00:05:00','2026-09-02 08:40:00'),
(4,'INV-2609-016','Scale',8,890.00,'paid','2026-09-01','2026-09-30','2026-09-01','2026-09-08','2026-09-01 14:22:00','2026-09-01 00:05:00','2026-09-01 14:22:00'),
(5,'INV-2609-017','Starter',1,185.00,'past_due','2026-09-01','2026-09-30','2026-09-01','2026-09-08',NULL,'2026-09-01 00:05:00','2026-09-01 00:05:00'),
(7,'INV-2609-018','Trial',2,0.00,'trial','2026-09-01','2026-09-30','2026-09-01',NULL,NULL,'2026-09-01 00:05:00','2026-09-01 00:05:00');

INSERT INTO `settings` (`key`,`value`,`created_at`,`updated_at`) VALUES
('agency_name','Webefy Today','2026-01-01 00:00:00','2026-01-01 00:00:00'),
('booking_domain','ai-appointment.webefytoday.com','2026-01-01 00:00:00','2026-01-01 00:00:00'),
('support_inbox','support@webefytoday.com','2026-01-01 00:00:00','2026-01-01 00:00:00'),
('default_whatsapp_reminders','1','2026-01-01 00:00:00','2026-01-01 00:00:00'),
('default_ai_confirmation_calls','1','2026-01-01 00:00:00','2026-01-01 00:00:00'),
('default_auto_suspend_past_due','0','2026-01-01 00:00:00','2026-01-01 00:00:00'),
('default_weekly_owner_digest','1','2026-01-01 00:00:00','2026-01-01 00:00:00'),
('reminder_credits_total','10000','2026-01-01 00:00:00','2026-09-01 00:00:00'),
('reminder_credits_used','8412','2026-01-01 00:00:00','2026-09-01 00:00:00'),
('n8n_booking_webhook_url','','2026-01-01 00:00:00','2026-01-01 00:00:00');

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
--  End of file
-- =====================================================================

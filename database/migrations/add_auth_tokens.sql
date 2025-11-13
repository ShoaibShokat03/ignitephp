-- Migration: Add authentication token columns to users table
-- Run this script if you have an existing database without the token columns

ALTER TABLE users 
ADD COLUMN IF NOT EXISTS access_token TEXT DEFAULT NULL AFTER notes,
ADD COLUMN IF NOT EXISTS refresh_token TEXT DEFAULT NULL AFTER access_token,
ADD COLUMN IF NOT EXISTS token_expires_at TIMESTAMP NULL DEFAULT NULL AFTER refresh_token;

-- Note: IF NOT EXISTS is MySQL 8.0.19+ syntax
-- For older MySQL versions, remove IF NOT EXISTS and run manually if columns don't exist


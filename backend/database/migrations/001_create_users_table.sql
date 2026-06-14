-- Users Table Migration (Supabase)
CREATE TABLE IF NOT EXISTS users (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  email_verified_at TIMESTAMP NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'user' CHECK (role IN ('user', 'admin')),
  phone VARCHAR(20),
  avatar VARCHAR(255),
  is_active BOOLEAN DEFAULT true,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Index for faster queries
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);

-- Row Level Security (RLS)
ALTER TABLE users ENABLE ROW LEVEL SECURITY;

-- Policy: Users can view their own profile
CREATE POLICY "Users can view own profile"
  ON users FOR SELECT
  USING (auth.uid()::TEXT = id::TEXT OR (SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin');

-- Policy: Users can update their own profile
CREATE POLICY "Users can update own profile"
  ON users FOR UPDATE
  USING (auth.uid()::TEXT = id::TEXT)
  WITH CHECK (auth.uid()::TEXT = id::TEXT);

-- Policy: Admins can view all users
CREATE POLICY "Admins can view all users"
  ON users FOR SELECT
  USING ((SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin');

-- Policy: Admins can update any user
CREATE POLICY "Admins can update any user"
  ON users FOR UPDATE
  USING ((SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin')
  WITH CHECK ((SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin');

-- Hotels Table Migration (Supabase)
CREATE TABLE IF NOT EXISTS hotels (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE NOT NULL,
  description TEXT,
  address VARCHAR(255),
  city VARCHAR(100),
  country VARCHAR(100),
  star_rating DECIMAL(3, 1) CHECK (star_rating >= 1 AND star_rating <= 5),
  amenities JSONB DEFAULT '[]'::jsonb,
  images JSONB DEFAULT '[]'::jsonb,
  is_active BOOLEAN DEFAULT true,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_hotels_city ON hotels(city);
CREATE INDEX idx_hotels_country ON hotels(country);
CREATE INDEX idx_hotels_is_active ON hotels(is_active);
CREATE INDEX idx_hotels_slug ON hotels(slug);

-- Row Level Security (RLS)
ALTER TABLE hotels ENABLE ROW LEVEL SECURITY;

-- Policy: Everyone can view active hotels
CREATE POLICY "Everyone can view active hotels"
  ON hotels FOR SELECT
  USING (is_active = true OR (SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin');

-- Policy: Admins can manage all hotels
CREATE POLICY "Admins can manage hotels"
  ON hotels FOR ALL
  USING ((SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin')
  WITH CHECK ((SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin');

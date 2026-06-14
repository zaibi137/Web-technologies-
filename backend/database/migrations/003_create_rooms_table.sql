-- Rooms Table Migration (Supabase)
CREATE TABLE IF NOT EXISTS rooms (
  id BIGSERIAL PRIMARY KEY,
  hotel_id BIGINT NOT NULL REFERENCES hotels(id) ON DELETE CASCADE,
  room_type VARCHAR(100),
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price_per_night DECIMAL(10, 2) NOT NULL,
  max_guests INT DEFAULT 2,
  amenities JSONB DEFAULT '[]'::jsonb,
  images JSONB DEFAULT '[]'::jsonb,
  is_available BOOLEAN DEFAULT true,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_rooms_hotel_id ON rooms(hotel_id);
CREATE INDEX idx_rooms_is_available ON rooms(is_available);

-- Row Level Security (RLS)
ALTER TABLE rooms ENABLE ROW LEVEL SECURITY;

-- Policy: Everyone can view available rooms of active hotels
CREATE POLICY "Everyone can view available rooms"
  ON rooms FOR SELECT
  USING (
    is_available = true OR 
    (SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin'
  );

-- Policy: Admins can manage rooms
CREATE POLICY "Admins can manage rooms"
  ON rooms FOR ALL
  USING ((SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin')
  WITH CHECK ((SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin');

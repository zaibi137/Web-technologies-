-- Bookings Table Migration (Supabase)
CREATE TABLE IF NOT EXISTS bookings (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  room_id BIGINT NOT NULL REFERENCES rooms(id) ON DELETE CASCADE,
  hotel_id BIGINT NOT NULL REFERENCES hotels(id) ON DELETE CASCADE,
  check_in_date DATE NOT NULL,
  check_out_date DATE NOT NULL,
  guests INT DEFAULT 1,
  total_price DECIMAL(10, 2),
  status VARCHAR(50) DEFAULT 'pending' CHECK (status IN ('pending', 'confirmed', 'cancelled', 'completed')),
  special_requests TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_bookings_user_id ON bookings(user_id);
CREATE INDEX idx_bookings_room_id ON bookings(room_id);
CREATE INDEX idx_bookings_hotel_id ON bookings(hotel_id);
CREATE INDEX idx_bookings_status ON bookings(status);
CREATE INDEX idx_bookings_check_in ON bookings(check_in_date);

-- Row Level Security (RLS)
ALTER TABLE bookings ENABLE ROW LEVEL SECURITY;

-- Policy: Users can view their own bookings
CREATE POLICY "Users can view own bookings"
  ON bookings FOR SELECT
  USING (user_id = auth.uid()::BIGINT OR (SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin');

-- Policy: Users can create bookings
CREATE POLICY "Users can create bookings"
  ON bookings FOR INSERT
  WITH CHECK (user_id = auth.uid()::BIGINT);

-- Policy: Users can update their own bookings (only cancel)
CREATE POLICY "Users can cancel own bookings"
  ON bookings FOR UPDATE
  USING (user_id = auth.uid()::BIGINT AND status != 'completed')
  WITH CHECK (user_id = auth.uid()::BIGINT AND status != 'completed');

-- Policy: Admins can manage all bookings
CREATE POLICY "Admins can manage bookings"
  ON bookings FOR ALL
  USING ((SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin')
  WITH CHECK ((SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin');

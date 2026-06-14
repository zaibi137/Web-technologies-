-- Payments Table Migration (Supabase)
CREATE TABLE IF NOT EXISTS payments (
  id BIGSERIAL PRIMARY KEY,
  booking_id BIGINT NOT NULL UNIQUE REFERENCES bookings(id) ON DELETE CASCADE,
  amount DECIMAL(10, 2) NOT NULL,
  payment_method VARCHAR(50),
  transaction_id VARCHAR(255),
  status VARCHAR(50) DEFAULT 'pending' CHECK (status IN ('paid', 'failed', 'refunded', 'pending')),
  paid_at TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes
CREATE INDEX idx_payments_booking_id ON payments(booking_id);
CREATE INDEX idx_payments_status ON payments(status);

-- Row Level Security (RLS)
ALTER TABLE payments ENABLE ROW LEVEL SECURITY;

-- Policy: Users can view their own payment records
CREATE POLICY "Users can view own payments"
  ON payments FOR SELECT
  USING (
    booking_id IN (SELECT id FROM bookings WHERE user_id = auth.uid()::BIGINT) OR
    (SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin'
  );

-- Policy: Admins can manage all payments
CREATE POLICY "Admins can manage payments"
  ON payments FOR ALL
  USING ((SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin')
  WITH CHECK ((SELECT role FROM users WHERE id = auth.uid()::BIGINT) = 'admin');

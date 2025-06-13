-- Table venue
CREATE TABLE IF NOT EXISTS venue (
  id SERIAL PRIMARY KEY,
  name TEXT,
  location TEXT,
  price_per_hour INTEGER,
  available_slots INTEGER
);

-- Table booking
CREATE TABLE IF NOT EXISTS booking (
  id SERIAL PRIMARY KEY,
  venue_id INTEGER,
  user_name TEXT,
  booking_time TIMESTAMP,
  duration INTEGER,
  total_price INTEGER
);

-- Table payment
CREATE TABLE IF NOT EXISTS payment (
  id SERIAL PRIMARY KEY,
  booking_id INTEGER,
  amount INTEGER,
  status TEXT,
  paid_at TIMESTAMP
);

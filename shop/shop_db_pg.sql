-- PostgreSQL version of shop_db

-- Table structure for table admins
CREATE TABLE admins (
  id SERIAL PRIMARY KEY,
  name VARCHAR(20) NOT NULL,
  password VARCHAR(50) NOT NULL
);

-- Dumping data for table admins
INSERT INTO admins (name, password) VALUES
('admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2');

-- Table structure for table cart
CREATE TABLE cart (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL,
  pid INTEGER NOT NULL,
  name VARCHAR(100) NOT NULL,
  price INTEGER NOT NULL,
  quantity INTEGER NOT NULL DEFAULT 1,
  image VARCHAR(100) NOT NULL,
  dimensions VARCHAR(500) DEFAULT NULL
);

-- Table structure for table orders
CREATE TABLE orders (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL,
  name VARCHAR(20) NOT NULL,
  number VARCHAR(10) NOT NULL,
  email VARCHAR(50) NOT NULL,
  method VARCHAR(50) NOT NULL,
  address VARCHAR(500) NOT NULL,
  total_products VARCHAR(1000) NOT NULL,
  total_price INTEGER NOT NULL,
  placed_on DATE NOT NULL DEFAULT CURRENT_DATE,
  status VARCHAR(20) NOT NULL DEFAULT 'pending',
  payment_status VARCHAR(20) NOT NULL DEFAULT 'pending',
  payment_id VARCHAR(255) NOT NULL
);

-- Table structure for table order_items
CREATE TABLE order_items (
  id SERIAL PRIMARY KEY,
  order_id INTEGER NOT NULL,
  product_id INTEGER NOT NULL,
  quantity INTEGER NOT NULL DEFAULT 1
);

-- Table structure for table products
CREATE TABLE products (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  details VARCHAR(500) NOT NULL,
  category VARCHAR(100) NOT NULL,
  price INTEGER NOT NULL,
  image_01 VARCHAR(100) NOT NULL,
  image_02 VARCHAR(100) NOT NULL,
  image_03 VARCHAR(100) NOT NULL,
  dimensions VARCHAR(500) DEFAULT NULL
);

-- Table structure for table product_pairs
CREATE TABLE product_pairs (
  id SERIAL PRIMARY KEY,
  product_id_1 INTEGER NOT NULL,
  product_id_2 INTEGER NOT NULL,
  count INTEGER DEFAULT 1
);

-- Table structure for table users
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  name VARCHAR(20) NOT NULL,
  email VARCHAR(50) NOT NULL,
  password VARCHAR(50) NOT NULL
);

-- Table structure for table wishlist
CREATE TABLE wishlist (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL,
  pid INTEGER NOT NULL,
  name VARCHAR(100) NOT NULL,
  price INTEGER NOT NULL,
  image VARCHAR(100) NOT NULL
); 
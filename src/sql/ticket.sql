-- Создание таблицы `admin`
CREATE TABLE admin (
  id SERIAL PRIMARY KEY,
  email TEXT NOT NULL,
  password TEXT NOT NULL
);

-- Вставка данных в таблицу `admin`
INSERT INTO admin (email, password) VALUES
('admin@admin.com', 'admin');

-- Создание таблицы `customers`
CREATE TABLE customers (
  id SERIAL PRIMARY KEY,
  last_name TEXT NOT NULL,
  first_name TEXT NOT NULL,
  email VARCHAR(30) NOT NULL,
  password VARCHAR(20) NOT NULL,
  credit_card_num TEXT NOT NULL,
  credit_card_expiry_date TEXT NOT NULL
);

-- Вставка данных в таблицу `customers`
INSERT INTO customers (last_name, first_name, email, password, credit_card_num, credit_card_expiry_date) VALUES
('Mostafa', 'Abdullah', 'theabdullahem@gmail.com', 'qqqqqq', '9834674398753487', '12/2030'),
('Bill', 'Gates', 'bill.gates@microsoft.com', 'aaaaaa', '1928364817389475', '09/2029'),
('Jackie', 'Chan', 'jc@company.com', 'qqqqqq', '36492273909461275', '10/2030'),
('Elon', 'Musk', 'elon@paypal.com', 'qqqqqq', '8364829506913745', '02/2020'),
('Bob', 'Bobby', 'bob@gmail.com', '123abc', '1234567890987654', '7/2029'),
('M', 'Ace', 'iace@gmail.com', '123abc', '1234567890987654', '7/2020');

-- Создание таблицы `orders`
CREATE TABLE orders (
  id SERIAL PRIMARY KEY,
  date DATE NOT NULL,
  time TIME NOT NULL,
  sub_total DECIMAL(6,0) NOT NULL,
  hst DECIMAL(6,0) NOT NULL,
  total_cost DECIMAL(6,0) NOT NULL,
  customer_id INT NOT NULL,
  FOREIGN KEY (customer_id) REFERENCES customers(id)
);

-- Вставка данных в таблицу `orders`
INSERT INTO orders (date, time, sub_total, hst, total_cost, customer_id) VALUES
('2015-08-02', '17:29:34', 34, 4, 38, 2),
('2016-02-19', '17:31:02', 18, 2, 20, 4);

-- Создание таблицы `seats`
CREATE TABLE seats (
  id SERIAL PRIMARY KEY,
  seat TEXT NOT NULL,
  price DECIMAL(4,0) NOT NULL,
  order_id INT NOT NULL
);

-- Вставка данных в таблицу `seats`
INSERT INTO seats (seat, price, order_id) VALUES
('A1', 20, 0),
('A2', 20, 0),
('A3', 20, 1),
('A4', 20, 0),
('B1', 18, 2),
('B2', 18, 0),
('B3', 18, 0),
('B4', 18, 0),
('C1', 16, 0),
('C2', 16, 0),
('C3', 16, 0),
('C4', 16, 0),
('D1', 14, 1),
('D2', 14, 0),
('D3', 14, 0),
('D4', 14, 0);
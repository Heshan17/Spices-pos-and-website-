-- Spice Shop POS database (MySQL / MariaDB). Import via phpMyAdmin, or let api.php install it automatically.
CREATE DATABASE IF NOT EXISTS spice_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE spice_pos;
CREATE TABLE IF NOT EXISTS roles(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(40) NOT NULL UNIQUE, perms VARCHAR(120) NOT NULL DEFAULT 'pos') ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS users(id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(30) NOT NULL UNIQUE, full_name VARCHAR(80) NOT NULL, pass_hash VARCHAR(255) NOT NULL, role_id INT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(role_id) REFERENCES roles(id)) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS security_answers(user_id INT NOT NULL, slot TINYINT NOT NULL, question_idx TINYINT NOT NULL, answer_hash VARCHAR(255) NOT NULL, PRIMARY KEY(user_id,slot), FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS products(id INT AUTO_INCREMENT PRIMARY KEY, code VARCHAR(12) UNIQUE, name VARCHAR(100) NOT NULL, category VARCHAR(40), price DECIMAL(10,2) NOT NULL, unit VARCHAR(20) NOT NULL DEFAULT 'unit', stock INT NOT NULL DEFAULT 0, image MEDIUMTEXT, emoji VARCHAR(16), color VARCHAR(9), active TINYINT NOT NULL DEFAULT 1) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS sales(id INT AUTO_INCREMENT PRIMARY KEY, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, channel VARCHAR(20) NOT NULL, customer VARCHAR(160), status VARCHAR(10) NOT NULL DEFAULT 'done', discount DECIMAL(10,2) NOT NULL DEFAULT 0, total DECIMAL(12,2) NOT NULL, payment VARCHAR(10) NOT NULL DEFAULT 'Cash', paid DECIMAL(12,2) NOT NULL DEFAULT 0, staff VARCHAR(30) NOT NULL, KEY(created_at), KEY(channel)) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS sale_items(id INT AUTO_INCREMENT PRIMARY KEY, sale_id INT NOT NULL, product_id INT, product_name VARCHAR(100) NOT NULL, qty INT NOT NULL, unit_price DECIMAL(10,2) NOT NULL, FOREIGN KEY(sale_id) REFERENCES sales(id) ON DELETE CASCADE, FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE SET NULL) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS login_attempts(id INT AUTO_INCREMENT PRIMARY KEY, ip VARCHAR(45) NOT NULL, ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP, KEY(ip,ts)) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS spice_blends(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, total_weight_g DECIMAL(10,2) NOT NULL DEFAULT 0, pack_unit VARCHAR(20) NOT NULL DEFAULT '100g', total_cost DECIMAL(10,2) NOT NULL DEFAULT 0, cost_per_unit DECIMAL(10,2) NOT NULL DEFAULT 0, selling_price DECIMAL(10,2) NOT NULL DEFAULT 0, profit_amount DECIMAL(10,2) NOT NULL DEFAULT 0, margin_pct DECIMAL(6,2) NOT NULL DEFAULT 0, ingredients MEDIUMTEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB;
INSERT IGNORE INTO roles(name,perms) VALUES ('Admin','pos,products,analytics,orders,users'),('Cashier','pos,orders'),('Stock Manager','pos,products');
INSERT IGNORE INTO products(id,code,name,price,unit,stock,image,emoji,color) VALUES
(1,'SP001','Cinnamon (Ceylon)',950,'100g',40,'assets/img/cinnamon.jpg','🪵','#f7d9b5'),
(2,'SP002','Cloves',1200,'100g',25,'assets/img/cloves.jpg','🌰','#f3c6a5'),
(3,'SP003','Cardamom',2400,'100g',18,'assets/img/cardamom.jpg','🫛','#d9e8c3'),
(4,'SP004','Black Pepper',700,'100g',60,'assets/img/black-pepper.jpg','⚫','#f6e3a1'),
(5,'SP005','Turmeric Powder',350,'100g',50,'assets/img/turmeric.jpg','🟡','#e8cdb3'),
(6,'SP006','Chilli Powder',420,'100g',45,'assets/img/chilli-powder.jpg','🌶️','#f2b8a8'),
(7,'SP007','Chilli Flakes',480,'100g',30,'assets/img/chilli-flakes.jpg','🔥','#f7d9b5'),
(8,'SP008','Coriander Powder',300,'100g',35,'assets/img/coriander.jpg','🌿','#f3c6a5'),
(9,'SP009','Cumin Seeds',520,'100g',3,'assets/img/cumin.jpg','🟤','#d9e8c3'),
(10,'SP010','Fennel Seeds',380,'100g',28,'assets/img/fennel.jpg','🌾','#f6e3a1'),
(11,'SP011','Fenugreek',250,'100g',22,'assets/img/fenugreek.jpg','🫘','#e8cdb3'),
(12,'SP012','Mustard Seeds',230,'100g',4,'assets/img/mustard.jpg','🟠','#f2b8a8'),
(13,'SP013','Nutmeg',1500,'100g',12,'assets/img/nutmeg.jpg','🥜','#f7d9b5'),
(14,'SP014','Mace',2800,'50g',9,'assets/img/mace.jpg','🏵️','#f3c6a5'),
(15,'SP015','Curry Powder',400,'100g',55,'assets/img/curry-powder.jpg','🍛','#d9e8c3'),
(16,'SP016','Roasted Curry Powder',450,'100g',48,'assets/img/roasted-curry.jpg','🥘','#f6e3a1'),
(17,'SP017','Goraka',600,'100g',15,'assets/img/goraka.jpg','🍊','#e8cdb3'),
(18,'SP018','Curry Leaves',100,'bunch',70,'assets/img/curry-leaves.jpg','🍃','#f2b8a8'),
(19,'SP019','Pandan Leaves',80,'bunch',40,'assets/img/pandan.jpg','🌱','#f7d9b5'),
(20,'SP020','Lemongrass',90,'bunch',33,'assets/img/lemongrass.jpg','🎋','#f3c6a5'),
(21,'SP021','Dry Ginger',650,'100g',20,'assets/img/dry-ginger.jpg','🫚','#d9e8c3'),
(22,'SP022','Garlic Powder',550,'100g',26,'assets/img/garlic-powder.jpg','🧄','#f6e3a1'),
(23,'SP023','Star Anise',1100,'50g',5,'assets/img/star-anise.jpg','⭐','#e8cdb3'),
(24,'SP024','Bay Leaves',200,'50g',30,'assets/img/bay-leaves.jpg','🍂','#f2b8a8'),
(25,'SP025','Saffron',4500,'1g',6,'assets/img/saffron.jpg','🧡','#f7d9b5'),
(26,'SP026','Tamarind',280,'250g',38,'assets/img/tamarind.jpg','🟫','#f3c6a5'),
(27,'SP027','Maldive Fish',1800,'100g',14,'assets/img/maldive-fish.jpg','🐟','#d9e8c3'),
(28,'SP028','Coconut Milk Powder',320,'300g',44,'assets/img/coconut-milk.jpg','🥥','#f6e3a1'),
(29,'SP029','Sea Salt',120,'500g',80,'assets/img/sea-salt.jpg','🧂','#e8cdb3'),
(30,'SP030','Vanilla Pods',1900,'10pcs',11,'assets/img/vanilla.jpg','🍦','#f2b8a8'),
(31,'SP031','Cinnamon sticks (කුරුඳු පොතු)',980,'100g',35,'assets/img/cinnamon-sticks.jpg','🪵','#f7d9b5'),
(32,'SP032','Garcinia paste (ගොරකා පේස්ට්)',750,'200g',25,'assets/img/garcinia-paste.jpg','🫙','#3a271d'),
(33,'SP033','Derana polthel (දෙරණ පොල්තෙල්)',850,'375ml',40,'assets/img/derana-polthel.jpg','🥥','#f6e3a1'),
(34,'SP034','Walnut (වොල්නට්)',1650,'100g',20,'assets/img/walnut.jpg','🥜','#e8cdb3'),
(35,'SP035','Pistachio (පිස්ටාචියෝ)',1850,'100g',18,'assets/img/pistachio.jpg','🫘','#d9e8c3'),
(36,'SP036','Pumpkin seeds (වට්ටක්කා ඇට)',620,'100g',30,'assets/img/pumpkin-seeds.jpg','🎃','#d9e8c3'),
(37,'SP037','Chia seeds (චියා ඇට)',580,'100g',35,'assets/img/chia-seeds.jpg','🌱','#e8cdb3'),
(38,'SP038','Cashew nuts (කජු)',1400,'100g',45,'assets/img/cashew-nuts.jpg','🥜','#f7d9b5'),
(39,'SP039','Flex seeds (හණ ඇට)',420,'100g',28,'assets/img/flex-seeds.jpg','🌾','#f3c6a5'),
(40,'SP040','Basil seeds (කසකසා ඇට)',390,'100g',32,'assets/img/basil-seeds.jpg','⚫','#e8cdb3');


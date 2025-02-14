-- MySQL dump 10.13  Distrib 8.0.41, for Linux (x86_64)
--
-- Host: localhost    Database: Restaurant
-- ------------------------------------------------------
-- Server version	8.0.41-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_name` varchar(100) NOT NULL,
  `quantity` int DEFAULT NULL,
  `reorder_level` int DEFAULT NULL,
  `supplier_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier_id` (`supplier_id`),
  CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (1,'Tomatoes',100,NULL,1),(2,'Lettuce',75,NULL,1),(3,'Spinach',50,NULL,1),(4,'Bell Peppers',60,NULL,1),(5,'Zucchini',45,NULL,1),(6,'Carrots',80,NULL,1),(7,'Onions',90,NULL,1),(8,'Garlic',30,NULL,1),(9,'Potatoes',120,NULL,1),(10,'Cucumbers',55,NULL,1),(11,'Mozzarella Cheese',50,NULL,2),(12,'Parmesan Cheese',30,NULL,2),(13,'Butter',40,NULL,2),(14,'Cream',25,NULL,2),(15,'Milk',60,NULL,2),(16,'Yogurt',35,NULL,2),(17,'Ricotta Cheese',20,NULL,2),(18,'Eggs',150,NULL,2),(19,'Sour Cream',15,NULL,2),(20,'Béchamel Sauce',10,NULL,2),(21,'Shrimp',30,NULL,3),(22,'Salmon Fillets',20,NULL,3),(23,'Tuna Steaks',15,NULL,3),(24,'Calamari',25,NULL,3),(25,'Scallops',10,NULL,3),(26,'Cod Fillets',18,NULL,3),(27,'Crab Legs',12,NULL,3),(28,'Clams',20,NULL,3),(29,'Mussels',22,NULL,3),(30,'Lobster Tails',8,NULL,3),(31,'Flour',200,NULL,4),(32,'Bread',50,NULL,4),(33,'Ciabatta',30,NULL,4),(34,'Croissants',40,NULL,4),(35,'Baguettes',35,NULL,4),(36,'Pita Bread',45,NULL,4),(37,'Rolls',60,NULL,4),(38,'Pasta',150,NULL,4),(39,'Pizza Dough',50,NULL,4),(40,'Pastry Sheets',25,NULL,4),(41,'Red Wine',60,NULL,5),(42,'White Wine',45,NULL,5),(43,'Sparkling Water',80,NULL,5),(44,'Soda',100,NULL,5),(45,'Juice',70,NULL,5),(46,'Beer',90,NULL,5),(47,'Coffee Beans',20,NULL,5),(48,'Tea Bags',150,NULL,5),(49,'Espresso Pods',100,NULL,5),(50,'Lemonade',50,NULL,5),(51,'Salt',50,NULL,6),(52,'Pepper',30,NULL,6),(53,'Oregano',20,NULL,6),(54,'Basil',15,NULL,6),(55,'Rosemary',10,NULL,6),(56,'Thyme',12,NULL,6),(57,'Cinnamon',8,NULL,6),(58,'Paprika',10,NULL,6),(59,'Chili Powder',15,NULL,6),(60,'Curry Powder',10,NULL,6),(61,'Beef',40,NULL,7),(62,'Chicken Breasts',50,NULL,7),(63,'Pork Chops',30,NULL,7),(64,'Lamb Chops',20,NULL,7),(65,'Ground Beef',60,NULL,7),(66,'Sausages',35,NULL,7),(67,'Bacon',25,NULL,7),(68,'Ham',15,NULL,7),(69,'Turkey Breast',10,NULL,7),(70,'Venison',8,NULL,7),(71,'Tagliatelle pasta',50,NULL,8),(72,'Pappardelle pasta',40,NULL,8),(73,'Lasagna noodles',30,NULL,8),(74,'Penne pasta',60,NULL,8),(75,'Spaghetti',70,NULL,8),(76,'Chocolate',80,NULL,10),(77,'Cocoa Powder',30,NULL,10),(78,'Vanilla Extract',20,NULL,10),(79,'Sugar',100,NULL,10),(80,'Flour (for desserts)',50,NULL,10),(81,'Butter (for desserts)',20,NULL,10),(82,'Eggs (for desserts)',50,NULL,10),(83,'Whipped Cream',15,NULL,10),(84,'Fruit Compote',25,NULL,10),(85,'Pastry Cream',10,NULL,10);
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_categories`
--

DROP TABLE IF EXISTS `menu_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_categories`
--

LOCK TABLES `menu_categories` WRITE;
/*!40000 ALTER TABLE `menu_categories` DISABLE KEYS */;
INSERT INTO `menu_categories` VALUES (1,'Pizza','active'),(2,'Pasta','active'),(3,'Appetizers','active'),(4,'Drinks','active'),(5,'Desserts','active');
/*!40000 ALTER TABLE `menu_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `availability` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (1,'Margherita','Classic Italian pizza with tomato sauce, fresh mozzarella, and basil.',10.99,'https://images.unsplash.com/photo-1598023696416-0193a0bcd302?q=80&w=1536&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',1,1),(2,'Quattro Formaggi','Pizza with four cheeses: mozzarella, gorgonzola, parmesan, and ricotta.',12.99,'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',1,1),(3,'Diavola','Spicy pizza with tomato sauce, mozzarella, and spicy salami.',11.99,'https://images.pexels.com/photos/30504707/pexels-photo-30504707/free-photo-of-delicious-pepperoni-pizza-with-slice-removed.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',1,1),(4,'Capricciosa','Pizza with tomato sauce, mozzarella, ham, mushrooms, artichokes, and olives.',13.99,'https://images.unsplash.com/photo-1576458088443-04a19bb13da6?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',1,1),(5,'Marinara','Simple pizza with tomato sauce, garlic, oregano, and olive oil.',9.99,'https://images.pexels.com/photos/12891059/pexels-photo-12891059.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',1,1),(6,'Prosciutto e Funghi','Pizza with tomato sauce, mozzarella, ham, and mushrooms.',12.99,'https://redketchup.it/wp-content/uploads/2024/03/pizza-con-prosciutto-e-funghi.webp',1,1),(7,'Calzone','Folded pizza filled with tomato sauce, mozzarella, ham, and ricotta.',11.99,'https://whiskedawaykitchen.com/wp-content/uploads/2024/08/italian-sausage-calzone-10-780x1170.webp',1,1),(8,'Vegetariana','Pizza with tomato sauce, mozzarella, and a variety of vegetables.',12.99,'https://hoytoca-cms.ext-sites-prd.cloudherdez.com/assets/b0d29d09-852e-42e1-9e66-b486a01e25a3',1,1),(9,'Bufalina','Pizza with tomato sauce and fresh buffalo mozzarella.',14.99,'https://www.tasteatlas.com/images/dishes/.jpg',1,1),(10,'Napoli','Pizza with tomato sauce, mozzarella, anchovies, and olives.',11.99,'https://www.donnamoderna.com/content/uploads/2021/08/pizza-napoli-830x625.jpg',1,1),(11,'Spaghetti Carbonara','Pasta with eggs, cheese, pancetta, and black pepper.',12.99,'https://www.sipandfeast.com/wp-content/uploads/2022/09/spaghetti-carbonara-recipe-snippet.jpg',2,1),(12,'Penne all\'Arrabbiata','Pasta with a spicy tomato sauce made with garlic and red chili peppers.',11.99,'https://www.vincenzosplate.com/wp-content/uploads/2023/07/1500x1500-Photo-11_2645-How-to-Make-Penne-allArrabbiata-Like-an-Italian-The-Angry-Pasta-V1.jpg',2,1),(13,'Lasagna','Layered pasta with meat sauce, béchamel, and cheese.',13.99,'https://www.unileverfoodsolutions.com.ph/dam/global-ufs/mcos/SEA/calcmenu/recipes/PH-recipes/the-vegetarian-butcher/lasagna/1245x600_Lasagna.jpg',2,1),(14,'Shrimp Fettuccine Alfredo','Pasta with a creamy sauce made from butter and parmesan cheese with shrimp.',12.99,'https://www.thespruceeats.com/thmb/gTjo1gnOuBEVJsttgDW2JljvKY0=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/shrimp-fettuccine-alfredo-recipe-5205738-hero-01-.jpg',2,1),(15,'Ravioli al Formaggio','Stuffed pasta with ricotta and spinach, served with tomato sauce.',14.99,'https://media.marketspread.com/storage/a349f9cd-c814-41a2-b7a9-fcc0acf7a495.apng',2,1),(16,'Gnocchi alla Sorrentina','Potato dumplings baked with tomato sauce and mozzarella.',13.99,'https://www.giallozafferano.it/images/ricette/197/19745/foto_hd/hd650x433_wm.jpg',2,1),(17,'Pappardelle al Cinghiale','Wide pasta with a wild boar ragu.',15.99,'https://images.squarespace-cdn.com/content/v1//1585687131209-EUANN8EMFMEVUUVJEGFA/IMG_0411.jpeg',2,1),(18,'Tagliatelle al Tartufo','Pasta with a creamy truffle sauce.',16.99,'https://www.giallozafferano.com/images/243-24328/Truffle-fettuccine-650x433_wm.jpg',2,1),(19,'Cacio e Pepe','Pasta with a simple sauce of cheese and black pepper.',11.99,'https://www.177milkstreet.com/assets/site/Recipes/_large/Updated-Cacio-e-Pepe.jpg',2,1),(20,'Rigatoni alla Vodka','Pasta with a creamy tomato and vodka sauce.',12.99,'https://www.eatbanza.com/cdn/shop/articles/37116D37-0B87-445D-B86B-951FFD194EF0_1200x1200.JPG?v=1590596326',2,1),(21,'Bruschetta','Toasted bread topped with tomatoes, garlic, and basil.',6.99,'https://www.stephiecooks.com/wp-content/uploads/2014/08/tomato-bruschetta-on-platter-hero.jpg',3,1),(22,'Caprese Salad','Fresh mozzarella, tomatoes, and basil drizzled with olive oil.',8.99,'https://www.thespruceeats.com/thmb/2pjgFA7_nbZtlXr68BECvf6fO48=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/caprese-salad-tomato-salad-2217097-hero-03-.jpg',3,1),(23,'Arancini','Fried rice balls stuffed with mozzarella and ragù.',7.99,'https://www.vincenzosplate.com/wp-content/uploads/2022/09/1500x1500-Photo-5_1949-How-to-Make-ARANCINI-CARBONARA-Like-an-Italian-V1.jpg',3,1),(24,'Prosciutto e Melone','Thinly sliced ham served with fresh cantaloupe.',9.99,'https://vaya.in/recipes/wp-content/uploads/2018/05/Prosciutto-e-Melone.jpg',3,1),(25,'Calamari Fritti','Fried calamari served with marinara sauce.',10.99,'https://basilandbubbly.com/wp-content/uploads/2021/04/fried-calamari-6.jpg',3,1),(26,'Antipasto Misto','A mixed platter of cured meats, cheeses, and vegetables.',12.99,'https://d1mf4ril8efyfr.cloudfront.net/media/store_872/products/e85a0add-7f44-4fb5-a443-ac4a6e429c27.jpg',3,1),(27,'Focaccia','Italian flatbread topped with olive oil and rosemary.',5.99,'https://www.allrecipes.com/thmb/uf3d7ybvsbXOVqS00vXqf0_MOhI=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/8443216-no-knead-big-bubble-focaccia-VAT-hero-4x3-.jpg',3,1),(28,'Zuppa di Cozze','Mussels cooked in a tomato and white wine sauce.',11.99,'https://primochef.it/wp-content/uploads/2021/04/SH_zuppa_di_cozze.jpg.webp',3,1),(29,'Insalata di Mare','Seafood salad with shrimp, calamari, and mussels.',13.99,'https://www.fattoincasadabenedetta.it/wp-content/uploads/2021/07/INSALATA-DI-MARE-sito4.jpg',3,1),(30,'Crostini ai Funghi','Toasted bread topped with a mushroom ragù.',8.99,'https://primochef.it/wp-content/uploads/2022/07/SH_crostini_ai_funghi.jpg',3,1),(31,'Espresso','A strong black coffee served in a small cup.',2.99,'https://www.seriouseats.com/thmb/MS_5p-UVwIHCrr5MaXonYJajP7o=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/__opt__aboutcom__coeus__resources__serious_eats__seriouseats.com__2018__06__20180613-coffee-vs-espresso-vicky-wasik-3-1500x1125-.jpg',4,1),(32,'Cappuccino','Espresso with steamed milk and a layer of foam.',3.99,'https://www.allrecipes.com/thmb/chsZz0jqIHWYz39ViZR-9k_BkkE=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/8624835-how-to-make-a-cappuccino-beauty-4x3-0301-.jpg',4,1),(33,'Latte Macchiato','Steamed milk with a shot of espresso.',4.99,'https://podmkr.com/wp-content/uploads/2023/11/latte-macchiato-500x500.png',4,1),(34,'Aperol Spritz','A refreshing cocktail made with Aperol, prosecco, and soda water.',7.99,'https://www.inspiredtaste.net/wp-content/uploads/2023/11/Aperol-Spritz-Recipe-1-1200.jpg',4,1),(35,'Negroni','A classic Italian cocktail made with gin, vermouth, and Campari.',8.99,'https://mixthatdrink.com/wp-content/uploads/2023/03/negroni-cocktail-2.jpg',4,1),(36,'Limonata','Freshly squeezed lemonade.',3.99,'https://www.hungrypinner.com/wp-content/uploads/2017/09/limonata-turkish-lemonade-2-social.jpg',4,1),(37,'Prosecco','Italian sparkling wine.',9.99,'https://media-cdn2.greatbritishchefs.com/media/yf2bjxio/img38191.whqc_660x440q80.jpg',4,1),(38,'Chianti','A red wine from Tuscany.',12.99,'https://www.nationaldaycalendar.com/.image/ar_16:9%2Cc_fill%2Ccs_srgb%2Cg_faces:center%2Cq_auto:eco%2Cw_768/MjA4NTY4NjkyODQwNjA0ODQ1/website-feature---national-chianti-day--first-friday-in-september.png',4,1),(39,'Sangiovese','A red wine with a rich, fruity flavor.',14.99,'https://m.media-amazon.com/images/I/61hY8qQA-yL.jpg',4,1),(40,'Bellini','A cocktail made with prosecco and peach purée.',8.99,'https://www.realsimple.com/thmb/9WN8Lyp6vIv67YDugTkJXnZe990=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/bellini-recipe-GettyImages-1147319663-.jpg',4,1),(41,'Tiramisu','A classic Italian dessert made with coffee-soaked ladyfingers and mascarpone cream.',6.99,'https://www.flavoursholidays.co.uk/wp-content/uploads/2020/07/Tiramisu.jpg',5,1),(42,'Panna Cotta','A creamy dessert served with a berry sauce.',5.99,'https://www.cookingclassy.com/wp-content/uploads/2021/05/panna-cotta-01-500x500.jpg',5,1),(43,'Cannoli','Crispy pastry tubes filled with sweet ricotta cream.',4.99,'https://hips.hearstapps.com/hmg-prod/images/cannoli-index-66a8204f9ba05.jpg?crop=0.8892201929846358xw:1xh;center,top&resize=1200:*',5,1),(44,'Gelato','Italian ice cream available in various flavors.',3.99,'https://vicioilmastropastaio.com/wp-content/uploads/2024/05/artisan-gelato-in-amsterdam.jpg',5,1),(45,'Zabaglione','A light dessert made with egg yolks, sugar, and sweet wine.',7.99,'https://www.redpathsugar.com/sites/redpathsugar_com/files/Zabaglione_hero_landscape_web-1.jpg',5,1),(46,'Sfogliatella','A shell-shaped pastry filled with ricotta and candied fruit.',5.99,'https://www.nonnabox.com/wp-content/uploads/sfogliatelle-01.jpg',5,1),(47,'Torta della Nonna','A traditional Italian custard tart topped with pine nuts.',6.99,'https://blog.giallozafferano.it/dulcisinforno/wp-content/uploads/2024/06/Torta-della-nonna-7918.jpg',5,1),(48,'Biscotti','Twice-baked almond cookies, perfect for dipping in coffee.',3.99,'https://thecozyapron.com/wp-content/uploads/2020/12/biscotti_thecozyapron_1.jpg',5,1),(49,'Cheesecake al Limone','Lemon-flavored cheesecake with a biscuit base.',7.99,'https://www.giallozafferano.it/images/ricette/167/16701/foto_hd/hd650x433_wm.jpg',5,1),(50,'Crostata di Frutta','A fruit tart with a buttery crust and fresh fruit topping.',8.99,'https://www.lucake.it/wp-content/uploads/2022/10/crostata-di-frutta.jpg',5,1);
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offer_menu_items`
--

DROP TABLE IF EXISTS `offer_menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offer_menu_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `offer_id` int DEFAULT NULL,
  `menu_item_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `offer_id` (`offer_id`),
  KEY `menu_item_id` (`menu_item_id`),
  CONSTRAINT `offer_menu_items_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `special_offers` (`id`),
  CONSTRAINT `offer_menu_items_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offer_menu_items`
--

LOCK TABLES `offer_menu_items` WRITE;
/*!40000 ALTER TABLE `offer_menu_items` DISABLE KEYS */;
INSERT INTO `offer_menu_items` VALUES (1,1,1),(2,1,2),(3,1,3),(4,1,4),(5,1,5),(6,1,6),(7,1,7),(8,1,8),(9,1,9),(10,1,10),(11,3,11),(12,3,12),(13,3,13),(14,3,14),(15,3,15),(16,3,16),(17,3,17),(18,3,18),(19,3,19),(20,3,20),(21,5,41),(22,5,42),(23,5,43),(24,5,44),(25,5,45),(26,5,46),(27,5,47),(28,5,48),(29,5,49),(30,5,50);
/*!40000 ALTER TABLE `offer_menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `customization` text,
  `price_at_order` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('pending','paid','refunded') DEFAULT 'pending',
  `status` enum('pending','preparing','ready','delivered') DEFAULT 'pending',
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `table_id` int DEFAULT NULL,
  `reservation_date` datetime DEFAULT NULL,
  `guests` int DEFAULT NULL,
  `status` enum('confirmed','cancelled') DEFAULT 'confirmed',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `table_id` (`table_id`),
  CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `special_offers`
--

DROP TABLE IF EXISTS `special_offers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `special_offers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `discount` decimal(5,2) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `applicable_to` enum('all','specific') DEFAULT 'all',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `special_offers`
--

LOCK TABLES `special_offers` WRITE;
/*!40000 ALTER TABLE `special_offers` DISABLE KEYS */;
INSERT INTO `special_offers` VALUES (1,'Buy One Get One Free on Pizzas','Get one free pizza when you buy any pizza.',50.00,'2025-03-31','specific','2025-02-14 18:12:33'),(2,'Happy Hour Discount','Enjoy 20% off on all drinks between 4 PM and 7 PM.',20.00,'2025-03-31','all','2025-02-14 18:12:33'),(3,'Weekend Pasta Special','Get 25% off on all pasta dishes every weekend.',25.00,'2025-03-31','specific','2025-02-14 18:12:33'),(4,'Early Bird Dinner','Enjoy 15% off on your entire meal if you dine before 6 PM.',15.00,'2025-03-31','all','2025-02-14 18:12:33'),(5,'Dessert Delight','Buy any main course and get a free dessert.',100.00,'2025-03-31','specific','2025-02-14 18:12:33');
/*!40000 ALTER TABLE `special_offers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier_inventory`
--

DROP TABLE IF EXISTS `supplier_inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `supplier_inventory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `supplier_id` int DEFAULT NULL,
  `inventory_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `inventory_id` (`inventory_id`),
  CONSTRAINT `supplier_inventory_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  CONSTRAINT `supplier_inventory_ibfk_2` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier_inventory`
--

LOCK TABLES `supplier_inventory` WRITE;
/*!40000 ALTER TABLE `supplier_inventory` DISABLE KEYS */;
/*!40000 ALTER TABLE `supplier_inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'Fresh Produce Co.','+1-800-555-1234','info@freshproduce.com'),(2,'Local Dairy Farm','+1-800-555-5678','sales@localdairyfarm.com'),(3,'Seafood Delights','+1-800-555-9101','orders@seafooddelights.com'),(4,'Bakery Supplies Ltd.','+1-800-555-2345','contact@bakerysupplies.com'),(5,'Wine Importers Inc.','+1-800-555-3456','support@wineimporters.com'),(6,'Spice Traders','+1-800-555-7890','info@spicetraders.com'),(7,'Meat Market','+1-800-555-6789','sales@meatmarket.com'),(8,'Pasta Makers','+1-800-555-4321','orders@pastamakers.com'),(9,'Ice Cream Factory','+1-800-555-1122','info@icecreamfactory.com'),(10,'Chocolate Supplier','+1-800-555-2233','sales@chocolatesupplier.com');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tables`
--

DROP TABLE IF EXISTS `tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tables` (
  `id` int NOT NULL AUTO_INCREMENT,
  `table_number` int NOT NULL,
  `capacity` int NOT NULL,
  `location` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_number` (`table_number`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tables`
--

LOCK TABLES `tables` WRITE;
/*!40000 ALTER TABLE `tables` DISABLE KEYS */;
INSERT INTO `tables` VALUES (1,1,4,'1st Floor'),(2,2,6,'1st Floor'),(3,3,2,'1st Floor'),(4,4,8,'1st Floor'),(5,5,4,'1st Floor'),(6,6,6,'2nd Floor'),(7,7,2,'2nd Floor'),(8,8,10,'2nd Floor'),(9,9,4,'2nd Floor'),(10,10,6,'2nd Floor');
/*!40000 ALTER TABLE `tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('staff','customer') DEFAULT 'customer',
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-14 20:27:26

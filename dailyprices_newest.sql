-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2018 at 12:29 AM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dailyprices_newest`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `calculate_discount` ()  BEGIN
  DECLARE row_counter INT DEFAULT 0;
  DECLARE row_all INT DEFAULT 0;
  DECLARE old_price DOUBLE;
  DECLARE new_price DOUBLE;
  DECLARE discount DOUBLE;
  
  SELECT COUNT(*) INTO row_all FROM offers;
  
  ALTER TABLE offers ADD offer_discount DECIMAL(4,4) NOT NULL AFTER new_price;
  
  WHILE row_counter < row_all DO
    SET row_counter = row_counter + 1;
    
    SELECT offers.old_price INTO old_price FROM offers WHERE offers.offer_id = row_counter;
    SELECT offers.new_price INTO new_price FROM offers WHERE offers.offer_id = row_counter;
    
    SET discount = 1 - (new_price / old_price);
    
    UPDATE offers SET offers.offer_discount = discount WHERE offers.offer_id = row_counter;
  END WHILE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_table_offers` ()  BEGIN
DROP TABLE IF EXISTS offers;

CREATE TABLE offers AS SELECT DISTINCT product_child.prod_child_bbd AS best_before_date, products.prod_id AS product_id, products.prod_brand AS brand, products.prod_name AS product_name, stores.store_id, products.prod_price AS old_price, product_child.prod_child_price AS new_price, products.prod_unit AS unit, products.prod_cat AS category, products.prod_subcat AS subcategory, products.prod_img1 AS image, stores.store_name AS store_name, stores.store_address AS store_address, stores.store_suburb AS store_suburb, stores.store_city AS store_city, stores.store_phone AS store_phone, stores.store_lat, stores.store_long 
FROM products
LEFT JOIN product_child ON products.prod_id = product_child.prod_id
LEFT JOIN stores ON products.prod_store_id = stores.store_id
WHERE product_child.prod_child_special = 1 AND DATEDIFF(product_child.prod_child_bbd, CURDATE()) > 0;

ALTER TABLE `offers` ADD `offer_id` INT(20) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`offer_id`);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `offers_add_quantity` ()  BEGIN
  DECLARE row_counter INT DEFAULT 0;
  DECLARE row_all INT;
  DECLARE best_before DATE;
  DECLARE product_ID INT;
  DECLARE store_ID INT;
  DECLARE offer_quantity INT;
  DECLARE price DOUBLE;
  SELECT COUNT(*) INTO row_all FROM offers;
  
  ALTER TABLE offers ADD offer_quantity INT(255) NOT NULL ;
  
  WHILE row_counter < row_all DO
    SET row_counter = row_counter + 1;
    SELECT offers.best_before_date INTO best_before FROM offers WHERE offers.offer_id = row_counter;
    SELECT offers.product_id INTO product_ID FROM offers WHERE offers.offer_id = row_counter;
    SELECT offers.store_id INTO store_ID FROM offers WHERE offers.offer_id = row_counter;
    SELECT offers.new_price into price from offers WHERE offers.offer_id = row_counter;
    
    SELECT COUNT(*) INTO offer_quantity FROM products
    LEFT JOIN product_child ON products.prod_id = product_child.prod_id
    LEFT JOIN stores ON products.prod_store_id = stores.store_id
    WHERE products.prod_id = product_ID AND product_child.prod_child_bbd = best_before AND stores.store_id = store_ID AND product_child.prod_child_special = 1 AND product_child.prod_child_price = price;
    
    UPDATE offers SET offers.offer_quantity = offer_quantity WHERE offers.offer_id = row_counter;
  END WHILE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateProductsQuantity` ()  BEGIN
  DECLARE product_counter INT DEFAULT 0;
  DECLARE product_ID INT DEFAULT 0;
  DECLARE product_quantity INT DEFAULT 0;
  DECLARE product_all INT DEFAULT 0;
  SELECT COUNT(*) INTO product_all FROM products;

  WHILE product_counter < product_all DO
    SELECT products.prod_id INTO product_ID FROM products LIMIT product_counter, 1;
    SELECT COUNT(product_child.prod_child_id) INTO product_quantity FROM product_child WHERE product_child.prod_id = product_ID;
    UPDATE products SET products.prod_qty = product_quantity WHERE products.prod_id = product_ID;
    SET product_counter = product_counter + 1;
  END WHILE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Update_offers_on_child_insert` ()  NO SQL
begin
DECLARE productid INT;
DECLARE storeid INT;
DECLARE isOffer INT;
DECLARE price DOUBLE;
DECLARE existInOffers INT;
DECLARE best_date DATE;
DECLARE old_price DOUBLE;
DECLARE discount DECIMAL(2,2);

SET isOffer = NEW.prod_child_special;
SET productid = NEW.prod_id;
SET price = NEW.prod_child_price;
SET best_date = NEW.prod_child_bbd;
SELECT products.prod_price INTO old_price FROM products WHERE products.prod_id = productid;
SELECT products.prod_store_id INTO storeid FROM products WHERE products.prod_id = productid;
SELECT COUNT(*) INTO existInOffers FROM offers WHERE offers.product_id = productid AND offers.store_id = storeid AND offers.best_before_date = best_date AND offers.new_price = price;

SET discount = price / old_price;

 IF isOffer = 1 THEN
     IF existInOffers > 0 THEN
     UPDATE offers SET offer_quantity = offer_quantity + 1 WHERE offers.product_id = productid AND offers.store_id = storeid AND offers.best_before_date = best_date AND offers.new_price = price;
     ELSE
     INSERT INTO offers (`row_number`, `best_before_date`, `product_id`, `brand`, `product_name`, `store_id`, `old_price`, `new_price`, `offer_discount`, `unit`, `category`, `subcategory`, `image`, `store_name`, `store_address`, `store_suburb`, `store_city`, `store_phone`, `store_lat`, `store_long`, `offer_quantity`)
     SELECT DISTINCT 0, best_date,  products.prod_id, products.prod_brand, products.prod_name, stores.store_id, products.prod_price, price, discount, products.prod_unit, products.prod_cat, products.prod_subcat, products.prod_img1, stores.store_name, stores.store_address, stores.store_suburb, stores.store_city, stores.store_phone, stores.store_lat, stores.store_long, 1
     FROM products
     LEFT JOIN product_child ON products.prod_id = product_child.prod_id
     LEFT JOIN stores ON products.prod_store_id = stores.store_id
     WHERE products.prod_id = productid AND product_child.prod_child_bbd = best_date AND product_child.prod_child_price = price;
     END IF;
 end if;
 end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `offer_id` int(20) NOT NULL,
  `best_before_date` date DEFAULT NULL,
  `product_id` int(255) NOT NULL DEFAULT '0',
  `brand` varchar(256) CHARACTER SET latin1 NOT NULL,
  `product_name` varchar(256) CHARACTER SET latin1 NOT NULL,
  `store_id` int(255) DEFAULT '0',
  `old_price` double NOT NULL,
  `new_price` double DEFAULT NULL,
  `offer_discount` decimal(4,4) NOT NULL,
  `unit` varchar(256) CHARACTER SET latin1 NOT NULL,
  `category` varchar(256) CHARACTER SET latin1 NOT NULL,
  `subcategory` varchar(256) CHARACTER SET latin1 NOT NULL,
  `image` varchar(256) CHARACTER SET latin1 NOT NULL,
  `store_name` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `store_address` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `store_suburb` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `store_city` varchar(256) CHARACTER SET latin1 DEFAULT NULL,
  `store_phone` int(10) DEFAULT NULL,
  `store_lat` decimal(10,8) DEFAULT NULL,
  `store_long` decimal(11,8) DEFAULT NULL,
  `offer_quantity` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`offer_id`, `best_before_date`, `product_id`, `brand`, `product_name`, `store_id`, `old_price`, `new_price`, `offer_discount`, `unit`, `category`, `subcategory`, `image`, `store_name`, `store_address`, `store_suburb`, `store_city`, `store_phone`, `store_lat`, `store_long`, `offer_quantity`) VALUES
(42, '2018-06-30', 15, '', 'Apples Gala', 1, 3.8, 1, '0.7400', '1 kg', 'Fruits', 'Apples', 'img/apples_gala.jpg', 'Countdown', '19/25 Victoria Street West', 'Auckland Central', 'Auckland', 93089249, '-36.84866974', '174.76463845', 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `prod_id` int(255) NOT NULL,
  `prod_store_id` int(255) NOT NULL,
  `prod_brand` varchar(256) NOT NULL,
  `prod_name` varchar(256) NOT NULL,
  `prod_unit` varchar(256) NOT NULL,
  `prod_desc` varchar(256) NOT NULL,
  `prod_price` double NOT NULL,
  `prod_cat` varchar(256) NOT NULL,
  `prod_subcat` varchar(256) NOT NULL,
  `prod_img1` varchar(256) NOT NULL,
  `prod_qty` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`prod_id`, `prod_store_id`, `prod_brand`, `prod_name`, `prod_unit`, `prod_desc`, `prod_price`, `prod_cat`, `prod_subcat`, `prod_img1`, `prod_qty`) VALUES
(1, 1, 'Anchor', 'Blue Milk', '2L', '', 6, 'Dairy', 'Milk', 'img/Anchor-Milk-Standard-Blue-Top-1l.jpg', 0),
(8, 1, 'Lewis road creamery', 'Chocolate milk', '750ml', '', 5.2, 'Dairy', 'Milk', 'img/Lewis_road_milk.jpg', 2),
(12, 1, 'Meadow Fresh', 'Farmhouse milk', '2L', '', 5.4, 'Dairy', 'Milk', 'img/Meadow_Milk_Farmhouse_2L.jpg', 0),
(13, 1, 'Meadow Fresh', 'Farmhouse milk', '1L', '', 3.4, 'Dairy', 'Milk', 'img/Meadow_Milk_Farmhouse_1L.jpg', 0),
(14, 1, 'Meadow Fresh', 'Yogurt Fresh\'n\'fruity lite', 'ea', '', 4.8, 'Dairy', 'Yogurt', 'img/fresh_and_fruty_lite.png', 0),
(15, 1, '', 'Apples Gala', '1 kg', '', 3.8, 'Fruits', 'Apples', 'img/apples_gala.jpg', 2),
(16, 1, '', 'Orange', '1 kg', '', 5, 'Fruits', 'Oranges', 'img/oranges.jpg', 0),
(17, 1, '', 'Pears', '1 kg', '', 4, 'Fruits', 'Pears', 'img/pears.jpg', 0),
(18, 1, '', 'Asian pears', '1 kg', '', 6, 'Fruits', 'Pears', 'img/Asian_pears.jpg', 2),
(19, 1, '', 'Mandarines', '1 kg', '', 8, 'Fruits', 'Mandarines', 'img/Mandarines.png', 2),
(21, 1, 'Russia', 'Drums', '1PCS', '', 100, 'Meat', 'Chicken', 'img/Anchor-Milk-Standard-Blue-Top-1l.jpg', 0);

--
-- Triggers `products`
--
DELIMITER $$
CREATE TRIGGER `update_offers_product_update` AFTER UPDATE ON `products` FOR EACH ROW begin
    declare prodid int;
    declare newbrand VARCHAR(50);
    declare newname VARCHAR(50);
    declare newunit VARCHAR(50);
    declare newprice DOUBLE;
    declare newcategory varchar(50);
    declare newsubcat varchar(50);
    declare newimg varchar(50);

    set prodid = NEW.prod_id;
    set newbrand = NEW.prod_brand;
    set newname = NEW.prod_name;
    set newunit = NEW.prod_unit;
    set newprice = NEW.prod_price;
    set newcategory = NEW.prod_cat;
    set newsubcat = NEW.prod_subcat;
    set newimg = NEW.prod_img1;

    update offers set offers.brand = newbrand, offers.product_name = newname, offers.unit = newunit,
      offers.old_price = newprice, offers.category = newcategory, offers.subcategory = newsubcat,
      offers.image = newimg where offers.product_id = prodid;

  end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `product_child`
--

CREATE TABLE `product_child` (
  `prod_child_id` int(255) NOT NULL,
  `prod_id` int(255) NOT NULL,
  `prod_child_bbd` date NOT NULL,
  `prod_child_special` tinyint(1) NOT NULL,
  `prod_child_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_child`
--

INSERT INTO `product_child` (`prod_child_id`, `prod_id`, `prod_child_bbd`, `prod_child_special`, `prod_child_price`) VALUES
(69, 18, '2018-06-23', 1, 4.5),
(70, 18, '2018-06-23', 1, 4.5),
(77, 19, '2018-06-23', 1, 3.99),
(78, 19, '2018-06-23', 1, 3.99),
(117, 8, '2018-06-29', 0, 3),
(118, 8, '2018-06-29', 0, 0),
(119, 15, '2018-06-30', 1, 1),
(120, 15, '2018-06-30', 1, 1);

--
-- Triggers `product_child`
--
DELIMITER $$
CREATE TRIGGER `update_offers_on_delete` AFTER DELETE ON `product_child` FOR EACH ROW begin
    DECLARE oldprodid INT;
    DECLARE oldoffer INT;
    DECLARE oldprice DOUBLE;
    DECLARE oldbestdate DATE;
    declare offerid INT;
    declare prod_quantity INT;

    SET oldoffer = OLD.prod_child_special;
    SET oldprodid = OLD.prod_id;
    SET oldprice = OLD.prod_child_price;
    SET oldbestdate = OLD.prod_child_bbd;

    if oldoffer = 1
    then
      select offer_id
      into offerid
      from offers
      where new_price = oldprice and offers.product_id = oldprodid and offers.best_before_date = oldbestdate;

      select offer_quantity
      into prod_quantity
      from offers
      where offer_id = offerid;

      if prod_quantity > 1 then
        update offers set offer_quantity = offer_quantity - 1 where offer_id = offerid;
      else
        delete from offers where offer_id = offerid;
      end if;

    end if;

    call updateProductsQuantity();
  end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_offers_on_input` AFTER INSERT ON `product_child` FOR EACH ROW begin
DECLARE productid INT;
DECLARE storeid INT;
DECLARE isOffer INT;
DECLARE price DOUBLE;
DECLARE existInOffers INT;
DECLARE best_date DATE;
DECLARE old_price DOUBLE;
DECLARE discount DECIMAL(2,2);
DECLARE date_diff INT;

SET isOffer = NEW.prod_child_special;
SET productid = NEW.prod_id;
SET price = NEW.prod_child_price;
SET best_date = NEW.prod_child_bbd;
SELECT products.prod_price INTO old_price FROM products WHERE products.prod_id = productid;
SELECT products.prod_store_id INTO storeid FROM products WHERE products.prod_id = productid;
SELECT COUNT(*) INTO existInOffers FROM offers WHERE offers.product_id = productid AND offers.store_id = storeid AND offers.best_before_date = best_date AND offers.new_price = price;
SELECT DATEDIFF(best_date, curdate()) INTO date_diff;

SET discount = 1 - (price / old_price);

 IF isOffer = 1 AND date_diff > 0 THEN
     IF existInOffers > 0 THEN
     UPDATE offers SET offer_quantity = offer_quantity + 1 WHERE offers.product_id = productid AND offers.store_id = storeid AND offers.best_before_date = best_date AND offers.new_price = price;
     ELSE
     INSERT INTO offers (`offer_id`, `best_before_date`, `product_id`, `brand`, `product_name`, `store_id`, `old_price`, `new_price`, `offer_discount`, `unit`, `category`, `subcategory`, `image`, `store_name`, `store_address`, `store_suburb`, `store_city`, `store_phone`, `store_lat`, `store_long`, `offer_quantity`)
     SELECT DISTINCT NULL, best_date,  products.prod_id, products.prod_brand, products.prod_name, stores.store_id, products.prod_price, price, discount, products.prod_unit, products.prod_cat, products.prod_subcat, products.prod_img1, stores.store_name, stores.store_address, stores.store_suburb, stores.store_city, stores.store_phone, stores.store_lat, stores.store_long, 1
     FROM products
     LEFT JOIN product_child ON products.prod_id = product_child.prod_id
     LEFT JOIN stores ON products.prod_store_id = stores.store_id
     WHERE products.prod_id = productid AND product_child.prod_child_bbd = best_date AND product_child.prod_child_price = price;
     END IF;
 end if;
 
 call updateProductsQuantity();
end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_offers_on_update` AFTER UPDATE ON `product_child` FOR EACH ROW begin
    DECLARE newproductid INT;
    DECLARE oldprodid INT;
    DECLARE storeid INT;
    DECLARE newoffer INT;
    DECLARE oldoffer INT;
    DECLARE oldprice DOUBLE;
    DECLARE newprice DOUBLE;
    DECLARE existInOffers INT;
    DECLARE newbestdate DATE;
    DECLARE oldbestdate DATE;
    DECLARE prod_price DOUBLE;
    DECLARE discount DECIMAL(2, 2);
    DECLARE offerid INT;
    DECLARE prod_quantity INT;
    DECLARE date_diff INT;

    SET oldoffer = OLD.prod_child_special;
    SET newoffer = NEW.prod_child_special;
    SET oldprodid = OLD.prod_id;
    SET newproductid = NEW.prod_id;
    SET oldprice = OLD.prod_child_price;
    SET newprice = NEW.prod_child_price;
    SET oldbestdate = OLD.prod_child_bbd;
    SET newbestdate = NEW.prod_child_bbd;

    SELECT products.prod_price
    INTO prod_price
    FROM products
    WHERE products.prod_id = newproductid;

    SELECT products.prod_store_id
    INTO storeid
    FROM products
    WHERE products.prod_id = newproductid;

    SELECT DATEDIFF(newbestdate, curdate())
    INTO date_diff;

    SET discount = 1 - (newprice / prod_price);

    IF (newoffer = 0 AND newoffer != oldoffer)
    THEN
      SELECT offers.offer_id
      INTO offerid
      FROM offers
      WHERE offers.best_before_date = oldbestdate AND offers.product_id = oldprodid AND offers.new_price = oldprice;

      SELECT offers.offer_quantity
      INTO prod_quantity
      FROM offers
      WHERE offers.offer_id = offerid;

      IF prod_quantity > 1
      THEN
        UPDATE offers
        SET offers.offer_quantity = prod_quantity - 1
        WHERE offers.offer_id = offerid;
      ELSE
        DELETE FROM offers
        WHERE offers.offer_id = offerid;
      END IF;

    ELSEIF (newoffer = 1 AND newoffer != oldoffer AND date_diff > 0)
      THEN

        SELECT COUNT(*)
        INTO existInOffers
        FROM offers
        WHERE
          offers.product_id = newproductid AND offers.store_id = storeid AND offers.best_before_date = newbestdate AND
          offers.new_price = newprice;

        if existInOffers > 0
        then
          update offers
          set offers.offer_quantity = offers.offer_quantity + 1
          where offers.product_id = newproductid
                AND offers.store_id = storeid AND offers.best_before_date = newbestdate AND offers.new_price = newprice;

        else
          INSERT INTO offers (`offer_id`, `best_before_date`, `product_id`, `brand`, `product_name`, `store_id`, `old_price`, `new_price`, `offer_discount`, `unit`, `category`, `subcategory`, `image`, `store_name`, `store_address`, `store_suburb`, `store_city`, `store_phone`, `store_lat`, `store_long`, `offer_quantity`)
            SELECT DISTINCT
              NULL,
              prod_child_bbd,
              products.prod_id,
              products.prod_brand,
              products.prod_name,
              stores.store_id,
              products.prod_price,
              prod_child_price,
              discount,
              products.prod_unit,
              products.prod_cat,
              products.prod_subcat,
              products.prod_img1,
              stores.store_name,
              stores.store_address,
              stores.store_suburb,
              stores.store_city,
              stores.store_phone,
              stores.store_lat,
              stores.store_long,
              1
            FROM products
              LEFT JOIN product_child ON products.prod_id = product_child.prod_id
              LEFT JOIN stores ON products.prod_store_id = stores.store_id
            WHERE products.prod_id = newproductid AND product_child.prod_child_bbd = newbestdate AND
                  product_child.prod_child_price = newprice;

        END IF;

    ELSEIF (newoffer = 1 AND newoffer = oldoffer AND date_diff > 0)
      THEN

        select offer_id
        into offerid
        from offers
        where offers.product_id = oldprodid AND offers.best_before_date = oldbestdate AND
              offers.new_price = oldprice;
        select offers.offer_quantity
        into prod_quantity
        from offers
        where offer_id = offerid;

        if prod_quantity > 1
        then
          UPDATE offers
          SET offers.offer_quantity = prod_quantity - 1
          WHERE offers.offer_id = offerid;
        ELSE
          DELETE FROM offers
          WHERE offers.offer_id = offerid;
        end if;

        SELECT COUNT(*)
        INTO existInOffers
        FROM offers
        WHERE
          offers.product_id = newproductid AND offers.store_id = storeid AND offers.best_before_date = newbestdate AND
          offers.new_price = newprice;

        if existInOffers > 0
        then
          update offers
          set offers.offer_quantity = offers.offer_quantity + 1
          where offers.product_id = newproductid
                AND offers.store_id = storeid AND offers.best_before_date = newbestdate AND offers.new_price = newprice;

        else
          INSERT INTO offers (`offer_id`, `best_before_date`, `product_id`, `brand`, `product_name`, `store_id`, `old_price`, `new_price`, `offer_discount`, `unit`, `category`, `subcategory`, `image`, `store_name`, `store_address`, `store_suburb`, `store_city`, `store_phone`, `store_lat`, `store_long`, `offer_quantity`)
            SELECT DISTINCT
              NULL,
              prod_child_bbd,
              products.prod_id,
              products.prod_brand,
              products.prod_name,
              stores.store_id,
              products.prod_price,
              prod_child_price,
              discount,
              products.prod_unit,
              products.prod_cat,
              products.prod_subcat,
              products.prod_img1,
              stores.store_name,
              stores.store_address,
              stores.store_suburb,
              stores.store_city,
              stores.store_phone,
              stores.store_lat,
              stores.store_long,
              1
            FROM products
              LEFT JOIN product_child ON products.prod_id = product_child.prod_id
              LEFT JOIN stores ON products.prod_store_id = stores.store_id
            WHERE products.prod_id = newproductid AND product_child.prod_child_bbd = newbestdate AND
                  product_child.prod_child_price = newprice;

        END IF;

    ELSEIF date_diff < 0
      THEN

        SELECT COUNT(*)
        INTO existInOffers
        FROM offers
        WHERE
          offers.product_id = oldprodid AND offers.store_id = storeid AND offers.best_before_date = oldbestdate AND
          offers.new_price = oldprice;

      if existInOffers > 0 then
        select offer_id
        into offerid
        from offers
        where offers.product_id = oldprodid AND offers.best_before_date = oldbestdate AND
              offers.new_price = oldprice;

        select offers.offer_quantity
        into prod_quantity
        from offers
        where offer_id = offerid;

        if prod_quantity > 1
        then
          UPDATE offers
          SET offers.offer_quantity = prod_quantity - 1
          WHERE offers.offer_id = offerid;
        ELSE
          DELETE FROM offers
          WHERE offers.offer_id = offerid;
        end if;

      end if;

    END IF;


call updateProductsQuantity();
  end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `store_id` int(255) NOT NULL,
  `store_name` varchar(256) NOT NULL,
  `store_address` varchar(256) NOT NULL,
  `store_suburb` varchar(256) NOT NULL,
  `store_city` varchar(256) NOT NULL,
  `store_zip` int(4) NOT NULL,
  `store_phone` int(10) NOT NULL,
  `store_lat` decimal(10,8) NOT NULL,
  `store_long` decimal(11,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`store_id`, `store_name`, `store_address`, `store_suburb`, `store_city`, `store_zip`, `store_phone`, `store_lat`, `store_long`) VALUES
(1, 'Countdown', '19/25 Victoria Street West', 'Auckland Central', 'Auckland', 1010, 93089249, '-36.84866974', '174.76463845'),
(2, 'Pak and Save', '27 A View road', 'Henderson', 'Auckland', 5545, 2147483647, '-36.84886506', '174.76518026');

--
-- Triggers `stores`
--
DELIMITER $$
CREATE TRIGGER `update_offers_stores_update` AFTER UPDATE ON `stores` FOR EACH ROW begin
    declare storeid int;
    declare newname VARCHAR(50);
    declare newaddress VARCHAR(50);
    declare newsuburb VARCHAR(50);
    declare newcity VARCHAR(50);
    declare newphone INT;
    declare newlat decimal;
    declare newlong decimal;

    set storeid = NEW.store_id;
    set newname = NEW.store_name;
    set newaddress = NEW.store_address;
    set newsuburb = NEW.store_suburb;
    set newcity = NEW.store_city;
    set newphone = NEW.store_phone;
    set newlat = NEW.store_lat;
    set newlong = NEW.store_long;

    update offers set offers.store_name = newname, offers.store_address = newaddress, offers.store_suburb = newsuburb,
      offers.store_city = newcity, offers.store_phone = newphone, offers.store_lat = newlat,
      offers.store_long = newlong where offers.store_id = storeid;

  end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `subscribe_id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL,
  `sub_offer_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(255) NOT NULL,
  `user_email` varchar(256) NOT NULL,
  `user_pwd` varchar(256) NOT NULL,
  `user_first` varchar(50) NOT NULL,
  `user_last` varchar(50) NOT NULL,
  `user_phone` int(15) NOT NULL,
  `user_role` varchar(5) NOT NULL DEFAULT 'basic',
  `user_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_email`, `user_pwd`, `user_first`, `user_last`, `user_phone`, `user_role`, `user_active`) VALUES
(6, 'chesnochenko.a@gmail.com', '$2y$10$MG.Y0jpazOt7GjNC.mF2aOXDWHZDutzRHWMnapWT9oF7c4oi0yR1O', 'Alex', 'Chesnochenko', 224695842, 'basic', 1),
(11, 'il.suglobov@gmail.com', '$2y$10$mO3REKZb6.3ujcXFiYy88OOusrZBTYhT9Ac9L8rdWR/s.DRS6YB7K', 'Ilya', 'Suglobov', 21094322, 'staff', 0),
(12, 'bnycastro@gmail.com', '$2y$10$u.eeXR4n0FkYJpFMU4QG5u9k67rY7/00ojX2yu08Gc3nRoRTx4LzC', 'Vincent', 'Castro', 224492145, 'staff', 1),
(15, 'yib00001we@aspire2student.ac.nz', '$2y$10$CneWNfdchhH4jRTMlDnatuNhFppy8x.vtrhHIHLIw9T/kghRIO9iS', 'Vincent ', 'AdminTest', 225551234, 'admin', 1),
(16, 'bnycastro@xavier97.com', '$2y$10$.rFtMv7MgjVv.oNawdqAX.9uE.ssGF2Xl2niJHL61feomv.KQ6qYy', 'Vincent', 'StaffTest', 225551234, 'staff', 1),
(17, 'bnycastro@yahoo.com', '$2y$10$.x6Zj/Ce3DZlgsFLL.GUHehc6vdYL4BONu1MG2chRJwqNBepKgv0e', 'Vincent', 'BasicTest', 225559876, 'basic', 1),
(18, 'il421suglobov@mail.ru', '$2y$10$uybL4SskNQ3VCBC2MwETa.CsDRNtBv7Jzlg6M0.NL8J512XOJkw4.', 'Ivan', 'Suglobov', 123, 'basic', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`prod_id`),
  ADD KEY `prod_store_id` (`prod_store_id`);

--
-- Indexes for table `product_child`
--
ALTER TABLE `product_child`
  ADD PRIMARY KEY (`prod_child_id`),
  ADD KEY `prod_id` (`prod_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`subscribe_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `offer_id` (`sub_offer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `offer_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `prod_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `product_child`
--
ALTER TABLE `product_child`
  MODIFY `prod_child_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `store_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `subscribe_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`prod_store_id`) REFERENCES `stores` (`store_id`);

--
-- Constraints for table `product_child`
--
ALTER TABLE `product_child`
  ADD CONSTRAINT `product_child_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `products` (`prod_id`) ON DELETE CASCADE;

--
-- Constraints for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD CONSTRAINT `offer_id` FOREIGN KEY (`sub_offer_id`) REFERENCES `offers` (`offer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `Delete old offers` ON SCHEDULE EVERY 30 SECOND STARTS '2018-06-14 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM offers WHERE DATEDIFF(best_before_date, CURDATE()) < 1$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

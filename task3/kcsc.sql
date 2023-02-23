-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2023 at 07:58 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kcsc`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `type`, `description`, `price`) VALUES
(1, 'iphone 14 pro max', 'iphone', 'Cuối cùng thì chiếc iPhone 14 Pro Max cũng đã chính thức lộ diện tại sự kiện ra mắt thường niên vào ngày 08/09 đến từ nhà Apple, kết thúc bao lời đồn đoán bằng một bộ thông số cực kỳ ấn tượng cùng vẻ ngoài đẹp mắt sang trọng. Từ ngày 14/10/2022 người dùng đã có thể mua sắm các sản phẩm iPhone 14 series với đầy đủ phiên bản tại Thế Giới Di Động.', 30000000),
(2, 'iphone 12 pro max', 'iphone', 'iPhone 12 Pro Max 128 GB một siêu phẩm smartphone đến từ Apple. Máy có một hiệu năng hoàn toàn mạnh mẽ đáp ứng tốt nhiều nhu cầu đến từ người dùng và mang trong mình một thiết kế đầy vuông vức, sang trọng', 8000000),
(3, 'iphone 6', 'iphone', 'Điện thoại iPhone 6 là bước chuyển mình trong thiết kế của Apple, với thiết kế màn hình được nâng cấp theo xu hướng hiện đại. Các thiết kế viền cạnh máy đẹp như mới không bị trầy xước, cấn móp. Kiểm tra kỹ phần tiếp giáp với khung viền và màn hình máy để đảm bảo không ảnh hưởng đến các bộ phận bên trong.', 2000000),
(4, 'iphone 7', 'iphone', 'Điện thoại iPhone 7 được Apple iPhone trang bị một thiế kế nhỏ gọn dễ dàng sử dụng và thao tác với một tay, máy sở hữu ngôn ngữ thiết kế kim loại nguyên khối sang trọng và cứng cáp. Tuy nhỏ gọn nhưng iPhone 7 chính hãng mang đến sự trải nghiệm hoàn hảo bởi chip cực mạnh Apple A10 Fusion. ', 3000000),
(5, 'samsung galaxy s10', 'samsung', 'Samsung Galaxy S10 là chiếc smartphone kỉ niệm 10 năm ngày kể từ ngày đầu tiên Samsung ra mắt chiếc Galaxy S và không phụ sự chờ đợi của người dùng thì Samsung Galaxy S10 thực sự rất ấn tượng.', 5000000),
(6, 'samsung s23 ultra', 'samsung', 'Cuối cùng thì chiếc điện thoại Samsung Galaxy S23 cũng đã chính thức ra mắt tại sự kiện Galaxy Unpacked vào đầu tháng 2 năm 2023, máy nổi bật với camera 200 MP chất lượng, hiệu năng mạnh mẽ nhờ tích hợp con chip Snapdragon 8 Gen 2 và được trang bị thêm nhiều công nghệ hàng đầu trong giới smartphone.', 25000000),
(7, 'samsung j2', 'samsung', 'Galaxy J2 là smartphone phổ thông với thiết kế tương tự J5, J7, viền cạnh bo tròn, màn hình 4.7 qHD, vi xử lý lõi tứ, RAM 1GB cùng khả năng tiết kiệm pin.', 500000),
(8, 'samsung j7', 'samsung', 'Điện thoại Samsung Galaxy J7 là một trong những sản phẩm nổi bật nhất từ ông lớn Samsung trong phân khúc điện thoại tầm trung. Nhìn chung, chiếc điện thoại này có thiết kế quen thuộc giống hầu hết các thiết bị trong dòng J Series', 3000000),
(9, 'Xiaomi Redmi Note 11', 'xiaomi', 'Redmi Note 11 (6GB/128GB) vừa được Xiaomi cho ra mắt, được xem là chiếc smartphone có giá tầm trung ấn tượng, với 1 cấu hình mạnh, cụm camera xịn sò, pin khỏe, sạc nhanh mà giá lại rất phải chăng.', 7000000),
(10, 'Xiaomi Redmi note 12 pro', 'xiaomi', 'Xiaomi Redmi Note 12 Pro 5G, siêu phẩm hàng đầu trong phân khúc tầm trung ra mắt vào cuối tháng 10/2022. Nối tiếp thành công rực rỡ của dòng Redmi Note 11, gã khổng lồ Trung Quốc hạ quyết tâm giữ vững doanh số bằng cách bổ sung, nâng cấp, cải tiến trên mẫu Redmi Note 12 Pro với công nghệ tốt, thiết kế đẹp hơn.', 15000000),
(11, 'Xiaomi 12t Pro', 'xiaomi', 'Xiaomi 12T series đã ra mắt trong sự kiện của Xiaomi vào ngày 4/10, trong đó có Xiaomi 12T 5G 128GB - máy sở hữu nhiều công nghệ hàng đầu trong giới smartphone tiêu biểu như: Chipset mạnh mẽ đến từ MediaTek, camera 108 MP sắc nét cùng khả năng sạc 120 W siêu nhanh.', 13000000),
(12, 'Poco X5 5G', 'xiaomi', 'POCO X5 sở hữu những ưu điểm vượt trội cả về thiết kế bên ngoài lẫn hiệu năng bên trọng, Poco X5 5G đang rất được ưa chuộng và phổ biến tại thị trường Việt Nam', 6000000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', 'the_admin_password'),
(2, 'chuong', 'chuong123'),
(3, 'guest', 'guest111'),
(4, 'peter', '12345678'),
(5, 'linda', 'iloveyou'),
(6, 'richa', 'siuuuuuuuuu'),
(7, 'lord', '987654321'),
(8, 'chicken', 'ducky'),
(9, 'john', 'theripper'),
(10, 'jungle', 'hihihihi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

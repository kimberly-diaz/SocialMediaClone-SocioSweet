-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2022 at 06:36 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `social`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_body` text NOT NULL,
  `posted_by` varchar(60) NOT NULL,
  `posted_to` varchar(60) NOT NULL,
  `date_added` datetime NOT NULL,
  `removed` varchar(3) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_body`, `posted_by`, `posted_to`, `date_added`, `removed`, `post_id`) VALUES
(1, 'Okie pearly!', 'kimberly-uni', 'charis-hs', '2022-01-06 22:06:42', 'no', 7),
(2, 'Yummy!', 'kimberly-uni', 'kimberly-uni', '2022-01-26 15:41:27', 'no', 33),
(3, 'Hey!', 'kimberly-uni', 'kimberly-uni', '2022-01-26 15:51:33', 'no', 33),
(4, 'Cute!', 'kimberly-uni', 'miniature-a', '2022-01-26 21:33:07', 'no', 30),
(5, 'Looks great!', 'kimberly-uni', 'miniature-a', '2022-01-26 21:36:11', 'no', 29),
(6, 'I love Doggos.', 'kimberly-uni', 'melody-ya', '2022-01-26 21:36:42', 'no', 26),
(7, 'Beatrice is snooping by in this site...', 'kimberly-uni', 'beatrice-ya', '2022-01-26 21:37:47', 'no', 25),
(8, 'Crane for sure', 'kimberly-uni', 'milenka-ya', '2022-01-27 20:06:29', 'no', 27),
(9, 'I love cookie dough ice cream too mmmm', 'kimberly-uni', 'charmaine-hs', '2022-01-27 20:08:32', 'no', 22),
(10, 'Ready! You sure we can do this?', 'kimberly-uni', 'charispearl-hs', '2022-01-27 20:11:44', 'no', 15),
(11, 'Doggos! Sooooo cute!', 'miriam-ya', 'melody-ya', '2022-01-27 20:20:40', 'no', 26),
(12, 'Looks like a pelican! Though never seen both before hehe', 'miriam-ya', 'milenka-ya', '2022-01-27 20:21:00', 'no', 27),
(13, 'Neopolitan is my favourite ice cream!', 'miriam-ya', 'charispearl-hs', '2022-01-27 20:21:27', 'no', 23),
(14, 'Hehe my mom wouldn\'t let me if I dared DX', 'miriam-ya', 'glynnii-hs', '2022-01-27 20:21:49', 'no', 11),
(15, 'SO CUTE!! <3', 'miriam-ya', 'carmen-uni', '2022-01-27 20:22:06', 'no', 9),
(16, 'Such good art <3', 'miriam-ya', 'kimberly-uni', '2022-01-27 20:22:21', 'no', 8);

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(11) NOT NULL,
  `user_to` varchar(50) NOT NULL,
  `user_from` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `friend_requests`
--

INSERT INTO `friend_requests` (`id`, `user_to`, `user_from`) VALUES
(3, 'charis-hs', 'kimberly-uni'),
(4, 'beatrice-ya', 'kimberly-uni'),
(5, 'beatrice-ya', 'kimberly-uni'),
(6, 'beatrice-ya', 'kimberly-uni'),
(7, 'beatrice-ya', 'kimberly-uni'),
(8, 'beatrice-ya', 'kimberly-uni'),
(9, 'beatrice-ya', 'kimberly-uni'),
(12, 'kimberly-uni', 'jillian-a'),
(13, 'beatrice-ya', 'miriam-ya'),
(14, 'milenka-ya', 'miriam-ya'),
(15, 'melody-ya', 'miriam-ya');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `username`, `post_id`) VALUES
(95, 'kimberly-uni', 4),
(100, 'kimberly-uni', 3),
(101, 'charmaine-hs', 5),
(102, 'charmaine-hs', 4),
(104, 'kimberly-uni', 6),
(106, 'kimberly-uni', 5),
(107, 'beatrice-ya', 9),
(108, 'beatrice-ya', 2),
(109, 'beatrice-ya', 6),
(110, 'glynnii-hs', 10),
(111, 'glynnii-hs', 9),
(112, 'glynnii-hs', 8),
(113, 'glynnii-hs', 6),
(114, 'glynnii-hs', 5),
(115, 'oona-hs', 9),
(116, 'oona-hs', 7),
(117, 'oona-hs', 6),
(118, 'oona-hs', 5),
(119, 'jillian-a', 12),
(120, 'jillian-a', 12),
(121, 'jillian-a', 10),
(122, 'jillian-a', 9),
(123, 'jillian-a', 6),
(124, 'jillian-a', 7),
(125, 'estelle-ya', 12),
(126, 'estelle-ya', 11),
(127, 'estelle-ya', 9),
(128, 'estelle-ya', 8),
(129, 'kimberly-uni', 12),
(130, 'kimberly-uni', 11),
(131, 'charispearl-hs', 5),
(133, 'charispearl-hs', 15),
(134, 'beatrice-ya', 26),
(135, 'beatrice-ya', 32),
(136, 'kimberly-uni', 31),
(137, 'kimberly-uni', 31),
(139, 'kimberly-uni', 30),
(140, 'kimberly-uni', 29),
(141, 'kimberly-uni', 8),
(143, 'kimberly-uni', 15),
(145, 'kimberly-uni', 33),
(146, 'kimberly-uni', 33),
(147, 'kimberly-uni', 32),
(148, 'kimberly-uni', 25),
(149, 'kimberly-uni', 24),
(150, 'kimberly-uni', 28),
(151, 'kimberly-uni', 23),
(152, 'kimberly-uni', 22),
(153, 'kimberly-uni', 27),
(154, 'kimberly-uni', 26),
(155, 'kimberly-uni', 34),
(156, 'kimberly-uni', 21),
(157, 'kimberly-uni', 20),
(158, 'jillian-a', 33),
(159, 'jillian-a', 32),
(160, 'jillian-a', 13),
(161, 'jillian-a', 26),
(162, 'jillian-a', 25),
(163, 'jillian-a', 20),
(164, 'jillian-a', 18),
(165, 'jillian-a', 15),
(166, 'jillian-a', 30),
(167, 'jillian-a', 23),
(168, 'jillian-a', 21),
(169, 'jillian-a', 27),
(170, 'jillian-a', 28),
(171, 'miriam-ya', 32),
(172, 'miriam-ya', 31),
(173, 'miriam-ya', 30),
(174, 'miriam-ya', 29),
(175, 'miriam-ya', 28),
(176, 'miriam-ya', 26),
(177, 'miriam-ya', 10),
(178, 'miriam-ya', 11),
(179, 'miriam-ya', 9),
(180, 'miriam-ya', 8),
(181, 'miriam-ya', 5),
(182, 'miriam-ya', 3),
(183, 'miriam-ya', 25);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `body` text NOT NULL,
  `added_by` varchar(60) NOT NULL,
  `user_to` varchar(60) NOT NULL,
  `date_added` datetime NOT NULL,
  `user_closed` varchar(3) NOT NULL,
  `deleted` varchar(3) NOT NULL,
  `likes` int(11) NOT NULL,
  `image_name` varchar(500) NOT NULL,
  `image_size` varchar(6) NOT NULL,
  `tag` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `body`, `added_by`, `user_to`, `date_added`, `user_closed`, `deleted`, `likes`, `image_name`, `image_size`, `tag`) VALUES
(1, 'Cutey', 'kimberly-uni', 'none', '2022-01-11 22:01:03', 'no', 'yes', 0, '../../assets/images/posts/61ddfe1f20943cute.png', 'small', 'general'),
(2, 'Fluffy kitten!', 'kimberly-uni', 'none', '2022-01-11 22:31:34', 'no', 'no', 1, '../../assets/images/posts/61de0546d6dbb42f513e99390ba2c4c557f6000bdd557.png', 'small', 'general'),
(3, 'This is so cute! Everyone go check this illustrator out! They\'re from dribbble! Shout out! Woohoo!', 'kimberly-uni', 'none', '2022-01-11 22:34:57', 'no', 'no', 2, '../../assets/images/posts/61de0611870b3aw.png', 'medium', 'general'),
(4, 'Panda.', 'kimberly-uni', 'none', '2022-01-13 21:29:04', 'no', 'no', 2, '../../assets/images/posts/61e099a0cce8dpanda.png', 'small', 'general'),
(5, 'I made this hehe', 'charmaine-hs', 'none', '2022-01-17 12:58:36', 'no', 'no', 6, '../../assets/images/posts/61e567fc8e7135dd7f3a679d75749be2adf35.jpg', 'small', 'food'),
(6, 'One of the best sweets I\'ve ever had! It\'s from Japan, strawberry-flavoured goodness!', 'charmaine-hs', 'none', '2022-01-17 12:59:00', 'no', 'no', 5, '../../assets/images/posts/61e5681418d52a4881322ccc9437798496e70831152c2.jpg', 'medium', 'food'),
(7, 'Best drawing ever! Can\'t wait to use it!', 'kimberly-uni', 'none', '2022-01-19 16:13:26', 'no', 'no', 2, '../../assets/images/posts/61e838a6c57100c8b97dcff49e02b46f730e099470186.png', 'small', 'general'),
(8, 'Made this last night- thoughts guys? hehe', 'kimberly-uni', 'none', '2022-01-19 18:09:31', 'no', 'no', 4, '../../assets/images/posts/61e853db93505aesthetic-6.jpg', 'small', 'aesthetic'),
(9, 'BEST. HAMSTER. EVER.', 'carmen-uni', 'none', '2022-01-19 18:10:38', 'no', 'no', 6, '../../assets/images/posts/61e8541e32ebdanimals-6.jpg', 'medium', 'animals'),
(10, 'Alice in Wonderland??', 'beatrice-ya', 'none', '2022-01-19 18:19:49', 'no', 'no', 3, '../../assets/images/posts/61e85645074b3nature-1.jpg', 'medium', 'general'),
(11, 'Dream setup- Mom pleaseeee', 'glynnii-hs', 'none', '2022-01-19 18:23:35', 'no', 'no', 3, '../../assets/images/posts/61e857279f3a3aesthetic-2.jpg', 'small', 'general'),
(12, 'Could I have this as my pet next? ', 'oona-hs', 'none', '2022-01-19 18:33:48', 'no', 'no', 4, '../../assets/images/posts/61e8598caa6b9animals-1.jpg', 'small', 'general'),
(13, 'Hey everyone! Hows your day going?', 'jillian-a', 'none', '2022-01-19 18:47:50', 'no', 'no', 1, '../../assets/images/posts/61e85cd65501aaesthetic-3.png', 'small', 'general'),
(14, 'Me during exam periods... it last wayyy longer than I want it to- how bout you guys?', 'estelle-ya', 'none', '2022-01-19 18:51:13', 'no', 'no', 0, '../../assets/images/posts/61e85da19f0c5aesthetic-7.jpg', 'medium', 'general'),
(15, 'Next thing to bake! IM EXCITED!! Hey Ates, are you guys ready?', 'charispearl-hs', 'none', '2022-01-19 20:44:21', 'no', 'no', 3, '../../assets/images/posts/61e87825207d6food-5.jpg', 'medium', 'food'),
(16, 'Strawberry Cream Cheese slime! One of the best from Slime OG!', 'charispearl-hs', 'none', '2022-01-19 20:49:51', 'no', 'no', 0, '../../assets/images/posts/61e8796f32394strawberry_900x.png', 'small', 'slime'),
(17, 'Another fan favourite!', 'charispearl-hs', 'none', '2022-01-19 21:17:25', 'no', 'no', 0, '../../assets/images/posts/61e87fe58a295thumbnail_1_720x.png', 'medium', 'slime'),
(18, 'LOOK AT THIS BIRTHDAY CAKE SLIME! THERE\'S EVEN A CANDLE!', 'charispearl-hs', 'none', '2022-01-19 21:19:28', 'no', 'no', 1, '../../assets/images/posts/61e8806084a3cbirthdaycake_720x.png', 'large', 'slime'),
(19, 'Best slime ever! Check it out at momoslimes shop!', 'carmen-a', 'none', '2022-01-19 21:21:31', 'no', 'no', 0, '../../assets/images/posts/61e880db756a3unicornsweater_900x.jpg', 'medium', 'slime'),
(20, 'Strawberry-in-the-eye! Check out kawaiislimecompanys shop!', 'carmen-a', 'none', '2022-01-19 21:22:35', 'no', 'no', 2, '../../assets/images/posts/61e8811b60bf7IMG_1582c_ca010533-bb7e-428f-98b8-d88ca4a980c5_800x.jpg', 'small', 'slime'),
(21, 'Unicorn post! Still from kawaiislimecompany', 'carmen-a', 'none', '2022-01-19 21:23:44', 'no', 'no', 2, '../../assets/images/posts/61e881602ae17IMG_7271_800x.jpg', 'small', 'slime'),
(22, 'This is my favourite slime from kawaiislimecompany! I looove cookie dough ice cream', 'charmaine-hs', 'none', '2022-01-19 21:26:48', 'no', 'no', 1, '../../assets/images/posts/61e88218c6f7cIMG_5315_800x.jpg', 'medium', 'slime'),
(23, 'Neopolitan ice cream! Probably smells really good!', 'charispearl-hs', 'none', '2022-01-19 21:28:44', 'no', 'no', 2, '../../assets/images/posts/61e8828c776cfIMG_5337_800x.jpg', 'medium', 'slime'),
(24, 'To add to the pint party! My favourite is circus cookie dough', 'glynnii-hs', 'none', '2022-01-19 21:30:48', 'no', 'no', 1, '../../assets/images/posts/61e8830809e4eIMG_9071_800x.jpg', 'medium', 'slime'),
(25, 'What a cute illustration... wonder if everyone else is there...', 'beatrice-ya', 'none', '2022-01-19 21:38:14', 'no', 'no', 3, '../../assets/images/posts/61e884c6bcdb4nature-2.jpg', 'medium', 'nature'),
(26, 'Doggos.', 'melody-ya', 'none', '2022-01-19 21:40:10', 'no', 'no', 4, '../../assets/images/posts/61e8853a84090animals-10.png', 'small', 'animals'),
(27, 'Cool illustration. Crane or pelican? Thoughts?', 'milenka-ya', 'none', '2022-01-19 21:43:09', 'no', 'no', 2, '../../assets/images/posts/61e885edadfc6nature-3.jpg', 'medium', 'nature'),
(28, 'Stoooones. Don\'t those rocks look like a face?', 'milenka-ya', 'none', '2022-01-19 21:44:15', 'no', 'no', 3, '../../assets/images/posts/61e8862f1b190nature-4.jpg', 'small', 'nature'),
(29, 'Blessing your feed with art- how aesthetic is this? Let me know what you guys think!', 'miniature-a', 'none', '2022-01-19 21:49:51', 'no', 'no', 2, '../../assets/images/posts/61e8877f86fcaaesthetic-1.png', 'large', 'aesthetic'),
(30, 'My actual passion- making miniature food! Been working on a set to make YT videos. ', 'miniature-a', 'none', '2022-01-19 21:54:25', 'no', 'no', 3, '../../assets/images/posts/61e888919f73bmini-1.png', 'medium', 'mini'),
(31, 'Pictured this great view- what do you guys think?', 'beatrice-ya', 'none', '2022-01-19 21:57:54', 'no', 'no', 3, '../../assets/images/posts/61e88962b31c1aesthetic-4.png', 'small', 'aesthetic'),
(32, 'Hamsters are the cutest. Just look at this guy!', 'beatrice-ya', 'none', '2022-01-19 21:59:07', 'no', 'no', 4, '../../assets/images/posts/61e889ab48f7danimals-7.jpg', 'large', 'animals'),
(33, 'Look at these macarons!', 'kimberly-uni', 'none', '2022-01-22 14:59:51', 'no', 'no', 3, '../../assets/images/posts/61ec1be70d2eeoriginal (1).jpg', 'medium', 'general'),
(34, 'Dandelions- what can I say?', 'kimberly-uni', 'none', '2022-01-26 19:56:55', 'no', 'no', 1, '../../assets/images/posts/61f1a787e947e2345214.jpg', 'small', 'aesthetic'),
(35, 'Looks like my sister hehe', 'kimberly-uni', 'none', '2022-01-27 20:17:24', 'no', 'no', 0, '../../assets/images/posts/61f2fdd49ed1bCharis.png', 'small', 'aesthetic');

-- --------------------------------------------------------

--
-- Table structure for table `pwdreset`
--

CREATE TABLE `pwdreset` (
  `pwdResetID` int(11) NOT NULL,
  `pwdResetEmail` text NOT NULL,
  `pwdResetSelector` text NOT NULL,
  `pwdResetToken` longtext NOT NULL,
  `pwdResetExpires` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `signup_date` date NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `num_posts` int(11) NOT NULL,
  `num_likes` int(11) NOT NULL,
  `user_closed` varchar(3) NOT NULL,
  `friend_array` text NOT NULL,
  `phase` varchar(3) NOT NULL,
  `quote1` varchar(500) NOT NULL DEFAULT 'What''s your latest inspirations?',
  `quote2` varchar(500) NOT NULL DEFAULT 'Motivate yourself!'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `signup_date`, `profile_pic`, `num_posts`, `num_likes`, `user_closed`, `friend_array`, `phase`, `quote1`, `quote2`) VALUES
(1, 'Kimberly', 'Diaz', 'kimberly-uni', 'kimmehdiaz@gmail.com', '821f3157e1a3456bfe1a000a1adf0862', '2021-12-30', '../../assets/images/profile_pics/kimberly-unifff5c5e129478cc4d6a47e044e3e0693n.jpeg', 8, 12, 'no', ',charmaine-hs,charis-hs,oona-hs,charispearl-hs,', 'uni', 'When life gives you lemons, make lemonade', 'Get 70% on all assignments'),
(2, 'Charmaine', 'Diaz', 'charmaine-hs', 'D.charmainegem@yahoo.com', '8ce87b8ec346ff4c80635f667d1592ae', '2021-12-30', '../../assets/images/profile_pics/defaults/Rice Kitty.webp', 3, 12, 'no', ',charis-hs,kimberly-uni,', 'hs', 'What\'s your latest inspirations?', 'Motivate yourself!'),
(3, 'CharisPearl', 'Diaz', 'charispearl-hs', 'D.charispearl@yahoo.com', '8ce87b8ec346ff4c80635f667d1592ae', '2021-12-30', '../../assets/images/profile_pics/defaults/Bevis Works.webp', 5, 6, 'no', ',charmaine-hs,kimberly-uni,', 'hs', 'What\'s your latest inspirations?', 'Motivate yourself!'),
(4, 'Imposter', 'Doe', 'imposter-a', 'Imposter_notfriend@yahoo.com', '8ce87b8ec346ff4c80635f667d1592ae', '2022-01-06', '../../assets/images/profile_pics/defaults/Hedgehog.webp', 0, 0, 'no', ',no-uni,', 'a', 'What\'s your latest inspirations?', 'Motivate yourself!'),
(5, 'Andrew', 'Friend', 'andrew-uni', 'Nofriend@yahoo.com', '8ce87b8ec346ff4c80635f667d1592ae', '2022-01-06', '../../assets/images/profile_pics/defaults/Bevis Works.webp', 0, 1, 'no', ',imposter-a,', '', 'What\'s your latest inspirations?', 'Motivate yourself!'),
(6, 'Kin', 'Diaz', 'kin_diaz', 'Kinbryandiaz@gmail.com', '80e1783471626480b8a18edcbb059008', '2022-01-10', '../../assets/images/profile_pics/defaults/aquamarine-andrey-prokopenko.webp', 0, 0, 'no', ',', '', 'What\'s your latest inspirations?', 'Motivate yourself!'),
(7, 'What', 'Youmean', 'what-ya', 'Whatyoumean@gmail.com', '8ce87b8ec346ff4c80635f667d1592ae', '2022-01-18', '../../assets/images/profile_pics/defaults/Shiba Inu.webp', 0, 0, 'no', ',', 'ya', 'What\'s your latest inspirations?', 'Motivate yourself!'),
(8, 'Luz', 'Diaz', 'luz-a', 'Luzdiaz@gmail.com', '821f3157e1a3456bfe1a000a1adf0862', '2022-01-19', '../../assets/images/profile_pics/defaults/Baby Penguin.webp', 0, 0, 'no', ',', 'a', 'What\'s your latest inspirations?', 'Motivate yourself!'),
(9, 'Carmen', 'Mel', 'carmen-uni', 'Carmenmel@gmail.com', '1bbd886460827015e5d605ed44252251', '2022-01-19', '../../assets/images/profile_pics/defaults/Llama Prints.webp', 2, 8, 'no', ',', 'uni', 'What are your latest inspirations?', 'Motivate yourself!'),
(10, 'Beatrice', 'Garden', 'beatrice-ya', 'Beatricegarden@gmail.com', '1bbd886460827015e5d605ed44252251', '2022-01-19', '../../assets/images/profile_pics/beatrice-yabc17c25b95a7692d9fc6f04bf1027c43n.jpeg', 4, 13, 'no', ',', 'ya', 'Oh, you... wonderful mistake of nature!', 'I guess in some ways Im trying to get home too.'),
(11, 'Glynnii', 'Salogel', 'glynnii-hs', 'Glynniisalogel@gmail.com', '1bbd886460827015e5d605ed44252251', '2022-01-19', '../../assets/images/profile_pics/defaults/Shiba Inu.webp', 2, 4, 'no', ',', 'hs', 'Aim high', 'Good, better, best, never let it rest.'),
(12, 'Oona', 'Oodlethunk', 'oona-hs', 'Oonaoodlethunk@gmail.com', '1bbd886460827015e5d605ed44252251', '2022-01-19', '../../assets/images/profile_pics/oona-hs912d7aae7c95021081e733dc0bfba51en.jpeg', 1, 4, 'no', ',kimberly-uni,', 'hs', 'Im the 4th tallest girl in my class.', 'Be a good egg.'),
(13, 'Jillian', 'Jamey', 'jillian-a', 'Jillianjamey@gmail.com', '1bbd886460827015e5d605ed44252251', '2022-01-19', '../../assets/images/profile_pics/defaults/Hedgehog.webp', 1, 1, 'no', ',', 'a', 'Life has got all those twists and turns.', 'Philippians 4:10 KJV'),
(14, 'Estelle', 'Ina', 'estelle-ya', 'Estelleina@gmail.com', '1bbd886460827015e5d605ed44252251', '2022-01-19', '../../assets/images/profile_pics/defaults/Autumn Lion.webp', 1, 0, 'no', ',', 'ya', 'Things have to get worse before they get better.', 'Art is about honesty'),
(15, 'Carmen', 'Meowy', 'carmen-a', 'Carmenmeowy@gmail.com', '1bbd886460827015e5d605ed44252251', '2022-01-19', '../../assets/images/profile_pics/defaults/Manatee v.2.webp', 3, 4, 'no', ',', 'a', 'What are your latest inspirations?', 'Motivate yourself!'),
(16, 'Melody', 'Sing', 'melody-ya', 'Melodysing@gmail.com', '1bbd886460827015e5d605ed44252251', '2022-01-19', '../../assets/images/profile_pics/melody-ya243589bf9efa865f1d8ec075a8458727n.jpeg', 1, 4, 'no', ',', 'ya', 'What are your latest inspirations?', 'Motivate yourself!'),
(17, 'Milenka', 'Rune', 'milenka-ya', 'Milenkarune@gmail.com', '1bbd886460827015e5d605ed44252251', '2022-01-19', '../../assets/images/profile_pics/defaults/Baby Penguin.webp', 2, 5, 'no', ',', 'ya', 'What are your latest inspirations?', 'Motivate yourself!'),
(18, 'Miniature', 'Master', 'miniature-a', 'Miniaturemaster@gmail.com', '1bbd886460827015e5d605ed44252251', '2022-01-19', '../../assets/images/profile_pics/defaults/Rice Kitty.webp', 2, 5, 'no', ',', 'a', 'What are your latest inspirations?', 'Motivate yourself!'),
(19, 'Luz', 'Diaz', 'luz-a-1', 'Luz1diaz@gmail.com', '1bbd886460827015e5d605ed44252251', '2022-01-21', '../../assets/images/profile_pics/defaults/Scarlet Macaw.webp', 0, 0, 'no', ',', 'a', 'What are your latest inspirations?', 'Motivate yourself!'),
(20, 'Miriam', 'Princess', 'miriam-ya', 'Miriamprincess@gmail.com', '1bbd886460827015e5d605ed44252251', '2022-01-27', '../../assets/images/profile_pics/defaults/Gimme Paw.webp', 0, 0, 'no', ',', 'ya', 'What are your latest inspirations?', 'Motivate yourself!');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pwdreset`
--
ALTER TABLE `pwdreset`
  ADD PRIMARY KEY (`pwdResetID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `pwdreset`
--
ALTER TABLE `pwdreset`
  MODIFY `pwdResetID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

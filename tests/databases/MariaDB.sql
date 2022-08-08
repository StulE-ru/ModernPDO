-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Авг 08 2022 г., 21:21
-- Версия сервера: 10.5.16-MariaDB-1:10.5.16+maria~focal
-- Версия PHP: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `modern_pdo`
--

-- --------------------------------------------------------

--
-- Структура таблицы `account`
--

CREATE TABLE `account` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(32) NOT NULL,
  `name` varchar(16) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `account`
--

INSERT INTO `account` (`id`, `email`, `name`, `password`) VALUES
(1, 'aFcGUZV7sYVUgDKL@yandex.ru', 'mNvYGcRK6tRHH9Pu', 'DfAC8LMCyxpY4W9hj586MJRLCVjA2N6T'),
(2, 'zy3bFhxkvEwk6uYR@gmail.com', 'ugyTXmhYaeEKc9Pd', 'SN3jWJkG4UxqFMbZJdH5kmdkspnnk2sS'),
(3, 'NggGw72dCEapBnfn@yandex.ru', 'kkWNUTR3wrWGukb5', 'QD2Qn3d6znfHJUuacDMN7QbEKWM6r7bZ');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `account`
--
ALTER TABLE `account`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

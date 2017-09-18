SET time_zone = "+00:00";

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `data` text NOT NULL,
  `payment` varchar(100) NOT NULL,
  `hash` varchar(40) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hash` (`hash`);

ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Table structure for table `dbSchoolSuppliesForm`
--

CREATE TABLE `dbSchoolSuppliesForm` (
  `id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `child_name` varchar(256) NOT NULL,
  `grade` varchar(25) NOT NULL,
  `school` varchar(256) NOT NULL,
  `bag_pickup_method` text NOT NULL,
  `need_backpack` boolean NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- AUTO_INCREMENT for table `dbSchoolSuppliesForm`
--
ALTER TABLE `dbSchoolSuppliesForm`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT;

--
-- Indexes for table `dbSchoolSuppliesForm`
--
ALTER TABLE `dbSchoolSuppliesForm`
  ADD PRIMARY KEY (`id`);

--
-- Constraints for table `dbSchoolSuppliesForm`, change dbAnimals to dbChildren
--
ALTER TABLE `dbSchoolSuppliesForm`
  ADD CONSTRAINT `FKschoolsupplies` FOREIGN KEY (`child_id`) REFERENCES `dbAnimals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
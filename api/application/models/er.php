
CREATE TABLE `hr_staff_details` (
  `emp_id` int(11) NOT NULL,
  `com_id` varchar(50) NOT NULL,
  `branch_id` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `type` varchar(5) NOT NULL DEFAULT 'U',
  `grade` varchar(50) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `staff_name` varchar(50) NOT NULL,
  `b_salary` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `join_date` varchar(50) NOT NULL,
  `retd_date` varchar(50) NOT NULL,
  `emp_type` varchar(10) NOT NULL,
  `fathers_name` varchar(50) NOT NULL,
  `dob` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `marital_status` varchar(50) NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `pin` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `kyc` varchar(50) NOT NULL,
  `contact_no` varchar(50) NOT NULL,
  `doc_link` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `login` tinyint(1) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hr_staff_details`
--

INSERT INTO `hr_staff_details` (`emp_id`, `com_id`, `branch_id`, `username`, `password`, `type`, `grade`, `designation`, `staff_name`, `b_salary`, `image`, `join_date`, `retd_date`, `emp_type`, `fathers_name`, `dob`, `gender`, `marital_status`, `nationality`, `address`, `city`, `state`, `pin`, `country`, `email`, `kyc`, `contact_no`, `doc_link`, `status`, `login`, `time`) VALUES
(1, 'EB001', '0', 'shiwam', 'e10adc3949ba59abbe56e057f20f883e', 'U', 'C', '4', 'Santosh Mahato', 10000, '', '26/09/2018', '30/09/2018', 'P', 'Ram Mahato', '10/02/2018', 'M', 'M', 'IN', 'siligfre', 'siliguri', 'WB', '734005', 'INDIA', 'pdshiwa@dfjfdbj.jhdb', '113212101000301', '989889545', '', 1, 1, '2018-10-03 09:51:45'),
(2, 'EB001', '0', '', '', 'U', 'A', '2', 'Ramesh Mahato', 9000, '', '25/09/2018', '26/09/2018', 'P', 'Ram Mahato', '10/02/2018', 'M', 'U', 'IN', 'siligfre', 'siliguri', 'WB', '734005', 'AR', 'pdshiwa@dfjfdbj.jhdb', '113212101000301', '9898895454', '', 1, 0, '2018-09-26 07:14:31'),
(3, 'df', 'df', 'df', '', 'df', '', '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'df', '', '', '', 1, 0, '2018-10-03 13:33:45'),
(4, 'SB160913085', 'MAIN', 'gr_admin', '', 'A', '', '', 'Shiwam', 0, '', '', '', '', '', '', 'M', '', '', '', '', '', '', '', 'shiwam@groveus.com', '', '', '', 1, 1, '2018-10-03 13:57:24'),
(5, 'SB160304114', 'MAIN', 'shiwamavg', '', 'A', '', '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'akjfd@sdfk.com', '', '', '', 1, 0, '2018-10-04 05:58:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hr_staff_details`
--
ALTER TABLE `hr_staff_details`
  ADD PRIMARY KEY (`emp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hr_staff_details`
--
ALTER TABLE `hr_staff_details`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
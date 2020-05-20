<?php
class Date_Holidays_Driver_Custom extends Date_Holidays_Driver 
{
    function Date_Holidays_Driver_Custom()
    {
	}

    function _buildHolidays()
    {
        parent::_buildHolidays();

		/**
		* Grouping:
		* ---------
		* USA & Canada holidays (including federal religious)
		* USA holidays
		* Canada holidays
		* Religious holidays (non-federal)
		* Miscellaneous holidays
		* Commercial holidays
		*/


		/**
		* BEGIN USA & CANADA HOLIDAYS
		*/

		// New Year's Eve
		$this->_addHoliday('newYearsEve', $this->_year . '-12-31', 'New Year\'s Eve');

		// New Year's Day
		$newYearsDay = $this->_year.'-01-01';
		$this->_addHoliday('newYearsDay',$newYearsDay,'New Year\'s Day');

		// New Year's Day (Federal)
		$newYearsDayFederal = $this->_calcNearestWorkDay('01','01');
		$newYearsDayFederalDate = substr($newYearsDayFederal->getDate(),0,10);
		if ($newYearsDay != $newYearsDayFederalDate) $this->_addHoliday('newYearsDayFederal', $newYearsDayFederalDate, 'New Year\'s Day (Federal)');

		// Labor Day
		$laborDay = $this->_calcNthMondayInMonth(9,1);
		$this->_addHoliday('laborDay',$laborDay,'Labor Day');

		// Veteran's  Day
		$vetDay = $this->_year.'-11-11';
		$this->_addHoliday('veteransDay',$vetDay,'Veteran\'s Day/Remembrance Day');

		// Christmas Eve
		$this->_addHoliday('xmasEve', $this->_year . '-12-24', 'Christmas Eve');

		// Christmas  Day
		$cday = $this->_year.'-12-25';
		$this->_addHoliday('christmasDay',$cday,'Christmas Day');

		/**
		* END USA & CANADA HOLIDAYS
		*/


		/**
		* BEGIN USA HOLIDAYS
		*/

		// Dr. Martin Luther King, Jr's Birthday
		$thirdMondayInJanuaryDate  = $this->_calcNthMondayInMonth(1,3);
		$this->_addHoliday('mlkDay', $thirdMondayInJanuaryDate, 'Dr. Martin Luther King, Jr\'s Birthday');

		// Presidents' Day
		$thirdMondayInFebruaryDate  = $this->_calcNthMondayInMonth(2,3);
		$this->_addHoliday('presidentsDay', $thirdMondayInFebruaryDate, 'Presidents\' Day');

		// Memorial Day
		$lastMondayInMayDate = $this->_calcLastMondayInMonth(5);
		$this->_addHoliday('memorialDay',$lastMondayInMayDate,'Memorial Day');

		// 4th of July
		$independenceDay = $this->_year.'-07-04';
		$this->_addHoliday('independenceDay',$independenceDay,'Independence Day');

		// 4th of July (Federal)
		$independenceDayFederal = $this->_calcNearestWorkDay('07','04');
		$independenceDayFederalDate = substr($independenceDayFederal->getDate(),0,10);
		if ($independenceDay != $independenceDayFederalDate) $this->_addHoliday('independenceDayFederal',$independenceDayFederalDate,'Independence Day (Federal)');

		// Columbus Day
		$columbusDay = $this->_calcNthMondayInMonth(10,2);
		$this->_addHoliday('columbusDay',$columbusDay,'Columbus Day');

		// Veteran's Day (Federal)
		$vetDayFederal= $this->_calcNearestWorkDay('11','11');
		$vetDayFederalDate = substr($vetDayFederal->getDate(),0,10);
		if ($vetDay != $vetDayFederalDate) $this->_addHoliday('veteransDayFederal',$vetDayFederalDate,'Veteran\'s Day (Federal USA)');

		// Thanksgiving Day
		$tday= $this->_calcNthThursdayInMonth(11,4);
		$this->_addHoliday('thanksgivingDay',$tday,'Thanksgiving Day');

		// Christmas Day (Federal USA)
		$cdayFederalUSA= $this->_calcNearestWorkDay('12','25');
		$cdayFederalUSADate = substr($cdayFederalUSA->getDate(),0,10);
		if ($cday != $cdayFederalUSADate) $this->_addHoliday('christmasDayFederalUSA',$cdayFederalUSADate,'Christmas (Federal USA)');

		/**
		* END USA HOLIDAYS
		*/


		/**
		* BEGIN CANADA HOLIDAYS
		*/

		// Nunavut Day
		$this->_addHoliday('nunavutDay', $this->_year . '-04-01', 'Nunavut Day (Nanavut, Canada)');

		// Victoria Day
		$vday = $this->_calcMondayBefore('05','25');
		$this->_addHoliday('victoriaDay',$vday, 'Victoria Day (Canada)');

		// Saint-Jean Baptiste Day
		$this->_addHoliday('saintjeanBaptisteDay', $this->_year . '-06-21', 'Saint-Jean Baptiste Day (Quebec, Canada)');

		// Discovery Day (Newfoundland and Labrador)
		$this->_addHoliday('discoveryDayNewfie', $this->_year . '-06-25', 'Discovery Day (Newfoundland and Labrador, Canada)');

		// Canada Day
		$canadaDay = $this->_year.'-07-01';
		$this->_addHoliday('canadaDay',$canadaDay, 'Canada Day');

		// Canada Day (Federal)
		$canadaDayFederal= $this->_calcNearestWorkDay('07','01');
		$canadaDayFederalDate = substr($canadaDayFederal->getDate(),0,10);
		if ($canadaDay != $canadaDayFederalDate) $this->_addHoliday('canadaDayFederal',$canadaDayFederalDate,'Canada Day (Federal Canada)');

		// Civic Holiday
		$firstMondayInAugustDate  = $this->_calcNthMondayInMonth(8,1);
		$this->_addHoliday('civicHoliday', $firstMondayInAugustDate, 'Civic Holiday (Canada)');

		// Discovery Day (Yukon Territory)
		$this->_addHoliday('discoveryDayYukon', $this->_year . '-08-20', 'Discovery Day (Yukon Territory, Canada)');

		// Thanksgiving Day (Canada)
		$secondMondayInOctoberDate  = $this->_calcNthMondayInMonth(10,2);
		$this->_addHoliday('thanksgivingDayCanada', $secondMondayInOctoberDate, 'Thanksgiving Day (Canada)');

		// Remembrance Day (Federal)
		$remDayFederal= $this->_calcNearestMonday('11','11');
		$remDayFederalDate = substr($remDayFederal->getDate(),0,10);
		if ($vetDay != $remDayFederalDate) $this->_addHoliday('remembranceDayFederal',$remDayFederalDate,'Remembrance Day (Federal Canada)');

		// Christmas  Day (Federal Canada)
		$cdayFederalCan= $this->_calcNearestMonday('12','25');
		$cdayFederalCanDate = substr($cdayFederalCan->getDate(),0,10);
		if ($cday != $cdayFederalCanDate) $this->_addHoliday('christmasDayFederalCan',$cdayFederalCanDate,'Christmas (Federal Canada)');

		// Boxing Day
		$boxDay = $this->_year.'-12-26';
		$this->_addHoliday('boxingDay',$boxDay, 'Boxing Day');

		// Boxing Day (Federal Canada)
		$boxDayPieces = explode("-", $cdayFederalCanDate);
		$boxDayFederalCan = $this->_calcFollowingWorkDay($boxDayPieces[1],$boxDayPieces[2]);
		$boxDayFederalCanDate = substr($boxDayFederalCan->getDate(),0,10);
		if ($boxDay != $boxDayFederalCanDate) $this->_addHoliday('boxDayFederalCan',$boxDayFederalCanDate,'Boxing Day (Federal Canada)');

		/**
		* END CANADA HOLIDAYS
		*/
		

		/**
		* BEGIN RELIGIOUS HOLIDAYS
		*/

		// Easter Sunday (***must be calculated FIRST***)
		$easterDate         = $this->calcEaster($this->_year);
		$this->_addHoliday('easter', $easterDate, 'Easter Sunday');

		// Palm Sunday
		$palmSundayDate     = new Date($easterDate);
		$palmSundayDate->subtractSpan(new Date_Span('7, 0, 0, 0'));
		$this->_addHoliday('palmSunday', $palmSundayDate, 'Palm Sunday');

		// Ash Wednesday
		$ashWednesdayDate   = new Date($easterDate);
		$ashWednesdayDate->subtractSpan(new Date_Span('46, 0, 0, 0'));
		$this->_addHoliday('ashWednesday', $ashWednesdayDate, 'Ash Wednesday');

		// Good Friday / Black Friday
		$goodFridayDate     = new Date($easterDate);
		$goodFridayDate->subtractSpan(new Date_Span('2, 0, 0, 0'));
		$this->_addHoliday('goodFriday', $goodFridayDate, 'Good Friday');

		// Easter Monday
		$this->_addHoliday('easterMonday', $easterDate->getNextDay(), 'Easter Monday');

		// All Saints' Day
		$this->_addHoliday('allSaintsDay', $this->_year . '-11-01', 'All Saints\' Day');

		/**
		* END RELIGIOUS HOLIDAYS
		*/


		/**
		* BEGIN MISCELLANEOUS HOLIDAYS
		*/

		// St. Patrick's Day
		$this->_addHoliday('stPatrick', $this->_year . '-03-17', 'St. Patrick\'s Day');

		// Cinco de Mayo
		$this->_addHoliday('cindodeMayo', $this->_year . '-05-05', 'Cinco de Mayo');

		/**
		* END MISCELLANEOUS HOLIDAYS
		*/


		/**
		* BEGIN COMMERCIAL HOLIDAYS
		*/

		// Valentine's Day
		$this->_addHoliday('valentines', $this->_year . '-02-14', 'Valentine\'s Day');

		// Mother's Day
		$secondSundayInMayDate  = $this->_calcNthSundayInMonth(5,2);
		$this->_addHoliday('mothersDay', $secondSundayInMayDate, 'Mother\'s Day');

		// Father's Day
		$thirdSundayInJuneDate  = $this->_calcNthSundayInMonth(6,3);
		$this->_addHoliday('fathersDay', $thirdSundayInJuneDate, 'Father\'s Day');

		// Halloween
		$this->_addHoliday('halloween', $this->_year . '-10-31', 'Halloween');

		/**
		* END COMMERCIAL HOLIDAYS
		*/


		if (Date_Holidays::errorsOccurred()) {
			return Date_Holidays::getErrorStack();
		}
		return true;
    }

   /**
    * Calculate Nth monday in a month
    *
    * @access   private
    * @param    int $month      month
    * @param    int $position   position
    * @return   object Date date
    */
    function _calcNthMondayInMonth($month, $position) {
        if ($position  ==1) {
          $startday='01';
        } elseif ($position==2) {
          $startday='08';
        } elseif ($position==3) {
          $startday='15';
        } elseif ($position==4) {
          $startday='22';
        } elseif ($position==5) {
          $startday='29';
        }
        $month=sprintf("%02d",$month);

        $date   = new Date($this->_year . '-' . $month . '-' . $startday);
        while ($date->getDayOfWeek() != 1) {
            $date  = $date->getNextDay();
        }
        return $date;
    }

   /**
    * Calculate Nth thursday in a month
    *
    * @access   private
    * @param    int $month      month
    * @param    int $position   position
    * @return   object Date date
    */
    function _calcNthThursdayInMonth($month, $position) {
        if ($position  ==1) {
          $startday='01';
        } elseif ($position==2) {
          $startday='08';
        } elseif ($position==3) {
          $startday='15';
        } elseif ($position==4) {
          $startday='22';
        } elseif ($position==5) {
          $startday='29';
        }
        $month=sprintf("%02d",$month);

        $date   = new Date($this->_year . '-' . $month . '-' . $startday);
        while ($date->getDayOfWeek() != 4) {
            $date  = $date->getNextDay();
        }
        return $date;
    }

   /**
    * Calculate last monday in a month
    *
    * @access   private
    * @param    int $month  month
    * @return   object Date date
    */
    function _calcLastMondayInMonth($month) {
        $month =sprintf("%02d",$month);
        $date   = new Date($this->_year . '-' . $month . '-01');
        $daysInMonth=$date->getDaysInMonth();
        $date   = new Date($this->_year . '-' . $month . '-' . $daysInMonth );
        while ($date->getDayOfWeek() != 1) {
            $date = $date->getPrevDay();
        }

        return $date;
    }

   /**
    * Calculate nearest workday for a certain day
    *
    * @access   private
    * @param    int $month  month
    * @param    int $day    day
    * @return   object Date date
    */
    function _calcNearestWorkDay($month,$day) {
        $month =sprintf("%02d",$month);
        $day  =sprintf("%02d",$day);
      $date   = new Date($this->_year . '-' . $month . '-' . $day);

      // When one of these holidays falls on a Saturday, the previous day is also a holiday
      // When New Year's Day, Independence Day, or Christmas Day falls on a Sunday, the next day is also a holiday.
      if ($date->getDayOfWeek() == 0 ) {
        // bump it up one
         $date   = $date->getNextDay();
      }
      if ($date->getDayOfWeek() == 6 ) {
        // push it back one
         $date   = $date->getPrevDay();
      }

      return $date;
    }

   /**
    * Calculate following workday for a certain day
    *
    * @access   private
    * @param    int $month  month
    * @param    int $day    day
    * @return   object Date date
    */
    function _calcFollowingWorkDay($month,$day) {
        $month =sprintf("%02d",$month);
        $day  =sprintf("%02d",$day);
      $date   = new Date($this->_year . '-' . $month . '-' . $day);

      $date = $date->getNextDay();
	  while ($date->getDayOfWeek() == 0 || $date->getDayOfWeek() == 6) {
         $date = $date->getNextDay();
      }
      return $date;
    }

   /**
    * Calculate first Monday preceeding a certain date
    *
    * @access   private
    * @param    int $month  month
    * @param    int $day    day
    * @return   object Date date
    */
    function _calcMondayBefore($month,$day) {
        $month =sprintf("%02d",$month);
        $day  =sprintf("%02d",$day);
      $date   = new Date($this->_year . '-' . $month . '-' . $day);

      $date = $date->getPrevDay();
	  while ($date->getDayOfWeek() != 1) {
         $date = $date->getPrevDay();
      }

      return $date;
    }

   /**
    * Calculate nearest Monday for a certain day
    *
    * @access   private
    * @param    int $month  month
    * @param    int $day    day
    * @return   object Date date
    */
    function _calcNearestMonday($month,$day) {
        $month =sprintf("%02d",$month);
        $day  =sprintf("%02d",$day);
      $date   = new Date($this->_year . '-' . $month . '-' . $day);

      if ($date->getDayOfWeek() == 0 ) {
        // bump it up one
         $date   = $date->getNextDay();
      }
      if ($date->getDayOfWeek() == 6 ) {
        // bump it up two
         $date   = $date->getNextDay();
		 $date   = $date->getNextDay();
      }

      return $date;
    }

   /**
    * Method that returns an array containing the ISO3166 codes that may possibly 
    * identify a driver.
    * 
    * @static
    * @access public
    * @return array possible ISO3166 codes
    */
    function getISO3166Codes() {
        return array('us', 'usa');
    }

    function _calcNthSundayInMonth($month, $position) {
        if ($position  ==1) {
          $startday='01';
        } elseif ($position==2) {
          $startday='08';
        } elseif ($position==3) {
          $startday='15';
        } elseif ($position==4) {
          $startday='22';
        } elseif ($position==5) {
          $startday='29';
        }
        $month=sprintf("%02d",$month);

        $date   = new Date($this->_year . '-' . $month . '-' . $startday);
        while ($date->getDayOfWeek() != 0) {
            $date  = $date->getNextDay();
        }
        return $date;
    }

    function calcEaster($year)
    {
        // golden number
        $golden     = null;
        $century    = null;
        // 23-Epact (modulo 30)
        $epact      = null;
        // number of days from 21 March to the Paschal Full Moon
        $i          = null;
        // weekday of the Full Moon (0=Sunday,...)
        $j          = null; 
        
        if ($year > 1582) {
            $golden     = $year % 19;
            $century    = floor($year / 100);
            $epact      = ($century - floor($century / 4) - floor((8 * $century + 13) / 25)+ 19 * $golden + 15) % 30;
            $i          = $epact - floor($epact / 28) * (1 - floor($epact / 28) * floor(29 / ($epact + 1)) * floor((21 - $golden) / 11));
            $j          = ($year + floor($year / 4) + $i + 2 - $century + floor($century / 4));
            $j          = $j % 7;
        } else {
            $golden = $year % 19;
            $i      = (19 * $golden + 15) % 30; 
            $j      = ($year + floor($year / 4) + $i) % 7;
        }
        $l      = $i - $j;
        $month  = 3 + floor(($l + 40) / 44);
        $day    = $l + 28 - 31 * floor($month / 4);
        
        $date = new Date(sprintf('%04d-%02d-%02d', $year, $month, $day));
        return $date;
    }
}
?>
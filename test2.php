<?php

echo '<hr>';

function addRollover($givenDate, $addtime, $dayStart, $dayEnd, $weekDaysOnly) {
    //Break the working day start and end times into hours, minuets
    $dayStart = explode(':', $dayStart);
    $dayEnd = explode(':', $dayEnd);
    //Create required datetime objects and hours interval
    $datetime = new DateTime($givenDate);
    $endofday = clone $datetime;
    $endofday->setTime($dayEnd[0], $dayEnd[1]); //set end of working day time
    $interval = 'PT'.$addtime.'H';
    //Add hours onto initial given date
    $datetime->add(new DateInterval($interval));
    //if initial date + hours is after the end of working day
    if($datetime > $endofday)
    {
        //get the difference between the initial date + interval and the end of working day in seconds
        $seconds = $datetime->getTimestamp()- $endofday->getTimestamp();

        //Loop to next day
        while(true)
        {
            $endofday->add(new DateInterval('PT24H'));//Loop to next day by adding 24hrs
            $nextDay = $endofday->setTime($dayStart[0], $dayStart[1]);//Set day to working day start time
			$holidays = array('2015-01-01','2015-03-21','2015-04-03','2015-04-06','2015-04-27','2015-05-01',
			'2015-06-16','2015-08-10','2015-09-24','2015-12-16','2015-12-25','2015-12-26');

            //If the next day is on a weekend and the week day only param is true continue to add days
            if((in_array($nextDay->format('l'), array('Sunday','Saturday')) || in_array($endofday->format('Y-m-d'), $holidays)) && $weekDaysOnly)
            {
                continue;
            }
            else //If not a weekend
            {
                $tmpDate = clone $nextDay;
                $tmpDate->setTime($dayEnd[0], $dayEnd[1]);//clone the next day and set time to working day end time
                $nextDay->add(new DateInterval('PT'.$seconds.'S')); //add the seconds onto the next day
                //if the next day time is later than the end of the working day continue loop
                if($nextDay > $tmpDate)
                {
                    $seconds = $nextDay->getTimestamp()-$tmpDate->getTimestamp();
                    $endofday = clone $tmpDate;
                    $endofday->setTime($dayStart[0], $dayStart[1]);
                }
                else //else return the new date.
                {
                    return $endofday;

                }
            }
        }
    }
    return $datetime;
}


$future = addRollover('2015-11-04 11:30:00', 8, '08:00', '16:30', true);

echo "Results: </br>";
$end = $future->format('Y-m-d H:i:s').'</br>';

echo $end;
?>
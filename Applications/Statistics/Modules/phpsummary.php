<?php

namespace Statistics\Modules;

use Statistics\Config;

function phpsummary($module, $interface, $date, $start_time, $offset)
{
    $strgroup = isset($_GET['group']) ? $_GET['group'] : '';
    $groups = getGroupByModule();
    
    $arrGroup=[];
    if(empty($strgroup))
    {
        $arrGroup=$groups;
    }
    else
    {
        if (!in_array($strgroup, $groups)) 
        {
            echo '错误的group';

            return;
        }
        $arrGroup[]=$strgroup;
    }
    
    $info=array();


    

    switch ($date) {
        case 'lastweek':
            //tab
            $tabFlag = 'lastweek';
            //get date string
            $startTime = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - date("w") + 1 - 7, date("Y")));
            $endTime = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - date("w") + 7 - 7, date("Y")));
            $date_str = $startTime . '～' . $endTime;

            //get date list
//            $date_str = $startTime . '～' . $endTime;
            
            foreach($arrGroup as $group)
            {
                $info[$group]=  get_start_all($startTime, $endTime,$group);
                
            }

            //echo "<pre>";
            //print_r($global_avail);

            break;


        case 'lastmonth':
            //tab
            $tabFlag = 'lastmonth';
            $date = getlastMonthDays(date('Y-m'));
            //$date = array('2017-04-01','2017-04-30');
            $startTime = $date[0];
            $endTime = $date[1];
            $date_str = $startTime . '～' . $endTime;
            
             foreach($arrGroup as $group)
            {
                $info[$group]=  get_start_all($startTime, $endTime,$group);
                
            }

//            //get date list
//            $timeLists = array();
//            $days = date('t', strtotime($startTime));
//
//            $starTimeStr = strtotime($startTime);
//            $endTimeStr = strtotime($endTime);
//
//            while ($starTimeStr <= $endTimeStr) {
//                $timeLists[] = date('Y-m-d', $starTimeStr);
//                $starTimeStr += 86400;
//            }
//            //print_r($timeLists);
//
//            //global availability
//            $global_avail = $global_avail_time = array();
//            foreach ($timeLists as $t) {
//                $global_avail_time[$t] = availability($group, $t);
//            }
//            $global_avail = array('total_count' => array_sum_field($global_avail_time, 'total_count'), 'success_count' => array_sum_field($global_avail_time, 'success_count'), 'fail_count' => array_sum_field($global_avail_time, 'fail_count'),);
//            // 总体成功率
//            $global_rate = $global_avail['total_count'] ? round((($global_avail['total_count'] - $global_avail['fail_count']) / $global_avail['total_count']) * 100, 4) : 100;
//
//            //整体分析
//            $statDays = array();
//            foreach ($timeLists as $t) {
//                $statDays[$t] = monthsummary($group, $t);
//            }
//            $statAll = array();
//            $statAll = statallByMonth($statDays);




            //print_r($statAll);

            break;
            case 'movetime':
                 //tab
            $tabFlag = 'movetime';
            //get date string
            $startTime = isset($_GET['start_time']) ? $_GET['start_time'] : '';
            if(empty($startTime))
            {
                $startTime=  date("Y-m-d",  time()-86400);
            }
            $endTime = isset($_GET['end_time']) ? $_GET['end_time'] : '';
            if(empty($endTime))
            {
                 $endTime=  date("Y-m-d");
            }
            
            $date_str = $startTime . '～' . $endTime;
            
            foreach($arrGroup as $group)
            {
                $info[$group]=  get_start_all($startTime, $endTime,$group);
                
            }
            
            
//            $daytime=86400;
//            $intStartTime=  strtotime($startTime);
//            $intEndTime=  strtotime($endTime);
//            $total_day=  ceil(($intEndTime-$intStartTime)/$daytime);
//            
//
//
//            //get date list
//            $timeLists = array();
//            for ($i = 0; $i < $total_day; $i++) {
//                $time = date("Y-m-d", $intStartTime+$i*$daytime);
//                $timeLists[] = $time;
//            }
//            
//            //global availability
//            $global_avail = $global_avail_time = array();
//            foreach ($timeLists as $t) {
//                $global_avail_time[$t] = availability($group, $t);
//            }
//            $global_avail = array('total_count' => array_sum_field($global_avail_time, 'total_count'), 'success_count' => array_sum_field($global_avail_time, 'success_count'), 'fail_count' => array_sum_field($global_avail_time, 'fail_count'),);
//            // 总体成功率
//            $global_rate = $global_avail['total_count'] ? round((($global_avail['total_count'] - $global_avail['fail_count']) / $global_avail['total_count']) * 100, 4) : 100;
//
//
//            //整体分析
//            $statDays = array();
//            foreach ($timeLists as $t) {
//                $statDays[$t] = monthsummary($group, $t);
//            }
//            $statAll = array();
//            $statAll = statallByMonth($statDays);


            //echo "<pre>";
            //print_r($global_avail);

            break;
            
        default:

            break;

    }
    include ST_ROOT . '/Views/header.tpl.php';
    include ST_ROOT . '/Views/phpsummary.tpl.php';
    include ST_ROOT . '/Views/footer.tpl.php';
}


function getlastMonthDays($date)
{
    $timestamp = strtotime($date);
    $firstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) - 1) . '-01'));
    $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));

    return array($firstday, $lastday);
}



function get_start_all($startTime,$endTime,$group)
{
    $daytime=86400;
    $intStartTime=  strtotime($startTime);
    $intEndTime=  strtotime($endTime);
    $total_day=  ceil(($intEndTime-$intStartTime)/$daytime);



    //get date list
    $timeLists = array();
    for ($i = 0; $i < $total_day; $i++) {
        $time = date("Y-m-d", $intStartTime+$i*$daytime);
        $timeLists[] = $time;
    }

    //global availability
    $global_avail = $global_avail_time = array();
    foreach ($timeLists as $t) {
        $global_avail_time[$t] = availability($group, $t);
    }
    $global_avail = array('total_count' => array_sum_field($global_avail_time, 'total_count'), 'success_count' => array_sum_field($global_avail_time, 'success_count'), 'fail_count' => array_sum_field($global_avail_time, 'fail_count'),);
    // 总体成功率
    $global_rate = $global_avail['total_count'] ? round((($global_avail['total_count'] - $global_avail['fail_count']) / $global_avail['total_count']) * 100, 4) : 100;


    //整体分析
    $statDays = array();
    foreach ($timeLists as $t) {
        $statDays[$t] = monthsummary($group, $t);
    }
    $statAll = array();
    $statAll = statallByMonth($statDays);
    if(!empty($statAll))
    {
        foreach($statAll as $s=> $stat)
        {
            if(!empty($stat))
            {
                foreach ($stat as $interface => $levels)
                {
                    foreach ($levels as $key => $val)
                    {
                        if($key == 'notice')
                        {
                            unset($statAll[$s][$interface][$key]);
                        }
                    }
                }
            }
        }
    }
    
    
    return array("global_avail"=>$global_avail,"global_rate"=>$global_rate,"startAll"=>$statAll);
}

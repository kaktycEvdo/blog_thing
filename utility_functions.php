<?php
function formatDisplayTime($interval){
    $res = '';
    if($interval->y > 0){
        $y = $interval->y;
        switch ($y){
            case 1:
                $res = "1 год назад"; break;
            case 2:
            case 3:
                $res = $y." года назад"; break;
            default:
                $res = $y." лет назад"; break;
        }
    }
    else if($interval->m > 0){
        $m = $interval->m;
        switch ($m){
            case 1:
                $res = "1 месяц назад"; break;
            case 2:
            case 3:
            case 4:
                $res = $m." месяца назад"; break;
            default:
                $res = $m." месяцев назад"; break;
        }
    }
    else if($interval->days > 0){
        $d = $interval->days;
        switch ($d){
            case 1:
                $res = "1 день назад"; break;
            case 2:
            case 3:
            case 4:
                $res = $d." дня назад"; break;
            default:
                $res = $d." дней назад"; break;
        }
    }
    else if($interval->h > 0){
        $h = $interval->h;
        switch ($h){
            case 1:
                $res = "час назад"; break;
            case 2:
            case 3:
            case 4:
                $res = $h." часа назад"; break;
            default:
                $res = $h." часов назад"; break;
        }
    }
    else if($interval->i > 0){
        $i = $interval->i;
        switch ($i){
            case 1:
                $res = "час назад"; break;
            case 2:
            case 3:
            case 4:
                $res = $i." часа назад"; break;
            default:
                $res = $i." часов назад"; break;
        }
    }
    else{
        $res = 'меньше минуты назад';
    }

    return $res;
}
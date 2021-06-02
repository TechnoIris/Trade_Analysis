<?php
include('indb.php');
session_start();

function buy($arr, $i, $tm, $nos){
  if ($tm>=$arr[$i]) {
    $nos = $tm/$arr[$i];
    $tm = $tm % $arr[$i];
  }else {
  }
  return array($nos, $tm);
}


function sell($arr, $i, $tm, $nos){
  if ($nos > 0 && $i < sizeof($arr)) {
    $tm+=$arr[$i]*$nos;
    $nos = 0;
  }else {
  }
  return array($nos, $tm);
}

function eval_max($r, $s, $t, $d1, $d2, $d3, $tt){
  // return ($r-$tt>=$s-$tt&&$r-$tt>=$t-$tt)?$d1:($s-$tt>=$t-$tt&&$s-$tt>=$r-$tt?$d2:$d3);
  return ($t-$tt>=$r-$tt&&$t-$tt>=$s-$tt)?$d3:($s-$tt>=$t-$tt&&$s-$tt>=$r-$tt?$d2:$d1);
}

function check_max($nos, $i, $arr, $n, $tm, $tps, $word) {
  $tmx1 = $tm;
  $tmx2 = $tm;
  $tmx3 = $tm;
  $d1 = array();
  $d2 = array();
  $d3 = array();
  if ($n == 0) {
    return array(array(array($i-1, $arr[$i-1], $word, $nos)), $tm);
  }elseif ($tps == 0) {
    $tmp = 1 * $arr[$i];
    $tpsp = 1;
    list($nos1, $tm1) = buy($arr, $i, $tmp, $nos);
    list($d1, $tmx1) = check_max($nos1, $i+1, $arr, $n-1, $tm, $tpsp, 'buy');
    list($d2, $tmx2) = check_max($nos, $i+1, $arr, $n-1, $tm, $tps, 'pass');
    $x = $tmx1 - $tmp;
    $y = $tmx2 - $tm;
    // $z = -10000;
    $tm+= max($tmx1-$tmp, $tmx2-$tm);
    $word = $x>$y?'buy':'pass';
  }else {
    list($nos1, $tm1) = buy($arr, $i, $tm, $nos);
    list($nos2, $tm2) = sell($arr, $i, $tm, $nos);
    if ($tm1 >= $arr[$i]) {
      list($d1, $tmx1) = check_max($nos1, $i+1, $arr, $n-1, $tm1, $tps, 'buy');
    }
    list($d2, $tmx2) = check_max($nos, $i+1, $arr, $n-1, $tm, $tps, 'pass');
    if ($nos>0) {
      list($d3, $tmx3) = check_max($nos2, $i+1, $arr, $n-1, $tm2, $tps, 'sell');
    }
    $tm+= max($tmx1-$tm, $tmx2-$tm, $tmx3-$tm);
  }
  $s = eval_max($tmx1, $tmx2, $tmx3, $d1, $d2, $d3, $tm);
  $new = array(array($i, $arr[$i], $word, $nos));
  if ($word == 'pass') {
    $new = array(array($i, $arr[$i], $word, 0));
  }
  foreach ($s as $value) {
    array_push($new, $value);
  }
  return array($new, $tm);
}

function finale($arr){
  $n = sizeof($arr);
  $i = 0;
  $nos = 0;
  $tm = 0;
  $tps = 0;
  $word = '';

  list($a, $b) = check_max($nos, $i, $arr, $n, $tm, $tps, $word);
  // $a[sizeof($a)-2] = $a[sizeof($a)-1];
  // unset($a[sizeof($a)-1]);


  $buy = 0;
  for ($i=0; $i < sizeof($a); $i++) {
    if ($a[$i][2]=='buy') {
      $buy = $a[$i][3];
    }elseif ($a[$i][2]=='sell') {
      $a[$i][3] = $buy;
    }
  }

  $total_buy = 0;
  $total_cost = 0;
  $ss = 0;
  // means = total val of shares buyed by total no of shares buyed.
  foreach ($a as $i) {
    if ($i[2]=='buy') {
      $ss = $i[3];
      $total_buy+=$i[3];
      $total_cost+=$i[1];
    }
  }

  $mean_stock = $total_cost/$total_buy;
  // echo "mean stock is " . $mean_stock . "\n";

  $sum = 0;
  $avg_mean = 0;
  $dev_sum = 0;
  $xmean = 0;
  $deviation = array();
  $deviation_sqr = array();

  foreach ($arr as $value) {
    $sum+=$value;
  }
  $avg_mean = $sum/$n;

  foreach ($arr as $i) {
    array_push($deviation, $i/$avg_mean);
    array_push($deviation_sqr, ($i/$avg_mean) * ($i/$avg_mean));
  }

  foreach ($deviation as $i) {
    $dev_sum+=$i;
  }
  foreach ($deviation_sqr as $i) {
    $xmean+=$i;
  }

  $variance = $xmean/($n-1);
  $SD = sqrt($variance);

  // echo json_encode(array($a, $b, $mean_stock, $SD));
  return array($a, $b, $mean_stock, $SD);
}


$name = '';
$datearr = array();
$price = array();
$from = 0;
$to = 0;
$mindate = 0;
$maxdate = 0;
$message = "";

if (isset($_POST['stocks']) && $_POST['stocks']!= 'None')
{
  $name = $_POST['stocks'];
  $from = $_POST['dateTimePickerFrom'];
  $to = $_POST['dateTimePickerTo'];

  if ($from == '') {
    $from = '01-01-1990';
  }
  if ($to == '') {
    $to = '01-01-1990';
  }

  try {
    $query = "select date_format(max(date_),'%d-%m-%Y') from stocks where stockname = ?";
    $statement = $connect->prepare($query);
    $statement->bindParam(1, $name, PDO::PARAM_STR, 12);
    $statement->execute();
    $result = $statement->fetchAll();
    $maxdate = strval($result[0][0]);
    // $message = $maxdate;

    $query = "select date_format(min(date_),'%d-%m-%Y') from stocks where stockname = ?";
    $statement = $connect->prepare($query);
    $statement->bindParam(1, $name, PDO::PARAM_STR, 12);
    $statement->execute();
    $result = $statement->fetchAll();
    $mindate = strval($result[0][0]);
    // $message = $mindate;

    $d1 = date_create($mindate);
    $d2 = date_create($maxdate);
    $d3 = date_create($from);
    $d4 = date_create($to);
    $diff_min1 = date_diff($d1,$d3);
    $diff_min2 = date_diff($d1, $d4);
    $diff_max1 = date_diff($d2,$d3);
    $diff_max2 = date_diff($d2, $d4);
    $diff1 = (int)$diff_min1->format("%R%a");
    $diff2 = (int)$diff_min2->format("%R%a");
    $diff3 = (int)$diff_max1->format("%R%a");
    $diff4 = (int)$diff_max2->format("%R%a");


    if ( $from == '01-01-1990' || $to == '01-01-1990') {
      $message = "Please specify Range.";
    }elseif ($diff3 > 0 && $diff4 > 0) {
      $message = "No range of Stocks present dates greater than " . $maxdate . ".";
    }elseif ($diff1 < 0 && $diff2 < 0) {
      // $message = $d1 . $d3 . $d4 . $diff3 . $diff4;
            $message = "No range of Stocks present dates lesser than " . $mindate . ".";
    }else{
      // $message = "coming here";
      $query = "select date_format(date_, '%d-%m-%Y') from stocks where stockname = ? and date_ <= str_to_date(?, '%d-%m-%Y') limit 1";
      $statement = $connect->prepare($query);
      $statement->bindParam(1, $name, PDO::PARAM_STR, 12);
      $statement->bindParam(2, $from, PDO::PARAM_STR, 12);
      $statement->execute();
      $result = $statement->fetchAll();
      $from = $result[0][0];
      // $message = $from;

      $query = "select date_format(date_, '%d-%m-%Y') from stocks where stockname= ? and date_ >= str_to_date(?, '%d-%m-%Y') limit 1";
      $statement = $connect->prepare($query);
      $statement->bindParam(1, $name, PDO::PARAM_STR, 12);
      $statement->bindParam(2, $to, PDO::PARAM_STR, 12);
      $statement->execute();
      $result = $statement->fetchAll();
      $to = $result[0][0];

      $query = "select distinct(date_), price from stocks where stockname=? and date_ between str_to_date(?, '%d-%m-%Y') and str_to_date(?, '%d-%m-%Y')";
      $statement = $connect->prepare($query);
      $statement->bindParam(1, $name, PDO::PARAM_STR, 12);
      $statement->bindParam(2, $from, PDO::PARAM_STR, 12);
      $statement->bindParam(3, $to, PDO::PARAM_STR, 12);
      $statement->execute();
      $result = $statement->fetchAll();

      foreach ($result as $value) {
        array_push($datearr, $value[0]);
        array_push($price, (int)$value[1]);
      }
      // $message = $price;
    }

  } catch (Exception $e) {
    $message = "Kindly spare for our inconvinient service for a while. Please retry after a while.";
  }

}

try {
  $connect = null;
} catch (Exception $e) {
}

$ar = array();
$pr = 0;
$ms = 0;
$sd = 0;
if (isset($price) && $price != '') {
  list($ar, $pr, $ms, $sd) = finale($price);
  $ms = intval($ms);
  $sd = intval($sd);
  $pr = intval($pr);
}
// , $ar, $pr, $ms, $sd
$dest = json_encode(array($message, $datearr, $price, $ar, $pr, $ms, $sd));
echo $dest;

?>

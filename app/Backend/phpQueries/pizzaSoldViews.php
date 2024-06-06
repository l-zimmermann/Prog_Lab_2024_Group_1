<?php

// include the DB connect file. ../ because its outside of this folder
global $conn;

// get sales numbers for specific year and week 
function getSalesFromWeek($year, $week, $mode)
{
  global $conn;
if($mode == 'units') {

    $sql =
    "SELECT   YEAR(o.orderDate)     as sellingYear,
              week(o.orderDate, 1)  as sellingWeek,
              DAYNAME(o.orderDate)  as sellingDay,
              sum(o.nItems)         as pizzaSold
    FROM orders o
    WHERE YEAR(o.orderDate) = $year
    AND WEEK(o.orderDate, 1) = $week
    GROUP BY sellingYear, sellingWeek, weekday(o.orderDate), sellingDay
    ORDER BY sellingYear, sellingweek, weekday(o.orderDate)";

}
elseif($mode == 'revenue') {

  $sql = "SELECT  YEAR(o.orderDate)     as sellingYear,
                  week(o.orderDate, 1)  as sellingWeek,
                  DAYNAME(o.orderDate)  as sellingDay,
                  sum(o.total)          as revenuePerDay
        FROM orders o
        WHERE YEAR(o.orderDate) = $year
          AND WEEK(o.orderDate, 1) = $week
        GROUP BY sellingYear, sellingWeek, weekday(o.orderDate), sellingDay
        ORDER BY sellingYear, sellingweek, weekday(o.orderDate)";

}
else
{
  echo 'no valid mode selected, chose units or revenue';
}



  $result = $conn->query($sql);

  $data = array();
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
  } else {
    $data[] = "0 results";
  }
  $conn->close();

  return json_encode($data);
}
?>
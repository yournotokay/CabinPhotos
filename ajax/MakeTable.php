<?php    $date = mktime(0,0,0,date('m',$date),date('d',$date)-$dayNum,date('Y',$date));
echo "<td class='nobg' align='center' style='width: 100%'><b>".date('D F j, Y',$date)."</b></td></tr><tr>";
?>
                <table class="special" border="1"><tr>
                    <th width="150">Front*</th>
                    <th width="150">Deck*</th>
                    <th width="200"><b>Time</b> Temp(In/Out)</th>
                </tr>
<?php
    $date = date('Y-m-d',$date);
    $query = "select * from images where creation_date='$date' order by creation_time;";

    $result = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));

    $resultTable = array();
    while($row = mysqli_fetch_array($result)){
        array_push($resultTable, $row);
    }
    mysqli_free_result($result);

    unset($timeTable);
    foreach($resultTable as $row){
        $file = $row['file_name'];
        $time = $row['creation_time'];
        $tempIn = $row['tempIn'];
        $tempOut = $row['tempOut'];
        if($tempIn == 'NULL' || $tempIn == null) {
            $tempIn = "";
        }
        if($tempOut == 'NULL' || $tempOut == null) {
            $tempOut = "";
        }
        if(!preg_match('/(\d+):(\d+):\d+/',$time,$group)){
            echo "Bad Time format.\n";
            die;
        }
        $hour = $group[1];

        preg_match('/([^\\/]*$)/',$file,$group);
        $filename = $group[1];
        preg_match('/^(deck_pan|deck|front)/',$filename,$group);
        $place = $group[1];

        $timeTable[$hour][$place] = $filename;
        $tempInTable[$hour][$place] = $tempIn;
        $tempOutTable[$hour][$place] = $tempOut;

    }
    foreach($timeTable as $hour => $pics){
        $tempInStr = "?";
        $tempOutStr = "?";

        $timeStr = date('g:i A  ',mktime($hour,0));

        echo "<tr>\n<td align='center'>";
        if($pics['front']){
            echo "<img style=\"width: 128px; height: 96px;\" src=\"cabinImages/".
                $pics["front"]."\" alt=\"\" onClick=\"showModal('cabinImages/".
                $pics["front"]."')\" />\n";
             if($tempInTable[$hour]['front']){
                 $tempInStr = $tempInTable[$hour]['front'];
             }
             if($tempOutTable[$hour]['front']){
                 $tempOutStr = $tempOutTable[$hour]['front'];
             }
        }
        echo "</td>\n<td align='center'>";
        if($pics['deck_pan']){
            echo "<img style=\"width: 128px; height: 96px;\" src=\"cabinImages/".
                $pics["deck_pan"]."\" alt=\"\" onClick=\"showModal('cabinImages/".
                $pics["deck_pan"]."')\" />\n";
             if($tempInTable[$hour]['deck_pan']){
                 $tempInStr = $tempInTable[$hour]['deck_pan'];
             }
             if($tempOutTable[$hour]['deck_pan']){
                 $tempOutStr = $tempOutTable[$hour]['deck_pan'];
             }
        }
        elseif($pics['deck']){
            echo "<img style=\"width: 128px; height: 96px;\" src=\"cabinImages/".
                $pics["deck"]."\" alt=\"\" onClick=\"showModal('cabinImages/".
                $pics["deck"]."')\" />\n";
             if($tempInTable[$hour]['deck']){
                 $tempInStr = $tempInTable[$hour]['deck'];
             }
             if($tempOutTable[$hour]['deck']){
                 $tempOutStr = $tempOutTable[$hour]['deck'];
             }
        }
        echo "</td><td align='center' style='font-size: small;'><b>$timeStr</b><br />(".@$tempInStr."/".@$tempOutStr." &deg;F)</td>\n</tr>\n";
    }
?>
</table>

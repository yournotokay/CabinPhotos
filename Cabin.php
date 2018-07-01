<html>
    <head>
        <title>Cabin Snapshots</title>
        <link href="styles.css" rel="stylesheet" type="text/css" />
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/redmond/jquery-ui.css" rel="stylesheet" type="text/css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.js"></script>
<!-- Location of javascript. -->
<script language="javascript" type="text/javascript" src="swfobject.js" ></script>
<script>
        var today = "<?php echo time(); ?>";
        var oneDay = 24 * 60 * 60;
        var live = false;
        var loaded = false;
        var music = false;

        $(function() {
            $( "#datepicker" ).datepicker({
                onSelect: function(dateText, inst) {
                    console.log(today);
                    today = Date.parse(dateText)/1000;
                    console.log(today);
                    $('#pics0').load('ajax/getDay.php?time='+today,changeDays());
                    $('#pics1').load('ajax/getDay.php?time='+(today - oneDay),changeDays());
                },
                maxDate: 0,
            });
        });

        function changeDays(){
            
        }

        function next(){
            today += oneDay;
            $('#pics1').html($('#pics0').html());
            $('#pics0').load('ajax/getDay.php?time='+today,changeDays());
        }
        function previous(){
            today -= oneDay;
            $('#pics0').html($('#pics1').html());
            $('#pics1').load('ajax/getDay.php?time='+(today - oneDay),changeDays());
        }

        $(document).ready(function(){$("#dialog-modal").dialog({ width: 'auto', autoOpen: false, modal: true })});
        function showModal(url){
            $("#dialog-modal").html('<img src="'+ url +'">');
            $("#dialog-modal").dialog('open');
        };
        function toggleMusic(){
            if(music){
                music=false;
                $('#player').hide();
            }
            else{
                music=true;
                $('#player').show();
            }

        };
        function toggleLiveCamera(){
            if(loaded && !live){
                $('#liveCamera').show();
                live = true;
                $('#liveLink').html('Hide Camera');
            }
            else if(loaded){
                $('#liveCamera').hide()
                live = false;
                $('#liveLink').html('Show Camera');
            }
            else{
                $('#liveCamera').html(
        '<center><applet height="480" width="640" code="xplug.class" codebase="http://cozycabin.dyndns.org:81/" name="cvcs"><param value="81" name="RemotePort"><param value="5000" name="Timeout"><param value="0" name="RotateAngle"><param value="2" name="PreviewFrameRate"><param value="" name="DeviceSerialNo"></applet></center>'
                    );
                loaded = true;
                live = true;
                $('#liveLink').html('Hide Camera');
            }
        }
        /********************************
        * *   (C) 2009 - Thiago Barbedo   *
        * *   - tbarbedo@gmail.com        *
        * *********************************/
        window.onscroll = function()
        {
            if( window.XMLHttpRequest ) {
                if (document.documentElement.scrollTop > 0 || self.pageYOffset > 0) {
                    $('#player').css('position','fixed');
                    $('#player').css('bottom','0');
                } else if (document.documentElement.scrollTop < 0 || self.pageYOffset < 0) {
                    $('#player').css('position','absolute');
                    $('#player').css('bottom','0px');
                }
            }
        }
        </script>
    </head>
    <body>
        <div id="dialog-modal" title="Image"></div>
        <center>
            <h1>Cabin Deck</h1>
            <a id='liveLink' href="javascript:toggleLiveCamera()">Live Camera</a>
            <a href="javascript:toggleMusic()">Music Player</a>
            <div id='liveCamera'></div>
<!-- Div that contains player. -->
<div id="player">
<h1>No flash player!</h1>
<p>It looks like you don't have flash player installed. <a href="http://www.macromedia.com/go/getflashplayer" >Click here</a> to go to Macromedia download page.</p>
</div>

<!-- Script that embeds player. -->
<script language="javascript" type="text/javascript">
var so = new SWFObject("flashmp3player.swf", "player", "290", "247", "9"); // Location of swf file. You can change player width and height here (using pixels or percents).
so.addParam("quality","high");
so.addVariable("content_path","mp3"); // Location of a folder with mp3 files (relative to php script).
so.addVariable("color_path","default.xml"); // Location of xml file with color settings.
so.addVariable("script_path","flashmp3player.php"); // Location of php script.
so.write("player");
$('#player').hide();
</script>
</div>
<table style="margin-top: -28px; margin-bottom: -10px; width: 100%;">
    <tr><td class="nobg" align="left"><a id="next arrow" href="javascript: next()">&lt;Next</a></td><td class="nobg" align="right"><a href="javascript: previous()">Previous&gt;</a></td></tr>
</table>
<div style="text-align: center;">
<p>Date: <input type="text" id="datepicker" readonly="readonly"></p>
</div>
            <table style="margin-top: -20px;" cellspacing="30">
            <tr>
<?php
$yesterday = mktime(0,0,0,date('m'),date('d')-1,date('Y'));

require("/var/www/html/private/Secrets.php");

$link = mysqli_connect($hostname, $db_user, $db_password, $database)
        or die('Could not connect: ' . mysqli_connect_error());


for($dayNum=0;$dayNum<=1;$dayNum++) {
?>
        <td id="pics<?php echo $dayNum;?>" class="nobg" valign="top">
            <table class="special" style="width: 100%">
                <tr align="center" style="width: 100%">
<?php
    $date = mktime(0,0,0,date('m'),date('d')/*-$dayNum*/,date('Y'));
    
    require("ajax/MakeTable.php");
?>
</td>
<?php
}

mysqli_close($link);
?>
            </tr></table>
        </center>
        *Click on the images to enlarge.
<br/><br/>
<br/><br/>
<br/><br/>
<br/><br/>
<br/><br/>
<br/><br/>
Powered by <a href="http://www.flashmp3player.org">Flash MP3 Player</a>
    </body>
</html>

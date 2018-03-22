<?php
/**
 * Created by PhpStorm.
 * User: purnima
 * Date: 2/23/18
 * Time: 6:32 PM
 */

include_once '/var/www/html/hw6/footer.php';
include_once '/var/www/html/hw6/hw6-lib.php';

$header = file_get_contents('/var/www/html/hw6/header.php');
$footer = file_get_contents('/var/www/html/hw6/footer.php');

if (isset($_POST['characterName']) and isset($_POST['characterRace'])) {
    $db=connect();
    $characterName = $_POST['characterName'];
    $characterRace = $_POST['characterRace'];
    $characterSide = $_POST['characterSide'];

    $characterName = mysqli_real_escape_string($db, $characterName);
    $characterRace = mysqli_real_escape_string($db, $characterRace);
    $characterSide = mysqli_real_escape_string($db, $characterSide);
    if ($stmt = mysqli_prepare($db, "insert into characters set name=?, race=?, side=?")) {
        mysqli_stmt_bind_param($stmt, "sss", $characterName, $characterRace, $characterSide);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $characterName, $characterRace, $characterSide);
        mysqli_stmt_close($stmt);
    }
    mysqli_commit($db);
    mysqli_close($db);
    echo $header . '<form method=post action=add.php> 
                                <table> <tr> <td colspan=2> Add Picture to Character  </td> </tr>
                                <tr> <td> Character Picture URL </td> <td> <input type=text name=characterPicture value=""> </td> </tr>
                                <tr> <td colspan=2>
                                     <input type=hidden name=s value=8>
                                     <input type=hidden name=characterName value='.$characterName.'>
                                     <input type=submit name=submit value=submit> </td></tr>
                                </table> 
                                </form><br> <br> <a href=login.php> Logout </a> <br>' . $footer;
}else if (isset($_POST['characterPicture']) ? $characterPicture = strip_tags($_REQUEST['characterPicture']) : $characterPicture = "" ) {
    $characterName = $_POST['characterName'];

    $db=connect();
    $characterName = mysqli_real_escape_string($db, $characterName);
    $characterPicture = $_POST['characterPicture'];
    if ($stmt = mysqli_prepare($db, "select characterid from characters where name=?")) {
        mysqli_stmt_bind_param($stmt, "s", $characterName);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $cid);
        while (mysqli_stmt_fetch($stmt)) {
            $cid = htmlspecialchars($cid);
        }
        mysqli_stmt_close($stmt);
        if ($stmt = mysqli_prepare($db, "insert into pictures set url=?, characterid=?")) {
            mysqli_stmt_bind_param($stmt, "si", $characterPicture, $cid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $characterPicture, $cid);
        }
        mysqli_stmt_close($stmt);
        echo $header . 'Added Picture for <br><form method=post action=add.php> 
		<table> <tr> <td colspan=2> Add  to Books </td> </tr>
		<tr> <td> Select Book </td> <td> <select name=bid> <option value="3"> The Fellowship of the Ring
            <option value="1"> The Hobbit
            <option value="5"> The Return of the King
            <option value="4"> The Two Towers
            </select> </td> </tr>
		<tr> <td>
		<input type=hidden name=s value=7>
		<input type=hidden name=cid value=' . $cid . '>
		<input type=hidden name=characterName value="">
		<input type=submit name=submit value="Add to Book">
		</td> <td> </td></tr>
		</table> 
		</form><br> <br> <a href=login.php> Logout </a> <br>' . $footer;
        mysqli_close($db);
    }
}else if (isset($_POST['bid']) ? $bid = strip_tags($_REQUEST['bid']) : $bid = "" ) {
    $db=connect();
    $cid = $_POST['cid'];
    $bid = $_POST['bid'];
    $cid = mysqli_real_escape_string($db, $cid);
    $bid = mysqli_real_escape_string($db, $bid);
    if ($stmt = mysqli_prepare($db, "insert into appears set bookid=?, characterid=?")) {
        mysqli_stmt_bind_param($stmt, "ii", $bid, $cid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $cid);
        mysqli_stmt_close($stmt);
    }
    mysqli_commit($db);
    mysqli_close($db);
    echo $header."Added  to book ".$bid . "<br>
        <form method=post action=add.php> 
		<table> <tr> <td colspan=2> Add  to Books </td> </tr>
		<tr> <td> Select Book </td> <td> <select name=bid> 
		        <option value='1'> The Hobbit
		        <option value='3'> The Fellowship of the Ring
                <option value='5'> The Return of the King
                <option value='4'> The Two Towers
                </select> </td> </tr>
		<tr> <td>
		<input type=hidden name=s value=7>
		<input type=hidden name=cid value='.$cid.'>
		<input type=hidden name=characterName value=''>
		<input type=submit name=submit value='Add to Book'>
		</td> <td> <a href=index.php?cid=$cid&s=3> Done </a> </td></tr>
		</table> 
		</form><br> <br> <a href=login.php> Logout </a> <br>".$footer;
}else{
    echo $header.'<form method=post action=add.php> 
		<table> <tr> <td colspan=2> Add Character to Books </td> </tr>
		<tr> <td> Character Name </td> <td> <input type=text name=characterName value=""> </td> </tr>
		<tr> <td> Race </td> <td> <input type=text name=characterRace value=""> </td> </tr>
		<tr> <td> Side </td> <td> <input type="radio" name="characterSide" value="good"> Good  <input type="radio" name="CharacterSide" value="evil"> Evil </td> </tr>
		<tr> <td colspan=2> <input type=hidden name=s value=5> <input type=submit name=submit value=submit> </td></tr>
		</table> 
		</form><br> <br> <a href=login.php> Logout </a> <br>'.$footer;
}

?>







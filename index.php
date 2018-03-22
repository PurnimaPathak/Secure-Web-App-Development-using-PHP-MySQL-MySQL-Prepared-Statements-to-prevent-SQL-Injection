<?php
/**
 * Created by PhpStorm.
 * User: purnima
 * Date: 2/22/18
 * Time: 5:19 PM
 */
#include_once '/var/www/html/hw6/header.php';
include_once '/var/www/html/hw6/footer.php';

include_once '/var/www/html/hw6/hw6-lib.php';


$header = file_get_contents('/var/www/html/hw6/header.php');
$footer = file_get_contents('/var/www/html/hw6/footer.php');
$db =connect();

if (isset ( $_REQUEST['s'] ) ? $s = strip_tags($_REQUEST['s']) : $s = ""){
    $s = $_GET['s'];
    icheck($s);
    switch ($s){
        case 1:
            #### Window S=1 Showing the books based on the storyId ######
            #$header = file_get_contents('header.php');
            echo $header.'<table> <tr> <td> <b> <u> Books </b></u> </td></tr>';
            if (isset($_GET['sid'])) {
                $sid = $_GET['sid'];
                $sid = mysqli_real_escape_string($db, $sid);

                if ($stmt = mysqli_prepare($db, "SELECT bookid, title from books where storyid=?")) {
                    mysqli_stmt_bind_param($stmt, "s", $sid);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $bid, $title);
                    while (mysqli_stmt_fetch($stmt)) {
                        $bid = htmlspecialchars($bid);
                        $title = htmlspecialchars($title);
                        echo "<tr> <td> <a href=index.php?bid=$bid&s=2>$title</a></td></tr>";
                    }
                    mysqli_stmt_close($stmt);
                }
                echo '</table>'. $footer;
            }
            break;
        case 2:
            #### Window S=2 Showing the characters based on the bookid ######
            echo $header.'<table> <tr> <td> <b> <u> Characters </b></u> </td></tr>';
            if (isset($_GET['bid'])) {
                $bid = $_GET['bid'];
                $bid = mysqli_real_escape_string($db, $bid);

                if ($stmt = mysqli_prepare($db, "select characterid, name from characters where characterid IN (select characterid from appears where bookid=$bid)")) {
                    mysqli_stmt_bind_param($stmt, "s", $bid);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $cid, $name);
                    while (mysqli_stmt_fetch($stmt)) {
                        $cid = htmlspecialchars($cid);
                        $name = htmlspecialchars($name);
                        echo "<tr> <td> <a href=index.php?cid=$cid&s=3>$name</a></td></tr>";
                    }
                    mysqli_stmt_close($stmt);
                }
                echo '</table>'. $footer;
            }
            break;
        case 3:
            #### Window S=3 Showing the Appearances based on the Characterid ######
            echo $header.'<table> <tbody><tr> <td colspan="3"> <b> <u> Appearances </b></u> </td> </tr>
                            <tr> <td> Character </td><td> Book </td> <td> Story </td> </tr>';
            if (isset($_GET['cid'])) {
                $cid = $_GET['cid'];
                $cid = mysqli_real_escape_string($db, $cid);

                if ($stmt = mysqli_prepare($db, "select characters.name, books.title, stories.story from characters JOIN appears on appears.characterid=characters.characterid join books on appears.bookid=books.bookid  JOIN stories on stories.storyid=books.storyid where characters.characterid=?")) {
                    mysqli_stmt_bind_param($stmt, "s", $cid);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $characterName, $title, $story);
                    while (mysqli_stmt_fetch($stmt)) {
                        $characterName = htmlspecialchars($characterName);
                        $title = htmlspecialchars($title);
                        $story = htmlspecialchars($story);
                        echo "<tr> <td> <a href=index.php>$characterName</a></td><td> <a href=index.php> $title</a> </td><td> <a href=index.php> $story </a> </td></tr><br>";
                    }
                    mysqli_stmt_close($stmt);
                }
                echo '</tbody></table>'. $footer;
            }
            break;
        case 50:
            #### Window S=50 Showing the pictures of characters ######
            echo $header.'<table> <tbody><tr> <td colspan="3"> <b> <u> Characters </b></u> </td> </tr>';
            if ($stmt = mysqli_prepare($db, "SELECT characters.characterid, characters.name, pictures.url FROM tolkien.pictures JOIN tolkien.characters on pictures.characterid=characters.characterid")) {
                    #mysqli_stmt_bind_param($stmt, "s", $cid);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $cid, $characterName, $url);
                    while (mysqli_stmt_fetch($stmt)) {
                        $characterName = htmlspecialchars($characterName);
                        $url = htmlspecialchars($url);
                        echo "<tr> <td colspan='2'> <a href=index.php?cid=$cid&s=3>$characterName</a></td><td> <img src=$url></td></tr><br>";
                    }
                    mysqli_stmt_close($stmt);
                }
                echo '</tbody></table>'. $footer;
            break;
        default:
            #$header = file_get_contents('header.php');
            echo $header.'<table> <tr> <td> <b> <u> Stories </b></u> </td></tr>';
            if ($stmt = mysqli_prepare($db, "select storyid, story from stories")) {
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $sid, $story);
                while (mysqli_stmt_fetch($stmt)) {
                    $sid = htmlspecialchars($sid);
                    $story = htmlspecialchars($story);
                    echo "<tr> <td> <a href=index.php?sid=$sid&s=1>$story</a></td></tr>";
                }mysqli_stmt_close($stmt);
            }
            echo '</table>'. $footer;
            break;

    }
} else {
    #$header = file_get_contents('header.php');
    echo $header.'<table> <tr> <td> <b> <u> Stories </b></u> </td></tr>';
    if ($stmt = mysqli_prepare($db, "select storyid, story from stories")) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $sid, $story);
        while (mysqli_stmt_fetch($stmt)) {
            $sid = htmlspecialchars($sid);
            $story = htmlspecialchars($story);
            echo "<tr> <td> <a href=index.php?sid=$sid&s=1>$story</a></td></tr>";
        }mysqli_stmt_close($stmt);
    }
    echo '</table>'. $footer;
}




?>
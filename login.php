<?php

#include_once '/var/www/html/hw6/header.php';
include_once '/var/www/html/hw6/footer.php';

$header = file_get_contents('/var/www/html/hw6/header.php');
$footer = file_get_contents('/var/www/html/hw6/footer.php');

echo $header.'<center>
        <form method=post action=add.php>
            <table><tr> <td> Username: </td> <td> <input type=text name=postUser>  </td> </tr>
                <tr> <td> Password: </td> <td> <input type=password name=postPass>  </td> </tr>
                <tr> <td colspan=2> <input type=submit name=submit value=Login> </td></tr>
            </table>
        </form>'.$footer;


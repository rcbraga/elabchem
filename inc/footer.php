<?php
/********************************************************************************
*  This file is part of eLabChem (http://github.com/martinp23/elabchem)         *
*  Copyright (c) 2013 Martin Peeks (martinp23@googlemail.com)                   *
*                                                                               *
*    eLabChem is free software: you can redistribute it and/or modify           *
*    it under the terms of the GNU Affero General Public License as             *
*    published by the Free Software Foundation, either version 3 of             *
*    the License, or (at your option) any later version.                        *
*                                                                               *
*    eLabChem is distributed in the hope that it will be useful,                *
*    but WITHOUT ANY WARRANTY; without even the implied                         *
*    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR                    *
*    PURPOSE.  See the GNU Affero General Public License for more details.      *
*                                                                               *
*    You should have received a copy of the GNU Affero General Public           *
*    License along with eLabChem.  If not, see <http://www.gnu.org/licenses/>.  *
*                                                                               *
*   eLabChem is a fork of elabFTW. This file incorporates work covered by the   *
*   copyright notice below.                                                     *                                                               
*                                                                               *
********************************************************************************/

/******************************************************************************
*   Copyright 2012 Nicolas CARPi
*   This file is part of eLabFTW. 
*
*    eLabFTW is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    eLabFTW is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with eLabFTW.  If not, see <http://www.gnu.org/licenses/>.
*
********************************************************************************/
?>
<footer>
<!-- common stuff -->
<script src="js/common.min.js"></script>
<!-- konami code and unicorns -->
<script src="js/cornify.min.js"></script>

<p>
<?php
// because inc/common.php is not here when not logged in
if (!isset($_SESSION['auth'])) {
    require_once('admin/config.php');
}
echo "<p>".LAB_NAME." powered by <a href='https://github.com/martinp23/elabchem'>eLabChem</a>, a fork of <a href='http://www.elabftw.net' onClick='cornify_add();return false;'>Nicolas CARPi's eLabFTW</a></p>";
?>
<figure><a href='http://www.php.net'><img id='php' onmouseover="mouseOverPhp('on')" onmouseout="mouseOverPhp('off')" class='img' src='img/phpoff.gif' /></a>
<a href='http://www.mysql.com'><img id='mysql' onmouseover="mouseOverSql('on')" onmouseout="mouseOverSql('off')" class='img' src='img/mysqloff.gif' /></a>
<a href='http://jigsaw.w3.org/css-validator/check/referer'><img id='css' onmouseover="mouseOverCss('on')" onmouseout="mouseOverCss('off')" class='img' src='img/cssoff.gif' /></a></figure>
<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo "Page generated in ".$total_time." seconds.<br />";
?>
<p><a href="legal.php">Legal</a></p> <br />
<section class='align_right'>
</section>
</footer>
<script src="js/jquery.pageslide.min.js"></script>
<?php
if (isset($_SESSION['auth'])){
echo "<script>
key('".$_SESSION['prefs']['shortcuts']['todo']."', function(){
    $.pageslide({href:'inc/todolist.php'});
});
</script>";
}
?>
</body>
</html>


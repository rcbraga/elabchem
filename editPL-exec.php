<?php
/********************************************************************************
*                                                                               *
*   Copyright 2012 Nicolas CARPi (nicolas.carpi@gmail.com)                      *
*   http://www.elabftw.net/                                                     *
*                                                                               *
********************************************************************************/

/********************************************************************************
*  This file is part of eLabFTW.                                                *
*                                                                               *
*    eLabFTW is free software: you can redistribute it and/or modify            *
*    it under the terms of the GNU Affero General Public License as             *
*    published by the Free Software Foundation, either version 3 of             *
*    the License, or (at your option) any later version.                        *
*                                                                               *
*    eLabFTW is distributed in the hope that it will be useful,                 *
*    but WITHOUT ANY WARRANTY; without even the implied                         *
*    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR                    *
*    PURPOSE.  See the GNU Affero General Public License for more details.      *
*                                                                               *
*    You should have received a copy of the GNU Affero General Public           *
*    License along with eLabFTW.  If not, see <http://www.gnu.org/licenses/>.   *
*                                                                               *
********************************************************************************/
require_once('inc/common.php');
//Array to store validation errors
$msg_arr = array();
//Validation error flag
$errflag = false;


// CHECKS
require_once('inc/check_id.php'); // $id
require_once('inc/check_date.php'); // $date
require_once('inc/check_files.php'); // $real_filenames[] $long_filenames[]

$name = $_POST['name'];
$alias = $_POST['alias'];
$priority = $_POST['priority'];
$resistance = $_POST['resistance'];
$organism = $_POST['organism'];
$tag = $_POST['tag'];
$comment = $_POST['comment'];
$results = $_POST['results'];

/*
// Store stuff in Session to get it back if error input
$_SESSION['new_title'] = $title;
$_SESSION['new_date'] = $date;
$_SESSION['new_body'] = $body;
$_SESSION['new_outcome'] = $outcome;
 */

// If input errors, redirect back to the experiment form
if($errflag) {
    $_SESSION['errors'] = $msg_arr;
    session_write_close();
    header("location: plasmids.php?mode=edit&id=$id");
    exit();
}

// SQL for editPL
    $sql = "UPDATE plasmids 
        SET name = :name, 
        date = :date, 
        alias = :alias, 
        priority = :priority, 
        resistance = :resistance, 
        organism = :organism,
        tag = :tag,
        comment = :comment,
        results = :results
        WHERE id = id";
$req = $bdd->prepare($sql);
$result = $req->execute(array(
        'date' => $date, 
        'alias' = $alias, 
        'priority' = $priority, 
        'resistance' = $resistance, 
        'organism' = $organism,
        'tag' = $tag,
        'comment' = $comment,
        'results' = $results,
        'userid' => $_SESSION['userid'],
        'id' => $id
));

// If FILES are uploaded
if (is_uploaded_file($_FILES['files']['tmp_name'][0])){
    // Assign the experiment id to $expid
        $item_id = $id;
        // Loop for each file
        for ($i = 0; $i < $cnt; $i++) {
        // Comments
    $filecomments[] = filter_var($_POST['filescom'][$i], FILTER_SANITIZE_STRING);
    if(strlen($filecomments[$i]) == 0){
        $filecomments[$i] = 'No comment added';
    }
        // Move file
            if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $upload_directory . $long_filenames[$i])) {
    //SQL for FILE uploads
    $sql = "INSERT INTO uploads(real_name, long_name, comment, item_id, userid, type) VALUES(:real_name, :long_name, :comment, :item_id, :userid, :type)";
    $req = $bdd->prepare($sql);
    $result = $req->execute(array(
        'real_name' => $real_filenames[$i],
        'long_name' => $long_filenames[$i],
        'comment' => $filecomments[$i],
        'item_id' => $item_id,
        'userid' => $_SESSION['userid'],
        'type' => 'plasmids'
    ));
    $req->closeCursor();
            } // end for each file loop
        } // end if move uploaded
    } // end is uploaded

// Check if insertion is successful
if($result) {
    unset($_SESSION['new_title']);
    unset($_SESSION['new_date']);
    unset($_SESSION['new_body']);
    unset($_SESSION['outcome']);
    unset($_SESSION['errors']);
    header("location: plasmids.php?mode=view&id=$id");
} else {
    die('Something went wrong in the database query. Check the flux capacitor.');
}
?>
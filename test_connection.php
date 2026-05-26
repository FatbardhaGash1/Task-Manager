<?php

include 'includes/db.php';

if(isset($pdo)) {

    echo "PDO WORKING";

} else {

    echo "PDO FAILED";

}
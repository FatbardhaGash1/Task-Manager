<?php

include 'config/db.php';

if(isset($pdo)) {

    echo "PDO WORKING";

} else {

    echo "PDO FAILED";

}

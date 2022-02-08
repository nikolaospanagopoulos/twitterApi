<?php
include '../Backend/init.php';
if (isset($_POST['username'])) {
    $userName = $_POST['username'];
    $excelExport  = new ExportExcel($userName);
    $excelExport->exportExcell($userName);
} else {
    echo 'No user selected';
}

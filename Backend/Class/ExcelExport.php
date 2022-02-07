<?php




class ExportExcel
{
    private $db;
    private $userName;
    public function __construct($userName)
    {
        $this->userName = $userName;
        $this->db = Database::instance();
    }

    public function exportExcell($userName)
    {
        $filename = "Webinfopen.xls"; // File Name
        // Download file
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        $user_query = "SELECT * FROM `tweets` WHERE `user` = :user";
        try {
            $stmt = $this->db->prepare($user_query);
            $stmt->bindParam(":user", $userName, PDO::PARAM_STR);
            // Write data to file
            $stmt->execute();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $columnNames = array();
            if (!empty($rows)) {
                //We only need to loop through the first row of our result
                //in order to collate the column names.
                $firstRow = $rows[0];
                foreach ($firstRow as $colName => $val) {
                    $columnNames[] = $colName;
                }
            }

            //Setup the filename that our CSV will have when it is downloaded.
            $fileName = 'mysql-export.csv';

            //Set the Content-Type and Content-Disposition headers to force the download.

            header('Content-Type: text/html; charset=utf-8');
            header('Content-Type: application/excel');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');

            //Open up a file pointer
            $fp = fopen('php://output', 'w');

            //Start off by writing the column names to the file.
            fputcsv($fp, $columnNames);

            // Then, loop through the rows and write them to the CSV file.
            foreach ($rows as $row) {
                fputcsv($fp, $row);
            }

            //Close the file pointer.
            fclose($fp);
        } catch (Exception $e) {
            echo $e . '----------------------';
        }
    }
}

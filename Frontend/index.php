

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>My Page Title</title>
    <meta name="description" content="My Page Description">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <div>
        <h2 class="title">Download Data</h2>
        <form method="POST" action="getData.php" class="first-form">


            <label for="name">UserToSearch</label>
            <input name="name" />


            <label for="max_results">Max Results</label>

            <input name="max_results" type="number" />



            <label for="start_date">Date to start</label>
            <input name="start_date" type="text" />
            <label for="end_date">Date to end</label>
            <input name="end_date" type="text" />
            <button>Submit</button>


        </form>

        <h2 class="title">Show Excel</h2>
        <form method="POST" action="getDataFromDb.php" class="second-form">


            <label for="username">UserToSearch</label>
            <input type="text" name="username">
            <button>Submit</button>


        </form>



    </div>
</body>

</html>
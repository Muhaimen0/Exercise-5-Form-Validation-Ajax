<?php  
$filename = 'attendance_history.txt';  

$fileLines = [];  

if (file_exists($filename)) {  
    $fileLines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);  
}  
?>  

<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Attendance Form</title>  
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
    <style>  
        body {  
            font-family: Arial, sans-serif;  
            margin: 0;  
            padding: 0;  
            background-color: #4CAF50;  
        }  
        .container {  
            width: 100%;  
            max-width: 600px;  
            margin: 50px auto;  
            padding: 20px;  
            background-color: #fff;  
            border-radius: 8px;  
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);  
        }  
        h2 {  
            text-align: center;  
            color: #333;  
        }  
        form {  
            display: flex;  
            flex-direction: column;  
        }  
        label {  
            margin-bottom: 5px;  
            font-weight: bold;  
            color: #333;  
        }  
        input[type="text"], input[type="password"], input[type="submit"], select {  
            padding: 10px;  
            margin-bottom: 20px;  
            border: 1px solid #ccc;  
            border-radius: 4px;  
            font-size: 16px;  
        }  
        input[type="text"].time-entry {  
            width: 80px;
        }  
        .time-container {  
            display: flex;  
            align-items: center;  
        }  
        .time-container label {  
            margin-left: 10px;  
        }  
        input[type="submit"] {  
            background-color: #4CAF50;  
            color: white;  
            cursor: pointer;  
        }  
        input[type="submit"]:hover {  
            background-color: #45a049;  
        }  
        .file-lines {  
            margin-top: 20px;  
        }  
        table {  
            width: 100%;  
            border-collapse: collapse;  
            margin-top: 20px;  
        }  
        th, td {  
            border: 1px solid #ccc;  
            text-align: left;  
            padding: 8px;  
        }  
        th {  
            background-color: #4CAF50;  
            color: white;  
        }  
        tr:nth-child(even) {  
            background-color: #f2f2f2;  
        }  
    </style>  
</head>  
<body>  

    <div class="container">  
        <h2>Attendance Form</h2>  
        <form id="attendanceForm">  
            <label for="id">ID:</label>  
            <input type="text" id="id" name="id" required>  

            <label for="name">Name:</label>  
            <input type="text" id="name" name="name" required>  

            <label for="password">Password:</label>  
            <input type="password" id="password" name="password" required>  

            <label for="gender">Gender:</label>  
            <select id="gender" name="gender" required>  
                <option value="">Select Gender</option>  
                <option value="Boy">Boy</option>  
                <option value="Girl">Girl</option>  
            </select>  

            <label for="time">Time of Entry:</label>  
            <div class="time-container">  
                <input type="text" id="time" name="time" class="time-entry" required placeholder="HH:MM">  
                <label><input type="radio" name="timePeriod" value="AM" required> AM</label>  
                <label><input type="radio" name="timePeriod" value="PM" required> PM</label>  
            </div>  

            <input type="submit" value="Submit Attendance">  
        </form>  

        <div class="file-lines">   
            <table id="attendanceTable">  
                <thead>  
                    <tr>  
                        <th>ID</th>  
                        <th>Name</th>  
                        <th>Gender</th>  
                        <th>Time</th>  
                    </tr>  
                </thead>  
                <tbody>  
                    <?php if (!empty($fileLines)): ?>  
                        <?php foreach ($fileLines as $lineContent): ?>  
                            <?php  
                                list($id, $name, $gender, $time) = explode(',', $lineContent);  
                            ?>  
                            <tr>  
                                <td><?php echo htmlspecialchars($id); ?></td>  
                                <td><?php echo htmlspecialchars($name); ?></td>  
                                <td><?php echo htmlspecialchars($gender); ?></td>  
                                <td><?php echo htmlspecialchars($time); ?></td>  
                            </tr>  
                        <?php endforeach; ?>  
                    <?php endif; ?>  
                </tbody>  
            </table>  
        </div>  
    </div>  

    <script>  
    $(document).ready(function() {  
        
        $("#attendanceForm").on("submit", function(event) {  
            event.preventDefault();  

            $.ajax({  
                type: "POST",  
                url: "ajax.php", 
                data: $(this).serialize(),  
                dataType: "xml",  
                success: function(response) {  
                    let newRow = "<tr>" +  
                        "<td>" + $(response).find("id").text() + "</td>" +  
                        "<td>" + $(response).find("name").text() + "</td>" +  
                        "<td>" + $(response).find("gender").text() + "</td>" +  
                        "<td>" + $(response).find("time").text() + "</td>" +  
                        "</tr>";  

                    $("#attendanceTable tbody").append(newRow);  
                    $('#attendanceForm')[0].reset();  
                },  
                error: function() {  
                    alert("An error occurred while submitting your attendance.");  
                }  
            });  
        });  
    });  
    </script>  
</body>  
</html>

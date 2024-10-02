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
        background-image: url('vibrant-green-watercolor-painting-background_53876-139888.avif'); 
        background-size: cover; 
        background-repeat: no-repeat; 
        background-position: center;  
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
        #suggestions {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            background-color: white;
            position: relative;
            z-index: 1000;
            display: none; 
        }
        .suggestion-item {
            padding: 10px;
            cursor: pointer;
        }
        .suggestion-item:hover {
            background-color: #f0f0f0; 
        }
    </style>  
</head>  
<body>  

    <div class="container">  
        <h2>Attendance Form</h2>  
        <form id="attendanceForm">  

        <label for="id">ID:</label>
            <input type="text" id="id" name="id" required pattern="\d+" title="ID should only contain numbers" oninput="validateID();">

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required title="Name should not contain email or '.com'" oninput="validateName();">
            
            <div id="suggestions"></div>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="section">Section:</label>
            <select id="section" name="section" required>
                <option value="">Select Section</option>
                <option value="IT3A">IT3A</option>
                <option value="IT3C">IT3C</option>
                <option value="IT3E">IT3E</option>
                <option value="IT3G">IT3G</option>
                <option value="IT3I">IT3I</option>
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
                        <th>Section</th>  
                        <th>Time</th>  
                    </tr>  
                </thead>  
                <tbody>  
                    <?php if (!empty($fileLines)): ?>  
                        <?php foreach ($fileLines as $lineContent): ?>  
                            <?php  
                                list($id, $name, $section, $time) = explode(',', $lineContent);  
                            ?>  
                            <tr>  
                                <td><?php echo htmlspecialchars($id); ?></td>  
                                <td><?php echo htmlspecialchars($name); ?></td>  
                                <td><?php echo htmlspecialchars($section); ?></td>  
                                <td><?php echo htmlspecialchars($time); ?></td>  
                            </tr>  
                        <?php endforeach; ?>  
                    <?php endif; ?>  
                </tbody>  
            </table>  
        </div>  
    </div>  

    <script>
        function validateID() {
            const idField = document.getElementById('id');
            const idValue = idField.value;
            const numberPattern = /^\d+$/;

            if (!numberPattern.test(idValue)) {
                alert('ID should contain numbers only.');
                idField.value = '';
            }
        }

        function validateName() {
            const nameField = document.getElementById('name');
            const nameValue = nameField.value;
            const invalidPattern = /@|\.com$/i;

            if (invalidPattern.test(nameValue)) {
                alert('Name cannot contain email-like addresses or ".com".');
                nameField.value = '';
            }
        }

        $("#name").on("input", function() {
            const nameInput = $(this).val();
            if (nameInput.length > 0) {
                $.ajax({
                    type: "GET",
                    url: "suggestions.php",
                    data: { q: nameInput },
                    success: function(data) {
                        const suggestionsDiv = $("#suggestions");
                        suggestionsDiv.empty();
                        
                        if (data === "no suggestion") {
                            suggestionsDiv.hide();
                        } else {
                            const names = data.split(", ");
                            names.forEach(name => {
                                suggestionsDiv.append(`<div class="suggestion-item">${name}</div>`);
                            });
                            suggestionsDiv.show();
                        }
                    }
                });
            } else {
                $("#suggestions").hide();
            }
        });

        $(document).on("click", ".suggestion-item", function() {
            $("#name").val($(this).text());
            $("#suggestions").hide();
        });

        $("#attendanceForm").on("submit", function(event) {

        });
    </script>  
</body>  
</html>



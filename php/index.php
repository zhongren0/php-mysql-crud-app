<?php include 'db.php'; ?> 

<!DOCTYPE html> 

<html> 

<head><title>CNAS Assignment - Team Members List</title></head> 

<body> 

<h2>Team Members of Class T02, Team 03</h2> 

<a href="create.php">Add New Team Member</a> 

<table border="1" cellpadding="8" cellspacing="0"> 

<tr><th>ID</th><th>Student Name</th><th>Email</th><th>Actions</th></tr> 

<?php 

$result = $conn->query("SELECT * FROM users"); 

while ($row = $result->fetch_assoc()) { 

    echo "<tr> 

            <td>{$row['id']}</td> 

            <td>{$row['name']}</td> 

            <td>{$row['email']}</td> 

            <td> 

                <a href='update.php?id={$row['id']}'>Edit</a> | 

                <a href='delete.php?id={$row['id']}'>Delete</a> 

            </td> 

          </tr>"; 

} 

$conn->close(); 

?> 

</table> 

<br> 

<div class="node-id"> 

    <strong>Served by node:</strong> <?php echo getenv('NODE_NAME'); ?> 

</div> 

</body> 

</html> 

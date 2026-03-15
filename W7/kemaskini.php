<?php
include("includes/header.php");
$result_array = null;
$mesej = "";
?>
<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $user_id = $_POST["user_id"];
    $username = $_POST["username"];
    $role_id = $_POST["role_id"];

    $stmt = mysqli_prepare($conn,"UPDATE users SET username=?, role_id=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "sii", $username, $user_id, $role_id);
    
    if(mysqli_stmt_execute($stmt)){
        $mesej = "<p style=color:green>User successfully updated!</p>";
    }else{
        $mesej = "<p style=color:red>User not successfully updated!</p>";
    }

    mysqli_stmt_close($stmt);
}

if(isset($_GET["hantar_id"])) {
    $edit_id = $_GET["hantar_id"];
    $stmt = mysqli_prepare($conn,"SELECT id,username,role_id FROM users WHERE id=?");
    mysqli_stmt_bind_param($stmt,"i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $result_array = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}
$roles_names = mysqli_query($conn,"SELECT id,name FROM roles");
$user_result = mysqli_query($conn,"SELECT users.id,username,roles.name as role_name FROM users join roles ON role_id = roles.id;");

?>
<?php if($result_array): ?>
    <form action="" method="POST">
        <h1>Edit User</h1>
        <input type="hidden" name="user_id" value="<?php echo $result_array["id"] ?>">
        <input type="text" name="username" value="<?php echo $result_array["username"] ?>">
        <select name="role_id">
            <?php while($row = mysqli_fetch_assoc($roles_names)): ?>
            <option value="<?php echo $row['id'] ?>"<?php if($row['id'] == $result_array['id']) {
                    echo "selected";
                }
                ?>>
                <?php echo $row["name"] ?>
            </option>
            <?php endwhile; ?>
        </select>
        <br><br>
        <input type="submit" id="username_users" value="Kemaskini">
    </form>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
    <?php echo $mesej; ?>
    <h1>All Users</h1>
    <table class="table table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Role</th>
            <th>Edit</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_array($user_result)) : ?>
        <tr>
            <td>
                <?php echo $row["id"]; ?>
            </td>
            <td>
                <?php echo $row["username"]; ?>
            </td>
            <td>
                <?php echo $row["role_name"]; ?>
            </td>
            <td><a href="kemaskini.php?hantar_id=<?php echo $row["id"]; ?></a>">Edit</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php
include("includes/footer.php");
?>
<?php
    session_start();
    if (isset($_SESSION['customerID'])) {
        include '..\..\database\db_connect.php';
    $stmt = $conn->prepare("SELECT role FROM customer WHERE customerID = ?");
    $stmt->bind_param("i", $_SESSION['customerID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user['role'] !== 'admin') {
        header("Location: index.html");
        exit;
    }
} else {
    header("Location: log-in.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Stock Management | Wine Exchange</title>

<link rel="icon" href="../../images/icon.png">
<link rel="stylesheet" href="../css/styles.css">

<style>

.stock-wrapper{
    width:90%;
    margin:120px auto 40px auto;
}

.stock-wrapper h2{
    margin-bottom:20px;
}

.stock-table{

    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:10px;
    overflow:hidden;
}

.stock-table th{

    background:#6B0F1A;
    color:white;
    padding:15px;
    text-align:left;
}

.stock-table td{

    padding:15px;
    border-bottom:1px solid #eee;
}

.stock-table img{

    width:80px;
    border-radius:8px;
}

.stock-controls{

    display:flex;
    align-items:center;
    gap:10px;
}

.stock-input{

    width:60px;
    padding:6px;
    text-align:center;
}

.stock-btn{

    padding:6px 12px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-weight:500;
}

.add-btn{

    background:#6B0F1A;
    color:white;
}

.add-btn:hover{

    opacity:0.8;
}

.remove-btn{

    background:#c0392b;
    color:white;
}

.remove-btn:hover{

    opacity:0.8;
}

.update-btn{

    background:#27ae60;
    color:white;
}

.update-btn:hover{

    opacity:0.8;
}

.add-new{

    margin-bottom:20px;
    padding:10px 18px;
    background:#27ae60;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

.add-new:hover{

    opacity:0.8;
}

.darkmode .stock-table{

    background:#2b2b2b;
    color:white;
}

.darkmode .stock-table td{

    border-bottom:1px solid #444;
}

.button-row {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.return-btn {
    background: #c0392b;
}

.return-btn:hover {
    opacity: 0.8;
}

</style>

</head>

<body>


<!-- NAVBAR -->

<div class="navbar">

<img src="../../images/icon.png">

<div class="navbar-links">

<a href="index.html">Home</a>

<a href="wines.html">Wines</a>

<a href="stock.html">Stock</a>

<a href="basket.php">Basket</a>

</div>

<div class="navbar-right">

<button id="dark-mode" class="dark-mode-button">

<img src="../../images/darkmode.png">

</button>

</div>

</div>



<div class="stock-wrapper">

<h2>Stock Management</h2>

<div class="button-row">
    <a class="add-new" href="newWine.php">+ Add New Wine</a>
    <a class="add-new return-btn" href="admin.php">↩ Admin Dashboard</a>
</div>

<form method="GET" style="margin-bottom:20px;">
    <input type="text" name="search" placeholder="Search wine name..."
           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
           style="padding:8px; width:250px; border-radius:6px; border:1px solid #ccc;">
    <button type="submit" class="add-new">Search</button>
</form>

<table class="stock-table">

<tr>

<th>Image</th>
<th>Wine Name</th>
<th>Price</th>
<th>Stock</th>
<th>Actions</th>

</tr>

<?php
    include '..\..\database\db_connect.php';
        $sql = "SELECT wineId, imageUrl, wineName, price, Stock, active FROM wines WHERE active = 1";

        if (!empty($_GET['search'])) {
            $search = "%" . $conn->real_escape_string($_GET['search']) . "%";
            $sql .= " AND wineName LIKE '$search'";
    }
        $result = $conn->query($sql);

        
        if ($result->num_rows === 0) {
            echo "<tr><td colspan='5'>No data found.</td></tr>";
            exit;
        }

        while ($row = $result->fetch_assoc()) {
            if (!$row['active']) {
                continue; // Skip inactive wines
            }
            echo "<tr>";


            echo "<form action='redirect.php?page=inventory' method='POST'>";

            echo "<td><img src='../../images/" . htmlspecialchars($row['imageUrl']) . "'></td>";
            echo "<td>" . htmlspecialchars($row['wineName']) . "</td>";
            echo "<td>£" . htmlspecialchars($row['price']) . "</td>";
            echo "<td>". htmlspecialchars($row['Stock']) ."</td>";
            
            echo "<td>
                    <input type='hidden' name='wineId' value='" . $row['wineId'] . "'>
                    <button type = 'submit' name = 'action' value = 'update' class='stock-btn update-btn'>Update</button>
                    <button type = 'submit' name = 'action' value = 'remove' class='stock-btn remove-btn'>Remove</button>
                </td>";
            
            echo "</form>";

            echo "</tr>";
        }
?>


</table>

</div>


<script>

/* DARK MODE */

const darkButton = document.getElementById("dark-mode");

if(localStorage.getItem("dark_mode") === "on"){

document.documentElement.classList.add("darkmode");

}

darkButton.addEventListener("click", () => {

document.documentElement.classList.toggle("darkmode");

localStorage.setItem(

"dark_mode",

document.documentElement.classList.contains("darkmode")

? "on"

: "off"

);

});



document.querySelectorAll(".stock-controls").forEach(control => {

    const minusBtn = control.querySelector(".remove-btn");
    const plusBtn = control.querySelector(".add-btn");
    const input = control.querySelector(".stock-input");

    minusBtn.addEventListener("click", () => {

        let value = parseInt(input.value) || 0;

        if(value > 0){
            input.value = value - 1;
        }

    });

    plusBtn.addEventListener("click", () => {

        let value = parseInt(input.value) || 0;

        input.value = value + 1;

    });

});

</script>

</body>

</html>

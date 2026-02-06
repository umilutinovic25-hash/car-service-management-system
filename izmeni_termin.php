<?php
include 'db.php';
$id = $_GET['id'];
$res = $conn->query("SELECT * FROM zakazivanja WHERE id = $id");
$row = $res->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ime = $_POST['ime_prezime'];
    $tel = $_POST['telefon'];
    $auto = $_POST['marka_vozila'];
    $datum = $_POST['datum_termina'];
    $status = $_POST['status'];

    $sql = "UPDATE zakazivanja SET ime_prezime='$ime', telefon='$tel', marka_vozila='$auto', datum_termina='$datum', status='$status' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Izmeni Termin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #050505; color: white; padding: 50px; }
        .card { background: #111; border: 1px solid #333; padding: 30px; }
        .form-control { background: #000; border: 1px solid #333; color: white; }
    </style>
</head>
<body>
<div class="container text-center">
    <div class="card mx-auto" style="max-width: 500px;">
        <h3 class="text-warning mb-4">Izmena Termina #<?php echo $id; ?></h3>
        <form method="POST">
            <input type="text" name="ime_prezime" class="form-control mb-3" value="<?php echo $row['ime_prezime']; ?>">
            <input type="text" name="telefon" class="form-control mb-3" value="<?php echo $row['telefon']; ?>">
            <input type="text" name="marka_vozila" class="form-control mb-3" value="<?php echo $row['marka_vozila']; ?>">
            <input type="date" name="datum_termina" class="form-control mb-3" value="<?php echo $row['datum_termina']; ?>">
            <select name="status" class="form-control mb-4">
                <option value="Na čekanju" <?php if($row['status'] == 'Na čekanju') echo 'selected'; ?>>Na čekanju</option>
                <option value="Završeno" <?php if($row['status'] == 'Završeno') echo 'selected'; ?>>Završeno</option>
            </select>
            <button type="submit" class="btn btn-warning w-100">AŽURIRAJ PODATKE</button>
            <a href="admin.php" class="btn btn-link w-100 text-secondary mt-2">Odustani</a>
        </form>
    </div>
</div>
</body>
</html>
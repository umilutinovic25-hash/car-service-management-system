<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ime = $_POST['ime_prezime'];
    $tel = $_POST['telefon'];
    $auto = $_POST['marka_vozila'];
    $datum = $_POST['datum_termina'];
    $status = $_POST['status'];

    $sql = "INSERT INTO zakazivanja (ime_prezime, telefon, marka_vozila, datum_termina, status) 
            VALUES ('$ime', '$tel', '$auto', '$datum', '$status')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Dodaj Termin | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #050505; color: white; padding: 50px; }
        .card { background: #111; border: 1px solid #333; padding: 30px; }
        .form-control { background: #000; border: 1px solid #333; color: white; }
    </style>
</head>
<body>
<div class="container">
    <div class="card mx-auto" style="max-width: 500px;">
        <h3 class="text-danger mb-4">Novi Ručni Unos</h3>
        <form method="POST">
            <input type="text" name="ime_prezime" class="form-control mb-3" placeholder="Ime klijenta" required>
            <input type="text" name="telefon" class="form-control mb-3" placeholder="Telefon" required>
            <input type="text" name="marka_vozila" class="form-control mb-3" placeholder="Vozilo" required>
            <input type="date" name="datum_termina" class="form-control mb-3" required>
            <select name="status" class="form-control mb-4">
                <option value="Na čekanju">Na čekanju</option>
                <option value="Završeno">Završeno</option>
            </select>
            <button type="submit" class="btn btn-danger w-100">SAČUVAJ TERMIN</button>
            <a href="admin.php" class="btn btn-link w-100 text-secondary mt-2">Odustani</a>
        </form>
    </div>
</div>
</body>
</html>
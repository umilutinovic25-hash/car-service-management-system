<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ime = $_POST['ime_prezime'];
    $tel = $_POST['telefon'];
    $auto = $_POST['marka_vozila'];
    $datum = $_POST['datum_termina'];
    $opis = $_POST['opis_kvara'];

    $sql = "INSERT INTO zakazivanja (ime_prezime, telefon, marka_vozila, datum_termina, opis_kvara) 
            VALUES ('$ime', '$tel', '$auto', '$datum', '$opis')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Uspešno zakazano!'); window.location.href='admin.php';</script>";
    } else {
        echo "Greška: " . $conn->error;
    }
}
?>
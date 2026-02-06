<?php
session_start();
// Provera da li je administrator ulogovan
if(!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit();
}

// Povezivanje sa bazom
include 'db.php';

// --- LOGIKA ZA BRISANJE ---
if (isset($_GET['obrisi_id'])) {
    $id = intval($_GET['obrisi_id']);
    $conn->query("DELETE FROM zakazivanja WHERE id = $id");
    header("Location: admin.php?status=obrisano");
    exit();
}

// --- LOGIKA ZA ZAVRŠETAK POSLA ---
if (isset($_GET['zavrsi_id'])) {
    $id = intval($_GET['zavrsi_id']);
    $conn->query("UPDATE zakazivanja SET status = 'Završeno' WHERE id = $id");
    header("Location: admin.php?status=zavrseno");
    exit();
}

// --- LOGIKA ZA ODJAVU ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Izvlačenje podataka
$rezultat = $conn->query("SELECT * FROM zakazivanja ORDER BY status DESC, datum_termina ASC");
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>AUTOZEN | Master Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --accent: #ff0000; --bg: #050505; --card: #111111; }
        body { background-color: var(--bg); color: #fff; font-family: 'Inter', sans-serif; padding: 40px 0; }
        .admin-wrapper { background: var(--card); border: 1px solid #222; border-radius: 15px; padding: 30px; }
        .table { color: #eee; border-color: #333; vertical-align: middle; }
        .status-badge { padding: 6px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
        .bg-pending { background: #ffc107; color: #000; }
        .bg-completed { background: #198754; color: #fff; }
        .btn-action { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; margin-right: 5px; transition: 0.3s; text-decoration: none; border: none; }
        .btn-edit { background: #0d6efd; color: white; }
        .btn-check { background: #198754; color: white; }
        .btn-trash { background: #dc3545; color: white; }
        .text-accent { color: var(--accent); }
    </style>
</head>
<body>

<div class="container">
    <div class="admin-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="fw-bold mb-0"><span class="text-accent">ADMIN</span> DASHBOARD</h1>
                <p class="text-secondary mb-0">Upravljanje klijentima i servisnim nalozima</p>
            </div>
            <div class="d-flex gap-2">
                <a href="dodaj_termin.php" class="btn btn-danger fw-bold"><i class="fas fa-plus me-2"></i>NOVI UNOS</a>
                <a href="admin.php?logout=1" class="btn btn-outline-secondary">ODJAVI SE</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-accent">
                        <th>STATUS</th>
                        <th>KLIJENT</th>
                        <th>VOZILO</th>
                        <th>DATUM</th>
                        <th class="text-center">AKCIJE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $rezultat->fetch_assoc()): 
                        $klasa = ($row['status'] == 'Završeno') ? 'bg-completed' : 'bg-pending';
                    ?>
                    <tr>
                        <td><span class="status-badge <?php echo $klasa; ?>"><?php echo $row['status']; ?></span></td>
                        <td>
                            <div class="fw-bold"><?php echo $row['ime_prezime']; ?></div>
                            <div class="small text-secondary"><?php echo $row['telefon']; ?></div>
                        </td>
                        <td><i class="fas fa-car me-2"></i><?php echo $row['marka_vozila']; ?></td>
                        <td><?php echo date("d.m.Y", strtotime($row['datum_termina'])); ?></td>
                        <td class="text-center">
                            <?php if($row['status'] != 'Završeno'): ?>
                                <a href="admin.php?zavrsi_id=<?php echo $row['id']; ?>" class="btn-action btn-check"><i class="fas fa-check"></i></a>
                            <?php endif; ?>
                            <a href="izmeni_termin.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit"><i class="fas fa-pen"></i></a>
                            <a href="admin.php?obrisi_id=<?php echo $row['id']; ?>" class="btn-action btn-trash" onclick="return confirm('Obrisati trajno?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
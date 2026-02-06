<?php
// 1. Povezivanje sa bazom
include 'db.php';

// --- LOGIKA ZA BRISANJE ---
if (isset($_GET['obrisi_id'])) {
    $id = intval($_GET['obrisi_id']);
    $conn->query("DELETE FROM zakazivanja WHERE id = $id");
    header("Location: admin.php?poruka=obrisano");
    exit();
}

// --- LOGIKA ZA BRZU PROMENU STATUSA (ZAVRŠI) ---
if (isset($_GET['zavrsi_id'])) {
    $id = intval($_GET['zavrsi_id']);
    $conn->query("UPDATE zakazivanja SET status = 'Završeno' WHERE id = $id");
    header("Location: admin.php?poruka=zavrseno");
    exit();
}

// 2. Izvlačenje svih termina iz baze
$rezultat = $conn->query("SELECT * FROM zakazivanja ORDER BY status DESC, datum_termina ASC");
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUTOZEN | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { --accent: #ff0000; --bg: #050505; --card: #111111; }
        body { background-color: var(--bg); color: #fff; font-family: 'Inter', sans-serif; padding: 40px 0; }
        
        .admin-wrapper { 
            background: var(--card); 
            border: 1px solid #222; 
            border-radius: 15px; 
            padding: 30px; 
            box-shadow: 0 10px 50px rgba(0,0,0,0.7);
        }

        .table { color: #eee; border-color: #333; vertical-align: middle; }
        .table thead { background: #1a1a1a; color: var(--accent); }
        
        .status-badge { 
            padding: 6px 12px; 
            border-radius: 50px; 
            font-size: 0.7rem; 
            font-weight: 800; 
            text-transform: uppercase;
        }
        .bg-pending { background: #ffc107; color: #000; }
        .bg-completed { background: #198754; color: #fff; }

        .btn-action { 
            width: 38px; height: 38px; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            border-radius: 8px; 
            margin-right: 5px; 
            transition: 0.3s; 
            text-decoration: none;
            border: none;
        }
        .btn-edit { background: #0d6efd; color: white; }
        .btn-check { background: #198754; color: white; }
        .btn-trash { background: #dc3545; color: white; }
        .btn-action:hover { transform: translateY(-3px); filter: brightness(1.2); color: white; }

        .text-accent { color: var(--accent); }
        .btn-add { background: var(--accent); color: #fff; border: none; font-weight: bold; border-radius: 5px; transition: 0.3s; }
        .btn-add:hover { background: #fff; color: #000; }
    </style>
</head>
<body>

<div class="container">
    <div class="admin-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="fw-bold mb-0"><span class="text-accent">AUTO</span>ZEN CONTROL</h1>
                <p class="text-secondary mb-0">Dobrodošli nazad, Uroše. Pregled radnih naloga.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="dodaj_termin.php" class="btn btn-add px-4 py-2">
                    <i class="fas fa-plus me-2"></i>DODAJ TERMIN
                </a>
                <a href="index.html" class="btn btn-outline-secondary px-4 py-2">SAJT</a>
            </div>
        </div>

        <?php if(isset($_GET['poruka'])): ?>
            <div class="alert alert-dark border-secondary text-white alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle text-accent me-2"></i>
                Akcija uspešno izvršena!
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover mt-3">
                <thead>
                    <tr>
                        <th>STATUS</th>
                        <th>KLIJENT</th>
                        <th>VOZILO</th>
                        <th>DATUM</th>
                        <th class="text-center">UPRAVLJANJE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($rezultat->num_rows > 0): ?>
                        <?php while($row = $rezultat->fetch_assoc()): 
                            $statusKlasa = ($row['status'] == 'Završeno') ? 'bg-completed' : 'bg-pending';
                        ?>
                        <tr>
                            <td>
                                <span class="status-badge <?php echo $statusKlasa; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($row['ime_prezime']); ?></div>
                                <div class="small text-secondary"><?php echo htmlspecialchars($row['telefon']); ?></div>
                            </td>
                            <td>
                                <i class="fas fa-car-side me-2 text-secondary"></i>
                                <?php echo htmlspecialchars($row['marka_vozila']); ?>
                            </td>
                            <td class="fw-bold">
                                <?php echo date("d.m.Y", strtotime($row['datum_termina'])); ?>
                            </td>
                            <td class="text-center">
                                <?php if($row['status'] != 'Završeno'): ?>
                                    <a href="admin.php?zavrsi_id=<?php echo $row['id']; ?>" class="btn-action btn-check" title="Završi posao">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>

                                <a href="izmeni_termin.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit" title="Izmeni">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <a href="admin.php?obrisi_id=<?php echo $row['id']; ?>" 
                                   class="btn-action btn-trash" 
                                   onclick="return confirm('PAŽNJA: Trajno brisanje termina?')" 
                                   title="Obriši">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">Nema podataka u bazi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
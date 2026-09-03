<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="page-title"><i class="fa-solid fa-calendar-plus"></i> Réserver une salle</h2>
        <p class="page-subtitle">Choisissez une salle, puis cliquez un créneau libre sur le calendrier</p>
    </div>
    <a href="index.php?controller=reservation&action=mine" class="btn btn-outline-success">
        <i class="fa-solid fa-clock-rotate-left"></i> Mes réservations
    </a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (empty($salles)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fa-solid fa-door-closed"></i>
            <p>Aucune salle disponible pour le moment.</p>
        </div>
    </div>
<?php else: ?>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body p-4">
                <form id="formReservation" action="index.php?controller=reservation&action=processCreate" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Salle</label>
                        <select name="salle_id" id="salleSelect" class="form-select" required>
                            <?php foreach ($salles as $s): ?>
                                <option value="<?= $s['id'] ?>">
                                    <?= htmlspecialchars($s['batiment_nom']) ?> — Étage <?= $s['etage_numero'] ?>
                                    — <?= htmlspecialchars($s['nom']) ?> (capacité <?= $s['capacite'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date et heure de début</label>
                        <input type="datetime-local" name="date_debut" id="dateDebutInput" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date et heure de fin</label>
                        <input type="datetime-local" name="date_fin" id="dateFinInput" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Motif</label>
                        <input type="text" name="motif" class="form-control" placeholder="Ex: Réunion d'équipe">
                    </div>

                    <button type="submit" class="btn btn-success w-100"><i class="fa-solid fa-check"></i> Réserver</button>
                    <p class="mt-2 mb-0" style="color: var(--muted); font-size: 0.85rem;">
                        <i class="fa-solid fa-circle-info"></i> Astuce : cliquez-glissez un créneau libre sur le calendrier pour remplir les dates automatiquement.
                    </p>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card p-3">
            <div id="calendar"></div>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales-all.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const salleSelect = document.getElementById('salleSelect');
    const dateDebutInput = document.getElementById('dateDebutInput');
    const dateFinInput = document.getElementById('dateFinInput');
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl || !salleSelect) return;

    function toLocalInputValue(date) {
        const pad = n => String(n).padStart(2, '0');
        return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate())
            + 'T' + pad(date.getHours()) + ':' + pad(date.getMinutes());
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'fr',
        initialView: 'timeGridWeek',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'timeGridWeek,timeGridDay' },
        slotMinTime: '07:00:00',
        slotMaxTime: '21:00:00',
        allDaySlot: false,
        height: 560,
        selectable: true,
        selectMirror: true,
        selectAllow: function(selectInfo) {
            return selectInfo.start >= new Date();
        },
        select: function(info) {
            dateDebutInput.value = toLocalInputValue(info.start);
            dateFinInput.value = toLocalInputValue(info.end);
        },
        events: []
    });
    calendar.render();

    function loadEvents() {
        const salleId = salleSelect.value;
        fetch('index.php?controller=reservation&action=calendarEvents&salle_id=' + encodeURIComponent(salleId))
            .then(res => res.json())
            .then(events => {
                calendar.removeAllEvents();
                calendar.addEventSource(events);
            });
    }

    salleSelect.addEventListener('change', loadEvents);
    loadEvents();
});
</script>

<?php require __DIR__ . '/../layout_footer.php'; ?>

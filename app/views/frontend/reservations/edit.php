<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="page-title"><i class="fa-solid fa-calendar-pen"></i> Modifier ma réservation</h2>
        <p class="page-subtitle">Changez la salle ou l'horaire de cette réservation</p>
    </div>
    <a href="index.php?controller=reservation&action=mine" class="btn btn-outline-success">
        <i class="fa-solid fa-clock-rotate-left"></i> Mes réservations
    </a>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body p-4">
                <form id="formReservation" action="index.php?controller=reservation&action=processEdit" method="POST">
                    <input type="hidden" name="id" value="<?= $reservation['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Salle</label>
                        <select name="salle_id" id="salleSelect" class="form-select" required>
                            <?php foreach ($salles as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= (int)$reservation['salle_id'] === (int)$s['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['batiment_nom']) ?> — Étage <?= $s['etage_numero'] ?>
                                    — <?= htmlspecialchars($s['nom']) ?> (capacité <?= $s['capacite'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date et heure de début</label>
                        <input type="datetime-local" name="date_debut" id="dateDebutInput" class="form-control"
                               value="<?= htmlspecialchars(str_replace(' ', 'T', substr($reservation['date_debut'], 0, 16))) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date et heure de fin</label>
                        <input type="datetime-local" name="date_fin" id="dateFinInput" class="form-control"
                               value="<?= htmlspecialchars(str_replace(' ', 'T', substr($reservation['date_fin'], 0, 16))) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Motif</label>
                        <input type="text" name="motif" class="form-control" value="<?= htmlspecialchars($reservation['motif']) ?>">
                    </div>

                    <button type="submit" class="btn btn-success w-100"><i class="fa-solid fa-check"></i> Enregistrer les modifications</button>
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

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales-all.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const salleSelect = document.getElementById('salleSelect');
    const dateDebutInput = document.getElementById('dateDebutInput');
    const dateFinInput = document.getElementById('dateFinInput');
    const calendarEl = document.getElementById('calendar');
    const reservationId = <?= (int)$reservation['id'] ?>;
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
        fetch('index.php?controller=reservation&action=calendarEvents&salle_id=' + encodeURIComponent(salleId) + '&exclude_id=' + reservationId)
            .then(res => res.json())
            .then(events => {
                calendar.removeAllEvents();
                calendar.addEventSource(events);
            });
    }

    salleSelect.addEventListener('change', loadEvents);
    loadEvents();

    const formReservation = document.getElementById('formReservation');
    if (formReservation) {
        formReservation.addEventListener('submit', function(e) {
            if (!dateDebutInput.value || !dateFinInput.value) {
                e.preventDefault();
                alert('Veuillez sélectionner une date de début et de fin.');
                return;
            }
            const debut = new Date(dateDebutInput.value);
            const fin = new Date(dateFinInput.value);
            if (fin <= debut) {
                e.preventDefault();
                alert('La date de fin doit être après la date de début.');
                return;
            }
            if (debut < new Date()) {
                e.preventDefault();
                alert('Impossible de réserver dans le passé.');
            }
        });
    }
});
</script>

<?php require __DIR__ . '/../layout_footer.php'; ?>

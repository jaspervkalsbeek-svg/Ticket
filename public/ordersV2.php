<?php
require_once '../includes/db.php';
 
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
$stmt = $conn->prepare('SELECT * FROM events WHERE id = ? LIMIT 1');
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);
 
if (!$event) {
    header('Location: festivals.php');
    exit;
}

$ttStmt = $conn->prepare('SELECT * FROM ticket_type_tb WHERE event_id = ? AND deleted_at IS NULL ORDER BY price ASC');
$ttStmt->execute([$event_id]);
$ticketTypes = $ttStmt->fetchAll(PDO::FETCH_ASSOC);
 
$coupons = $conn->query('SELECT * FROM coupon_tb')->fetchAll(PDO::FETCH_ASSOC);
?>j

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets bestellen – <?= htmlspecialchars($event['name']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="orderstyle.css">
</head>
<body>
<div class="page">
    <a class="back" href="festivals.php">← Terug naar festivals</a>
 
    <header>
        <img src="../img/Spik en Span.png" alt="Spik en Span">
        <h1>Tickets bestellen</h1>
        <div class="event-badge">
            🎪 <?= htmlspecialchars($event['name']) ?> &nbsp;·&nbsp;
            <?= date('d M', strtotime($event['start_date'])) ?>
            <?php if ($event['end_date'] && $event['end_date'] !== $event['start_date']): ?>
                – <?= date('d M Y', strtotime($event['end_date'])) ?>
            <?php else: ?>
                <?= date('Y', strtotime($event['start_date'])) ?>
            <?php endif; ?>
            &nbsp;·&nbsp; 📍 <?= htmlspecialchars($event['location']) ?>
        </div>
    </header>
 
    <?php if (empty($ticketTypes)): ?>
        <div class="section">
            <p class="no-types">Er zijn geen ticket types beschikbaar voor dit evenement.</p>
        </div>
    <?php else: ?>
 
    <form action="ConfirmOrder.php" method="POST" id="orderForm">
        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
 
        <div class="section">
            <div class="section-title">Jouw gegevens</div>
            <div class="field">
                <label>Aanhef</label>
                <select name="Aanhef" required>
                    <option value="">Selecteer</option>
                    <option value="Man">Man</option>
                    <option value="Vrouw">Vrouw</option>
                    <option value="None">Wil ik liever niet zeggen</option>
                </select>
            </div>
            <div class="row">
                <div class="field">
                    <label>Voornaam</label>
                    <input name="Fname" placeholder="Jan" required>
                </div>
                <div class="field">
                    <label>Achternaam</label>
                    <input name="Sname" placeholder="Jansen" required>
                </div>
            </div>
            <div class="field">
                <label>E-mailadres</label>
                <input type="email" name="Email" placeholder="jan@email.com" required>
            </div>
            <div class="field">
                <label>Datum</label>
                <div class="date-options">
    <div class="date-btn" onclick="toggleDate(this, '21')">
        21 augustus
        <input type="checkbox" name="Date[]" value="21" style="display:none;">
    </div>
    <div class="date-btn" onclick="toggleDate(this, '22')">
        22 augustus
        <input type="checkbox" name="Date[]" value="22" style="display:none;">
    </div>
</div>
                <div class="field">
            <label>Kortingscode (optioneel)</label>
            <div class="section">
    <div class="section-title">Kortingscode</div>
    <div class="field">
        <div class="coupon-row">
            <input type="text" id="coupon-input" placeholder="Voer code in">
            <button type="button" class="coupon-btn" onclick="applyCoupon()">Toepassen</button>
        </div>
        <input type="hidden" name="coupon" id="coupon-value" value="">
        <div class="coupon-msg" id="coupon-msg"></div>
    </div>
</div>
        </div>
            </div>
        </div>
 
        <div class="section">
            <div class="section-title">Aantal kaartjes</div>
            <div class="count-row">
                <button type="button" class="count-btn" id="decreaseBtn">−</button>
                <div class="count-display" id="countDisplay">1</div>
                <button type="button" class="count-btn" id="increaseBtn">+</button>
                <span style="color: rgba(255,255,255,0.4); font-size:0.85rem;">max. <?= (int)min(array_column($ticketTypes, 'max_per_order')) ?> per bestelling</span>
            </div>
            <input type="hidden" name="aantal" id="aantalInput" value="1">
        </div>
 
        <div class="section">
            <div class="section-title">Kaartjes invullen</div>
            <div id="ticket-forms"></div>
        </div>

        <div class="section">
            <div class="section-title">Overzicht</div>
            <div id="summary-rows"></div>
            <div class="summary-total">
                <span>Totaal</span>
                <span id="total-price">€0,00</span>
            </div>
        </div>
 
        <button type="submit" class="submit-btn">Bestellen →</button>
        <div class="terms">
            Door te bestellen ga ik akkoord met de
            <a href="Voorwaarde.html">algemene voorwaarden</a> en de
            <a href="privacyverklaring.html">privacyverklaring</a>
        </div>
    </form>
 
    <?php endif; ?>
</div>
 
<script>
const TICKET_TYPES = <?= json_encode($ticketTypes) ?>;
const COUPONS      = <?= json_encode($coupons) ?>;
const MAX_TICKETS  = <?= (int)min(array_column($ticketTypes, 'max_per_order')) ?>;

function toggleDate(btn, value) {
    btn.classList.toggle('active');
    const checkbox = btn.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;
}
 
let count = 1;
 
document.getElementById('increaseBtn').addEventListener('click', () => {
    if (count < MAX_TICKETS) { count++; updateCount(); }
});
document.getElementById('decreaseBtn').addEventListener('click', () => {
    if (count > 1) { count--; updateCount(); }
});
 
function updateCount() {
    document.getElementById('countDisplay').textContent = count;
    document.getElementById('aantalInput').value = count;
    renderTicketForms();
}
 
function fmt(p) {
    return '€' + parseFloat(p).toFixed(2).replace('.', ',');
}
 
function renderTicketForms() {
    const container = document.getElementById('ticket-forms');
    while (container.children.length < count) {
        const i = container.children.length + 1;
        container.insertAdjacentHTML('beforeend', ticketCardHTML(i));
    }
    while (container.children.length > count) {
        container.removeChild(container.lastChild);
    }
    updateSummary();
}
 
function ticketCardHTML(i) {
    const firstType = TICKET_TYPES[0];
    const typeBtns = TICKET_TYPES.map((t, idx) => `
        <div class="type-btn ${idx === 0 ? 'active' : ''}"
             data-type-id="${t.id}"
             data-price="${t.price}"
             onclick="selectType(this, ${i})">
            <div class="type-name">${t.name}</div>
            <div class="type-price">${fmt(t.price)}</div>
        </div>
    `).join('');
 
    return `
    <div class="ticket-card" id="ticket-${i}">
        <div class="ticket-card-header"><span>${i}</span> Ticket ${i}</div>
        <input type="hidden" name="tickets[${i}][type_id]" id="type-input-${i}" value="${firstType.id}">
        <input type="hidden" name="tickets[${i}][price]"   id="final-price-${i}" value="${firstType.price}">
        <div class="field">
            <label>Naam op ticket</label>
            <input type="text" name="tickets[${i}][name]" placeholder="Volledige naam" required oninput="updateSummary()">
        </div>
        <label>Type ticket</label>
        <div class="type-options">${typeBtns}</div>       
    </div>`;
}
 
function selectType(btn, i) {
    btn.closest('.type-options').querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('type-input-' + i).value = btn.dataset.typeId;
    document.getElementById('final-price-' + i).value = btn.dataset.price;
    updateSummary();
}
 
function applyCoupon() {
    const code   = document.getElementById('coupon-input').value.trim();
    const msg    = document.getElementById('coupon-msg');
    const coupon = COUPONS.find(c => c.couponcode === code);

    if (!coupon) {
        msg.textContent = 'Ongeldige kortingscode.';
        msg.className = 'coupon-msg err';
        document.getElementById('coupon-value').value = '';
    } else {
        msg.textContent = 'Kortingscode toegepast!';
        msg.className = 'coupon-msg ok';
        document.getElementById('coupon-value').value = code;
    }
    updateSummary();
}
 
function updateSummary() {
    let total = 0;
    let html  = '';
    const couponCode = document.getElementById('coupon-value')?.value || '';
    const coupon = COUPONS.find(c => c.couponcode === couponCode);

    for (let i = 1; i <= count; i++) {
        const typeInput = document.getElementById('type-input-' + i);
        if (!typeInput) continue;

        const typeId     = parseInt(typeInput.value);
        const ticketType = TICKET_TYPES.find(t => t.id === typeId);
        if (!ticketType) continue;

        let price = parseFloat(ticketType.price);

        if (coupon) {
            if (coupon.korting_eur) price -= parseFloat(coupon.korting_eur);
            else if (coupon['korting_%']) price -= price * (parseFloat(coupon['korting_%']) / 100);
            price = Math.max(0, price);
        }

        document.getElementById('final-price-' + i).value = price.toFixed(2);
        total += price;

        const nameInput = document.querySelector(`input[name="tickets[${i}][name]"]`);
        const name = nameInput?.value || `Ticket ${i}`;
        html += `<div class="summary-row"><span>${name} (${ticketType.name})</span><span>${fmt(price)}</span></div>`;
    }

    document.getElementById('summary-rows').innerHTML = html;
    document.getElementById('total-price').textContent = fmt(total);
}
renderTicketForms();
</script>
</body>
</html>
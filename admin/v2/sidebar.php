<aside class="sidebar">
    <div class="sidebar-logo">
        Admin Panel
        <span>Spik &amp; Span</span>
    </div>

    <div class="nav-label">Beheer</div>
    <a href="index.php" class="nav-item <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>"><span class="icon">🏠</span> Dashboard</a>
    <a href="events.php" class="nav-item <?= ($currentPage ?? '') === 'events' ? 'active' : '' ?>"><span class="icon">🎪</span> Evenementen</a>
    <a href="ticket_types.php" class="nav-item <?= ($currentPage ?? '') === 'ticket_types' ? 'active' : '' ?>"><span class="icon">🎟️</span> Ticket types</a>
    <a href="coupons.php" class="nav-item <?= ($currentPage ?? '') === 'coupons' ? 'active' : '' ?>"><span class="icon">🏷️</span> Kortingscodes</a>
    <a href="orders.php" class="nav-item <?= ($currentPage ?? '') === 'orders' ? 'active' : '' ?>"><span class="icon">📦</span> Bestellingen</a>
    <a href="success.php" class="nav-item <?= ($currentPage ?? '') === 'success' ? 'active' : '' ?>"><span class="icon">🏆</span> Dagranglijst</a>
  
    <div class="sidebar-footer">
        <a href="../../public/festivals.php">← Terug naar site</a>
    </div>
</aside>

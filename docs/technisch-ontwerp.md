# Technisch Ontwerp

**Project:** Spik en Span Festival - Ticketsysteem Kasteel Limbricht
**Versie:** v1.2
**Datum:** juli 2026

---

## 1. Architectuur

```
┌─────────────┐     ┌──────────────────┐     ┌──────────────┐
│   Browser   │────▶│  Apache (PHP 8.2)│────▶│   MariaDB    │
│  (HTML/CSS/ │◀────│  XAMPP localhost  │◀────│  10.4.32     │
│   JS)       │     └──────────────────┘     │  tickets_db  │
└─────────────┘                              └──────────────┘
       │                                             ▲
       │         ┌──────────────────┐                │
       └────────▶│  PHPMailer (SMTP)│                │
                 │  Gmail TLS       │                │
                 └──────────────────┘                │
                                                     │
┌─────────────┐     ┌──────────────────┐             │
│  Flutter    │────▶│  ScanTicket.php  │─────────────┘
│  (QR Scan)  │◀────│  (JSON API)      │
└─────────────┘     └──────────────────┘

┌─────────────┐
│  FPDF       │  PDF-generatie server-side
└─────────────┘
```

## 2. Tech stack

| Laag | Technologie | Versie |
|---|---|---|
| Backend | PHP | 8.2 (proceduraal) |
| Database | MariaDB via PDO | 10.4.32 |
| PDF generatie | FPDF (setasign/fpdf) | ^1.8 |
| E-mail | PHPMailer | ^7.0 |
| QR-codes | Externe API (api.qrserver.com) | - |
| Mobile scanner | Flutter + mobile_scanner | 3.11+ / ^5.2.0 |
| Frontend | Vanilla HTML/CSS/JS | - |
| i18n | PHP array-gebaseerd | NL + LI |
| Server | XAMPP | Apache + MariaDB + PHP |

## 3. Database schema

### Database: `tickets_db` (MariaDB, utf8mb4)

```sql
-- Evenementen
CREATE TABLE events (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    start_date DATETIME,
    end_date DATETIME,
    location VARCHAR(255),
    description_li VARCHAR(255),
    name_li VARCHAR(255)
);

-- Ticket types
CREATE TABLE ticket_type_tb (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    price INT(11),
    max_per_order INT(11),
    created_at DATETIME,
    deleted_at DATETIME,
    max_available INT(11),
    event_id INT(11) FOREIGN KEY -> events(id)
);

-- Bestellingen
CREATE TABLE orders (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    Fname VARCHAR(255),
    Lname VARCHAR(255),
    email VARCHAR(255),
    Aanhef VARCHAR(50),
    geboortedatum DATE,
    event_id INT(11) FOREIGN KEY -> events(id),
    total_price DECIMAL(10,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    herkomst VARCHAR(100)
);

-- Tickets
CREATE TABLE tickets_tb (
    id INT(255) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    ticket_id VARCHAR(255),
    Fname VARCHAR(255),
    Lname VARCHAR(255),
    date DATE,
    dateofattendance INT(11),
    scanned TINYINT(1),
    order_id INT(11) FOREIGN KEY -> orders(id),
    ticket_type_id INT(11)
);

-- Kortingscodes
CREATE TABLE coupon_tb (
    id INT(255) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    korting_euro DECIMAL(3,0),
    korting_% INT(255),
    event_id INT(11),
    couponcode VARCHAR(255)
);
```

## 4. Bestandsstructuur

```
Ticket/
├── admin/
│   ├── v2/                         # Huidig adminpaneel
│   │   ├── index.php               # Dashboard met statistieken
│   │   ├── auth.php                # Sessie-authenticatie
│   │   ├── sidebar.php             # Navigatie sidebar
│   │   ├── events.php              # Evenement toevoegen
│   │   ├── ticket_types.php        # Ticket type toevoegen
│   │   ├── coupons.php             # Kortingscode toevoegen
│   │   ├── add_festival.php        # Festival + ticket types aanmaken
│   │   ├── orders.php              # Bestellingen/tickets beheren
│   │   ├── edit.php                # Ticket bewerken
│   │   ├── success.php             # Dagranglijst (tier list)
│   │   └── main.css                # Admin styles
│   └── [admin/, create.php, ...]   # Legacy V1 admin (dode code)
├── database/
│   ├── tickets_db.sql              # Volledige DB dump
│   └── events (1).sql              # Oude dump (dubbel)
├── includes/
│   ├── db.php                      # PDO database connectie
│   ├── check_age.php               # Leeftijdscontrole (onbenut)
│   ├── smtp_config.php             # SMTP instellingen (gitignore)
│   ├── footer.html                 # Gedeelde footer
│   └── translate.js                # Client-side vertaling
├── inlog_page/
│   ├── login.php                   # Inlogformulier
│   ├── login_register.php          # Inlog handler
│   ├── user_page.php               # Gebruikersdashboard
│   ├── admin_page.php              # Admin welkomst pagina
│   ├── logout.php                  # Uitloggen
│   └── login.css                   # Login styles
├── lang/
│   ├── nl.php                      # Nederlandse vertalingen
│   └── li.php                      # Limburgse vertalingen
├── public/
│   ├── index.php                   # Homepage
│   ├── festivals.php               # Festival overzicht
│   ├── ordersV2.php                # Bestelformulier
│   ├── confirmorder.php            # Bestelling verwerken
│   ├── confirmation.php            # Bestelbevestiging
│   ├── Voorwaarde.html             # Algemene voorwaarden
│   ├── privacyverklaring.html      # Privacyverklaring
│   └── *.css                       # Pagina-specifieke styles
├── ticket/
│   ├── lib/main.dart               # Flutter QR-scanner app
│   ├── ScanTicket.php              # QR-scan API endpoint
│   └── pubspec.yaml                # Flutter dependencies
├── img/                            # Afbeeldingen en logo's
├── composer.json                   # PHP dependencies
└── README.md                       # Project readme
```

## 5. Authenticatie en autorisatie

### 5.1 Bezoeker authenticatie
- Login op basis van e-mail + ticket ID combinatie
- Sessie wordt gestart met `$_SESSION['email']`
- Geen rolonderscheiding tussen bezoeker en beheerder

### 5.2 Admin authenticatie
- `auth.php` controleert of `$_SESSION['email']` niet leeg is
- Geen apart admin-rol systeem
- **Beveiligingsrisico:** geen onderscheid tussen gebruikers- en adminsessie

### 5.3 API authenticatie (ScanTicket.php)
- Geen authenticatie
- `Access-Control-Allow-Origin: *`
- **Beveiligingsrisico:** iedereen kan tickets scannen

## 6. Kerndomeinen

### 6.1 Bestelproces
```
ordersV2.php → [client-side validatie]
    ↓
confirmorder.php → [server-side prijsvalidatie]
    ↓
[INSERT order] → [INSERT tickets met unieke ticket_id]
    ↓
[Genereer PDF met QR-codes] → [Verstuur e-mail via PHPMailer]
    ↓
redirect → confirmation.php
```

### 6.2 QR-scanproces
```
Flutter app → [scan QR-code]
    ↓
POST /ticket/ScanTicket.php → [zoek ticket op ticket_id]
    ↓
[controleer scanned status]
    ↓
[UPDATE scanned = 1] → [JSON response]
```

### 6.3 Admin dashboard queries
- Tellingen: evenementen, ticket types, kortingscodes, bestellingen
- Topdagen: `GROUP BY DATE(created_at) ORDER BY SUM(total_price) DESC`
- Provincieverdeling: `GROUP BY herkomst ORDER BY COUNT(*) DESC`

## 7. Externe dependencies

| Dependency | Doel | Type |
|---|---|---|
| PHPMailer | SMTP e-mailverzending | Composer |
| FPDF | PDF-generatie | Composer |
| api.qrserver.com | QR-code generatie | Externe API |
| Google Fonts | Bebas Neue + DM Sans | CDN |
| mobile_scanner | Flutter QR-scanner | pubspec.yaml |

## 8. Bekende beveiligingsproblemen

| # | Ernst | Probleem |
|---|---|---|
| 1 | Kritisch | Geen authenticatie/autorisatie op ScanTicket.php |
| 2 | Kritisch | IDOR op confirmation.php (order_id in URL) |
| 3 | Kritisch | Kortingscodes volledig client-side gevalideerd |
| 4 | Hoog | Geen CSRF-bescherming op formulieren |
| 5 | Hoog | Geen sessie-regeneratie na inloggen |
| 6 | Hoog | Admin paneel geen rolgebaseerde toegang |
| 7 | Hoog | Externe QR-code API lekt ticket IDs aan derden |
| 8 | Medium | Foutafhandeling toont database-details aan gebruikers |
| 9 | Medium | Uitloggen wist geen session cookie |
| 10 | Medium | Geen HTTPS of security headers |

## 9. Bekende codeproblemen

| # | Probleem | Locatie |
|---|---|---|
| 1 | Dode code: `admin/` V1 verwijst naar niet-bestaande tabellen | `admin/admin.php`, `admin/create.php` |
| 2 | Dode code: `Program.cs`, `Ticket.csproj` (lege .NET projecten) | Root |
| 3 | Dode code: `oop test/` (leesoefeningen) | `oop test/` |
| 4 | Double `fetchAll()` op hetzelfde resultaat | `admin/v2/orders.php:115,123` |
| 5 | Query naar niet-bestaande kolommen `birthdate`, `scannen` | `admin/v2/orders.php:106-107` |
| 6 | Limburgish vertalingen niet opgeslagen bij INSERT | `admin/v2/events.php`, `admin/v2/add_festival.php` |
| 7 | `check_age.php` nergens aangeroepen | `includes/check_age.php` |
| 8 | N+1 query in gebruikersdashboard | `inlog_page/user_page.php:44` |
| 9 | Lege `Employees.html` | `public/Employees.html` |
| 10 | SQL dump met spaties in bestandsnaam | `database/events (1).sql` |

## 10. Git overzicht

### Tags
| Tag | Beschrijving |
|---|---|
| `v1.0` | Initiële versie |
| `v1.1` | Security fixes, bug fixes, code cleanup |
| `v1.2` | SQL injection fixes, pad fixes, hardcoded SMTP, form mismatches |

### Branches
| Branch | Beschrijving |
|---|---|
| `main` | Productie branch |
| `backup` | Remote backup branch |
| `docs` | Documentatie branch (nieuw) |

## 11. Ponytail bevindingen

### Over-engineering
- **Geen.** Het project is eerder under-georganiseerd dan over-engineered.

### Te verwijderen (dode code)
- `Program.cs` + `Ticket.csproj` - lege .NET projecten
- `oop test/` - oefenbestanden, niet onderdeel van het project
- `admin/admin.php`, `admin/create.php`, `admin/insert.php`, `admin/edit.php` - V1 admin verwijst naar oude schema
- `admin/adminpageV2.html` - verwijst naar niet-bestaand `config.php`
- `public/Employees.html` - leeg bestand
- `database/events (1).sql` - dubbele dump
- `includes/check_age.php` - nergens aangeroepen
- `ticket/test/widget_test.dart` - standaard Flutter test, niet bijgewerkt

### Simplificaties mogelijk
- `admin/v2/orders.php`: twee aparte queries vervangen door één query met optionele WHERE
- QR-codes genereren met een ingebouwde library i.p.v. externe API
- i18n kan met een eenvoudiger array-patroon

### Op te lossen (volgende iteratie)
- CSRF tokens toevoegen aan alle formulieren
- Admin authenticatie met rolgebaseerde toegang
- Session cookie wissen bij uitloggen
- IDOR fix: order ownership controleren
- Coupon validatie server-side verplaatsen
- ScanTicket.php authenticatie toevoegen

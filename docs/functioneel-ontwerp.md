# Functioneel Ontwerp

**Project:** Spik en Span Festival - Ticketsysteem Kasteel Limbricht
**Versie:** v1.2
**Datum:** juli 2026
**Auteurs:** Jasper van Kalsbeek, Noel, Anouk, Dylano (VISTA College)

---

## 1. Projectomschrijving

Het ticketsysteem stelt bezoekers van het Spik en Span Festival op Kasteel Limbricht in staat om online tickets te bestellen voor verschillende evenementen. Het systeem bestaat uit een klantportaal (website), een beheerpaneel (admin) en een mobiele QR-scanner app.

## 2. Doelgroep

| Gebruiker | Rol |
|---|---|
| Bezoeker | Bekijkt evenementen, bestelt tickets, ontvangt PDF per e-mail, bekijkt bestellingen |
| Medewerker | Scant QR-codes bij de ingang via mobiele app |
| Beheerder | Beheert evenementen, ticket types, kortingscodes en bekijkt bestellingen/dashboard |

## 3. Gebruikersstromen

### 3.1 Bestelproces (Bezoeker)

1. Bezoeker opent de website en navigeert naar het festivaloverzicht
2. Bezoeker kiest een evenement
3. Bezoeker vult persoonlijke gegevens in (aanhef, naam, e-mail, provincie, datum)
4. Bezoeker kiest het aantal tickets en vult per ticket een naam en type in
5. Bezoeker kan optioneel een kortingscode invoeren
6. Systeem toont een overzicht met totaalprijs
7. Bezoeker bevestigt de bestelling
8. Systeem genereert tickets met unieke QR-codes en slaat de bestelling op
9. Systeem genereert een PDF met alle tickets en QR-codes
10. Systeem verstuurt de PDF per e-mail naar de bezoeker
11. Bezoeker wordt doorgestuurd naar de bevestigingspagina

### 3.2 Inloggen (Bezoeker)

1. Bezoeker navigeert naar de inlogpagina
2. Bezoeker vult e-mailadres en ticket ID in
3. Systeem verifieert de combinatie tegen de database
4. Bij succes: bezoeker ziet een dashboard met zijn bestellingen en tickets

### 3.3 Tickets scannen (Medewerker)

1. Medewerker opent de Flutter QR-scanner app op mobiel
2. App scant de QR-code van het ticket
3. App stuurt het ticket ID naar de server (API)
4. Server controleert of het ticket bestaat en nog niet gescand is
5. Server markeert het ticket als gescand
6. App toont een bevestiging (geldig/ongeldig/al gescand)

### 3.4 Beheer (Beheerder)

1. Beheerder logt in op het adminpaneel
2. Dashboard toont overzichtsstatistieken: aantal evenementen, ticket types, kortingscodes, bestellingen
3. Dashboard toont topdagen (op basis van omzet) en provincieverdeling
4. Beheerder kan nieuw festival aanmaken (inclusief ticket types)
5. Beheerder kan individueel evenementen, ticket types en kortingscodes toevoegen
6. Beheerder kan bestellingen/tickets bekijken, zoeken, bewerken en verwijderen

## 4. Functionele eisen

### FE-01: Evenementen tonen
Het systeem toont een overzicht van beschikbare evenementen met naam, datum, locatie en minimale prijs.

### FE-02: Tickets bestellen
Een bezoeker kan voor een gekozen evenement een bestelling plaatsen met persoonlijke gegevens en ticket selectie.

### FE-03: Kortingscodes
Het systeem ondersteunt kortingscodes met een vast eurobedrag of percentage korting.

### FE-04: PDF-generatie
Na bestelling genereert het systeem een PDF met per ticket: naam, type, prijs, ticket ID en QR-code.

### FE-05: E-mailnotificatie
De PDF wordt automatisch per e-mail verstuurd naar de bezoeker.

### FE-06: QR-code scanning
Een mobiele app scant QR-codes en verifieert tickets tegen het systeem.

### FE-07: Gebruikersdashboard
Ingelogde bezoekers zien hun eigen bestellingen en tickets.

### FE-08: Admin dashboard
Beheerders zien statistieken: aantal evenementen, tickets, bestellingen, topdagen en provincieverdeling.

### FE-09: Beheer evenementen
Beheerders kunnen evenementen, ticket types en kortingscodes aanmaken en beheren.

### FE-10: Bestellingen beheren
Beheerders kunnen bestellingen en tickets bekijken, zoeken, bewerken en verwijderen.

### FE-11: Meertaligheid
De website ondersteunt Nederlands en Limburgs.

## 5. Niet-functionele eisen

### NFE-01: Responsive design
Alle pagina's zijn responsive en werken op mobiel en desktop.

### NFE-02: Beveiliging
- Prijzen worden server-side gevalideerd (niet vertrouwen op client)
- SQL-injectie wordt voorkomen via prepared statements
- E-mailadressen en namen worden geescaped in HTML-output

### NFE-03: Prestaties
- Database queries maken gebruik van indexen en LIMIT clauses
- PDF-generatie vindt place-to-server-side plaats

### NFE-04: Beschikbaarheid
- Het systeem draait op XAMPP (Apache + MariaDB + PHP)
- SMTP-verzending via PHPMailer (Gmail)

## 6. Gegevensmodel (Entiteiten)

### Evenement
- ID, naam, omschrijving, begindatum, einddatum, locatie
- Vertalingen: naam (Limburgs), omschrijving (Limburgs)

### Ticket Type
- ID, naam, prijs (euro), max per bestelling, max beschikbaar, verwijderdatum (soft delete), koppel aan evenement

### Bestelling
- ID, voornaam, achternaam, e-mail, aanhef, geboortedatum, evenement ID, totaalprijs, aanmaakdatum, herkomst (provincie)

### Ticket
- ID, e-mail, ticket ID (hex string voor QR), voornaam, achternaam, datum,datum vanwezig,scann order ID, ticket type ID

### Kortingscode
- ID, naam, korting euro, korting percentage, evenement ID, couponcode

## 7. Interfaces

### Klantportaal (website)
- Homepage: welkomsttekst, kasteelinformatie
- Festivaloverzicht: lijst van evenementen
- Bestelformulier: persoonlijke gegevens, ticket selectie, kortingscode
- Bevestigingspagina: overzicht van de bestelling
- Gebruikersdashboard: eigen bestellingen en tickets

### Beheerpaneel (admin)
- Dashboard: statistieken en overzicht
- Evenementen beheren
- Ticket types beheren
- Kortingscodes beheren
- Bestellingen/tickets beheren met zoekfunctie

### Mobiele app (Flutter)
- QR-code scanner
- API-communicatie met server

### API-endpoints
- `POST /ticket/ScanTicket.php` - QR-code valideren en ticket scannen

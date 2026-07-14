# Ticket Systeem v1.2

Spik en Span Festival – Kasteel Limbricht

## Wat is er veranderd in v1.2

- **Security:** SQL injectie gefixt in admin/create.php (prepared statements)
- **Security:** Hardcoded SMTP wachtwoord verplaatst naar includes/smtp_config.php
- **Bugfix:** Verkeerde include pad gefixt in includes/check_age.php
- **Bugfix:** Form fields in admin/edit.php kloppen nu met de database
- **Bugfix:** Admin pagina HTML herschreven (kapotte tags gefixt)
- **Bugfix:** Groepscode veld toegevoegd aan admin insert formulier
- **Bugfix:** Scannen checkbox check gefixt in insert.php

## Setup

1. `composer install`
2. Database importeren uit `database/` map
3. SMTP wachtwoord invullen in `includes/smtp_config.php`
4. Open `public/index.php` in de browser

# Perfectly Taylored

Password-gated wedding website.

## Live URL

perfectlytaylored.us (when deployed)

## Stack

PHP, HTML, CSS, JS. Hosted on Dreamhost.

## Local development

From the repo root:

```
php -S localhost:8000
```

Then visit http://localhost:8000.

## File structure

- `index.php`: gate page
- `home.php`: authenticated page
- `auth.php`: login form handler
- `logout.php`: clears the session
- `data/`: server-side data, including the guest allowlist
- `css/`: stylesheets and design tokens
- `js/`: client-side scripts
- `images/`: image assets
- `brand_assets/`: brand assets

## How to add guests

Edit `data/guests.json`. The file has two top-level keys:

- `password`: the shared password used by every guest
- `guests`: an array of full names in `"First Last"` form

Names are matched case-insensitively, with surrounding whitespace trimmed and internal whitespace collapsed to a single space. The password is matched exactly. Redeploy after editing.

## Deploy

TBD. Will document later.

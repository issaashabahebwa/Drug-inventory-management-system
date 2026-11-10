# Images Directory

Place your image files in this folder.

---

## Logo (`logo.png`)

Used in the sidebar and login page header.

**Set in `.env`:**
```
APP_LOGO=images/logo.png
```

---

## Banner / Crest Image (`banner.png`)

Used as the circular crest/coat-of-arms in the top header bar.  
When set, it replaces the default shield icon.

**Set in `.env`:**
```
APP_BANNER_IMAGE=images/banner.png
```

**Recommended specs:**
- Size: at least **96 × 96 px** (displayed at 48 × 48 px, circular crop)
- Format: PNG or WEBP (transparent background works best)
- Keep the file in `public/images/`

Leave `APP_BANNER_IMAGE` blank (or remove the line) to fall back to the default icon.

---

Supported formats: PNG, JPG, SVG, WEBP

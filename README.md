# DogPages WordPress Plugin

**DogPages** is a lightweight WordPress plugin that allows administrators to easily add and manage a public `/dog` page on their site, displaying a single dog image. It supports multisite networks, license key validation, and includes a sample cron job.

## 📦 Features

- Adds a **DogPages** settings menu in the WordPress admin sidebar
- Allows uploading a **dog image** via the admin settings
- Publicly accessible route `/dog` displays the uploaded dog image
- Requires a **license key** on first use
- Stores license key locally (no API validation needed)
- Includes a sample **daily cron job** to check the license key (logs output only)
- Supports **WordPress Multisite**: each site can upload its own dog image and will show it on its own `/dog` page

---

## 🛠 Installation

1. Download or clone this repository into your `wp-content/plugins` directory:

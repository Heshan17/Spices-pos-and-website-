# 🌶️ Ceylon Spices – POS System & Selling Website

A complete, enterprise-grade spice shop management and e-commerce suite built for Sri Lankan spice retailers and exporters.

---

## 📁 Repository Structure

```
├── 📁 spices-pos/                # Internal POS & Inventory Management System (Shop Owner & Cashier)
│   ├── index.html                # Terminal dashboard, Counter Billing, Spice Mixer, Analytics
│   ├── api.php                   # Secure Backend PHP REST API
│   ├── config.php                # Database credentials
│   ├── database.sql              # MySQL schema with 30 authentic Sri Lankan spices
│   └── assets/img/               # High-resolution spice product imagery
│
├── 📁 spices-selling-website/    # Customer-Facing Online Spice Selling Storefront
│   └── index.html                # 3D spices visualization, catalog browsing, and ordering
│
└── index.php                     # Local portal launcher for XAMPP
```

---

## 🚀 Features

### 1. `spices-pos` (Internal POS Terminal)
- **POS / Counter Billing**: Fast physical QR / Barcode scanner integration (USB/Bluetooth) with change due calculator and thermal receipt printing.
- **🧪 Spice Mixer & Recipe Cost Calculator**: Mix raw spices together, calculate total batch weights, packaging overheads, cost per 100g, profit margins, and auto-generate sellable products.
- **📦 Products & Inventory**: Stock tracking, low-stock notifications, restock prompt, and photo management.
- **👥 Modern Users & Roles Console**: Role-based access control (Admin, Cashier, Stock Manager), permissions matrix, and protected root accounts.
- **📈 Analytics & Reports**: Revenue curves, top-selling spices, payment breakdowns, and CSV export.

### 2. `spices-selling-website` (Online Spice Storefront)
- **Interactive 3D Spice Showcase**: Three.js interactive display.
- **Product Catalog**: Comprehensive spice details, weights, and price tags.
- **Customer Cart & Checkout**: Quick checkout and online ordering.

---

## 🛠️ Setup Instructions (Local XAMPP)

1. Start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Place this repository folder in `C:\xampp\htdocs\spice-pos`.
3. Open in browser:
   - **Project Portal**: `http://localhost/spice-pos/`
   - **POS Terminal**: `http://localhost/spice-pos/spices-pos/`
   - **Customer Website**: `http://localhost/spice-pos/spices-selling-website/`
4. Default Staff Logins:
   - **Admin**: `admin` / `admin123`
   - **Cashier**: `cashier` / `cash123`

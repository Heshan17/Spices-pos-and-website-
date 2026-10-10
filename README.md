# 🌶️ Ceylon Spices – POS System & Selling Website

A complete, enterprise-grade spice shop management and e-commerce suite built for Sri Lankan spice retailers and exporters.

---

## 📁 Repository Structure

```
├── 📁 spices-pos/                # Internal POS & Inventory Management System (Shop Owner & Cashier)
│   ├── index.html                # Terminal dashboard, Counter Billing, Recipe Costing, Analytics
│   ├── api.php                   # Secure Backend PHP REST API
│   ├── config.php                # Database credentials
│   ├── database.sql              # MySQL schema with 40 authentic Sri Lankan spices & nuts
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
- **🧪 Recipe Production, Raw Material & Profit Calculator (අමුද්‍රව්‍ය හා ලාභ මිනුම් යන්ත්‍රය)**:
  - **15 Authentic Pre-configured Formulations**: Curry powder (තුනපහ කුඩු), Meat curry powder (මස් කරි කුඩු), Black pepper powder (ගම්මිරිස් කුඩු), Turmeric powder (කහ කුඩු), Fenugreek (උළුහාල් කුඩු), Biriyani masala (බිරියානි මසාලා), Garam masala (ගරම් මසාලා), Chai masala (චායි මසාලා), Black curry powder (කළු කරි කුඩු), Serial porridge (ධාන්‍ය කැඳ), Serial mix(golden grains) (ධාන්‍ය මිශ්‍රණය), Chilli powder (මිරිස් කුඩු), Crushed chilli (කෑලි මිරිස්), Ginger powder (ඉඟුරු කුඩු), Mastard Powder (අබ කුඩු).
  - **Raw Material Measurement (අමුද්‍රව්‍ය ප්‍රමාණය)**: Enter batch weight (500g, 1kg, 2kg, 5kg, 10kg, 25kg, 50kg) or target pack count (10, 25, 50, 100 packs) to auto-calculate the exact grams/kg of each raw ingredient needed.
  - **Live Inventory Stock Verification**: Compares required raw materials with active shop inventory and alerts if any spice has a shortage.
  - **Production Cost Calculation (වියදම් මැනීම)**: Computes raw material costs, pouch/label packaging overhead, and grinding/milling/labor costs per batch and per packet.
  - **Profit & Margin Credentials (ලාභය මැනීම)**: Measures Net Profit per packet (+ Rs), Total Batch Profit (+ Rs), Realized Profit Margin %, Markup %, Breakeven price, and displays an executive profitability rating badge.
  - **Batch Production & Inventory Sync**: Deducts raw ingredients from inventory and adds finished packaged products to stock.
- **📦 Products & Inventory (40 Authentic Products)**: Includes newly added Cinnamon sticks (කුරුඳු පොතු), Garcinia paste (ගොරකා පේස්ට්), Derana polthel (දෙරණ පොල්තෙල්), Walnut (වොල්නට්), Pistachio (පිස්ටාචියෝ), Pumpkin seeds (වට්ටක්කා ඇට), Chia seeds (චියා ඇට), Cashew nuts (කජු), Flex seeds (හණ ඇට), Basil seeds (කසකසා ඇට) with high-res product photos.
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

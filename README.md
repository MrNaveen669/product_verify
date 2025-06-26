# 🔐 Product Authentication System

A simple and secure **product verification platform** to help businesses fight counterfeit goods using unique product codes and QR scanning. Built with **HTML, CSS, JavaScript, PHP, and MySQL**, it's ideal for manufacturers, rice mills, and brands who want to protect their products and build trust.

---

## 🚀 Features

- 🔎 Unique Product Code / ID Verification  
- 📷 QR Code Integration  
- 📁 Admin Panel for JSON/CSV Product Upload  
- 🧾 Product Details View (Name, Batch, Description, etc.)  
- ✅ Real-time Authenticity Check (Valid / Invalid)  
- 📱 Mobile-friendly Design  

---

## 🛠️ Tech Stack

- **Frontend**: HTML, CSS, JavaScript  
- **Backend**: PHP  
- **Database**: MySQL  
- **QR Code**: Google Chart API or PHP QR Code Library  

---

🛠️ Set Up Database

Import the provided product_data.sql into your MySQL server.

Update your db.php file with correct DB credentials.

// db.php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "your_db_name";
$conn = mysqli_connect($host, $user, $pass, $dbname);

---

💡 Use Cases
🌾 Rice Mills (Verify genuine rice bags)

🧴 FMCG Packaging

💊 Pharma & Supplements

📦 Electronics & Gadgets

👕 Textile & Fashion Brands

---

📌 Herbaras System Versions
Version 1.0
➤ Basic product verification with no admin panel.

Version 2.0
➤ Admin panel added.
➤ When the same UID is entered more than once, system detects it as “already used.”
➤ Duplicate UIDs are tracked using local storage and session management.

Version 3.0
➤ Adds is_used logic stored in the database.
➤ UID can only be verified once. After that, system flags it as used on subsequent scans.
➤ Enhances security and tracks product verification status persistently.

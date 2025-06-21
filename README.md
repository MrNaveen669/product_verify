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

php
Copy code
// db.php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "your_db_name";
$conn = mysqli_connect($host, $user, $pass, $dbname);

---

🛠️ Set Up Database

Import the provided product_data.sql into your MySQL server.

Update your db.php file with correct DB credentials.

php
Copy code
// db.php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "product_auth";
$conn = mysqli_connect($host, $user, $pass, $dbname);

---

💡 Use Cases
🌾 Rice Mills (Verify genuine rice bags)

🧴 FMCG Packaging

💊 Pharma & Supplements

📦 Electronics & Gadgets

👕 Textile & Fashion Brands

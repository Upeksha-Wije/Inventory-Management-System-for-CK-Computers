# Web-Based Inventory Management System for CK Computers

![Home page](https://github.com/user-attachments/assets/41c462b2-84b4-4a4d-a6a9-9c469527c62a)

## 📋 Project Overview

This project is a comprehensive **Web-Based Inventory Management System** developed for **CK Computers**, a leading reseller of computer components in Gampaha, Sri Lanka. The system automates and streamlines critical business processes like real-time stock management, sales tracking, and document management, replacing the traditional manual paperwork-based approach.

## ✨ Key Features
- 📦 **Real-Time Stock Management**: Monitor stock levels and manage inventory updates effectively.
- 🛒 **Sales & Purchase Management**: Streamline sales, purchase orders, returns, and invoices.
- 🧾 **Dynamic Reports**: Generate detailed reports such as sales reports, purchase order reports, and best-selling products reports.
- 👥 **Supplier & Customer Management**: Manage relationships with suppliers and customers efficiently.
- 🧑‍💼 **Staff Management**: Manage employee records and user login functionalities.
- 📊 **Analytics**: Visualize data insights for business decision-making.
  
## 💻 Technologies Used
- **Backend**: PHP (PHP Hypertext Preprocessor)
- **Frontend**: HTML5, CSS, Bootstrap, JavaScript
- **Database**: MySQL
- **Web Server**: Apache (via XAMPP)
- **Operating System**: Developed on Windows 10/11
- **Browsers Tested**: Google Chrome, Microsoft Edge

## 🚀 Installation and Setup

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/your-username/inventory-management-system.git
   ```
   
2. **Set Up the Environment:**
   - Install **XAMPP** (or any LAMP/WAMP server stack) to run the project locally.
   - Place the project folder in the `htdocs` directory of XAMPP.
   
3. **Import the Database:**
   - Open phpMyAdmin and create a new database.
   - Import the `database.sql` file (provided in the `database` folder) to set up the tables.
   
4. **Update Database Credentials:**
   - In the project, locate the `config.php` file and update the database connection details:
     ```php
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "your_database_name";
     ```
   
5. **Run the Project:**
   - Start **Apache** and **MySQL** services from the XAMPP control panel.
   - Open your browser and navigate to `http://localhost/inventory-management-system`.

## 📂 Project Structure

```bash
├── assets/              # CSS, JS, images, and other front-end resources
├── config/              # Configuration files like database connections
├── database/            # SQL files for database setup
├── views/               # Front-end pages (HTML + PHP)
├── controllers/         # Backend logic and functions
└── README.md            # Project documentation
```

## 📊 System Modules
- **Login Management**
- **Staff Management**
- **Category Management**
- **Product Management**
- **Supplier and Customer Management**
- **Sales and Purchase Management**
- **GRN Management**
- **Invoice and Sales Return Management**
- **Stock Management**
- **Reports and Analytics**

## 🛠️ Future Enhancements
- Add real-time notifications for low stock.
- Implement advanced analytics with graphical representations.
- Introduce multi-language support for international clients.
  
## 📝 License

![License](https://img.shields.io/badge/License-CC%20BY--NC--ND%204.0-lightgrey)

## ✉️ Contact

If you have any questions, feel free to contact me:
- **Email**: upekshawij@gmail.com
- **LinkedIn**: [Upeksha Wijerathne](https://www.linkedin.com/in/sanduni-upeksha-wijerathne/)

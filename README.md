# Cosmotown WHMCS Domain Registrar Module (with DNS Management)

[![WHMCS Compatible](https://img.shields.io/badge/WHMCS-8.x--9.x-blue.svg)](https://www.whmcs.com/)
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-orange.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)

Enhance your WHMCS platform with the **Cosmotown Domain Reseller Module**. This integration allows you to automate domain registration, transfers, and renewals while providing your customers with full DNS management directly from your client area.

## 🚀 Key Features

* **Automated Registration:** Instant domain registration upon payment.
* **Domain Transfers:** Seamlessly handle incoming domain transfers with EPP code validation.
* **DNS Management:** Full support for adding/editing A, AAAA, MX, CNAME, and TXT records.
* **Nameserver Management:** Allow clients to update their custom nameservers.
* **EPP/Auth Code Handling:** Clients can retrieve their transfer codes securely.
* **Auto-Renewal Support:** Support for WHMCS domain renewal automation.
* **Registrar Lock:** Enable/Disable domain theft protection (Client Area).
* **Contact Information Management:** View and update WHOIS contact details.

## 🛠 Installation

1.  **Download:** Clone or download this repository.
2.  **Upload:** Upload the `cosmotown` folder to your WHMCS directory:
    ```text
    /your_whmcs_root/modules/registrars/cosmotown/
    ```
3.  **Ensure File Structure:** Your directory should look like this:
    ```text
    registrars/
    └── cosmotown/
        ├── cosmotown.php
        ├── logo.gif
        └── (any additional helper files)
    ```

## ⚙️ Configuration

1.  Log in to your **WHMCS Admin Area**.
2.  Navigate to **System Settings > Domain Registrars** (or *Setup > Products/Services > Domain Registrars* in older versions).
3.  Find **Cosmotown** in the list and click **Activate**.
4.  Enter your **Cosmotown API Key** and **Account ID** (obtained from the Cosmotown Reseller Dashboard).
5.  Check **DNS Management** and **IP Address** settings as per your preference.
6.  Click **Save Changes**.

## 💻 Requirements

* **WHMCS:** v8.0 or later.
* **PHP:** v7.4 or v8.1+ (Recommended).
* **Cosmotown Account:** A valid reseller account with API access enabled.
* **CURL:** PHP CURL extension enabled on your server.

## 📄 License
This module is released under the **GNU General Public License v2.0**. You are free to modify and distribute it for your own business needs.

## 👨‍💻 Developer
**Swastik Chakraborty**
* **Website:** [swastik.dev](https://swastik.dev/)
* **Email:** [hello@swastik.dev](mailto:hello@swastik.dev)
* **GitHub:** [@SwastikBelieves](https://github.com/SwastikBelieves)

---
*Disclaimer: This is a third-party module and is not officially affiliated with Cosmotown Inc. Please test in a staging environment before moving to production.*

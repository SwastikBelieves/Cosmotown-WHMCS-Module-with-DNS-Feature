# Cosmotown WHMCS Registrar Module (with DNS Management)

[![WHMCS Compatible](https://img.shields.io/badge/WHMCS-8.x--9.x-blue.svg)](https://www.whmcs.com/)
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-orange.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
[![PHP](https://img.shields.io/badge/PHP-7.4%20|%208.1%2B-777bb4.svg)]()

Automate your domain reselling business with this comprehensive **Cosmotown WHMCS Module**. This integration connects your WHMCS installation directly to the Cosmotown API, providing automated registration, transfers, renewals, and full DNS record management for your clients.

## 📂 Project Structure

For the module to function correctly, ensure the folder is named `cosmotown` and placed in your WHMCS `/modules/registrars/` directory.

```text
cosmotown/
├── lib/                      # Core API logic classes
│   ├── ApiClient.php         # API Connection handler
│   ├── RegisterDomain.php    # Registration logic
│   ├── TransferDomain.php    # Transfer logic
│   ├── Sync.php              # Domain expiry/status sync
│   └── ... (and other helper classes)
├── constants.php             # Module constants
├── cosmotown.php             # Main WHMCS integration file
├── dnsmanagement.tpl         # Client Area DNS template
├── hooks.php                 # WHMCS automation hooks
└── logo.png                  # Admin area provider logo
```

## 🚀 Key Features

- **Automated Domain Lifecycle**  
  Instant registration, renewals, and incoming transfers.

- **Complete DNS Management**  
  Clients can add/edit A, AAAA, MX, CNAME, and TXT records via the `dnsmanagement.tpl` interface.

- **Security & Protection**  
  Full support for Registrar Lock and ID Protection toggles.

- **EPP Retrieval**  
  Automated Auth Code generation for domain transfers.

- **Sync Task**  
  Keeps your WHMCS database updated with real-time domain status and expiry dates via the WHMCS cron.

## 🛠 Installation & Setup

### 1. Whitelist Your Server IP (CRITICAL)

The Cosmotown API will reject all requests until your server IP is authorized.

1. Log in to your Cosmotown Account.  
2. Navigate to **My Account > Reseller API**.  
3. Add your WHMCS server's **Public IP Address** to the authorized list and save changes.

### 2. Upload Files

Upload the `cosmotown` folder to your WHMCS installation directory:

```
path_to_whmcs/modules/registrars/cosmotown/
```

### 3. Module Activation

1. In your WHMCS Admin Area, go to **System Settings > Domain Registrars**.  
2. Locate **Cosmotown** in the list and click **Activate**.  
3. Enter your API Key provided by Cosmotown.  
4. Click **Save Changes**.

## ⚙️ Requirements

- **WHMCS:** Version 8.0 or higher  
- **PHP:** Version 7.4 or 8.1+  
- **Cosmotown Account:** Must have an active reseller status and API access enabled

## 📄 License

This module is released under the **GNU General Public License v2.0**.

## 👨‍💻 Developer

**Swastik Chakraborty**

- GitHub: `@SwastikBelieves`  
- Website: https://swastik.dev  
- Inquiries: hello@swastik.dev

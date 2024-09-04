# SKRIME Plesk License WHMCS Module

## Overview

This module enables the integration of SKRIME's Plesk licensing service into WHMCS. With this module, you can manage and provision Plesk licenses directly from your WHMCS dashboard.

## System Requirements

- WHMCS version 7.0 or higher
- PHP version 7.2 or higher

## Installation

1. **Download and Unzip:** 
   Download the module ZIP file and unzip its contents into the root directory of your WHMCS installation.

2. **Add Server:**
   - Go to your WHMCS admin area: `yourdomain.com/admin/configservers.php`
   - Click on "Add New Server"
   - Select "SKRIME Plesk License" as the module
   - Enter `skrime.eu` as the hostname
   - Enter your API token in the "Username", "Password", and "Access Hash" fields

3. **Test Connection:**
   - Click on "Test Connection" and wait for confirmation of a successful connection
   - Optionally, give the server a custom name for better identification

4. **Set Maximum Accounts (Optional):**
   - Set the maximum number of licenses that can be obtained through SKRIME

5. **Save Settings:**
   - Click "Save Changes" to store your configuration

6. **Create Product Group and Product:**
   - Navigate to `yourdomain.com/admin/configproducts.php`
   - Create a new product group and add a new product
   - When creating the product, choose "Shared Hosting" as the product type and "SKRIME Plesk Licenses" as the module
   - Uncheck "Create as Hidden" to make the product visible

## Usage

After configuration, you can use SKRIME to manage Plesk licenses within your WHMCS products and services. The module supports the following features:

- **License Provisioning:** Automatically provision new Plesk licenses
- **License Renewal:** Renew existing Plesk licenses
- **License Management:** Manage existing licenses, including IP binding

## Support

If you need assistance with installing or using the module, please contact SKRIME support through our ticket system:

- Support ticket: https://skrime.eu/support/overview

Our support team will be happy to assist you with any questions or issues you may encounter.

## License

This module is released under the MIT License. See the LICENSE file for more details.

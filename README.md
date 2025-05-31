# Okala Product IDs

A PHP script to fetch and save product IDs from Okala's online store API across multiple categories and store branches.

This tool automates data collection across multiple store branches and product categories, making it ideal for inventory tracking, price monitoring, or data analysis. Efficient, lightweight, and easy to integrate.

## Features

- Reads store IDs from `store-ids.txt`
- Iterates over a predefined list of product categories
- Sends HTTP requests to Okala's API for each store-category combination
- Extracts product IDs and saves them to `product-ids.txt` in `storeId:productId` format
- Provides real-time feedback via browser while running

## Usage

### 1. **Clone the repository:**

   ```bash
   git clone https://github.com/BaseMax/okala-product-ids.git
   cd okala-product-ids
   ```

### 2. **Prepare input files:**

Add one store ID per line in store-ids.txt.

### 3. **Run the script in a browser or CLI:**

Place `okala-save-product-ids.php` on a local or remote PHP server.

### 4. **Open the script in a web browser or run it from the command line using:**

```bash
php okala-save-product-ids.php
```

## Output

The product IDs will be saved to product-ids.txt in the format:

```
storeId:productId storeId:productId ...
```

## Requirements

PHP 7.4 or higher

cURL enabled in PHP

Network access to Okala's API

## Notes

The script uses hardcoded headers including a Bearer token. Update the token if it expires.

Execution is not rate-limited beyond a short `usleep(500000)` between requests — be mindful of API rate limits.

Use with permission; scraping public APIs may violate terms of service.

## License

MIT License

Copyright (c) 2025 Max Base

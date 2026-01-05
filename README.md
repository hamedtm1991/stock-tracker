# Stock Price Tracker

A Laravel-based application to upload, store, and analyze stock prices.

---

## Table of Contents

- [Features](#features)
- [API Endpoints](#api-endpoints)
- [API Testing with Postman](#api-testing-with-postman)
- [Quick Start](#quick-start)

---

## Features

- Upload large Excel/CSV files containing stock prices.
- Memory-efficient import using **chunking**.
- Automatic date normalization (Excel numeric and string dates).
- Performance calculation over:
    - 1D, 1M, 3M, 6M, 1Y, YTD, MAX
    - Custom date ranges
- Queue-based import for asynchronous processing.

---

## API Endpoints
GET  /stocks/upload       → show upload form  
POST /stocks/upload       → handle file upload

GET  /api/stocks/period/{period}        → Performance for period   
GET  /api/stocks/custom?start=&end=     → Performance between dates


## API Testing with Postman
#### /postman/stock-tracker-api.postman_collection.json


## Quick Start

```bash
# 1. Clone the project
git clone <repository-url>
cd stock-app

# 2. Copy example env file
cp .env.example .env

# 3. Start all containers
docker compose up -d --build

# 4. Install composer dependencies
docker compose exec app composer install

# 5. Generate application key (if needed)
docker compose exec app php artisan key:generate

# 6. Run migrations
docker compose exec app php artisan migrate

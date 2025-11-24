# SMS Scheduling API

A simple, Laravel-based API for sending and scheduling SMS messages.

## 🚀 Features

- **Send SMS Immediately** - Instant message delivery
- **Schedule SMS** - Send messages at specific dates/times
- **Status Tracking** - message status tracking (pending, sent, queued, failed)
- **RESTful API** - Clean JSON API endpoints
- **Error Handling** - Comprehensive logging and error management
- **Queue Support** - Built-in job scheduling for reliable delivery
- **Pagination** - Efficient message listing with pagination

## 📋 Requirements

- PHP 8.1+
- Laravel 10+
- MySQL
- Composer
- Sail (Docker Desktop)
- SMS Provider Account (Twilio)

## 🛠 Installation

### 1. Clone and Setup
```
git clone git@github.com:khollinzx/sms-api.git - using SSH
git clone https://github.com/khollinzx/sms-api.git - using HTTPs
cd sms-api
composer install
cp .env.example .env
./vendor/bin/sail up
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```
### To listen or start up scheduler
```
sail artisan sms:send-scheduled-sms
```

### 2. Env Setup
#### Required variable - ```this will be attached to the mail.```
```
TWILIO_API_KEY
TWILIO_API_URL
TWILIO_SENDER_NUMBER
```
### 2. Postman Link
#### https://documenter.getpostman.com/view/10224661/2sB3dHVD61


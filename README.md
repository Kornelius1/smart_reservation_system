# Smart Reservation System - Homey Cafe

A smart reservation system specifically designed to facilitate table and room reservations while helping Homey Cafe manage its day-to-day reservation operations more efficiently.

This application provides a digital solution for customers to reserve tables or rooms and for administrators to manage reservations, rooms, and tables through a centralized system.

## Core Features

### Customer Reservation

Customers can make reservations for both **tables and rooms** at Homey Cafe.

When making a table or room reservation, customers are required to order menu items, including **food and/or beverages**. The minimum menu order quantity is determined based on the number of tables included in the reservation.

This reservation flow ensures that every table or room booking is accompanied by the required menu order.

### Room Reservation

Customers can reserve available rooms according to the available schedule.

Room reservations are integrated with the menu ordering process, meaning customers must select food and/or beverages as part of their reservation.

### Table Reservation

Customers can reserve available tables based on the desired date, time, and table availability.

The number of required menu items is calculated based on the number of tables reserved, ensuring that the reservation follows Homey Cafe's operational requirements.

### Menu Ordering

The reservation process supports food and beverage ordering.

Customers are required to include menu items when completing a table or room reservation. The required quantity is determined based on the number of tables included in the reservation.

### DOKU Payment Integration

The application is integrated with **DOKU** to support the payment process for customer reservations and menu orders.

The payment integration allows the reservation flow to be connected with a digital payment gateway, providing a more convenient and structured payment experience for customers.

### Admin Management

The application provides an **Admin** role with access to reservation and venue management features.

Administrators can perform CRUD (Create, Read, Update, Delete) operations for:

* **Reservations**
* **Rooms**
* **Tables**

This allows administrators to manage reservation data and maintain the availability and configuration of rooms and tables.

## Application Flow

The general reservation flow is:

1. Customer selects a table or room.
2. Customer selects the reservation date and time.
3. Customer provides the required reservation information.
4. Customer selects food and/or beverage menu items.
5. The system validates the required menu quantity based on the number of tables reserved.
6. Customer proceeds with the payment process through the integrated DOKU payment gateway.
7. After the payment process is completed, the reservation can be processed by the cafe.
8. Admin can manage and monitor reservation data through the administrative features.

## Production Status

This application has **previously been deployed and operated in a production environment**.

The system has therefore gone beyond local development and testing and has been used as a real-world reservation solution for Homey Cafe.

## Technology Stack

* **Backend Framework:** Laravel 12.25.0
* **Frontend:** Blade Templates with Tailwind CSS
* **Testing:** Pest Framework
* **Payment Gateway:** DOKU

## Development Standards

To maintain code consistency across the codebase when pushing or pulling changes through GitHub, all team members must follow the naming conventions below:

* **PHP Class Files:** Must use PascalCase. Example: `ReservationController.php`
* **Blade View Files:** Must use lowercase or snake_case. Example: `create_reservation.blade.php`

## Installation Guide

Follow the steps below to run the project in your local development environment.

1. Clone this repository to your local machine.
2. Run `composer install` to install all PHP dependencies.
3. Copy `.env.example` to `.env`.
4. Configure the database credentials in the `.env` file.
5. Generate the application key by running:

```bash
php artisan key:generate
```

6. Run the database migrations:

```bash
php artisan migrate
```

7. Start the local development server:

```bash
php artisan serve
```

## Testing

This project uses **Pest Framework** for automated testing.

Before pushing new code to the repository, make sure that existing functionality is not affected by running the test suite:

```bash
./vendor/bin/pest
```

## Project Purpose

The Smart Reservation System aims to provide Homey Cafe with a centralized digital platform for managing customer reservations, table and room availability, menu ordering, and payments.

By combining reservation management with mandatory menu ordering and digital payment integration, the system helps streamline the customer booking process while making reservation operations easier for cafe administrators to manage.

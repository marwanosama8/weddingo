# weddingo

**weddingo** is a portfolio-first backend project built with Laravel, designed as a wedding services marketplace API that connects couples with professional service providers for their wedding day.

The project focuses on **system design, booking workflows, and multi-role architecture**, rather than being a fully commercial product.

---

## Overview

weddingo centralizes all wedding-related services into one platform, allowing couples to discover, compare, and book professionals such as DJs, photographers, makeup artists, barbers, and other wedding service providers.

This project was built to demonstrate **real-world backend architecture**, clean API design, and booking logic commonly found in production systems.

---

## Key Features

### Marketplace Core
- Multi-role authentication (Client / Provider / Admin)
- Professional provider profiles with portfolio and pricing
- Wedding service categories (DJ, photography, makeup, etc.)
- Service availability and booking management
- Booking status lifecycle (pending, confirmed, completed, canceled)

### Booking Logic
- Time-based availability handling
- Booking conflict prevention
- Provider-controlled schedules
- Client booking history

### Admin Capabilities
- Provider approval and moderation
- Category and service management
- Platform-level oversight

---

## Architecture Highlights

- RESTful API built with Laravel
- Clean separation of concerns (Controllers, Services, Repositories)
- API Resources for consistent responses
- Form Request validation
- Role-based access control
- Scalable structure suitable for mobile or web clients

---

## Tech Stack

- **Backend:** Laravel
- **Authentication:** Laravel Sanctum
- **Database:** MySQL
- **API Design:** RESTful APIs with Resources
- **Architecture:** Service & Repository patterns

---

## Core Entities

- Users
- Providers
- Services
- Categories
- Availabilities
- Bookings
- Reviews

---

## Demo & Testing

The project includes seeded demo data:
- Sample providers and services
- Example bookings
- Predefined user roles

This allows reviewers to explore the system logic without requiring real users or payments.

---

## Project Goal

weddingo was created as a **portfolio showcase** to demonstrate:
- Backend system design
- Booking and scheduling logic
- Marketplace workflows
- Clean and maintainable Laravel code

It is not intended to be a production-ready commercial platform.

---

## Possible Improvements

- Payment integration
- Notifications system
- Advanced search and filtering
- Mobile application support
- Analytics dashboard

---

## 📄 License

This project is built for educational and portfolio purposes.

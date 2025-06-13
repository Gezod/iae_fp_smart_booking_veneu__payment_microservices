# 🏟️ Sport Venue Booking System — Microservices with Docker Compose

Sistem ini merupakan **aplikasi reservasi dan pembayaran venue olahraga** berbasis **microservices** yang dibangun menggunakan Laravel, Hasura GraphQL, MySQL, dan RabbitMQ. Semua layanan dijalankan dan dikelola secara terintegrasi menggunakan **Docker Compose**.

---

## 📦 Struktur Direktori
.
├── venue-service/ # Service Laravel untuk manajemen venue
├── booking-service/ # Service Laravel untuk manajemen booking
├── payment-service/ # Service Laravel untuk manajemen pembayaran
├── graphql-gateway/ # Gateway GraphQL (Lighthouse)
├── hasura-init/ # Inisialisasi Hasura Postgres
├── docker-compose.yml # Definisi semua layanan

yaml
Salin
Edit


---

## 🔌 Daftar Layanan & Port

| Service              | Port        | Deskripsi                                       |
|----------------------|-------------|-------------------------------------------------|
| MySQL Venue          | internal    | Database untuk layanan Venue                    |
| phpMyAdmin Venue     | `8081`      | Antarmuka database untuk Venue DB               |
| MySQL Booking        | internal    | Database untuk layanan Booking                  |
| phpMyAdmin Booking   | `8082`      | Antarmuka database untuk Booking DB             |
| MySQL Payment        | internal    | Database untuk layanan Payment                  |
| phpMyAdmin Payment   | `8083`      | Antarmuka database untuk Payment DB             |
| RabbitMQ             | `5672/15672`| Broker untuk event `booking_created`            |
| Hasura + Postgres    | `8090`      | GraphQL Engine untuk database Postgres          |
| Venue Service        | `8001`      | Laravel API untuk manajemen venue               |
| Booking Service      | `8002`      | Laravel API untuk reservasi lapangan            |
| Payment Service      | `8003`      | Laravel API untuk proses pembayaran             |
| GraphQL Gateway      | `8010`      | API terpadu menggunakan Lighthouse GraphQL      |

---
---
## 🔐 Environment Penting
MYSQL_ROOT_PASSWORD=root

HASURA_GRAPHQL_ADMIN_SECRET=myadminsecretkey

HASURA_GRAPHQL_JWT_SECRET: Digunakan untuk autentikasi GraphQL

RabbitMQ

User: guest

Password: guest

Port: 5672 (AMQP), 15672 (UI)


---
## 📚 Fitur Utama

✅ CRUD venue, booking, dan pembayaran  
🔁 Komunikasi antar layanan menggunakan RabbitMQ  
🌐 GraphQL Gateway sebagai satu pintu API  
🔍 Monitoring database dengan phpMyAdmin  
🚀 Otomatisasi setup Hasura + PostgreSQL

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan akademik dan pembelajaran tentang arsitektur microservices menggunakan Laravel, Docker, dan GraphQL.

---

## 👥 Anggota Kelompok

- 👨‍💻 Refangga Lintar Prayoga – NIM 1204220137  
- 👩‍💻 Nama 2 – NIM 0987654321  
- 👨‍💻 Nama 3 – NIM 1122334455  
 

Made with ❤️ by Kelompok 1


---
## 🚀 Cara Menjalankan

### 1. Pastikan Sudah Terinstall

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

### 2. Jalankan Proyek

```bash
docker-compose build --no-cache && docker-compose up

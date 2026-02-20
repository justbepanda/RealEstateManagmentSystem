📦 Delivery API Training Project
================================

**Laravel 12 | Docker | RoadRunner | Redis | Meilisearch**

🌍 Choose Language / Выберите язык
----------------------------------

*   [English Version](#english-version)

*   [Русская версия](#russian-version)

<a name="english-version"></a>
🇬🇧 English Version
--------------------

### 📌 Description

This is a training backend project built with **Laravel 12**. It demonstrates modern backend architecture, Docker infrastructure, API development, queues, search, and scalable design patterns.

### ⚙️ System Requirements

*   **Docker** & **Docker Compose**

*   **Make**

*   **Git**

*   _Note: No PHP or Composer installation is required locally._


### 🚀 Installation

1.  git clone https://github.com/justbepanda/delivery-api-training.git

2.  cd project-folder

3.  make setup


**The make setup command will:**

*   Build Docker containers.

*   Install Composer dependencies.

*   Copy .env.

*   Generate app key.

*   Run migrations.

*   Seed database (if configured).


### ▶️ Run Project

**make up**

The application will be available at: **http://localhost:8000**

### 🧱 What’s Inside

**Stage 1: Infrastructure & Database (Docker + Architecture)**

*   Docker + PHP + RoadRunner, PostgreSQL, Redis.

*   Container networking and Laravel installation from scratch.

*   **Result:** Fully working environment with real container communication.


**Stage 2: Authentication & API (Sanctum + API Resources)**

*   User registration & login using Laravel Sanctum.

*   API Resources for clean JSON responses and Form Requests for validation.

*   **Result:** Secure and well-structured REST API.


**Stage 3: Core System & Complex Relations (Eloquent + Polymorphic)**

*   Order and Driver entities.

*   Comments with polymorphic relations.

*   Service Layer for business logic.

*   **Result:** Flexible database without duplication.


**Stage 4: Queues & Asynchronous Processing (Redis + Jobs)**

*   Background email sending and Telegram notifications.

*   Redis queue driver, Jobs & Observers.

*   **Result:** Fast API responses with background processing.


**Stage 5: Advanced Logic (Interfaces + Patterns)**

*   Notification system (Email, SMS) using Strategy pattern.

*   Action pattern (e.g., CreateOrderAction).

*   SOLID principles in practice.

*   **Result:** Easily extendable architecture.


**Stage 6: Search & Scaling (Scout + Meilisearch)**

*   Full-text search with Laravel Scout and Meilisearch integration.

*   Redis caching for popular queries.

*   **Result:** Fast search without database overload.

<a name="russian-version"></a>
🇷🇺 Русская версия
-------------------

### 📌 Описание

Учебный backend-проект на **Laravel 12**. Демонстрирует современную архитектуру, Docker-инфраструктуру, построение API, очереди, поиск и масштабируемый код.

### ⚙️ Системные требования

*   **Docker** & **Docker Compose**

*   **Make**

*   **Git**

*   _Локально PHP и Composer не требуются._


### 🚀 Установка

1.  git clone https://github.com/justbepanda/delivery-api-training.git

2.  cd project-folder

3.  make setup


**Команда make setup:**

*   Соберёт контейнеры.

*   Установит зависимости Composer.

*   Создаст .env и сгенерирует ключ приложения.

*   Выполнит миграции и запустит сидеры.


### ▶️ Запуск

**make up**

Приложение будет доступно по адресу: **http://localhost:8000**

### 🧱 Что внутри

**Этап 1: Инфраструктура и база данных**

*   Docker + PHP + RoadRunner, PostgreSQL, Redis.

*   Настройка сетей контейнеров и установка Laravel с нуля.

*   **Результат:** Рабочая инфраструктура без «магии».


**Этап 2: Аутентификация и API**

*   Регистрация и вход (Laravel Sanctum).

*   API Resources и Form Requests.

*   **Результат:** Защищённое и структурированное API.


**Этап 3: Ядро системы и сложные связи**

*   Сущности Order, Driver и полиморфные связи для Comments.

*   Service Layer для выноса бизнес-логики.

*   **Результат:** Гибкая архитектура базы данных.


**Этап 4: Очереди и асинхронность**

*   Email и Telegram уведомления в фоне.

*   Redis как драйвер очередей, Jobs и Observers.

*   **Результат:** Быстрый API + фоновые процессы.


**Этап 5: Продвинутая архитектура**

*   Интерфейсы и паттерны Strategy, Action.

*   Соблюдение принципов SOLID.

*   **Результат:** Расширяемый и чистый код.


**Этап 6: Поиск и масштабирование**

*   Полнотекстовый поиск (Laravel Scout + Meilisearch).

*   Кэширование популярных запросов в Redis.

*   **Результат:** Мгновенный поиск без нагрузки на PostgreSQL.

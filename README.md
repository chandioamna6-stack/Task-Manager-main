# Task Manager

🧩 Task Manager is a Laravel-based web application developed by Amna Chandio to help users manage projects and daily tasks efficiently.
It provides a ClickUp or Trello-like experience with a drag-and-drop board layout for easy task management.

---

## Prerequisites

Make sure your environment includes:

- PHP 8.1+
- Composer
- Laravel 10+
- MySQL or another supported DB
- Node.js & npm (for frontend assets)

---

## 🗂️ Features

- 🗂️ Project Management  
  - Create and manage multiple projects to organize work.
- ✅ Task Management  
  - Add, edit, delete tasks and move them between stages with drag-and-drop.
- 🧠 Notes  
  - Attach notes to projects or tasks for context.
- ⏰ Reminders  
  - Set reminders to meet important deadlines.
- 🔁 Routines  
  - Create recurring routine tasks (daily/weekly).
- 📎 File Uploads  
  - Attach and manage files for each project or task.
- 🖼️ Board View  
  - Visual Kanban-style board for fast workflows.

---

## Quick Start — Setup Instructions

1. Clone the repository
```bash
git clone https://github.com/chandioamna6-stack/Task-Manager-main.git
cd Task-Manager
```

2. Install PHP dependencies
```bash
composer install
```

3. Copy and configure environment
```bash
cp .env.example .env
# Edit .env to set DB credentials and other variables
```

4. Generate application key
```bash
php artisan key:generate
```

5. Run migrations and seeders
```bash
php artisan migrate --seed
```

6. (Optional) Compile front-end assets
```bash
npm install
npm run dev    # or npm run build for production
```

7. Serve the application
```bash
php artisan serve
```

Open http://localhost:8000 in your browser.

---

## 💡 Default Admin Account

Use this to log in to the admin panel:

- Email: `amna@example.com`  
- Password: `amna12345`

---

## How to Use

1. Run the project (see Quick Start).
2. Visit the app in your browser.
3. Log in with the admin credentials above.
4. Create projects, add tasks, drag tasks across stages, attach files and notes, and set reminders or routines.

---

## Demo


<img width="1919" height="907" alt="routines" src="https://github.com/user-attachments/assets/41a2e8e7-35fb-4549-8b1e-9c2d3593e879" />
<img width="1909" height="913" alt="reminder" src="https://github.com/user-attachments/assets/f3298a69-23fb-455f-bdb6-f8f84deeeeff" />
<img width="1919" height="905" alt="task" src="https://github.com/user-attachments/assets/1d6db4d8-ecb4-49da-96ed-90a09f217f25" />
<img width="1918" height="909" alt="dashboard" src="https://github.com/user-attachments/assets/33431106-f087-4dd4-8727-0f583ddfd7db" />

---

## Contributing

🎉 Thank you for your interest in contributing! 🌟

- Found a bug? Please open an Issue.
- Want to add a feature or fix something? Fork the repo, create a branch, and open a Pull Request.
- Follow the repo's coding style and include tests when appropriate.

---

## Contact

- ✉️ Email: chandioamna6@gmail.com  
- 🔗 LinkedIn: https://www.linkedin.com/in/amna-chandio-it85/

---

## License

This project is licensed under the [MIT License](./LICENSE) — see the LICENSE file for details.

---

Made with ❤️ by Amna Chandio

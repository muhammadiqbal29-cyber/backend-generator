# Backend Generator 🚀

> **“Ketergantungan yang berlebihan pada AI menciptakan kerapuhan sistem. Jika AI tiba-tiba menghilang, apakah kita masih bisa membangun sesuatu dari awal?”**

Selamat datang di **Backend Generator**, sebuah project yang lahir dari sebuah keresahan. Di era modern ini, behavior developer mulai bergeser. Sangat banyak dari kita yang bergantung penuh pada AI-Agent atau Copilot untuk menulis kode *boilerplate*. Ketergantungan ini ibarat pedang bermata dua: ia mempercepat alur kerja, namun menciptakan "kerapuhan" *(fragility)*. Jika suatu hari layanan AI mengalami *downtime*, dihentikan, atau menjadi sangat mahal sehingga tidak dapat diakses, banyak developer akan merasa "lumpuh" dan kesulitan menyusun fondasi sistem dari awal. 

Project ini hadir sebagai **kemandirian sistem**. Backend Generator adalah alat generator statis yang cepat, ringan, dan berjalan independen tanpa perlu *prompting* ke LLM untuk membuat fondasi CRUD. Anda cukup mendefinisikan skema tabel Anda, dan generator ini akan merakit kode berkualitas tinggi seketika!

## 🌟 Fitur Utama
- **Go Generator (Gin + GORM):** Menghasilkan Struct Model, Repository Layer, Handler, dan Route API secara otomatis.
- **Laravel Generator:** Menghasilkan Migration, Model, Controller, dan API Resource Route dengan standar best practice.
- **Glassmorphism UI:** Antarmuka yang bersih, premium, dan sangat mudah digunakan.
- **Independent & Fast:** Menghasilkan *source code* dalam hitungan milidetik. Tidak bergantung pada eksternal API.
- **Zero Config:** Langsung jalankan dan nikmati!

## 🛠️ Instalasi & Penggunaan

### Persyaratan Sistem
- PHP 8.1+
- Composer
- Node.js (Opsional, untuk Vite asset jika diperlukan)

### Langkah-langkah
1. Klon repositori ini:
   ```bash
   git clone https://github.com/muhammadiqbal29-cyber/backend-generator.git
   cd backend-generator
   ```
2. Instal dependensi PHP:
   ```bash
   composer install
   ```
3. Salin `.env.example` ke `.env` dan generate application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Jalankan *development server*:
   ```bash
   php artisan serve
   ```
5. Buka `http://localhost:8000` di *browser* Anda. Rancang entitas/tabel Anda, klik **Generate Code**, dan segera salin kodenya ke *project* inti Anda!

## 🔄 CI/CD (GitHub Actions)
Repositori ini telah dilengkapi dengan GitHub Actions untuk memastikan kualitas kode dan kemudahan deployment:
- **Linting & Code Style:** Mengecek standar penulisan kode pada Laravel.
- **Testing:** Menjalankan PHPUnit test secara otomatis pada setiap *push* dan *pull request*.

File konfigurasi berada di `.github/workflows/ci.yml`.

## 📜 Lisensi
Proyek ini dilisensikan di bawah lisensi **MIT**. Silakan lihat file [LICENSE](LICENSE) untuk informasi lebih detail.

---
*Dibuat untuk memperkuat kemandirian Developer. Keep Coding, Keep Building!*

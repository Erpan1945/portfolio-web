Personal Web Portfolio - Irfan Abdurrahman
Aplikasi portofolio pribadi berbasis web yang dirancang untuk menampilkan karya desain grafis, pengembangan aplikasi mobile, dan proyek pengembangan web secara profesional. Proyek ini menggunakan arsitektur modern dengan Laravel sebagai backend API dan Vue.js sebagai frontend interaktif.

Fitur Utama
1. Landing & Portfolio Showcase
Landing View: Halaman utama yang memperkenalkan profil sebagai mahasiswa Sistem Informasi.

Portfolio Gallery: Galeri dinamis yang menampilkan berbagai proyek yang telah dikerjakan, termasuk detail teknologi yang digunakan.

Potozon Gallery: Komponen khusus untuk menampilkan aset visual atau karya desain grafis.

2. Digital Resume (CV View)
Interactive CV: Halaman khusus untuk menampilkan riwayat pendidikan, pengalaman organisasi (seperti DPM FILKOM UB), dan keahlian teknis secara digital.

Dynamic Content: Data riwayat hidup dikelola melalui backend dan ditampilkan secara responsif.

3. Admin & Content Management
Admin Dashboard: Panel kendali untuk menambah, mengubah, atau menghapus data proyek dan sertifikat tanpa menyentuh kode program.

Secure Authentication: Sistem login terintegrasi untuk melindungi akses ke dashboard admin.

4. Integrasi Layanan
Supabase Integration: Menggunakan Supabase untuk manajemen database atau penyimpanan file secara cloud.

Contact Form: Fitur komunikasi bagi pengunjung untuk menghubungi pemilik portofolio secara langsung.

Teknologi yang Digunakan
Core Framework: Laravel 11.

Frontend: Vue.js 3 dengan Vue Router untuk navigasi Single Page Application (SPA).

State & Database: Supabase & PostgreSQL.

Styling: Tailwind CSS.

Build Tool: Vite.

Panduan Instalasi
Clone Repository:

Bash
git clone https://github.com/Erpan1945/portfolio-web.git
cd porto-web
Backend Setup:

Bash
composer install
cp .env.example .env
php artisan key:generate
Frontend Setup:

Bash
npm install
npm run dev
Database Migration:

Bash
php artisan migrate

👤 Author
Irfan Abdurrahman Student of Information Systems, Universitas Brawijaya.

Interests: UI/UX Design, Mobile Development (Kotlin/Flutter), Web Development.

LinkedIn: [Link LinkedIn Anda]

GitHub: @Erpan1945
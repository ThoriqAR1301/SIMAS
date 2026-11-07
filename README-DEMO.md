# 🎬 Panduan Demo SIMAS ke Bu Aesa

## Akun Demo

| Role | Email | Password | Nama |
|---|---|---|---|
| **Admin TU** | `aesa@simas.test` | `password` | Aesa Nurhaliza, S.Kom. |
| Admin TU | `rina@simas.test` | `password` | Rina Marlina, A.Md. |
| **Pimpinan** | `kepsek@simas.test` | `password` | Dr. H. Bambang Sutrisno, M.Pd. |
| Pimpinan | `wakasek@simas.test` | `password` | Dra. Siti Aminah, M.Pd. |
| Guru | `ahmad@simas.test` | `password` | Ahmad Fauzi, S.Pd. |
| Guru | `dewi@simas.test` | `password` | Dewi Lestari, S.Pd. |
| Guru | `rudi@simas.test` | `password` | Rudi Hartono, S.Kom. |
| Guru | `sari@simas.test` | `password` | Sari Indah, S.Pd. |

## Skenario Demo (15 menit)

### 🎯 Skenario 1 — Guru Mengajukan Surat (3 Menit)
1. Login Sebagai **Ahmad Fauzi** (`ahmad@simas.test`)
2. Klik **Buat Pengajuan**
3. Isi Wizard :
   - **Step 1**: Pilih Blanko "Surat Tugas", Jenis "Surat Tugas", Kegiatan "Rapat MGMP"
   - **Step 2**: Tanggal 3 Hari Ke Depan, Waktu 08:00–12:00, Tempat "Aula Sekolah"
   - **Step 3**: Upload 1 File Dummy PDF
4. Klik **Simpan & Ajukan** → Notif Muncul Di Bell

### 🎯 Skenario 2 — Admin Verifikasi (3 Menit)
1. **Buka Browser Lain** (Atau Incognito), Login Sebagai **Aesa** (`aesa@simas.test`)
2. **Perhatikan Bell** — Ada Notif Merah "Pengajuan Baru" (Realtime!)
3. Klik menu **Approval** → Lihat Pengajuan Ahmad
4. Klik **Review** → **Verifikasi & Teruskan**
5. Notif Ke Pimpinan Ter-Trigger

### 🎯 Skenario 3 — Pimpinan Approve (3 Menit)
1. Buka **Browser Ketiga**, Login Sebagai **Kepsek** (`kepsek@simas.test`)
2. Bell Berkedip — Notif "Pengajuan Diverifikasi"
3. Menu **Approval** → klik **Review** → **Setujui Final**
4. **Nomor Surat Ter-Generate Otomatis**, QR Code Muncul
5. Notif Ke Guru "Pengajuan Disetujui"

### 🎯 Skenario 4 — Guru Lihat Hasil & Download PDF (2 Menit)
1. Kembali Ke Browser Ahmad → Refresh
2. Bell Ada Notif "Pengajuan Disetujui"
3. Buka Pengajuan → Lihat **Timeline Hijau**
4. Klik **Download PDF** → PDF Dengan QR Code Terbuka
5. Scan QR Pakai HP → Halaman Verifikasi Publik Muncul

### 🎯 Skenario 5 — Laporan (2 Menit)
1. Login Admin → Menu **Laporan**
2. Filter Minggu Ini → Lihat Rekap 8 Pengajuan
3. Klik **Export Excel** / **Export PDF**

### 🎯 Skenario 6 — Disposisi Surat Masuk (2 Menit)
*(Kalau Modul Rekan B Sudah Siap)*
1. Login Admin → **Surat Masuk** → Input Surat Dari Industri
2. Pilih Folder **Surat Industri/Mitra**
3. Disposisi Ke Pimpinan Dengan Instruksi

## Tips Presentasi

✅ **Poin Jual Ke Bu Aesa :**
- "Notifikasi Realtime — Bu Aesa Tidak Akan Ketinggalan Pengajuan"
- "Nomor Surat Otomatis — Tidak Perlu Hitung Manual"
- "QR Code — Surat Tidak Bisa Dipalsukan"
- "Timeline Approval — Transparansi Penuh"
- "Rekap Mingguan Otomatis Via Email"

❌ **Hindari :**
- Bicara Technical (Framework, Database)
- Demo Fitur Yang Belum Selesai
- Lebih Dari 20 Menit

## Reset Data Demo

```bash
php artisan migrate:fresh --seed
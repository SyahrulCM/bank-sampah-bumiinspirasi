<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


# 📱 Mobile API - Bank Sampah Bumi Inspirasi

Dokumentasi API untuk aplikasi mobile Bank Sampah Bumi Inspirasi.

---

## 🔐 Autentikasi

Semua endpoint (kecuali register dan login) membutuhkan token Bearer:

```
Authorization: Bearer {token}
```

---

## ✅ REGISTER

**POST** `/api/nasabah/register`

```json
{
  "nama_lengkap": "Syahrul Barnacleboy",
  "alamat": "Ngamprah",
  "nomer_telepon": "081903421111",
  "password": "123456",
  "tanggal": "2025-07-22"
}
```

**Response:**

```json
{
  "message": "Registrasi berhasil",
  "token": "2|FB6DCpsvEt...",
  "nasabah": {
    "id_registrasi": 3,
    "nama_lengkap": "Syahrul Barnacleboy",
    "alamat": "Ngamprah",
    "nomer_telepon": "081903421111",
    "nomer_induk_nasabah": "NS0003",
    "tanggal": "2025-07-22",
    "foto": null
  }
}
```

---

## 🔑 LOGIN

**POST** `/api/nasabah/login`

```json
{
  "email": "user@example.com",
  "password": "password"
}
```

**Response:**

```json
{
  "message": "Login berhasil",
  "token": "1|qFmMxCHVI4...",
  "nasabah": {
    "id_registrasi": 1,
    "nama_lengkap": "Syahrul Choliq Mawardi",
    "alamat": "Jl. Rancabogo",
    "nomer_telepon": "081904526785",
    "nomer_induk_nasabah": "NS0001",
    "tanggal": "2025-07-23",
    "foto": null
  }
}
```

---

## 👤 PROFILE

**GET** `/api/nasabah/profile`

```json
{
  "id_registrasi": 1,
  "nama_lengkap": "Syahrul Choliq Mawardi",
  "alamat": "Jl. Rancabogo",
  "nomer_telepon": "081904526785",
  "nomer_induk_nasabah": "NS0001",
  "tanggal": "2025-07-23",
  "foto": null
}
```

---

## 💰 TRANSAKSI

**GET** `/api/nasabah/transaksi`

```json
{
  "message": "Data transaksi nasabah",
  "data": [
    {
      "id_transaksi": 1,
      "tanggal": "2025-07-23",
      "saldo": 13620,
      "detail_transaksi": [
        {
          "berat_sampah": 12,
          "jumlah_setoran": 3,
          "sampah": {
            "jenis_sampah": "Plastik",
            "harga_ditabung": 960
          }
        }
      ]
    }
  ]
}
```

---

## 💸 PENARIKAN

### 🧾 Ajukan Penarikan

**POST** `/api/penarikan`

```json
{
  "jumlah": 10000,
  "keterangan": "Penarikan via mobile"
}
```

**Response:**

```json
{
  "status": true,
  "message": "Penarikan berhasil diajukan.",
  "data": {
    "jumlah": 10000,
    "status": "pending",
    "keterangan": "Penarikan via mobile"
  }
}
```

### 🕓 Riwayat Penarikan

**GET** `/api/penarikan/histori`

```json
{
  "status": true,
  "data": [
    {
      "tanggal": "2025-07-20",
      "jumlah": 15000,
      "status": "disetujui"
    },
    {
      "tanggal": "2025-07-21",
      "jumlah": 10000,
      "status": "ditolak",
      "alasan_ditolak": "Saldo belum mencukupi"
    }
  ]
}
```

---

## 🗑️ SAMPAH

**GET** `/api/sampah`

```json
{
  "data": [
    {
      "jenis_sampah": "Plastik",
      "harga_pengepul_rp": "Rp 1.200",
      "harga_ditabung_rp": "Rp 960",
      "deskripsi": "Sampah Plastik"
    }
  ]
}
```

---

## 📚 EDUKASI

**GET** `/api/edukasi`

```json
{
  "data": [
    {
      "judul": "Sampah Plastik",
      "isi": "Edukasi tentang dampak dan pengelolaan sampah plastik."
    }
  ]
}
```

---

## 📢 PENGUMUMAN

**GET** `/api/pengumuman`

```json
{
  "data": [
    {
      "judul": "Libur",
      "isi": "Tidak ada kegiatan pada hari Minggu",
      "status": "aktif"
    }
  ]
}
```

---

## 📊 STATUS PENARIKAN

- `pending` → Menunggu validasi
- `disetujui` → Berhasil
- `ditolak` → Gagal (dengan alasan)

---

📝 Dokumentasi ini akan terus diperbarui jika ada fitur baru.






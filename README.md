# Project study case software development PT Swadharma Duta Data 

# Techstack :

- PHP, Laravel Breeze, Tailwind CSS, JavaScript, PHPUnit, MySQL

- Java, Spring Boot, JUnit, Mysql

# Arsitektur Aplikasi :

Aplikasi Laravel (Frontend):

Fungsi: Menampilkan data klaim per LOB, import data klaim lob, serta mengirimkan data klaim ke API.

Database: Menyimpan data klaim per LOB.

Fitur Utama:

- Import data dari Excel ke database aplikasi.
- Menampilkan data klaim dalam interface.
- Mengirim data klaim LOB KUR dan PEN ke API (Spring Boot).

Aplikasi Spring Boot:

- Spring Boot menerima data klaim yang dikirim oleh Laravel melalui API.
- Data klaim disimpan ke database penampungan.

# Struktur database :

- Nama database Laravel : db_klaim_lob 

- Field" pada table klaim_lobs : 
id, lob, penyebab_klaim, periode, id_wilker, tgl_keputusan_klaim, jumlah_terjamin, nilai_beban_klaim, debet_kredit, created_by, created_at, updated_at

- Field" pada table api_logs :
id, processdate, totaldata, deliverystatus, lastupd_process, created_by, created_at, updated_at

- Nama database Spring Boot : db_klaim_lob 

- Field" pada table klaim : 
id, lob, penyebab_klaim, periode, nilai_beban_klaim

# Catatan :

- Data klaim lob yang dapat di import berada pada folder public/data/data_klaim_lob.xlsx

- Laporan hasil unit testing pada project Laravel berada di folder test/test-reports/testdox.html

- Coverage report testing berada di folder test/reports/coverage-report

- Lokasi unit testing spring boot berada pada folder KlaimApiService/src/test/java package com.klaimapi.appservice - Terdapat 2 file test yaitu (KlaimApiControllerTests.java) dan (KlaimRepositoryTests.java)
```mermaid
erDiagram

    ANGGOTA ||--o{ PEMINJAMAN : melakukan
    BUKU ||--o{ PEMINJAMAN : memiliki

    ANGGOTA {
        int id PK
        varchar nama
    }

    BUKU {
        int id PK
        varchar judul
        varchar penulis
        varchar penerbit
        year tahun
    }    

    PEMINJAMAN {
        int id PK
        int id_buku FK
        int id_anggota FK
        date tanggal_pinjam
        date tanggal_kembali
    }
```
# Kamus data target V2

Dokumen ini membedakan tabel MVP yang sudah ada di migration starter dengan tabel ekstensi yang masih direncanakan.

## A. Tabel pada fondasi MVP

### users
| Kolom | Tipe konseptual | Aturan |
|---|---|---|
| id | bigint | PK |
| name | varchar | wajib |
| email | varchar | wajib, unik |
| password | varchar | hash, jangan pernah diekspor ke audit |
| role | varchar | `admin` atau `petugas_gudang` |
| timestamps | datetime | waktu pencatatan |

### categories
`id` PK, `name` unik dan wajib, `description` nullable, timestamps.

### suppliers
`id` PK, `name` wajib, `contact_person`, `phone`, `email`, `address` nullable, timestamps.

### items
`id` PK, `code` unik (dibuat otomatis: BRG-YYYYMMDD-xxxxxx), `name` wajib, `category_id` FK ke categories, `location` teks pada MVP, `unit`, `stock` unsigned integer, `minimum_stock` unsigned integer, `target_stock` unsigned integer untuk menghitung saran restock otomatis, timestamps.

### stock_ins
`id` PK, `transaction_code` unik (dibuat otomatis dengan awalan IN), `item_id` FK, `supplier_id` FK, `user_id` FK nullable (diisi dari pengguna login), `date` (default hari ini), `quantity` positif, `note` nullable, timestamps.

### stock_outs
`id` PK, `transaction_code` unik (dibuat otomatis dengan awalan OUT), `item_id` FK, `user_id` FK nullable (diisi dari pengguna login), `date` (default hari ini), `quantity` positif, `note` wajib, timestamps.

## B. Tabel ekstensi V2 (belum ada di starter migration)

### locations
`id` PK, `code` unik, `name`, `description`, `is_active`, timestamps. Satu lokasi dapat menyimpan banyak item. Pada migrasi transisi, kolom `items.location` teks perlu dipetakan ke `items.location_id`; jangan menghapus data teks sebelum mapping diverifikasi.

### purchase_orders
`id` PK, `po_number` unik, `supplier_id` FK, `created_by` FK users, `order_date`, `expected_date` nullable, `status` (`draft`, `ordered`, `partially_received`, `received`, `cancelled`), `note` nullable, timestamps.

### purchase_order_items
`id` PK, `purchase_order_id` FK, `item_id` FK, `quantity_ordered` unsigned, `quantity_received` unsigned default 0, `unit_cost` decimal nullable, `note` nullable, timestamps. Constraint bisnis: `quantity_received <= quantity_ordered` kecuali ada prosedur perubahan PO yang tercatat.

### stock_opnames
`id` PK, `opname_number` unik, `location_id` FK, `created_by` FK users, `approved_by` FK users nullable, `opname_date`, `status` (`draft`, `counting`, `submitted`, `approved`, `cancelled`), `note` nullable, `approved_at` nullable, timestamps.

### stock_opname_items
`id` PK, `stock_opname_id` FK, `item_id` FK, `system_quantity` unsigned, `physical_quantity` unsigned nullable selama belum dihitung, `difference` integer nullable, `reason` nullable, timestamps. Satu item hanya boleh muncul sekali dalam satu sesi opname.

### activity_logs
`id` PK, `user_id` FK nullable, `action`, `subject_type`, `subject_id` nullable, `description` nullable, `before` JSON nullable, `after` JSON nullable, `ip_address` nullable, `created_at`. Jangan menyimpan password, token, cookie, APP_KEY atau rahasia lainnya.

## C. Relasi utama
- categories 1:M items.
- locations 1:M items.
- suppliers 1:M stock_ins.
- items 1:M stock_ins dan stock_outs.
- users 1:M stock_ins dan stock_outs.
- suppliers 1:M purchase_orders.
- purchase_orders 1:M purchase_order_items.
- items 1:M purchase_order_items.
- locations 1:M stock_opnames.
- stock_opnames 1:M stock_opname_items.
- items 1:M stock_opname_items.
- users 1:M activity_logs.

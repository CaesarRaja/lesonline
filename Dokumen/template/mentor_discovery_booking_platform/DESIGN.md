---
name: Mentor Discovery & Booking Platform
colors:
  surface: '#faf8ff'
  surface-dim: '#d9d9e5'
  surface-bright: '#faf8ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f3fe'
  surface-container: '#ededf9'
  surface-container-high: '#e7e7f3'
  surface-container-highest: '#e1e2ed'
  on-surface: '#191b23'
  on-surface-variant: '#434655'
  inverse-surface: '#2e3039'
  inverse-on-surface: '#f0f0fb'
  outline: '#737686'
  outline-variant: '#c3c6d7'
  surface-tint: '#0053db'
  primary: '#004ac6'
  on-primary: '#ffffff'
  primary-container: '#2563eb'
  on-primary-container: '#eeefff'
  inverse-primary: '#b4c5ff'
  secondary: '#795900'
  on-secondary: '#ffffff'
  secondary-container: '#ffc329'
  on-secondary-container: '#6f5100'
  tertiary: '#943700'
  on-tertiary: '#ffffff'
  tertiary-container: '#bc4800'
  on-tertiary-container: '#ffede6'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe1ff'
  primary-fixed-dim: '#b4c5ff'
  on-primary-fixed: '#00174b'
  on-primary-fixed-variant: '#003ea8'
  secondary-fixed: '#ffdf9f'
  secondary-fixed-dim: '#f9bd22'
  on-secondary-fixed: '#261a00'
  on-secondary-fixed-variant: '#5c4300'
  tertiary-fixed: '#ffdbcd'
  tertiary-fixed-dim: '#ffb596'
  on-tertiary-fixed: '#360f00'
  on-tertiary-fixed-variant: '#7d2d00'
  background: '#faf8ff'
  on-background: '#191b23'
  surface-variant: '#e1e2ed'
  bg-base: '#f9fafb'
  text-main: '#0f172a'
  text-muted: '#6b7280'
  success-bg: '#ecfdf5'
  success-text: '#047857'
  pending-bg: '#fffbeb'
  pending-text: '#b45309'
typography:
  display-logo:
    fontFamily: Plus Jakarta Sans
    fontSize: 20px
    fontWeight: '800'
    lineHeight: 28px
    letterSpacing: -0.025em
  headline-card:
    fontFamily: Plus Jakarta Sans
    fontSize: 16px
    fontWeight: '700'
    lineHeight: 24px
  body-main:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-bold:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
  price-display:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '900'
    lineHeight: 28px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  sidebar-width: 16rem
  container-max: 80rem
  gutter: 1.5rem
  margin-mobile: 1rem
  margin-desktop: 2rem
---

Berikut adalah isi file `design.md` resmi yang terstruktur menggunakan pendekatan **Stitch Style Architectural Design**. Dokumen ini berfokus pada cetak biru arsitektur antarmuka, pemetaan token Tailwind, dan struktur komponen modular agar mudah diimplementasikan oleh tim *frontend*.

---

# 📐 System Design UI Specification (`design.md`)

**Project:** Platform Les Online — Real-Time Mentor Discovery & Booking

**Design Standard:** Google Stitch Component Framework

**Stack Integration:** Tailwind CSS v3+ & Laravel Breeze (Blade)

---

## 1. Architectural Layout Shell (`Stitch-Shell`)

Spesifikasi tata letak utama aplikasi untuk memisahkan area navigasi global dengan area konten dinamis (*canvas*).

### 1.1. Public Layout (Landing & Discovery)

Struktur satu kolom terpusat dengan area navigasi atas yang statis.

* **Viewport Constraints:** `min-h-screen flex flex-col bg-gray-50`
* **Content Max-Width:** `max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full`

### 1.2. Internal Dashboard Layout (Student & Mentor)

Struktur dua kolom menggunakan sistem *Fixed Sidebar* di sisi kiri untuk navigasi menu internal.

* **Layout Wrapper:** `flex flex-row min-h-screen bg-gray-50`
* **Left Pane (Sidebar):** `w-64 bg-white border-r border-gray-100 fixed h-screen top-0 left-0 z-40`
* **Right Pane (Canvas Content):** `flex-1 ml-64 p-6 md:p-8 min-h-screen overflow-y-auto`

---

## 2. Global Design Tokens

Sistem penamaan token variabel untuk memastikan konsistensi warna, tipografi, dan elevasi di seluruh komponen sistem.

### 2.1. Color Tokens (Palet Biru - Kuning - Putih)

```css
/* Pemetaan Utility Classes Tailwind CSS ke Token Desain Resmi */
--color-primary:      theme('colors.blue.600');    /* bg-blue-600 | text-blue-600 */
--color-primary-dark: theme('colors.blue.700');    /* hover:bg-blue-700 */
--color-accent:       theme('colors.amber.400');   /* bg-amber-400 | text-amber-400 */
--color-accent-dark:  theme('colors.amber.500');   /* hover:bg-amber-500 */
--color-bg-base:      theme('colors.gray.50');     /* bg-gray-50 */
--color-bg-surface:   theme('colors.white');       /* bg-white */
--color-text-main:    theme('colors.slate.900');   /* text-slate-900 */
--color-text-muted:   theme('colors.gray.500');    /* text-gray-500 */

```

### 2.2. Shape & Elevation Tokens

* `shape-radius-button`: `rounded-xl` (12px)
* `shape-radius-card`: `rounded-2xl` (16px)
* `elevation-idle`: `shadow-sm border border-gray-200`
* `elevation-hover`: `shadow-md transition duration-200 ease-in-out`

---

## 3. Atomized Component Library (`Stitch-Blocks`)

### Block A: Navigation Bar (`Stitch-Nav`)

Komponen navigasi global atas untuk autentikasi dan identitas brand.

* **Base Utility:** `w-full bg-white border-b border-gray-100 px-6 py-4 flex justify-between items-center fixed top-0 z-50`
* **Brand Slot (Kiri):** Teks logo tebal `text-xl font-extrabold text-blue-600 tracking-tight`
* **Action Slot (Kanan):** * Link Login: `text-sm font-semibold text-blue-600 hover:text-blue-700 px-4 py-2`
* Button Register: `text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-xl transition`



### Block B: Search Unit (`Stitch-Hero-Search`)

Komponen pencarian terintegrasi pada halaman penemuan mentor.

* **Input Element:** `w-full max-w-2xl pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 text-slate-900 placeholder-gray-400`
* **Submit Button:** `bg-amber-400 hover:bg-amber-500 text-slate-900 font-bold px-6 py-3.5 rounded-xl text-sm transition tracking-wide shadow-sm`

### Block C: Mentor Discovery Card (`Stitch-Card-Mentor`)

Komponen modular berstruktur *Grid* untuk menampilkan data profil mentor publik.

* **Grid Wrapper:** `grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full`
* **Card Container:** `bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200`
* **Header Slot (Profile Info):** Flex container `flex items-center space-x-4`
* *Avatar:* `w-14 h-14 rounded-full object-cover border-2 border-blue-50`
* *Text Group:* Nama (`text-base font-bold text-slate-900`), Kategori Keahlian (`text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full`)


* **Body Slot (Rating & Bio):** * *Rating Container:* `flex items-center space-x-1 mt-3 text-sm text-gray-600`
* *Icon:* `w-4 h-4 text-amber-400 fill-current`


* **Footer Slot (Pricing & CTA):** `flex items-center justify-between mt-5 pt-4 border-t border-gray-100`
* *Price Text:* `text-lg font-black text-slate-900`
* *Button Action:* `bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition`



---

## 4. Component States & Contextual Badges (`Stitch-Badges`)

Sistem pewarnaan berbasis konteks status (*soft-badges system*) untuk menjaga kejelasan informasi visual.

### 4.1. Success / Verified State (Pembayaran Sukses / Kelas Selesai)

* **Tailwind Code:** `bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full text-xs font-semibold tracking-wide flex items-center w-fit`

### 4.2. Pending / Idle State (Menunggu Pembayaran / Slot Jadwal Kosong)

* **Tailwind Code:** `bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1 rounded-full text-xs font-semibold tracking-wide flex items-center w-fit`

### 4.3. Disabled / Booked State (Slot Terpesan via WebSocket)

Komponen ini diubah secara dinamis oleh frontend ketika menerima *broadcast event* dari Laravel Reverb.

* **Tailwind Code:** `bg-gray-100 text-gray-400 border border-gray-200 px-3 py-1 rounded-full text-xs font-medium cursor-not-allowed pointer-events-none line-through w-fit`

---

## 5. Platform Specific Call-to-Actions (CTA Spec)

### 5.1. Primary Action: "Masuk Kelas Virtual"

* **Context:** Dasbor Akun Student (Akses cepat menuju link eksternal Zoom/Meet).
* **Tailwind Formula:** `w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-center text-sm transition shadow-sm shadow-blue-200 focus:ring-4 focus:ring-blue-100 block`

### 5.2. Secondary Action: "Cetak Laporan Pendapatan PDF"

* **Context:** Dasbor Akun Mentor (Ekspor data analitik finansial).
* **Tailwind Formula:** `inline-flex items-center justify-center bg-white border border-blue-600 text-blue-600 hover:bg-blue-50 font-medium px-4 py-2 rounded-xl text-sm transition focus:ring-4 focus:ring-blue-50`

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

# Servify 🍽️

Servify, restoranlar, kafeler ve benzeri yiyecek-içecek işletmeleri için özel olarak tasarlanmış, kapsamlı bir Restoran/Kafe Yönetim ve Satış Noktası (POS) sistemidir. İşletmelerin günlük operasyonlarını kolaylaştırmak, verimliliği artırmak ve müşteri deneyimini geliştirmek amacıyla geliştirilmiştir.

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
  <img src="https://img.shields.io/badge/PHP-8.2+-8892BF.svg?logo=php" alt="PHP Version">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20.svg?logo=laravel" alt="Laravel Version">
  <img src="https://img.shields.io/badge/TailwindCSS-v4-06B6D4.svg?logo=tailwindcss" alt="TailwindCSS Version">
  <img src="https://img.shields.io/badge/Vite-7.x-646CFF.svg?logo=vite" alt="Vite Version">
</p>

## Hakkında 🚀

Servify, yiyecek-içecek sektöründeki işletmelerin sipariş almaktan envanter yönetimine, masa düzenlemesinden personel vardiyalarına kadar tüm süreçlerini tek bir platformdan yönetmelerini sağlayan modern bir web uygulamasıdır. Kullanıcı dostu arayüzü ve güçlü arka plan mimarisi ile işletmelerin operasyonel yükünü azaltır ve daha stratejik kararlar almalarına olanak tanır.

## Temel Özellikler ✨

*   **Kapsamlı Satış Noktası (POS) Sistemi:** Hızlı ve kolay sipariş girişi, ürün varyasyonları ve indirim yönetimi.
*   **Masa ve Rezervasyon Yönetimi:** Masa durumlarını takip etme, rezervasyonları yönetme ve masa atamaları yapma.
*   **Ürün ve Kategori Yönetimi:** Menü öğelerini kategorilere ayırma, fiyatları ve açıklamaları güncelleme.
*   **Malzeme ve Tarif Yönetimi:** Ürünlerin tariflerini oluşturma, kullanılan malzemeleri takip etme ve maliyet analizi yapma.
*   **Envanter Takibi:** Malzeme stoklarını izleme, otomatik stok uyarıları ve envanter hareketlerini kaydetme.
*   **Mutfak Ekran Sistemi (KDS):** Siparişleri mutfağa anlık olarak iletme, sipariş durumlarını takip etme ve hazırlık süreçlerini optimize etme.
*   **Ödeme ve Gider Takibi:** Çeşitli ödeme yöntemlerini destekleme, giderleri kaydetme ve finansal raporlama.
*   **Vardiya Yönetimi:** Personel vardiyalarını planlama ve takip etme.
*   **Kullanıcı ve Rol Yönetimi:** Yönetici, garson, aşçı gibi farklı rollerle kullanıcı yetkilendirme ve erişim kontrolü.
*   **Yazıcı Entegrasyonu:** Sipariş fişleri, mutfak biletleri ve faturalar için yazıcı ayarları ve test imkanı.
*   **Profil ve Ayar Yönetimi:** Kullanıcı profillerini ve genel uygulama ayarlarını kişiselleştirme.
*   **Çoklu Dil Desteği:** Türkçe dil desteği ile yerel kullanıma uygunluk.

## Teknoloji Yığını 🛠️

Servify projesi, modern web geliştirme standartlarına uygun olarak aşağıdaki teknolojilerle geliştirilmiştir:

*   **Backend:**
    *   PHP 8.2+
    *   Laravel 12.x
*   **Frontend:**
    *   HTML5
    *   CSS3 (TailwindCSS ile)
    *   JavaScript (Vite ile derlenmiş)
    *   Axios (HTTP istekleri için)
*   **Veritabanı:**
    *   MySQL (veya Laravel tarafından desteklenen diğer ilişkisel veritabanları)
*   **Paket Yöneticileri:**
    *   Composer (PHP bağımlılıkları için)
    *   npm (JavaScript bağımlılıkları için)

## Kurulum ⚙️

Bu projeyi yerel makinenizde çalıştırmak için aşağıdaki adımları takip edin.

### Önkoşullar

Başlamadan önce sisteminizde aşağıdakilerin kurulu olduğundan emin olun:

*   PHP >= 8.2
*   Composer
*   Node.js & npm (veya Yarn)
*   Bir veritabanı sunucusu (MySQL, PostgreSQL vb.)

### Adımlar

1.  **Depoyu Klonlayın:**

    ```bash
    git clone https://github.com/ethemdemirkaya/Servify.git
    cd Servify
    ```

2.  **Ortam Dosyasını Yapılandırın:**

    `.env.example` dosyasını `cp .env.example .env` komutu ile kopyalayın ve veritabanı bağlantı bilgileriniz ile diğer ortam değişkenlerini düzenleyin.

    ```bash
    cp .env.example .env
    ```

    `.env` dosyasını açın ve aşağıdaki satırları kendi veritabanı bilgilerinizle güncelleyin:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=servify_db # Veritabanı adınızı buraya girin
    DB_USERNAME=root      # Veritabanı kullanıcı adınızı buraya girin
    DB_PASSWORD=          # Veritabanı parolanızı buraya girin
    ```

3.  **PHP Bağımlılıklarını Yükleyin:**

    ```bash
    composer install
    ```

4.  **Uygulama Anahtarını Oluşturun:**

    ```bash
    php artisan key:generate
    ```

5.  **Veritabanı Migrasyonlarını Çalıştırın ve Verileri Doldurun (Seed):**

    Bu komut, veritabanı tablolarını oluşturacak ve örnek verilerle dolduracaktır.

    ```bash
    php artisan migrate --seed
    ```

    *Not: `--seed` komutu, `database/seeders` dizinindeki verileri veritabanına ekler. Bu, başlangıç için kategori, ürün ve kullanıcı gibi temel verileri sağlar.*

6.  **JavaScript Bağımlılıklarını Yükleyin ve Frontend Varlıklarını Derleyin:**

    ```bash
    npm install
    npm run build
    ```

    *Geliştirme sırasında otomatik yenileme için `npm run dev` kullanabilirsiniz.*

## Kullanım 🚀

Uygulamayı yerel geliştirme sunucusunda başlatmak için aşağıdaki komutu kullanın:

```bash
php artisan serve
```

Vite geliştirme sunucusunu başlatmak için ayrı bir terminalde:

```bash
npm run dev
```

Tarayıcınızda `http://127.0.0.1:8000` adresine giderek uygulamaya erişebilirsiniz.

### Varsayılan Giriş Bilgileri

Eğer `php artisan migrate --seed` komutunu çalıştırdıysanız, varsayılan bir yönetici kullanıcısı oluşturulmuş olabilir. Genellikle bu bilgiler `database/seeders/UserSeeder.php` dosyasında bulunur. Örnek:

*   **E-posta:** `admin@example.com` (veya seeder dosyasında belirtilen başka bir e-posta)
*   **Parola:** `password` (veya seeder dosyasında belirtilen başka bir parola)

Lütfen bu bilgileri kendi seeder dosyanızdan kontrol edin veya yeni bir kullanıcı oluşturun.

## Proje Yapısı 📂

Projenin ana dizin yapısı aşağıdaki gibidir:

```
.
├── app/                  # Uygulama kaynak kodları (Modeller, Kontrolcüler, Middleware vb.)
│   ├── Http/             # HTTP katmanı (Kontrolcüler, Middleware)
│   ├── Models/           # Eloquent modelleri
│   └── Providers/        # Servis sağlayıcıları
├── bootstrap/            # Uygulama başlatma dosyaları
├── config/               # Yapılandırma dosyaları
├── database/             # Veritabanı migrasyonları, seed'ler ve factory'ler
│   ├── migrations/       # Veritabanı tablo tanımları
│   └── seeders/          # Örnek veri doldurucular
├── public/               # Genel erişilebilir dosyalar (CSS, JS, resimler, index.php)
│   ├── assets/           # Frontend varlıkları (CSS, JS, resimler)
├── resources/            # Frontend kaynakları (Blade şablonları, Sass/Less, JS)
│   ├── css/              # TailwindCSS ve diğer stil dosyaları
│   └── js/               # JavaScript dosyaları
├── routes/               # Uygulama rotaları (web, api, console)
├── storage/              # Depolama alanı (loglar, cache, oturumlar, kullanıcı yüklemeleri)
├── tests/                # Otomatik testler
├── vendor/               # Composer tarafından yönetilen PHP bağımlılıkları
├── .env.example          # Ortam değişkenleri için örnek dosya
├── composer.json         # PHP bağımlılıkları ve proje meta verileri
├── package.json          # JavaScript bağımlılıkları ve npm scriptleri
└── vite.config.js        # Vite yapılandırma dosyası
```

## Katkıda Bulunma 🤝

Servify projesine katkıda bulunmak isterseniz, lütfen aşağıdaki adımları izleyin:

1.  Depoyu forklayın.
2.  Yeni bir özellik veya hata düzeltmesi için yeni bir dal (`git checkout -b feature/AmazingFeature`) oluşturun.
3.  Değişikliklerinizi yapın ve commit'leyin (`git commit -m 'Add some AmazingFeature'`).
4.  Dalınızı uzak depoya itin (`git push origin feature/AmazingFeature`).
5.  Bir Pull Request (Çekme İsteği) açın.

## Lisans 📄

Bu proje [MIT Lisansı](https://opensource.org/licenses/MIT) altında lisanslanmıştır. Daha fazla bilgi için `LICENSE` dosyasına bakınız.

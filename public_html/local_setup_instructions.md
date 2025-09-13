# دليل إعداد المشروع على البيئة المحلية

لتشغيل المشروع الذي تم تعديله على بيئتك المحلية، يرجى اتباع الخطوات التالية بعناية. تأكد من أن لديك المتطلبات الأساسية مثبتة على جهازك.

## المتطلبات الأساسية

قبل البدء، تأكد من تثبيت البرامج التالية على جهازك:

1.  **PHP 8.2 أو أحدث**: يمكنك التحقق من إصدار PHP الخاص بك عن طريق فتح سطر الأوامر (Terminal/CMD) وكتابة `php -v`.
    *   **لمستخدمي Ubuntu/Debian**: يمكنك إضافة مستودع Ondrej PPA وتثبيت PHP 8.2:
        ```bash
        sudo apt update
        sudo apt install -y software-properties-common
        sudo add-apt-repository ppa:ondrej/php -y
        sudo apt update
        sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-mysql php8.2-xml php8.2-curl php8.2-gd php8.2-mbstring php8.2-zip php8.2-bcmath php8.2-intl
        ```
    *   **لمستخدمي macOS**: يمكنك استخدام Homebrew:
        ```bash
        brew update
        brew install php@8.2
        brew link php@8.2 --force --overwrite
        ```
    *   **لمستخدمي Windows**: يفضل استخدام Laragon أو XAMPP أو WAMP Server، والتي تأتي مع PHP مثبتًا مسبقًا. تأكد من اختيار إصدار يدعم PHP 8.2 أو أحدث.

2.  **Composer**: مدير حزم PHP. يمكنك تنزيله من [الموقع الرسمي لـ Composer](https://getcomposer.org/download/).

3.  **قاعدة بيانات (MySQL أو MariaDB)**: تأكد من تثبيت خادم قاعدة بيانات مثل MySQL أو MariaDB وتشغيله.

4.  **Node.js و npm (اختياري، لتطوير الواجهة الأمامية)**: إذا كنت تخطط لتعديل ملفات الواجهة الأمامية (CSS/JS)، فستحتاج إلى Node.js و npm.

## خطوات إعداد المشروع

1.  **استخراج ملفات المشروع**: 
    *   قم بتنزيل ملف المشروع المضغوط الذي أرسلته لك (`educational_updated_YYYYMMDD_HHMMSS.tar.gz`).
    *   استخرج محتويات هذا الملف إلى مجلد على جهازك (على سبيل المثال، `C:\xampp\htdocs\educational` على Windows أو `/var/www/html/educational` على Linux/macOS).

2.  **فتح سطر الأوامر**: 
    *   انتقل إلى مجلد المشروع المستخرج باستخدام سطر الأوامر:
        ```bash
        cd path/to/your/project/educational
        ```

3.  **تثبيت تبعيات Composer**: 
    *   قم بتثبيت جميع تبعيات PHP المطلوبة للمشروع:
        ```bash
        composer install
        ```

4.  **إعداد ملف البيئة (.env)**:
    *   قم بإنشاء نسخة من ملف `.env.example` وقم بتسميتها `.env`:
        ```bash
        cp .env.example .env
        ```
    *   افتح ملف `.env` وقم بتحديث معلومات قاعدة البيانات الخاصة بك:
        ```dotenv
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=your_database_name # اسم قاعدة البيانات التي ستنشئها
        DB_USERNAME=your_database_user # اسم مستخدم قاعدة البيانات
        DB_PASSWORD=your_database_password # كلمة مرور مستخدم قاعدة البيانات
        ```
    *   تأكد من إنشاء قاعدة بيانات جديدة بالاسم الذي حددته في `DB_DATABASE` على خادم قاعدة البيانات الخاص بك.

5.  **توليد مفتاح التطبيق**: 
    *   قم بتوليد مفتاح تشفير للتطبيق (ضروري لعمل Laravel):
        ```bash
        php artisan key:generate
        ```

6.  **تشغيل الهجرات (Migrations) وتعبئة البيانات (Seeding)**:
    *   قم بتشغيل الهجرات لإنشاء جداول قاعدة البيانات وتعبئتها بالبيانات الأولية (بما في ذلك ملف الموضوعات الثابت):
        ```bash
        php artisan migrate:fresh --seed
        ```
        *ملاحظة*: الأمر `migrate:fresh` سيقوم بحذف جميع الجداول الموجودة في قاعدة البيانات وإعادة إنشائها من جديد. إذا كانت لديك بيانات مهمة، يرجى عمل نسخة احتياطية أولاً.

7.  **ربط مجلد التخزين (Storage Link)**:
    *   قم بإنشاء رابط رمزي لمجلد التخزين، وهو ضروري للوصول إلى الملفات المرفوعة (مثل ملفات المكتبة وملفات المستخدمين):
        ```bash
        php artisan storage:link
        ```

8.  **تشغيل خادم التطوير**: 
    *   يمكنك الآن تشغيل خادم Laravel المدمج:
        ```bash
        php artisan serve
        ```
    *   سيتم تشغيل التطبيق عادةً على `http://127.0.0.1:8000`. افتح هذا العنوان في متصفح الويب الخاص بك.

## ملاحظات إضافية

*   **مشكلة `question_type`**: الخطأ الذي واجهته في بيئة Sandbox كان بسبب عدم توافق إصدار PHP. بعد ترقية PHP وتشغيل `migrate:fresh --seed` على بيئتك المحلية، يجب أن يتم حل هذه المشكلة تلقائيًا لأن الهجرة التي تضيف العمود `question_type` ستعمل بشكل صحيح قبل الهجرة التي تحاول تحديثه.

*   **Docker (موصى به)**: إذا كنت تواجه صعوبة في إعداد البيئة المحلية أو ترغب في بيئة تطوير متسقة، فإن استخدام Docker هو الحل الأمثل. لقد قمت بتضمين ملف `Dockerfile` في المشروع. يمكنك بناء صورة Docker وتشغيل المشروع داخل حاوية Docker. هذا يضمن أن المشروع سيعمل بنفس الطريقة بغض النظر عن نظام التشغيل الخاص بك.
    *   **لبناء صورة Docker**: 
        ```bash
        docker build -t educational-app .
        ```
    *   **لتشغيل حاوية Docker**: 
        ```bash
        docker run -p 8000:8000 educational-app
        ```
    *   **للدخول إلى الحاوية لتشغيل الأوامر (مثل الهجرات)**:
        ```bash
        docker exec -it <container_id_or_name> bash
        ```
        (ستحتاج إلى تشغيل `php artisan migrate:fresh --seed` و `php artisan storage:link` داخل الحاوية بعد الدخول إليها).

آمل أن يكون هذا الدليل مفيدًا لك. إذا واجهت أي مشاكل أخرى، فلا تتردد في السؤال.


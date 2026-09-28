# Contact / Service Request — локальная доработка

## Результат

Новая компактная Contact: hero Request Appliance Repair, звонок и anchor #service-request, существующий TrustStripSection с четырьмя согласованными преимуществами, две колонки на desktop, форма первой на mobile, информационная карточка и три шага What Happens Next. Восемь полей, optional email/brand/model, состояния Sending/error/success, предупреждение о том, что appointment ещё не подтверждён. Единый ScheduleFormSection используется также в Electronic Repair. Upload не добавлен; данные формы и обработчик выделены так, чтобы его можно было добавить отдельно.

Title, description, canonical https://tsrepairservice.com/contact, Open Graph, один H1. Query service не попадает в canonical. Нет запретов индексации и FAQ schema.

## CTA и общий интерфейс

Book Service, Book Service Online, BOOK ONLINE заменены на Request Service на девяти service pages, homepage и Extra. Кнопка homepage теперь одна ссылка без вложенной интерактивной кнопки. Пользовательские изменения отступов и AOS в MainSection сохранены.

Все девять service pages передают /contact?service=<slug>#service-request:
refrigerator, washer, dryer, oven-stove, dishwasher, microwave, range-hood, trash-compactor, electronic.

Старый #schedule остаётся совместимым. Desktop/mobile Contact navigation работает с query parameter. Телефон и список услуг перенесены из Layout в config/repair.php; те же значения доступны форме, навигации и серверу. Footer: Locally, текущий год, кликабельный телефон. В общем верхнем баннере неподтверждённое FREE estimate заменено на приглашение позвонить.

## Сервер и безопасность

StoreRepairRequest: обязательные name/phone/location/serviceId/description; строки и пределы длины; проверка email; телефон допускает несколько форматов; числовой appliance ID только из config allowlist; trim входных строк. Описание до 5000 символов. Query service выбирает только известный slug, остальные значения и массивы игнорируются.

Восстановлена стандартная Laravel web CSRF-защита для POST /contact. Inertia/Axios передаёт XSRF-cookie. Honeypot website отклоняется при заполнении; middleware throttle ограничивает пять попыток на IP за десять минут, включая невалидные. Лимит возвращает понятную Inertia-совместимую ошибку и Retry-After. CAPTCHA не добавлена.

Contact и RepairRequest сохраняются транзакционно. Новые location/brand/model сохраняются отдельно, description расширен до TEXT. Миграция применена только к локальной PostgreSQL-базе. При сбое отправки транзакция откатывается, подтверждения нет, введённые данные остаются. Email-шаблон экранирует пользовательский текст и содержит все новые поля и название техники. Получатель перенесён в config с прежним значением и optional SERVICE_REQUEST_EMAIL.

Удалён публичный GET /send-request-received-email — ненужный тестовый обход основного обработчика почты. Теперь он возвращает 404.

После успеха выполняется redirect на GET /contact с flash requestReceived. Форма очищается только при этом подтверждении. Refresh не повторяет POST. Ошибки у полей, aria-invalid/describedby, required/aria-required, фокус первого ошибочного поля, общий aria-live и клавиатурный focus сохранены/добавлены.

## Проверки

- Targeted Laravel: 11 passed, 294 assertions. GET, query allowlist (включая массив), обязательные поля, email/phone, City и ZIP/ZIP+4, ID allowlist, длинное описание, optional поля, сохранение и безопасная почта, honeypot, rate limiting, Inertia errors/flash, mail failure rollback, XSS escaping, CSRF 419, legacy routes/CTA.
- Все письма в PHPUnit заменены Mail::fake/mock; основной mailer тестов array, БД SQLite :memory:.
- Полный php artisan test: 33 passed, 7 skipped, 2 failed. AuthenticationTest и RegistrationTest ожидают dashboard, которого нет и в исходном HEAD; эти тесты и функциональность авторизации не менялись.
- Laravel Pint по затронутым PHP-файлам выполнен.
- npm run build -- --outDir /tmp/tsrepair-contact-final: успешно (6.98 s). Пользовательский public/build/manifest.json не перезаписан.
- Frontend lint/typecheck в package.json отсутствуют.
- php artisan route:list --path=contact -v: GET create и POST store с web и throttle:service-requests.
- git diff --check: успешно.
- Browser 390/768/1440: без horizontal overflow, правильный mobile order, все изображения загружены, один H1 и description, canonical, preselection, переход CTA.
- Реальная browser-проверка только на изолированной SQLite /tmp/tsrepair-contact-preview.sqlite с MAIL_MAILER=array: validation errors, сохранение ввода, focus, disabled Sending, success и refresh GET. Реальные письма не отправлялись, production не вызывался.
- Production bundle проверен отдельно на localhost:8012: console errors [] и JS exceptions [].
- В обычном Vite preview осталась прежняя ошибка HMR WebSocket из-за жёстко заданного LAN-адреса в vite.config.js. Несвязанный конфиг не менялся.

## Что не проверялось / данные владельца

Реальная доставка email и production не тестировались по заданию. Часы работы и SMS не указаны в авторитетной конфигурации, поэтому не добавлены; нужны подтверждения владельца, если их требуется показывать. При необходимости уточнить получателя заявок (сохранён существующий). Analytics/conversion tracking в проекте не найден; внешняя платформа не подключалась.

Для будущей публикации потребуется выполнить новую миграцию и пересобрать frontend на сервере. Сейчас нет коммита, push или deploy.

## Изменённые файлы

- `app/Http/Controllers/RepairRequestController.php`
- `app/Http/Middleware/HandleInertiaRequests.php`
- `app/Mail/WebFormRequestReceivedEmail.php`
- `app/Models/RepairRequest.php`
- `app/Providers/AppServiceProvider.php`
- `bootstrap/app.php`
- `resources/js/Components/Custom/MainSection.vue`
- `resources/js/Components/Custom/ScheduleFormSection.vue`
- `resources/js/Layouts/Layout.vue`
- `resources/js/Pages/Contact.vue`
- `resources/js/Pages/Extra.vue`
- `resources/js/Pages/Services/DishwasherRepairService.vue`
- `resources/js/Pages/Services/DryerRepairService.vue`
- `resources/js/Pages/Services/ElectronicRepairService.vue`
- `resources/js/Pages/Services/MicrowaveRepairService.vue`
- `resources/js/Pages/Services/OvenRepairService.vue`
- `resources/js/Pages/Services/RangeHoodRepairService.vue`
- `resources/js/Pages/Services/RefrigeratorRepairService.vue`
- `resources/js/Pages/Services/TrashCompactorRepairService.vue`
- `resources/js/Pages/Services/WasherRepairService.vue`
- `resources/views/emails/message-received.blade.php`
- `routes/web.php`

## Созданные файлы

- `app/Http/Requests/StoreRepairRequest.php`
- `config/repair.php`
- `database/migrations/2026_09_22_000000_add_details_to_repair_requests_table.php`
- `tests/Feature/ServiceRequestTest.php`
- `docs/contact-page.md`

## git diff --stat (весь рабочий каталог)

Включает существовавшие до задачи изменения .DS_Store, composer.lock, public/build/manifest.json, public/hot и пользовательскую часть MainSection. Эти изменения сохранены. Неотслеживаемые новые файлы в обычный diff --stat не входят; они перечислены выше.

```text
 .DS_Store                                          |  Bin 6148 -> 6148 bytes
 app/Http/Controllers/RepairRequestController.php   |   69 +-
 app/Http/Middleware/HandleInertiaRequests.php      |    8 +-
 app/Mail/WebFormRequestReceivedEmail.php           |   18 +-
 app/Models/RepairRequest.php                       |    8 +-
 app/Providers/AppServiceProvider.php               |   14 +-
 bootstrap/app.php                                  |    9 +-
 composer.lock                                      | 2823 ++++++++++++++------
 public/build/manifest.json                         |  388 +--
 public/hot                                         |    2 +-
 resources/js/Components/Custom/MainSection.vue     |   10 +-
 .../js/Components/Custom/ScheduleFormSection.vue   |  413 +--
 resources/js/Layouts/Layout.vue                    |   75 +-
 resources/js/Pages/Contact.vue                     |  102 +-
 resources/js/Pages/Extra.vue                       |    6 +-
 .../js/Pages/Services/DishwasherRepairService.vue  |    4 +-
 resources/js/Pages/Services/DryerRepairService.vue |    4 +-
 .../js/Pages/Services/ElectronicRepairService.vue  |    7 +-
 .../js/Pages/Services/MicrowaveRepairService.vue   |    4 +-
 resources/js/Pages/Services/OvenRepairService.vue  |    4 +-
 .../js/Pages/Services/RangeHoodRepairService.vue   |    4 +-
 .../Pages/Services/RefrigeratorRepairService.vue   |   10 +-
 .../Pages/Services/TrashCompactorRepairService.vue |    4 +-
 .../js/Pages/Services/WasherRepairService.vue      |    4 +-
 resources/views/emails/message-received.blade.php  |   18 +-
 routes/web.php                                     |   14 +-
 26 files changed, 2431 insertions(+), 1591 deletions(-)
```

## Краткое резюме diff

Общий справочник услуг и телефона; новая Contact; переработан общий компонент формы; серверная валидация, CSRF и throttle; схема данных и почтовое уведомление; связанные CTA и footer; 11 тестов. Посторонние файлы не откатывались.

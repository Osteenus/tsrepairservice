# Каталог рабочих фотографий и обновление Oven & Stove

## Объём просмотра

Рекурсивно обнаружены и прочитаны 900 изображений из `Appliances photos`: 537 JPG, 361 JPEG, один PNG и один WebP. Все 900 просмотрены на 17 обзорных листах; для финального выбора использованы увеличенные превью. Это визуальный отбор, а не автоматическая гарантия отсутствия персональных данных на каждом оригинале.

Оригиналы открывались только для чтения. В проект не копировалась коллекция целиком. Для первоначально выбранных трёх реальных фотографий SHA-256 до и после обработки совпадает: см. [web-assets.json](web-assets.json). Три веб-изображения — 1200×900, Residential — 756×567 WebP, без EXIF/GPS/XMP, без увеличения исходного разрешения.

## 32 лучших кандидата

Полные поля, включая относительные пути, категории, назначение, качество, privacy concerns и необходимое редактирование: [candidates.csv](candidates.csv).

Контактные листы для внутреннего просмотра, не для публикации на сайте:

- [Лист 1](contact-sheet-1.jpg)
- [Лист 2](contact-sheet-2.jpg)
- [Лист 3](contact-sheet-3.jpg)

Статус `reference` означает, что исходный кадр пока не следует публиковать. Маленькие снимки 756×1008 подходят для небольших карточек; не увеличивать их до hero. Commercial/residential обозначает визуальную оценку обстановки, а не доказательство оказанной услуги. Крупная домашняя плита не классифицируется как ресторанное оборудование только по размеру или названию Professional.

Из отбора исключены портреты клиентов/детей, документы и крупные шильдики, кадры с выраженными отражениями людей, сильно размытые и тёмные снимки. Например, `IMG_0851.JPG` и `IMG_8252.jpeg` имеют отражения фотографа; `IMG_8072.jpeg` преимущественно показывает личные продукты; кадр `photo-534…` с человеком у техники не принят за фотографию техника за работой. Кадры с полезными техническими деталями, но с бытовым беспорядком или возможными этикетками сохранены лишь как условные кандидаты с явными ограничениями в CSV.

## Фотографии новой страницы

| Место | Источник | Веб-файл | Обработка |
| --- | --- | --- | --- |
| Hero, banner, карточка услуги, OG | `IMG_5960.jpeg` | `public/images/services/oven-stove-repair-moorpark.webp` | Уменьшение, WebP; бытовая кухня, естественный логотип производителя |
| Common Problems | `IMG_6742.jpeg` | `public/images/services/oven-stove-diagnostics.webp` | Сломанный нагреватель; уменьшение, WebP |
| Residential | `IMG_7789.JPG` | `public/images/services/oven-stove-residential-cooktop.webp` | Реальная газовая варочная панель; crop 4:3, 756×567 без увеличения |
| Commercial | Генерация | `public/images/services/oven-stove-commercial-repair.webp` | Согласованное 4:3, WebP; подпись Illustrative image |

Подходящего реального кадра ремонта cooking equipment в ресторанной кухне не найдено. `IMG_9393.jpeg` показывает коммерческие холодильники и беспорядок: только reference для будущей страницы холодильников. Residential после отзыва владельца заменён на реальный кадр газовой варочной панели IMG_7789.JPG. Генерации для этой карточки отклонены. Исходный кадр IMG_9368.jpeg остаётся кандидатом в каталоге, но больше не используется на странице.

Генерация выполнена встроенным инструментом imagegen (generate, без reference). Проверены руки, инструмент, отсутствие надписей и узнаваемых лиц. Исходная генерация сохранена инструментом в `/Users/osteen/.codex/generated_images/01a0b7dc-7d3e-7ae3-a402-f87cb20ca761/exec-0232f571-3e14-4a18-ad8b-2ae62b45c13c.png`; используемая веб-копия находится в проекте.

### Финальный prompt

Use case: photorealistic-natural. Asset: 4:3 landscape website service card, commercial oven and range repair. Candid realistic editorial photograph in a clean working restaurant kitchen, stainless steel commercial electric range and oven, anonymous technician seen from behind/side with face outside frame, navy plain work shirt. Technician actually using an ordinary screwdriver to remove a side access panel on an unpowered cool range, panel screws and tool held naturally, two realistic hands. Power supply disconnected, no exposed live wires, no flames, no food preparation nearby. Enough restaurant kitchen context: stainless prep table and ventilation hood. Natural soft window light, restrained realistic colors, eye-level medium shot, equipment fills most of frame, no staged posing, no text, no logos, no watermarks, no customers, no identifying documents. Anatomically correct hands and physically credible appliance construction.

## Подтверждения владельца

- Реальные марки и модели commercial cooking equipment. По просьбе владельца добавлен минимальный предварительный список: convection ovens, gas/electric ranges with ovens, countertop gas/electric cooking units. Все позиции требуют согласования модели; это не обещание обслуживания любых ресторанных установок.
- В общей секции Service Area уже присутствует название `San Bernandino Valley`. Уточнить, какая зона подразумевается; географию самовольно не расширяли и общий компонент не меняли.
- Для дальнейшего использования условных кандидатов из CSV: проверить отмеченные отражения/этикетки в полном разрешении и обрезать личные предметы. На текущей странице уже используются отдельные проверенные веб-копии.

## Изменения сайта

Изменены `resources/js/Pages/Services/OvenRepairService.vue`, `resources/js/Layouts/Layout.vue`, `resources/js/app.js`, `routes/web.php`. Удалён `resources/js/Pages/Services/StoveRangeRepairService.vue`. Добавлены четыре WebP и файлы этого каталога. Предсуществующие несвязанные изменения оставлены без изменений.

Навигация, footer, главная и Services используют единый список услуг в Layout. Отдельная карточка Stove & Range удалена из этого списка. Общие компоненты и старые изображения сохранены.

Новый адрес: `/oven-stove-repair-moorpark`. Постоянные 301 redirects: `/services/oven-repair` и `/services/stove-range-repair` → новый адрес.

Добавлены canonical, Open Graph, Service и BreadcrumbList JSON-LD, видимые breadcrumbs. Общий обработчик title сохраняет уже полностью брендированный SEO title без повторного суффикса. Sitemap в проекте не обнаружен. Существующие страницы Refrigerator и Washer не изменены.

FAQ про запах газа сверено с [SoCalGas: Recognize and Respond to a Natural Gas Leak](https://www.socalgas.com/safety/safety-and-prevention/natural-gas-leak).

Никаких коммитов, отправки в GitHub или публикации на сервер не выполнялось.

## Проверки

- `npm run build -- --outDir /tmp/tsrepair-oven-final-build`: успешно, 3222 модуля. Сборка в отдельный временный каталог сохраняет предсуществующий локальный manifest.
- `git diff --check`: без ошибок.
- Laravel route list: новый Inertia route и два RedirectController.
- HTTP: новый адрес 200; оба старых адреса 301 с Location `/oven-stove-repair-moorpark`.
- Chrome: 390, 768, 1440 px; нет горизонтального переполнения, 10 FAQ, открытие/закрытие работают, изображения загружены, исключений JavaScript нет.
- Проверены точный title, canonical, JSON.parse содержимого JSON-LD; разметка удаляется при Inertia-переходе на Services.
- Реальные переходы из карточки Services и dropdown navigation открывают новую страницу; на главной одна объединённая карточка.
- Три WebP имеют размеры 1200×900, Residential — 756×567; EXIF/GPS/XMP отсутствуют.

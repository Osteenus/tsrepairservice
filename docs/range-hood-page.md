# Range Hood Repair — local update

URL `/range-hood-repair-moorpark`; old `/services/range-hood-repair` returns permanent 301. Layout's service list updates navigation, footer, homepage and Services cards.

The page follows updated Microwave/Washer/Oven layouts with 12 sections, 11 FAQ, shared trust strip, brands and service area. Content covers hood motors, controls, lighting, filters, airflow and noise. Commercial models and ventilation work require individual confirmation; restaurant exhaust, rooftop fans, duct cleaning and fire-suppression work are not represented as standard appliance service.

SEO: branded title, description, canonical, Open Graph, Service/BreadcrumbList JSON-LD and visible breadcrumbs. No new shared components.

## Images

Existing site assets reused as separate WebP copies, metadata removed, no upscaling:

- `range-hood-repair-moorpark.webp`: `storage/app/public/img/components/range-hood-1.jpeg`, 400×400 hero and service card.
- `range-hood-ventilation.webp`: `storage/app/public/img/components/services/range-hood-repair-1.jpg`, wide banner.
- `range-hood-diagnostics.webp`: `storage/app/public/img/components/services/range-hood-repair-3.jpeg`, exposed blower on workbench.

Original files remain unchanged. Residential/Commercial now use owner-supplied images converted to WebP (1024×765, quality 85, metadata stripped). Commercial source: Downloads/7bbab255-5840-421a-a5b2-a62fbf37acb7.jpeg. Residential source: Downloads/eb86b1ca-c1ca-4e24-868f-680118824898.jpeg. Web copies: public/images/services/range-hood-commercial-repair.webp and range-hood-residential-repair.webp. Originals unchanged. Specific commercial equipment and any additional range-hood brands still need owner confirmation.

## Validation

Production build to `/tmp/tsrepair-range-hood-build`, `git diff --check`, Laravel route list. Browser checks cover 390/768/1440 px, images, overflow, FAQ open/close, title/canonical/JSON-LD and Services-card navigation. No commit or deployment performed for this local request.

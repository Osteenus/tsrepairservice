# Dishwasher Repair — local update

- URL: `/dishwasher-repair-moorpark`; old `/services/dishwasher-repair` returns 301.
- Design follows the updated Washer/Oven pages and reuses Layout, BrandsSection, ServiceAreaSection, TrustStripSection. No new shared components.
- 12 sections: hero and CTAs, trust, appliance types, symptoms, parts, residential/commercial, brands, reasons to choose, process, service area, FAQ, final CTA.
- Exact branded title, description, canonical, OG, Service/BreadcrumbList JSON-LD. Navigation/footer/home/Services inherit the updated Layout service entry.
- Commercial coverage requires model confirmation; no claim to service every restaurant warewasher. Residential/commercial cards now use two generated repair illustrations; see dishwasher-images.md for prompts and provenance.

## Photos

Originals were read only. Web copies have EXIF/GPS removed; no upscaling during conversion.

| Web asset | Source | Treatment |
| --- | --- | --- |
| `public/images/services/dishwasher-repair-moorpark.webp` | `Appliances photos/IMG_1391.JPG` | Crop x=0,y=100,w=756,h=850 to remove countertop possessions; portrait displayed without stretching/cropping appliance |
| `public/images/services/dishwasher-diagnostics.webp` | Existing `storage/app/public/img/components/services/dishwasher-repair-3.jpg` | Existing photo of pump/hoses/motor, WebP |
| `public/images/services/dishwasher-racks.webp` | Existing `storage/app/public/img/components/services/dishwasher-repair-1.jpg` | Rack detail for wide banner, WebP |

The previous dishwasher page listed Fisher & Paykel. It remains in FAQ copy; the shared carousel has no logo for it and displays the other existing dishwasher brand logos.

## Checks / constraints

Production build to a temporary output directory; no deployment build files overwritten. HTTP new route 200, legacy 301. Browser checks at 390/768/1440: image loading, no horizontal overflow, 11 FAQ with open/close animation, title, canonical and parseable Service/BreadcrumbList. Services card opens the new page.

Owner confirmation still needed for specific commercial models. Shared service-area wording (including existing `San Bernandino Valley`) remains unchanged. No commits or production deployment.

Local preview: `http://127.0.0.1:8000/dishwasher-repair-moorpark`. Started Laravel and Vite locally; `public/hot` points at localhost for this preview because the configured old LAN IP was unavailable. This development file is not a production release change.

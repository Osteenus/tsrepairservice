# Mobile performance optimization

Based on the September 28 PageSpeed report `exrgmwt6u5` (mobile Performance 53, FCP 7.5s, LCP 16.3s).

- Removed duplicate Tailwind CSS generation from Layout; retained a reference for its utilities.
- Removed unused global Fancybox CSS/import (no gallery bindings exist).
- Added dedicated WebP service thumbnails and brand logos; original photos remain intact.
- Service cards and brand logos load lazily; Home hero gets a high-priority image preload.
- Service Area starts loading its map within 200px of the viewport, retaining the map's space and a no-JavaScript link.
- Replaced invalid definition-list markup with a normal list and improved About-link contrast.

Server compression requires an administrator to run `sudo sh deploy/enable-gzip.sh`. The script creates a separate nginx configuration, checks `nginx -t`, then reloads nginx. If validation fails it removes the new file without reloading. Existing configuration is never overwritten. Verify CSS/JS GET responses with `Accept-Encoding: gzip` contain `Content-Encoding: gzip` afterwards.

Build browser and SSR bundles and restart the SSR service as described in ssr-deployment.md. Repeat PageSpeed after deployment; the original score is not a prediction of the new score.

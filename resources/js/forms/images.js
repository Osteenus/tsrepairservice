// Shrinks phone photos in the browser before upload: long side up to 2048 px, JPEG 82%.
// A 4–8 MB photo becomes roughly 0.4–0.8 MB and model/serial labels stay readable.
// Re-encoding also drops EXIF data such as the GPS location of the customer's home.
// If the browser cannot decode a file (for example HEIC outside Safari), the original is used.

export const MAX_SIDE = 2048;
export const QUALITY = 0.82;

async function decode(file) {
    if ('createImageBitmap' in window) {
        try {
            return await createImageBitmap(file, { imageOrientation: 'from-image' });
        } catch {
            // Fall back to an <img> element below.
        }
    }

    const url = URL.createObjectURL(file);
    try {
        const image = new Image();
        image.src = url;
        await image.decode();
        return image;
    } finally {
        URL.revokeObjectURL(url);
    }
}

export async function shrinkImage(file, { maxSide = MAX_SIDE, quality = QUALITY } = {}) {
    if (!file.type.startsWith('image/') && !/\.(heic|heif)$/i.test(file.name)) return file;

    try {
        const image = await decode(file);
        const width = image.width;
        const height = image.height;
        const scale = Math.min(1, maxSide / Math.max(width, height));

        const canvas = document.createElement('canvas');
        canvas.width = Math.round(width * scale);
        canvas.height = Math.round(height * scale);
        const context = canvas.getContext('2d');
        context.fillStyle = '#fff'; // transparent PNG screenshots get a white background in JPEG
        context.fillRect(0, 0, canvas.width, canvas.height);
        context.drawImage(image, 0, 0, canvas.width, canvas.height);
        image.close?.();

        const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', quality));
        if (!blob) return file;

        const name = file.name.replace(/\.[^.]+$/, '') + '.jpg';
        return new File([blob], name, { type: 'image/jpeg', lastModified: file.lastModified });
    } catch {
        return file;
    }
}

export const shrinkImages = files => Promise.all(Array.from(files).map(file => shrinkImage(file)));

export const formatSize = bytes => (bytes < 1024 * 1024 ? `${Math.round(bytes / 1024)} KB` : `${(bytes / 1024 / 1024).toFixed(1)} MB`);

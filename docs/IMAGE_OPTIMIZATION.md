Image optimization

This project can benefit from compressing and converting images to WebP for faster page loads.

Recommended approach (local):

1. Install tools:

- With npm (recommended):

```bash
npm ci
npm install --save-dev imagemin-cli imagemin-webp
```

2. Run the provided NPM script to optimize images in `public/images` and `storage/app/public` (it will overwrite optimized files):

```bash
npm run images:opt
```

3. Example commands used by the script (imagemin):

```bash
npx imagemin "public/images/*.{jpg,png}" --plugin=webp --out-dir=public/images
npx imagemin "storage/app/public/**/*.{jpg,png}" --plugin=webp --out-dir=storage/app/public
```

Notes:
- Test on a copy first. Keep originals if you need full-quality sources.
- For large sets, consider batching or using a dedicated image service (Cloudinary, imgix) for on-the-fly conversion and delivery.
- The CI currently does not run image optimization; it's intended as a local/manual step or pre-deploy build step.

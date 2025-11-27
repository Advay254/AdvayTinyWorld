# AdvayTinyWorld
A professional tracking system with location capture and content delivery.

## Deployment

### Koyeb
1. Fork this repository
2. Go to [Koyeb](https://app.koyeb.com/)
3. Click "Create App"
4. Select "GitHub" and choose your repository
5. Koyeb will automatically detect the Dockerfile
6. Set environment variables in the Koyeb dashboard:
   - `ADMIN_USERNAME` - Admin username
   - `ADMIN_PASSWORD` - Admin password
   - `SITE_BASE_URL` - Your app URL

### Render
1. Fork this repository  
2. Go to [Render](https://render.com/)
3. Click "New +" → "Web Service"
4. Connect your GitHub repository
5. Render will automatically detect the Dockerfile
6. Set environment variables in the Render dashboard

## Environment Variables
- `ADMIN_USERNAME` - Admin login username
- `ADMIN_PASSWORD` - Admin login password (also used as API key)
- `SITE_BASE_URL` - Your application URL

## Features
- ✅ Location tracking
- ✅ Shareable items with expiry
- ✅ Admin dashboard
- ✅ API endpoints for n8n automation
- ✅ Auto-cleanup of old data
- ✅ Professional UI/UX

## API Endpoints
All API endpoints require `X-API-Key: your_admin_password` header.

- `GET /api/locations/get` - Get location data
- `POST /api/items/create` - Create new item
- `GET /api/items/list` - List all items
- `POST /api/items/update` - Update item
- `POST /api/items/delete` - Delete item
- `POST /api/cleanup` - Manual cleanup

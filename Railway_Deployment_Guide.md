# Deploy MPCMS to Railway.app

## Step 1: Create Railway Account

1. Go to: https://railway.app/
2. Click "Login" and sign up with your GitHub account
3. Authorize Railway to access your GitHub repositories

---

## Step 2: Create New Project on Railway

1. Click "New Project"
2. Select "Deploy from GitHub repo"
3. Choose your repository: `RichterP-sys/MPCMS-Final`
4. Railway will automatically detect it's a PHP/Laravel project

---

## Step 3: Add Required Services

Your Laravel app needs:
- MySQL Database
- Redis (optional, for caching/sessions)

In Railway dashboard:
1. Click "New" → "Database" → "Add MySQL"
2. Railway will create a MySQL database and provide connection details

---

## Step 4: Configure Environment Variables

In Railway project settings → Variables, add these:

```
APP_NAME=MPCMS
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MYSQL_HOST}}
DB_PORT=${{MYSQL_PORT}}
DB_DATABASE=${{MYSQL_DATABASE}}
DB_USERNAME=${{MYSQL_USER}}
DB_PASSWORD=${{MYSQL_PASSWORD}}

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

---

## Step 5: Generate APP_KEY

Run locally to get your app key:
```bash
php artisan key:generate --show
```

Copy the output and paste it as `APP_KEY` in Railway variables.

---

## Step 6: Deploy

Railway will automatically deploy when you push to GitHub.

To manually trigger deployment:
1. Go to your Railway project
2. Click "Deploy" or push changes to GitHub

---

## Step 7: Run Migrations

After first deployment, open Railway's terminal and run:
```bash
php artisan migrate --force
```

---

## Quick Setup Checklist

- [ ] Sign up on Railway.app with GitHub
- [ ] Create new project from GitHub repo
- [ ] Add MySQL database
- [ ] Configure environment variables
- [ ] Generate and add APP_KEY
- [ ] Deploy
- [ ] Run migrations
- [ ] Test your application

---

## Troubleshooting

### Build fails
- Check Railway build logs
- Ensure composer.json is in root directory

### Database connection error
- Verify MySQL service is running
- Check environment variables are set correctly

### 500 Error
- Set APP_DEBUG=true temporarily to see errors
- Check storage permissions
- Run: `php artisan config:clear`

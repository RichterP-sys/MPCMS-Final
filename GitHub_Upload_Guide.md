# Guide: Upload MPCMS Project to GitHub

## Prerequisites
- Project location: `C:\xampp\htdocs\MPCMS1\MPCMS`
- GitHub repository: `https://github.com/RichterP-sys/MPCMS-Final`

---

## Step 1: Install Git

1. Go to: https://git-scm.com/download/win
2. Download Git for Windows
3. Run the installer (use default settings - just click "Next" through everything)
4. Restart your computer or close all terminals

---

## Step 2: Verify Git Installation

Open Command Prompt (cmd) or Git Bash and type:

```bash
git --version
```

You should see: `git version 2.x.x`

---

## Step 3: Configure Git

Replace `Your Name` and `your-email@example.com` with your actual GitHub username and email:

```bash
git config --global user.name "Your Name"
git config --global user.email "your-email@example.com"
```

---

## Step 4: Navigate to Your Project

```bash
cd C:\xampp\htdocs\MPCMS1\MPCMS
```

---

## Step 5: Initialize Git Repository

```bash
git init
```

---

## Step 6: Add GitHub Remote Repository

```bash
git remote add origin https://github.com/RichterP-sys/MPCMS-Final.git
```

---

## Step 7: Add All Files to Git

```bash
git add .
```

---

## Step 8: Commit Your Files

```bash
git commit -m "Initial commit - Upload MPCMS project"
```

---

## Step 9: Push to GitHub

```bash
git push -u origin main
```

If you get an error about the branch name, try:

```bash
git branch -M main
git push -u origin main
```

---

## Authentication Options

When you run `git push`, you'll need to authenticate:

### Option 1: Personal Access Token (Recommended)

1. Go to: https://github.com/settings/tokens
2. Click "Generate new token" → "Generate new token (classic)"
3. Give it a name (e.g., "MPCMS Upload")
4. Check the `repo` checkbox
5. Click "Generate token" at the bottom
6. **COPY THE TOKEN** (you won't see it again!)
7. When prompted for password during `git push`, paste this token

### Option 2: GitHub Desktop (Easier)

1. Download: https://desktop.github.com/
2. Install and sign in with your GitHub account
3. Click "Add" → "Add existing repository"
4. Browse to: `C:\xampp\htdocs\MPCMS1\MPCMS`
5. Click "Publish repository" or "Push origin"

---

## Troubleshooting

### If you get "fatal: not a git repository"
Make sure you're in the correct directory:
```bash
cd C:\xampp\htdocs\MPCMS1\MPCMS
```

### If you get "remote origin already exists"
```bash
git remote remove origin
git remote add origin https://github.com/RichterP-sys/MPCMS-Final.git
```

### If you get "failed to push some refs"
```bash
git pull origin main --allow-unrelated-histories
git push -u origin main
```

---

## Quick Command Summary

Copy and paste these commands one by one:

```bash
cd C:\xampp\htdocs\MPCMS1\MPCMS
git init
git remote add origin https://github.com/RichterP-sys/MPCMS-Final.git
git add .
git commit -m "Initial commit - Upload MPCMS project"
git branch -M main
git push -u origin main
```

---

## Done!

Once completed, refresh your GitHub repository page to see all your files uploaded.

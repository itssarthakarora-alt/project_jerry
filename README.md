# project_jerry

Website for `jerryscvv.vc` with CI/CD from GitHub to GoDaddy cPanel.

## The two pipelines

Both live in `.github/workflows/`.

1. **`deploy.yml` — deploy on push.**
   Every push to `main` (or `master`) uploads the whole repo to
   `public_html/jerryscvv.vc/` on GoDaddy via FTP. You can also run it manually
   from the **Actions** tab.

2. **`sync-internal.yml` — keep `internal.php` in sync.**
   Runs on every push to `internal.php`, plus every 15 minutes, and on demand.
   It compares the server's
   `public_html/jerryscvv.vc/internal.php` with the GitHub copy. If they
   differ, GitHub's copy is pushed to the server. **GitHub is the source of
   truth.**

## One-time setup

### 1. Add FTP secrets to GitHub
In this repo: **Settings → Secrets and variables → Actions → New repository secret**

| Name           | Value                                                  |
|----------------|--------------------------------------------------------|
| `FTP_SERVER`   | `ftp.o4x.8fd.mytemp.website`                           |
| `FTP_USERNAME` | `deploy@jerryscvv.vc`                                  |
| `FTP_PASSWORD` | your FTP account password                              |

### 2. Server folder (already configured)
Your `deploy@jerryscvv.vc` FTP account opens directly inside
`public_html/jerryscvv.vc/`, so the workflows already use `./` (the FTP
account's own root). No extra variables are needed.

### 3. Push your files to GitHub
From this folder run:

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/itssarthakarora-alt/project_jerry.git
git push -u origin main
```

The deploy workflow fires on the first push.

## Important notes

- The deploy action **syncs** files: files on the server that are not in the
  repo get deleted. Common cPanel system files are protected via the `exclude`
  list in `deploy.yml`. Add to that list if your server has other files to keep.
- Never commit real credentials or secrets — use GitHub secrets instead.

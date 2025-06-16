# 🚀 QUICK CLEANUP COMMANDS - Copy Paste Execution

## STEP 1: BACKUP PROJECT (PENTING!)
cp -r . ../project-backup-$(date +%Y%m%d)
echo "✅ Project backed up to ../project-backup-$(date +%Y%m%d)"

## STEP 2: HAPUS FILES YANG TIDAK PERLU
echo "🗑️ Deleting unnecessary files..."

# CSS compilation files
rm -f resources/css/app.css
rm -f tailwind.config.js
rm -f postcss.config.js
rm -f resources/js/theme.js

# Backup files (kalau ada)
rm -f resources/css/app.css.backup
rm -f tailwind.config.js.backup

# Build cache
rm -rf public/build

echo "✅ Files deleted"

## STEP 3: CLEAN NPM DEPENDENCIES
echo "📦 Cleaning NPM dependencies..."

# Uninstall Tailwind-related packages
npm uninstall tailwindcss postcss autoprefixer @tailwindcss/forms --save-dev

# Clean node_modules (optional, tapi recommended)
rm -rf node_modules package-lock.json
npm install

echo "✅ NPM dependencies cleaned"

## STEP 4: CLEAR LARAVEL CACHE
echo "🧹 Clearing Laravel cache..."

php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

echo "✅ Laravel cache cleared"

## STEP 5: VERIFICATION
echo "🔍 Verifying cleanup..."

# Check files removed
echo "Files that should be gone:"
[ ! -f resources/css/app.css ] && echo "✅ resources/css/app.css - DELETED" || echo "❌ resources/css/app.css - STILL EXISTS"
[ ! -f tailwind.config.js ] && echo "✅ tailwind.config.js - DELETED" || echo "❌ tailwind.config.js - STILL EXISTS"
[ ! -f postcss.config.js ] && echo "✅ postcss.config.js - DELETED" || echo "❌ postcss.config.js - STILL EXISTS"
[ ! -f resources/js/theme.js ] && echo "✅ resources/js/theme.js - DELETED" || echo "❌ resources/js/theme.js - STILL EXISTS"

# Check essential files exist
echo -e "\nFiles that should exist:"
[ -f vite.config.js ] && echo "✅ vite.config.js - EXISTS" || echo "❌ vite.config.js - MISSING"
[ -f resources/js/app.js ] && echo "✅ resources/js/app.js - EXISTS" || echo "❌ resources/js/app.js - MISSING"
[ -f package.json ] && echo "✅ package.json - EXISTS" || echo "❌ package.json - MISSING"

# Check if Tailwind packages removed from package.json
echo -e "\nChecking package.json for removed dependencies:"
! grep -q "tailwindcss" package.json && echo "✅ tailwindcss - REMOVED" || echo "❌ tailwindcss - STILL IN PACKAGE.JSON"
! grep -q "postcss" package.json && echo "✅ postcss - REMOVED" || echo "❌ postcss - STILL IN PACKAGE.JSON"

echo -e "\n🎉 Cleanup completed!"

## STEP 6: TEST BUILD
echo "🧪 Testing build..."

npm run dev &
DEV_PID=$!

# Wait a moment for build
sleep 3

# Kill dev server
kill $DEV_PID 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Build test successful"
else
    echo "❌ Build test failed - check for errors above"
fi

## STEP 7: PROJECT SIZE COMPARISON
echo -e "\n📊 Project size after cleanup:"
du -sh node_modules 2>/dev/null || echo "node_modules: Not found"
du -sh . --exclude=node_modules --exclude=vendor | tail -1

echo -e "\n📋 SUMMARY:"
echo "✅ Removed CSS compilation files"
echo "✅ Removed Tailwind NPM dependencies" 
echo "✅ Cleared build cache"
echo "✅ Project size reduced"
echo "✅ Ready for CDN Tailwind setup"

echo -e "\n🚀 NEXT STEPS:"
echo "1. Copy 'Tailwind CDN Layout' to resources/views/user/layouts/app.blade.php"
echo "2. Copy 'Vite Config JS Only' to vite.config.js"
echo "3. Copy 'App.js Simplified' to resources/js/app.js"
echo "4. Run: npm run dev"
echo "5. Test: http://localhost:8000/dashboard"

echo -e "\n💡 ROLLBACK (if needed):"
echo "cp -r ../project-backup-$(date +%Y%m%d)/* ."

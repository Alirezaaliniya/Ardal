<#
  ساخت نسخهٔ zip قابل نصب افزونهٔ اردال.

  اجرا: روی build-zip.bat دوبار کلیک کنید، یا:
      powershell -ExecutionPolicy Bypass -File build-zip.ps1

  خروجی: پوشهٔ «ardal-builds» کنار پوشهٔ افزونه، با نام Ardal-<نسخه>.zip
  نسخه از سربرگ ardal.php (Version:) خوانده می‌شود.

  داخل zip یک پوشهٔ «Ardal» است، همان نام پوشهٔ فعلی افزونه؛ پس نصب یا
  به‌روزرسانی از پیشخوان، همین افزونه را جایگزین می‌کند نه یک کپی تازه.

  چرا Compress-Archive نه: در PowerShell 5.1 گاهی مسیرها را با «\» در zip
  می‌نویسد و روی هاست لینوکسی، فایل‌ها به‌جای پوشه‌ها با نام‌های عجیب باز
  می‌شوند. اینجا هر ورودی صریحاً با «/» ساخته می‌شود.
#>

$ErrorActionPreference = 'Stop'

$root   = $PSScriptRoot
$slug   = Split-Path $root -Leaf
$outDir = Join-Path (Split-Path $root -Parent) 'ardal-builds'

# ------------------------------------------------------------- چیزهایی که نمی‌روند
# مسیرها نسبت به ریشهٔ افزونه‌اند؛ پوشه‌ها با هرچه زیرشان است حذف می‌شوند.
$excludeDirs = @(
    '.git',
    '.github',
    '.vscode',
    '.idea',
    '.claude',
    'node_modules',
    'docs',
    'Panel Elano HTML implementation'
)

# الگوی نام فایل، در هر عمقی.
$excludeFiles = @(
    'README.md',
    'conversation-*.txt',
    'build-zip.ps1',
    'build-zip.bat',
    '.gitignore',
    '.gitattributes',
    '.distignore',
    '.editorconfig',
    '*.zip',
    '*.map',
    '*.log',
    '*.bak',
    '*.tmp',
    '.DS_Store',
    'Thumbs.db',
    'desktop.ini'
)

# ------------------------------------------------------------- نسخه
$header  = Get-Content (Join-Path $root 'ardal.php') -Raw -Encoding UTF8
$match   = [regex]::Match($header, 'Version:\s*([0-9A-Za-z.\-]+)')
$version = if ($match.Success) { $match.Groups[1].Value } else { (Get-Date -Format 'yyyyMMdd-HHmm') }

if (-not (Test-Path $outDir)) {
    New-Item -ItemType Directory -Path $outDir | Out-Null
}

$zipPath = Join-Path $outDir ("{0}-{1}.zip" -f $slug, $version)

if (Test-Path $zipPath) {
    Remove-Item $zipPath -Force
}

# ------------------------------------------------------------- فهرست فایل‌ها
function Test-Excluded([string] $relative) {
    $parts = $relative -split '[\\/]'

    foreach ($dir in $excludeDirs) {
        $dirParts = $dir -split '[\\/]'

        if ($parts.Count -gt $dirParts.Count) {
            $head = ($parts[0..($dirParts.Count - 1)]) -join '/'

            if ($head -ieq ($dirParts -join '/')) {
                return $true
            }
        }
    }

    $name = $parts[-1]

    foreach ($pattern in $excludeFiles) {
        if ($name -like $pattern) {
            return $true
        }
    }

    return $false
}

$files = Get-ChildItem -Path $root -Recurse -File -Force | Where-Object {
    $relative = $_.FullName.Substring($root.Length).TrimStart('\', '/')
    -not (Test-Excluded $relative)
}

# ------------------------------------------------------------- ساخت zip
Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

$stream  = [System.IO.File]::Open($zipPath, [System.IO.FileMode]::CreateNew)
$archive = New-Object System.IO.Compression.ZipArchive($stream, [System.IO.Compression.ZipArchiveMode]::Create)

try {
    foreach ($file in $files) {
        $relative = $file.FullName.Substring($root.Length).TrimStart('\', '/') -replace '\\', '/'
        $entry    = "$slug/$relative"

        [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile(
            $archive,
            $file.FullName,
            $entry,
            [System.IO.Compression.CompressionLevel]::Optimal
        ) | Out-Null
    }
}
finally {
    $archive.Dispose()
    $stream.Dispose()
}

$size = '{0:N1} MB' -f ((Get-Item $zipPath).Length / 1MB)

Write-Host ''
Write-Host "  Ardal $version" -ForegroundColor Green
Write-Host "  $($files.Count) files, $size"
Write-Host "  $zipPath"
Write-Host ''

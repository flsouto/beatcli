rm bundle.zip 2>/dev/null
zip -j bundle.zip out/*.wav
gh release upload bundle bundle.zip

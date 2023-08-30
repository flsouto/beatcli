rm bundle.zip 2>/dev/null
zip -j bundle.zip $(find out/*.wav -daystart -ctime 0 -print)
gh release upload bundle bundle.zip --clobber

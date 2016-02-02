for i in `find ./ | grep '.jpg' | cut -d'/' -f2 | cut -d'.' -f1`; do convert $i.jpg -resize 300 $i.png ; done;
